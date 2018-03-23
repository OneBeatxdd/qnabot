<?php

use App\Product;
use App\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();
        $this->call(UsersTableSeeder::class);
        factory(Product::class,100)->create();
        $this->call(CategoriesTableSeeder::class);

        for ( $i = 0; $i < 100; $i++) {
            DB::table('category_product')->insert(
                [
                    'category_id'  => Category::select('id')->orderByRaw("RAND()")->first()->id,
                    'product_id' => Product::select('id')->orderByRaw("RAND()")->first()->id,
                ]
            );
        }

    }
}
