<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Seller;


class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $seller = Seller::has('products')->get()->random();
        $buyer = User::all()->except($seller->id)->random();
        return [
   
            'quantity' => $this->faker->numberBetween(1, 3),
            'buyer_id' => $buyer,
            'product_id' =>  $seller->products->random()->id,
           
    
        ];
    }
}
