<?php

namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
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

        $user = User::findOrFail($user_id);
        $folder = $this->getUserFolder($user);
        $files = $folder == null ? [] : \Storage::disk('google')->listContents($folder);

        usort($files, function ($a, $b) {
            if ($a['timestamp'] < $b['timestamp']) {
                return -1;
            }
            if ($a['timestamp'] > $b['timestamp']) {
                return 1;
            }
            return 0;
        });

        $files = collect($files)->map(function ($file) {
            $file['date'] = Carbon::createFromTimestamp($file['timestamp'])->format('d/m/Y h:iA');
            return $file;
        });

        if ($files->count() > 3) {
            $files = $files->slice(0, 3);
        }

        $current_date = Carbon::now();

        $days = ['Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sábado'];
        $months = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

        $day = $current_date->day;
        $month = $months[$current_date->month - 1];
        $year = $current_date->year;
        $weekday = $days[$current_date->dayOfWeek];

        $format_date = "$weekday, $day de $month de 2021";

        return view('home', compact('files', 'user', 'format_date'));
    }

    public function file(Request $request, String $path)
    {
        $user_id = $request->session()->get('user', null);

        if ($user_id == null) {
            return abort(404);
        }

        $user = User::findOrFail($user_id);
        $name = $this->getFileName($user, $path);
        return \Storage::disk('google')->download($path, $name ?: 'documento.pdf');
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
