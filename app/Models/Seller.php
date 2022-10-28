<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

use App\Models\Seller;
use App\Scopes\SellerScope;

class Seller extends User
{
    use HasFactory;


    // protected static function boot()
    // {
    //   parent::boot();
    //   static::addGlobalScope(new SellerScope);
    // }

    public function product(){
        return $this->hasMany(Product::class);
       }

 
}
