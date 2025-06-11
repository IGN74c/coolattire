<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();

        // Пользователи
        DB::table('users')->insert([
            [
                'email' => 'user1@example.com',
                'password' => bcrypt('password123'),
                'role' => 'user',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'email' => 'user2@example.com',
                'password' => bcrypt('password123'),
                'role' => 'user',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'email' => 'admin@example.com',
                'password' => bcrypt('admin123'),
                'role' => 'admin',
                'created_at' => $now,
                'updated_at' => $now
            ]
        ]);

        // Категории
        DB::table('categories')->insert([
            ['name' => 'Одежда'],
            ['name' => 'Обувь'],
            ['name' => 'Аксессуары']
        ]);

        // Товары
        DB::table('products')->insert([
            [
                'name' => 'Хлопковая футболка',
                'description' => 'Мягкая хлопковая футболка с круглым вырезом',
                'price' => 29.99,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'name' => 'Слим джинсы',
                'description' => 'Классические синие джинсы скинни',
                'price' => 89.99,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'name' => 'Кроссовки Runner',
                'description' => 'Спортивные кроссовки для бега',
                'price' => 120.00,
                'created_at' => $now,
                'updated_at' => $now
            ]
        ]);

        // Размеры товаров
        DB::table('product_sizes')->insert([
            ['product_id' => 1, 'size' => 'S', 'quantity' => 10],
            ['product_id' => 1, 'size' => 'M', 'quantity' => 15],
            ['product_id' => 2, 'size' => '32/32', 'quantity' => 5],
            ['product_id' => 2, 'size' => '34/32', 'quantity' => 8],
            ['product_id' => 3, 'size' => '42', 'quantity' => 7]
        ]);

        // Изображения товаров
        DB::table('product_images')->insert([
            ['product_id' => 1, 'image_url' => 'tshirt_white.jpg'],
            ['product_id' => 1, 'image_url' => 'tshirt_white_back.jpg'],
            ['product_id' => 3, 'image_url' => 'runners_black.jpg']
        ]);

        // Связи категорий и товаров
        DB::table('category_product')->insert([
            ['category_id' => 1, 'product_id' => 1],
            ['category_id' => 1, 'product_id' => 2],
            ['category_id' => 2, 'product_id' => 3]
        ]);

        // Заказы
        DB::table('orders')->insert([
            [
                'user_id' => 1,
                'status' => 'completed',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'user_id' => 2,
                'status' => 'pending',
                'created_at' => $now,
                'updated_at' => $now
            ]
        ]);

        // Элементы заказов
        DB::table('order_items')->insert([
            [
                'order_id' => 1,
                'product_id' => 1,
                'quantity' => 2,
                'size' => 'S',
                'price' => 29.99
            ],
            [
                'order_id' => 1,
                'product_id' => 3,
                'quantity' => 1,
                'size' => 'S',
                'price' => 120.00
            ]
        ]);

        // Корзины
        DB::table('carts')->insert([
            ['user_id' => 1],
            ['user_id' => 3]
        ]);

        // Элементы корзин
        DB::table('cart_items')->insert([
            ['cart_id' => 1, 'product_id' => 2, 'quantity' => 1, 'size' => 'S'],
            ['cart_id' => 2, 'product_id' => 3, 'quantity' => 2, 'size' => 'S']
        ]);
    }
}