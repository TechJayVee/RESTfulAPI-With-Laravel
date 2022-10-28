<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\User;
use App\Models\Buyer;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\DB;

class ProductBuyerTransactionController extends ApiController
{
   
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Product $product, User $buyer)
    {
        
        $request->validate([
            'quantity' => 'required|integer|min:1',
            
        ]);

        if($buyer->id==$product->seller_id){
            return $this->errorResponse('The buyer must be different from the seller', 409);
        }
        if(!$buyer->isVerified()){
            return $this->errorResponse('The buyer must be verified user', 409);
        }
        if(!$product->seller->isVerified()){
            return $this->errorResponse('The seller must be verified user', 409);
        }
        if(!$product->isAvailable()){
            return $this->errorResponse('The Product is not available', 409);
        }
        if($product->quantity < $request->quantity){
            return $this->errorResponse('The Product does not have enought unit for this transaction', 409);
        }

        return DB::transaction(function() use ($request, $product, $buyer){
            $product->quantity -= $request->quantity;
            $product->save();

            $transaction = Transaction::create([
                'quantity'=> $request->quantity,
                'buyer_id'=> $buyer->id,
                'product_id'=> $product->id,
            ]);


            return $this->showOne($transaction);
        });
    }

    
}
