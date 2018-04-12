<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Symfony\Component\Finder\Finder;

Route::get('/', function () {
    return view('welcome');
});

Route::match(['get', 'post'], '/botman', 'BotManController@handle');
Route::get('/botman/tinker', 'BotManController@tinker');

// qnabot
Route::get('test', 'QnABotman\QnABotController@index');

Route::get('debug', 'BotManController@debug');

Route::get('vr',function(){
  return view('three_js.playground');
});


//shopify
Route::get('/show_products', function() {
    // Code put here will run when you navigate to /show_products
    // This creates an instance of the Shopify API wrapper and
    // authenticates our app.
    $shopify = App::make('ShopifyAPI', [
        'API_KEY'       => env('SHOPIFY_KEY'),
        'API_SECRET'    => env('SHOPIFY_SECRET'),
        'SHOP_DOMAIN'   => 'is-project-management-trial.myshopify.com',
        'ACCESS_TOKEN'  => env('SHOPIFY_PASSWORD')
    ]);

    // Gets a list of products
    $result = $shopify->call([
        'METHOD'    => 'GET',
        'URL'       => '/admin/products.json?page=1'
    ]);
    $products = $result->products;

    // Print out the title of each product we received
    foreach($products as $product) {
        echo ' ' . $product->id . ': ' . $product->title . ' ';
    }
});
