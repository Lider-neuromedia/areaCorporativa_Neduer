<?php

use App\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $faker = Faker\Factory::create();

        // $users = [
        //     "31579075",
        //     "31564974",
        //     "31677270",
        //     "31884364",
        // ];

        // foreach ($users as $user) {
        //     User::create([
        //         'name' => $user,
        //         'email' => "{$user}@mail.com",
        //         'password' => \Hash::make($user),
        //     ]);
        // }

        User::loadDataFromCSV();
    }
}
