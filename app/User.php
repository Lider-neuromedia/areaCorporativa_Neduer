<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use League\Csv\Reader;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'email',
        'name',
        'document',
        'status',
        'description_job',
        'description_co',
        'description_group',
        'admission_date',
        'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getInitialsAttribute()
    {
        $words = explode(' ', $this->name);
        $count_words = count($words);

        if ($count_words >= 2) {
            return substr($words[0], 0, 1) . substr($words[1], 0, 1);
        }
        if ($count_words == 1) {
            return substr($words[0], 0, 2);
        }
        return 'GN';
    }

    public static function loadDataFromCSV()
    {
        $csv = Reader::createFromPath(storage_path('app/empleados.csv'), 'r')->setHeaderOffset(0);

        foreach ($csv as $record) {
            $document = $record['Empleado'];
            $email = "$document@mail.com";
            $admission = Carbon::createFromFormat('Y-m-d', $record['Fecha ingreso']);

            if (User::where('email', $email)->exists()) {
                \Log::info($email);
                continue;
            }

            User::create([
                'email' => $email,
                'name' => $record['Nombre del empleado'],
                'document' => $document,
                'status' => $record['Descripcion estado'],
                'description_job' => $record['Descripcion del cargo'],
                'description_co' => $record['Descripcion C.O.'],
                'description_group' => $record['Descripcion grupo empleados'],
                'admission_date' => $admission,
                'password' => \Hash::make($document),
            ]);
        }
    }
}
