<?php

namespace App\Http\Controllers;



use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;

use App\Settings;
use App\Product;
use App\Orders;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;



class AdminMostSoldController extends Controller
{
    public function index(Request $request)
    {
        $settings  = Settings::where("keyname","setting")->first();
        $sales = [];
        $totalOrders = 0;
        $totalPrice = 0;

        $orders = Orders::all();

        //apply date range filter
        if(!empty(Session::get('mostsold_filter_dates'))){
            $explodeDates = explode("-",Session::get('mostsold_filter_dates'));
            if(!empty($explodeDates[0]) && !empty($explodeDates[1])){
                $date1 = date("Y-m-d",strtotime($explodeDates[0]));
                $date2 = date("Y-m-d",strtotime($explodeDates[1]));
                $orders = $orders->whereBetween('created_at', [$date1, $date2]);
            }
        }

        //calculating sale quantity of each product id
        foreach($orders as $order){
            if(isset($sales[$order->product_id])){
                $sales[$order->product_id] += $order->quantity;
            }
            else{
                $sales[$order->product_id] = $order->quantity;
            }

            $totalOrders += $order->quantity;
            $totalPrice += $order->unit_price * $order->quantity;
        }

        //sorting sales descending according to their sale quantity
        arsort($sales);

        //making an array for products and their sale quantity
        $products = [];
        foreach($sales as $product_id => $quantity){
            $product = Product::find($product_id);
            if($product){
                $products[] = [
                    'product' => $product,
                    'sales' => $quantity
                ];
            }
        }

        //making a collection from the array and paginating the array
        $page = null;
        $perPage = $settings->item_per_page_back;
        $options = ['path' => $request->url(), 'query' => $request->query()];
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $products instanceof Collection ? $products : Collection::make($products);
        $products = new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);

        return view('gwc.mostsold.index',['products'=>$products, 'totalOrders'=>$totalOrders, 'totalPrice'=>$totalPrice, 'settings'=>$settings]);
    }


    //reset mostsold filtration
    public function resetDateRange()
    {
        Session::forget('mostsold_filter_dates');
        return ["status"=>200,"message"=>""];
    }


    //store most sold filtration values in cookie by ajax
    public function storeValuesInCookies(Request $request)
    {
        $minutes=3600;

        //date range
        if(!empty($request->mostsold_dates)){
            Session::put('mostsold_filter_dates', $request->mostsold_dates);
        }

        return ["status"=>200,"message"=>""];
    }

}