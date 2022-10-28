<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Product;

class Category extends Model
{   
    use HasFactory, SoftDeletes;
protected $dates = ['deleted_at'];
protected $hidden=[
    'pivot'
   ] ;
    protected $fillable = [
    'name',
    'description',
]; 

public function product(){
    return $this->belongsToMany(Product::class);
   }



}
