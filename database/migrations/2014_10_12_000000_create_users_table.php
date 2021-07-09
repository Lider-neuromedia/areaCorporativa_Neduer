<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();

            $table->string('name')->comment('Nombre del empleado');
            $table->string('document')->comment('Empleado');
            $table->string('status')->comment('Descripcion estado');
            $table->string('description_job')->comment('Descripcion del cargo');
            $table->string('description_co')->comment('Descripcion C.O.');
            $table->string('description_group')->comment('Descripcion grupo empleados');
            $table->date('admission_date')->comment('Fecha ingreso');

            $table->string('password');
            $table->rememberToken();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
