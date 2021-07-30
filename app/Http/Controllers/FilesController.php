<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

class FilesController extends Controller
{
    const MONTHS = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];

    public function index()
    {
        return $this->canUpdateNow() ? view('files') : redirect('/');
    }

    private function canUpdateNow()
    {
        $path = 'drive-files/config.json';
        $exists = \Storage::disk('local')->exists($path);

        if ($exists) {
            $config = json_decode(\Storage::disk('local')->get($path), true);
            $last = Carbon::createFromFormat('Y-m-d H:i:s', $config['last_update']);
            $now = Carbon::now();

            if ($now->diffInMinutes($last) < 60) {
                return false;
            }
        }

        return true;
    }

    public function preloadFileList()
    {
        if (!$this->canUpdateNow()) {
            return response()->json(['message' => 'No puede actualizar en este momento.'], 401);
        }

        $base_folder = \Storage::disk('google')->listContents(env('GOOGLE_DRIVE_BASE_FOLDER'));

        foreach ($base_folder as $folder) {
            $year = intval($folder['name']);
            $months_folder = \Storage::disk('google')->listContents($folder['path']);

            if ($year < 2000 || $year > Carbon::now()->year) {
                // Ignorar las carpetas que no tengan como nombre un año entre 2000 y el año actual.
                continue;
            }

            foreach ($months_folder as $mfolder) {
                $month = strtolower($mfolder['name']);
                $month_folder = \Storage::disk('google')->listContents($mfolder['path']);
                $month_name = null;

                // Validar nombre de la carpeta de mes.
                foreach (self::MONTHS as $key => $lmonth) {
                    if (strpos($month, $lmonth) !== false) {
                        $month_name = $lmonth;
                    }
                }

                if ($month_name === null) {
                    // Se ignora la carpeta si el nombre de la carpeta no contiene un mes correctamente escrito.
                    continue;
                }

                $filename = "{$year}-{$month_name}.json";
                $content = json_encode($month_folder);

                \Storage::disk('local')->put("drive-files/$filename", $content);
            }
        }

        // Actualizar fecha de última actualización.
        $config_path = 'drive-files/config.json';
        $config_data = ['last_update' => Carbon::now()->format('Y-m-d H:i:s')];
        \Storage::disk('local')->put($config_path, json_encode($config_data));

        return response()->json(['message' => 'Actualización finalizada correctamente.'], 200);
    }

    public function loadFilesFromLocal(String $user_document)
    {
        $time_list = (new HomeController)->getLastMonths();
        $files = [];

        foreach ($time_list as $year => $months) {
            foreach ($months as $month) {
                $m = strtolower($month);
                $path = "drive-files/{$year}-{$m}.json";
                $exists = \Storage::disk('local')->exists($path);

                if ($exists) {
                    $month_folder = json_decode(\Storage::disk('local')->get($path), true);

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

        return collect($files);
    }
}
