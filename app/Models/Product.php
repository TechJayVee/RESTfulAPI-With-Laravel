<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\Seller;
use App\Models\Transaction;
use App\Models\Product;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
   use HasFactory, SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $hidden=[
        'pivot'
       ] ;
    const AVAILBALE_PRODUCT = 'available';
    const UNAVAILBALE_PRODUCT = 'unavailable';

    protected $fillable = [
        'name',
        'description',
        'quantity',
        'status',
        'image',
        'seller_id',
    ]; 

    public function isAvailable()
    {
        return $this->status==Product::AVAILBALE_PRODUCT;
    }

    
    public function Categories(){
    return $this->belongsToMany(Category::class);
   }

    public function seller(){
    return $this->belongsTo(Seller::class);
   }


   
    public function transaction(){
    return $this->hasMany(Transaction::class);
   }

}
