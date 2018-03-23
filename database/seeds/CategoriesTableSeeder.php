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
            'name' => "spotlight",
            'title' => "Spot Light",
        ]);
        DB::table('categories')->insert([
            'name' => "GU10",
            'title' => "GU 10",
        ]);
        DB::table('categories')->insert([
            'name' => "MR16",
            'title' => "MR 16",
        ]);
    }
}
