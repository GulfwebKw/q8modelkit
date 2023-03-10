<?php

namespace App\Http\Controllers;



use Illuminate\Http\Request;

use App\Settings;
use App\Product;


class AdminInventoryController extends Controller
{
    public function index(Request $request)
    {
        $settings  = Settings::where("keyname","setting")->first();

        $totalProducts = 0;
        $totalAmount = 0;

        $products = Product::orderBy('quantity','DESC')->paginate($settings->item_per_page_back);

        foreach($products as $product){
            $totalProducts++;
            $totalAmount += ($product->countdown_price) ?: ($product->retail_price);
        }

        return view('gwc.inventory.index',['products'=>$products,'totalProducts'=>$totalProducts,'totalAmount'=>$totalAmount,'settings'=>$settings]);
    }


}