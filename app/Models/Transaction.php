<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\Buyer;
use App\Models\Seller;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
      use HasFactory, SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'quantity',
        'buyer_id',
        'product_id',
    ]; 

    public function product(){
        return $this->belongsTo(Product::class);
       }
       
    public function buyer(){
        return $this->belongsTo(Buyer::class);
       }



}
