<?php

use App\Models\Cate;
use App\Models\Goods;
use Faker\Generator as Faker;

$factory->define(Goods::class, function (Faker $faker) {
  $imgs = [
    '5e353624b9d1266fb773c02b790194f1.jpg', 'd2c3a0eae99559b74c9347d5027866d0.jpg', '12dd300b918df284571668d48db1753c.jpg',
    'c5652fea0e20ecdc56a82f39c2a6ab62.jpg', 'carouse-3.jpg', '76cef7884b1c01e1aee940143126d968.jpg', 'carouse-5.jpg', 'carouse-1.jpg'
  ];
  $cates = Cate::select('id')->get()->toArray();
  return [
    'cate_id' => $cates[array_rand($cates)]['id'],
    'title' => $faker->realText(60, 3),
    'subtitle' => $faker->realText(40, 3),
    'price' => $faker->randomFloat(2, 9.9, 1000),
    'amount' => $faker->numberBetween(0, 300),
    'sold' => $faker->numberBetween(0, 80),
    'thumb' => $imgs[array_rand($imgs)],
    'status' => 0,
  ];
});
