<?php

namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    const DAYS = ['Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sábado'];
    const MONTHS = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

    public function loginForm(Request $request)
    {
        if ($request->session()->get('user', null)) {
            return redirect('/');
        }
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate(['document' => 'required|string|max:30|min:5']);
        $user_id = $request->input('document');
        $user = User::where('document', $user_id)->first();

        if (!$user) {
            $request->session()->forget('user');
            return back()->with(['message' => 'Documento no válido']);
        }

        $request->session()->put('user', $user->id);
        return redirect('/');
    }

    public function logout(Request $request)
    {
        $request->session()->forget('user');
        return redirect('/');
    }

    public function index(Request $request)
    {
        $user_id = $request->session()->get('user', null);

        if ($user_id == null) {
            return redirect('/login');
        }

        $format_date = $this->getFormattedDate();
        $user = User::findOrFail($user_id);

        return view('home', compact('user', 'format_date'));
    }

    public function files(Request $request)
    {
        $user_id = $request->session()->get('user', null);

        if ($user_id == null) {
            return response()->json(['message' => 'Usuario no autorizado.'], 401);
        }

        $user = User::findOrFail($user_id);
        $files = $this->loadFilesFromDrive($user->document);

        $files = $files->map(function ($file) use ($user) {
            $date = Carbon::createFromTimestamp($file['timestamp']);
            $file['date'] = $date->format('d/m/Y h:iA');
            $file['month'] = self::MONTHS[$date->month - 1];
            $file['download_name'] = "doc-{$file['month']}-{$user->document}.pdf";
            $file['download_url'] = url("files/{$file['path']}") . "?name=" . $file['download_name'];

            return $file;
        });

        return response()->json(compact('files'), 200);
    }

    private function getFormattedDate()
    {
        $current_date = Carbon::now();
        $day = $current_date->day;
        $month = self::MONTHS[$current_date->month - 1];
        $year = $current_date->year;
        $weekday = self::DAYS[$current_date->dayOfWeek];

        return "$weekday, $day de $month de 2021";
    }

    private function loadFilesFromDrive(String $user_document)
    {
        $base_folder = \Storage::disk('google')->listContents(env('GOOGLE_DRIVE_BASE_FOLDER'));
        $time_list = $this->getLastMonths();
        $files = [];

        foreach ($base_folder as $folder) {
            foreach ($time_list as $year => $months) {

                if ($folder['name'] == $year) {
                    $months_folder = \Storage::disk('google')->listContents($folder['path']);

                    foreach ($months_folder as $mfolder) {
                        $month_folder_name = strtoupper($mfolder['name']);
                        $use_month = false;

                        foreach ($months as $month) {
                            if (strpos($month_folder_name, $month) !== false) {
                                $use_month = true;
                            }
                        }

                        if ($use_month) {
                            $month_folder = \Storage::disk('google')->listContents($mfolder['path']);

                            foreach ($month_folder as $document) {
                                $cloud_name = strtolower($document['name']);
                                $document_name = "{$user_document}.pdf";
                                $is_valid_document = strpos($cloud_name, $document_name) == 2;

                                if ($is_valid_document) {
                                    $files[] = $document;
                                }
                            }
                        }
                    }

                }

            }
        }

        return collect($files);
    }

    /**
     * Obtener los nombre/indices de los últimos 3 meses.
     */
    private function getLastMonths($is_index = false)
    {
        $current_date = Carbon::now();
        $month_index = $current_date->month - 1;
        $previous_year = $current_date->year - 1;

        if ($month_index < 1) {
            return [
                "{$previous_year}" => [
                    strtoupper(self::MONTHS[$month_index + 12 - 2]),
                    strtoupper(self::MONTHS[$month_index + 12 - 1]),
                ],
                "{$current_date->year}" => [
                    strtoupper(self::MONTHS[$month_index]),
                ],
            ];
        }
        if ($month_index < 2) {
            return [
                "{$previous_year}" => [
                    strtoupper(self::MONTHS[$month_index + 11 - 1]),
                ],
                "{$current_date->year}" => [
                    strtoupper(self::MONTHS[$month_index - 1]),
                    strtoupper(self::MONTHS[$month_index]),
                ],
            ];
        }

        return [
            "{$current_date->year}" => [
                strtoupper(self::MONTHS[$month_index - 2]),
                strtoupper(self::MONTHS[$month_index - 1]),
                strtoupper(self::MONTHS[$month_index]),
            ],
        ];
    }

    public function file(Request $request, String $path)
    {
        $user_id = $request->session()->get('user', null);

        if ($user_id == null) {
            return abort(404);
        }

        $name = $request->get('name') ?: "document.pdf";
        return \Storage::disk('google')->download($path, $name);
    }

    private function getUserFolder(User $user)
    {
        $folders = \Storage::disk('google')->listContents();
        $path = null;

        foreach ($folders as $folder) {
            if ($folder['type'] != 'dir') {
                continue;
            }
            if ($folder['filename'] != $user->document) {
                continue;
            }
            $path = $folder['path'];
        }

        return $path;
    }

    private function getFileName(User $user, String $path)
    {
        $folder = $this->getUserFolder($user);
        $files = $folder == null ? [] : \Storage::disk('google')->listContents($folder);
        $file = collect($files)->firstWhere('path', $path);
        return $file == null ? null : $file['name'];
    }
}
