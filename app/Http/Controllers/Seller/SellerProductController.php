<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Seller;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;


class SellerProductController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function index(Seller $seller)
    {
        $products = $seller->product
       ;
        return $this->showAll($products);
    }

   

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $seller)
    {
        $request->validate([
            'name'=>'required',
            'description' =>'required',
            'quantity'=> 'required|integer|min:1',  
            'image'=> 'required|image',  
        ]);
        $avatar = $request->file('image')->getClientOriginalName();
        $products = Product::create([
            'name'=> $request->name,
            'description'=> $request->description,
            'quantity'=> $request->quantity,
            'status'=>Product::UNAVAILBALE_PRODUCT,
            'image'=> $request->image->store('', 'images' ),
            'seller_id'=>$seller->id,

        ]);

        return $this->showOne($products, 201);
    }

   
   
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Seller $seller, Product $product)
    {

        $request->validate([
            'quantity' => 'integer|min:1',
            'status' => 'in:' . Product::AVAILBALE_PRODUCT . ',' . Product::UNAVAILBALE_PRODUCT,
            'image' => 'image', 
        ]);

      
        if ($seller->id != $product->seller_id) {
            return $this->errorResponse('The specified seller is not the actual seller of the product', 422);           
        }

  
        $product->fill($request->only([
            'name',
            'description',
            'quantity',
        ]));

        if ($request->has('status')) {
            $product->status = $request->status;

            if ($product->isAvailable() && $product->categories()->count() == 0) {
                return $this->errorResponse('An active product must have at least one category', 409);
            }
        }

        if ($product->isClean()) {
            return $this->errorResponse('You need to specify a different value to update', 422);
        }

        $product->save();

        return $this->showOne($product);

      
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function destroy(Seller $seller, Product $product)
    {
        if ($seller->id != $product->seller_id) {
            return $this->errorResponse('The specified seller is not the actual seller of the product', 422);           
        }
        $seller->delete();
        storage::delete($product->image);
         return $this->showOne($seller);
    }
}
