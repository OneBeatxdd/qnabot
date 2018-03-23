<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('users')->insert([
            'name' => "sally",
            'email' => 'sallyyue123xdd@gmail.com',
            'password' => bcrypt('5wyhS4R$%_c'),
        ]);
    }
}
