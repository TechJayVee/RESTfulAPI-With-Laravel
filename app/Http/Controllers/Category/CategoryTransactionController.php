<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class CategoryTransactionController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Category $category)
    {
        $transaction = $category->product()
        ->whereHas('transaction')
        ->with('transaction')
        ->get()
        ->pluck('transaction')
        ->collapse();
        return $this->showAll($transaction);
    }


}
