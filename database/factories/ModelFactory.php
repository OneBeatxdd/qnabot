<?php

use Illuminate\Support\Facades\DB;
use Faker\Generator as Faker;

$factory->define(App\Product::class, function (Faker $faker) {
    return [
        'Title'             => $faker->name,
        'Variant Price'     =>(float)mt_rand(100,500)+mt_rand() / mt_getrandmax(),
        'Image Src'         =>$faker->imageUrl($width = 640, $height = 480),
        'link'              =>$faker->url,
    ];
});
