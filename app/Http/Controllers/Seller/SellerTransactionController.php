<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Seller;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class SellerTransactionController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Seller $seller)
    {
        $transaction=$seller->product()
        ->whereHas('transaction')
        ->with('transaction')
        ->get()
        ->pluck('transaction')
        ->collapse();
        return $this->showAll($transaction);
    
    }

   
}
