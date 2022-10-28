<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Transaction;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        User::truncate();
        Product::truncate();
        Category::truncate();
        Transaction::truncate();
        DB::table('category_product')->truncate();

        User::flushEventListeners();
        Product::flushEventListeners();
        Category::flushEventListeners();
        Transaction::flushEventListeners();
        
        $userQuantity=1000;
        $categoryQuantity= 30;
        $productQuantity=1000;
        $transactionQuantity=1000;

        User::factory()->count($userQuantity)->create();
        Category::factory()->count($categoryQuantity)->create();
        Product::factory()->count($productQuantity)->create()->each(
            function($product){
                $categories = Category::all()->random(mt_rand(1, 5))->pluck('id');
                $product ->categories()->attach($categories);
            }
        );
       Transaction::factory()->count( $transactionQuantity)->create();
    }
}
