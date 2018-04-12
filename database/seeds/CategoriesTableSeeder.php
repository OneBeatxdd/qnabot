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
            'link' => "https://is-project-management-trial.myshopify.com/collections/lamp-shades",
        ]);
        DB::table('categories')->insert([
            'name' => "light bulb",
            'title' => "Light Bulb",
            'link' => "https://is-project-management-trial.myshopify.com/collections/light-bulb",
        ]);
        DB::table('categories')->insert([
            'name' => "Spotlight GU10",
            'title' => "Spotlight GU10",
            'link' => "https://is-project-management-trial.myshopify.com/collections/spotlight-gu-10",
        ]);
        DB::table('categories')->insert([
            'name' => "Spotlight MR16",
            'title' => "Spotlight MR16",
            'link' => "https://is-project-management-trial.myshopify.com/collections/spotlight-mr-16",
        ]);
    }
}
