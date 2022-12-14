<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use App\Models\Buyer;

class BuyerController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $buyer = Buyer::has('transactions')->get();
    return $this->showAll($buyer);
    }

   
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Buyer $buyer)
    {
        // $buyer = Buyer::has('transactions')->findOrFail($id);
        return $this->showOne($buyer);
    }

   
}
