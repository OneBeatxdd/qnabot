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
        $this->call(CategoriesTableSeeder::class);
        // factory(Product::class,100)->create();
        $shopify_products = DB::table('shopify_products')->get();
        foreach ($shopify_products as $one_product) {
          # code...
          $one_product = get_object_vars($one_product);
          DB::table('products')->insert([
            'Title' => $one_product["Title"],
            'Variant Price' => $one_product["Variant Price"],
            'Image Src' => $one_product["Image Src"],
            'link'  => "https://is-project-management-trial.myshopify.com/products/".$one_product["Handle"]
          ]);

          $exploded_tags = explode(',',$one_product["Tags"]);
          foreach($exploded_tags as $tag){
            $tag = ltrim($tag);
            $have_category = Category::where("name", $tag)->first();
            if (count($have_category)) {
              DB::table('category_product')->insert(
                  [
                      'category_id'  => Category::where("name", $tag)->first()->id,
                      'product_id' => Product::where("Title",$one_product["Title"])->first()->id,
                  ]
              );
            }
          }
        }


        // for ( $i = 0; $i < 100; $i++) {
        //     DB::table('category_product')->insert(
        //         [
        //             'category_id'  => Category::select('id')->orderByRaw("RAND()")->first()->id,
        //             'product_id' => Product::select('id')->orderByRaw("RAND()")->first()->id,
        //         ]
        //     );
        // }



    }
}
