<?php

use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('categories')->insert([
            'name' => "lampshade",
            'title' => "Lamp Shades",
        ]);
        DB::table('categories')->insert([
            'name' => "light bulb",
            'title' => "Light Bulb",
        ]);
        DB::table('categories')->insert([
            'name' => "Spotlight GU10",
            'title' => "Spotlight GU10",
        ]);
        DB::table('categories')->insert([
            'name' => "Spotlight MR16",
            'title' => "Spotlight MR16",
        ]);
    }
}
