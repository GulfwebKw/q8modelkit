<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Spatie\Permission\Models\Role;
use App\Admin;//model
use App\Menus;//model
use App\Customers; //model
use App\Contactus; //model
use App\Settings; //model
use App\Categories; //model
use App\Product; //model
use App\Brand; //model
use App\AdminLogs; //model
use App\OrdersDetails; //model
use App\Orders;
use App\Transaction;
use DB;
use Common;
use Carbon;
//gapi
use App\Gapi\Gapi;


class VendorDashboardController extends Controller
{
    
		
	
	//view home page
	public function index()
    {
	 $countCustomers        = Customers::all();
	 $countCustomers_today  = Customers::whereDate('created_at', Carbon::today())->get();
	 $countCustomers_week   = Customers::whereDate('created_at','>=', Carbon::now()->subWeeks(1))->get();
	 $countCustomers_month  = Customers::whereDate('created_at','>=', Carbon::now()->subDays(30))->get();
	 $cutomersStats =['total'=>count($countCustomers),'today'=>count($countCustomers_today),'week'=>count($countCustomers_week),'month'=>count($countCustomers_month)];
	 
	 $countContactus        = Contactus::all();
	 $countContactus_today  = Contactus::whereDate('created_at', Carbon::today())->get();
	 $countContactus_week   = Contactus::whereDate('created_at','>=', Carbon::now()->subWeeks(1))->get();
	 $countContactus_month  = Contactus::whereDate('created_at','>=', Carbon::now()->subDays(30))->get();
	 $contactStats =['total'=>count($countContactus),'today'=>count($countContactus_today),'week'=>count($countContactus_week),'month'=>count($countContactus_month)];
	 
	 
	 $countCategories        = Categories::all();
	 $countCategories_today  = Categories::whereDate('created_at', Carbon::today())->get();
	 $countCategories_week   = Categories::whereDate('created_at','>=', Carbon::now()->subWeeks(1))->get();
	 $countCategories_month  = Categories::whereDate('created_at','>=', Carbon::now()->subDays(30))->get();
	 $categoryStats =['total'=>count($countCategories),'today'=>count($countCategories_today),'week'=>count($countCategories_week),'month'=>count($countCategories_month)];
	 
	 
	 if(Auth::guard('admin')->user()->userType=="vendor"){
	 $countProduct        = Product::where('manufacturer_id',Auth::guard('admin')->user()->id)->get();
	 $countProduct_today  = Product::where('manufacturer_id',Auth::guard('admin')->user()->id)->whereDate('created_at', Carbon::today())->get();
	 $countProduct_week   = Product::where('manufacturer_id',Auth::guard('admin')->user()->id)->whereDate('created_at','>=', Carbon::now()->subWeeks(1))->get();
	 $countProduct_month  = Product::where('manufacturer_id',Auth::guard('admin')->user()->id)->whereDate('created_at','>=', Carbon::now()->subDays(30))->get();
	 $productsStats =['total'=>count($countProduct),'today'=>count($countProduct_today),'week'=>count($countProduct_week),'month'=>count($countProduct_month)];
	 }else{
	 $countProduct        = Product::all();
	 $countProduct_today  = Product::whereDate('created_at', Carbon::today())->get();
	 $countProduct_week   = Product::whereDate('created_at','>=', Carbon::now()->subWeeks(1))->get();
	 $countProduct_month  = Product::whereDate('created_at','>=', Carbon::now()->subDays(30))->get();
	 $productsStats =['total'=>count($countProduct),'today'=>count($countProduct_today),'week'=>count($countProduct_week),'month'=>count($countProduct_month)];
	 }
	 
	 //orders
	 
   
	 $countOrders        = $this->getTotalVendorOrders();
	 $countOrders_today  = $this->getTotalVendorOrders("today");
	 $countOrders_week   = $this->getTotalVendorOrders("week");
	 $countOrders_month  = $this->getTotalVendorOrders("month");
	 $OrdersStats        =['total'=>count($countOrders),'today'=>count($countOrders_today),'week'=>count($countOrders_week),'month'=>count($countOrders_month)];
	 //soldout
	 $countSoldout        = $this->getTotalCompletedVendorOrders();
	 $countSoldout_today  = $this->getTotalCompletedVendorOrders("today");
	 $countSoldout_week   = $this->getTotalCompletedVendorOrders("week");
	 $countSoldout_month  = $this->getTotalCompletedVendorOrders("month");
	 $SoldOutStats        =['total'=>count($countSoldout),'today'=>count($countSoldout_today),'week'=>count($countSoldout_week),'month'=>count($countSoldout_month)];
	 //payments knet
	 $countPayments        = $this->getTotalKnetVendorPayments();
	 $countPayments_today  = $this->getTotalKnetVendorPayments('today');
	 $countPayments_week   = $this->getTotalKnetVendorPayments("week");
	 $countPayments_month  = $this->getTotalKnetVendorPayments("month");
	 $paymentStats        =['total'=>$countPayments,'today'=>$countPayments_today,'week'=>$countPayments_week,'month'=>$countPayments_month];
	 
	 //shipment count
	 //soldout
	 $countShipment        = OrdersDetails::where('order_status','completed')->get()->sum('delivery_charges');
	 $countShipment_today  = OrdersDetails::where('order_status','completed')->whereDate('created_at', Carbon::today())->get()->sum('delivery_charges');
	 $countShipment_week   = OrdersDetails::where('order_status','completed')->whereDate('created_at','>=', Carbon::now()->subWeeks(1))->get()->sum('delivery_charges');
	 $countShipment_month  = OrdersDetails::where('order_status','completed')->whereDate('created_at','>=', Carbon::now()->subDays(30))->get()->sum('delivery_charges');
	 $shipmenttats         =['total'=>$countPayments,'today'=>$countPayments_today,'week'=>$countPayments_week,'month'=>$countPayments_month];
	 //payments cod
	 $countcod            = $this->getTotalVendorCodPayment();
	 $countcod_today      = $this->getTotalVendorCodPayment('today');
	 $countcod_week       = $this->getTotalVendorCodPayment('week');
	 $countcod_month      = $this->getTotalVendorCodPayment('month');
	 
	 $codstats =['total'=>$countcod,'today'=>$countcod_today,'week'=>$countcod_week,'month'=>$countcod_month];
	 
	 //payments cod
	 $countpost = OrdersDetails::where("pay_mode","POSTKNET")->where("order_status","completed")->get()->sum('total_amount');
	
	 $countpost_today= OrdersDetails::where("pay_mode","POSTKNET")->where("order_status","completed")->whereDate('created_at', Carbon::today())->get()->sum('total_amount');
	 $countpost_week= OrdersDetails::where("pay_mode","POSTKNET")->where("order_status","completed")->whereDate('created_at','>=', Carbon::now()->subWeeks(1))->get()->sum('total_amount');
	 $countpost_month= OrdersDetails::where("pay_mode","POSTKNET")->where("order_status","completed")->whereDate('created_at','>=', Carbon::now()->subDays(30))->get()->sum('total_amount');
	 $poststats =['total'=>$countpost,'today'=>$countpost_today,'week'=>$countpost_week,'month'=>$countpost_month];
	 
	 //traffic charts
	 $trafficcharts = [];
	 $trafficcharts['users_web']      =   Customers::where('register_from','web')->get()->count();
	 $trafficcharts['users_android']  =   Customers::where('register_from','android')->get()->count();
	 $trafficcharts['users_ios']      =   Customers::where('register_from','ios')->get()->count();
	 $trafficcharts['orders_web']     =   OrdersDetails::where('device_type','web')->get()->count();
	 $trafficcharts['orders_android'] =   OrdersDetails::where('device_type','android')->get()->count();
	 $trafficcharts['orders_ios']     =   OrdersDetails::where('device_type','ios')->get()->count();
	
	 //profit
	 
	 $countProfit        = $this->getStatistics('total');
	 $countProfit_today  = $this->getStatistics('today');
	 $countProfit_week   = $this->getStatistics('week');
	 $countProfit_month  = $this->getStatistics('month');
	 $profitstats        =['total'=>$countProfit,'today'=>$countProfit_today,'week'=>$countProfit_week,'month'=>$countProfit_month];
	
	 if(Auth::guard('admin')->user()->userType=="vendor"){
	 $dashboard = "vendor.dashboard";
	 }else{
	 $dashboard = "dashboard.dashboard";
	 }
	 return view('gwc.'.$dashboard,compact('cutomersStats','contactStats','categoryStats','productsStats','OrdersStats','paymentStats','codstats','poststats','SoldOutStats','trafficcharts','shipmenttats','profitstats'));
	}
	public function getTotalVendorCodPayment($type=""){
	 $totalAmt=0;
	 $orderLists  = OrdersDetails::with('area');
     $orderLists  = $orderLists->select('gwc_orders_details.*','gwc_orders.*','gwc_products.manufacturer_id','gwc_products.id');
     $orderLists  = $orderLists->join('gwc_orders','gwc_orders.order_id','=','gwc_orders_details.order_id');
     $orderLists  = $orderLists->join('gwc_products','gwc_products.id','=','gwc_orders.product_id');
     $orderLists  = $orderLists->where('gwc_products.manufacturer_id',Auth::guard('admin')->user()->id);
	 if($type=="today"){
	 $orderLists  = $orderLists->whereDate('gwc_orders_details.created_at', Carbon::today());
	 }
	 
	 if($type=="week"){
	 $orderLists  = $orderLists->whereDate('gwc_orders_details.created_at','>=', Carbon::now()->subWeeks(1));
	 }
	 
	 if($type=="month"){
	 $orderLists  = $orderLists->whereDate('gwc_orders_details.created_at','>=', Carbon::now()->subDays(30));
	 }
	 
	 $orderLists  = $orderLists->wherein("gwc_orders_details.pay_mode",["COD","POSTKNET"])
	                           ->where("gwc_orders_details.order_status","completed");
							   
    
	 $orderLists  = $orderLists->groupBy('gwc_orders.id')->get();
	 if(!empty($orderLists) && count($orderLists)>0){
	   foreach($orderLists as $listOrder){
	   $totalAmt+=($listOrder->quantity*$listOrder->unit_price);
	   }
	 }
	 return $totalAmt;
	}
	
	public function getTotalVendorOrders($type=""){
	
	 $orderLists  = OrdersDetails::with('area');
     $orderLists  = $orderLists->select('gwc_orders_details.*','gwc_orders.*','gwc_products.*');
     $orderLists  = $orderLists->join('gwc_orders','gwc_orders.order_id','=','gwc_orders_details.order_id');
     $orderLists  = $orderLists->join('gwc_products','gwc_products.id','=','gwc_orders.product_id');
     $orderLists  = $orderLists->where('gwc_products.manufacturer_id',Auth::guard('admin')->user()->id);
	 if($type=="today"){
	 $orderLists  = $orderLists->whereDate('gwc_orders_details.created_at', Carbon::today());
	 }
	 
	 if($type=="week"){
	 $orderLists  = $orderLists->whereDate('gwc_orders_details.created_at','>=', Carbon::now()->subWeeks(1));
	 }
	 
	 if($type=="month"){
	 $orderLists  = $orderLists->whereDate('gwc_orders_details.created_at','>=', Carbon::now()->subDays(30));
	 }
	 
     $orderLists  = $orderLists->orderBy('gwc_orders_details.id','DESC')->groupBy('gwc_orders_details.order_id');
	 $orderLists  = $orderLists->get();
	 
	 return $orderLists;
	}
	
	public function getTotalCompletedVendorOrders($type=""){
	
	 $orderLists  = OrdersDetails::with('area');
     $orderLists  = $orderLists->select('gwc_orders_details.*','gwc_orders.*','gwc_products.*');
     $orderLists  = $orderLists->join('gwc_orders','gwc_orders.order_id','=','gwc_orders_details.order_id');
     $orderLists  = $orderLists->join('gwc_products','gwc_products.id','=','gwc_orders.product_id');
     $orderLists  = $orderLists->where('gwc_products.manufacturer_id',Auth::guard('admin')->user()->id);
	 if($type=="today"){
	 $orderLists  = $orderLists->where('gwc_orders_details.order_status','completed')->whereDate('gwc_orders_details.created_at', Carbon::today());
	 }else if($type=="week"){
	 $orderLists  = $orderLists->where('gwc_orders_details.order_status','completed')->whereDate('gwc_orders_details.created_at','>=', Carbon::now()->subWeeks(1));
	 }else if($type=="month"){
	 $orderLists  = $orderLists->where('gwc_orders_details.order_status','completed')->whereDate('gwc_orders_details.created_at','>=', Carbon::now()->subDays(30));
	 }else{
	 $orderLists  = $orderLists->where('gwc_orders_details.order_status','completed');
	 }
	 
     $orderLists  = $orderLists->orderBy('gwc_orders_details.id','DESC')->groupBy('gwc_orders_details.order_id');
	 $orderLists  = $orderLists->get();
	 
	 return $orderLists;
	}
	
	public function getTotalKnetVendorPayments($type=""){
	
	 $totalAmt = 0;
	 
	 $orderLists  = DB::table('gwc_orders');
     $orderLists  = $orderLists->select('gwc_orders.*','gwc_products.id','gwc_products.manufacturer_id','gwc_transaction.*');
     $orderLists  = $orderLists->join('gwc_products','gwc_products.id','=','gwc_orders.product_id');
	 $orderLists  = $orderLists->join('gwc_transaction','gwc_transaction.trackid','=','gwc_orders.order_id');
     $orderLists  = $orderLists->where('gwc_products.manufacturer_id',Auth::guard('admin')->user()->id);
	 if($type=="today"){
	 $orderLists  = $orderLists->where('gwc_transaction.presult','CAPTURED')->whereDate('gwc_transaction.created_at', Carbon::today());
	 }else if($type=="week"){
	 $orderLists  = $orderLists->where('gwc_transaction.presult','CAPTURED')->whereDate('gwc_transaction.created_at','>=', Carbon::now()->subWeeks(1));
	 }else if($type=="month"){
	 $orderLists  = $orderLists->where('gwc_transaction.presult','CAPTURED')->whereDate('gwc_transaction.created_at','>=', Carbon::now()->subDays(30));
	 }else{
	 $orderLists  = $orderLists->where('gwc_transaction.presult','CAPTURED');
	 }
     $orderLists  = $orderLists->groupBy('gwc_orders.id')->get();
	 if(!empty($orderLists) && count($orderLists)>0){
	   foreach($orderLists as $listOrder){
	   $totalAmt+=($listOrder->quantity*$listOrder->unit_price);
	   }
	 }
	 return $totalAmt;
	}
	
	public function getTotalofflineVendorPayments($type=""){
	
	 $totalAmt = 0;
	 
	 $orderLists  = DB::table('gwc_orders_details');
     $orderLists  = $orderLists->select('gwc_orders_details.*','gwc_orders.*','gwc_products.id','gwc_products.manufacturer_id');
     $orderLists  = $orderLists->join('gwc_products','gwc_products.id','=','gwc_orders.product_id');
	 $orderLists  = $orderLists->join('gwc_transaction','gwc_transaction.trackid','=','gwc_orders.order_id');
     $orderLists  = $orderLists->where('gwc_products.manufacturer_id',Auth::guard('admin')->user()->id);
	 if($type=="today"){
	 $orderLists  = $orderLists->where('gwc_orders_details.is_paid',1)->wherein('gwc_orders_details.pay_mode',['COD','POSTKNET'])->whereDate('gwc_orders_details.created_at', Carbon::today());
	 }else if($type=="week"){
	 $orderLists  = $orderLists->where('gwc_orders_details.is_paid',1)->wherein('gwc_orders_details.pay_mode',['COD','POSTKNET'])->whereDate('gwc_orders_details.created_at','>=', Carbon::now()->subWeeks(1));
	 }else if($type=="month"){
	 $orderLists  = $orderLists->where('gwc_orders_details.is_paid',1)->wherein('gwc_orders_details.pay_mode',['COD','POSTKNET'])->whereDate('gwc_orders_details.created_at','>=', Carbon::now()->subDays(30));
	 }else{
	 $orderLists  = $orderLists->where('gwc_orders_details.is_paid',1)->wherein('gwc_orders_details.pay_mode',['COD','POSTKNET']);
	 }
     $orderLists  = $orderLists->groupBy('gwc_orders.id')->get();
	 if(!empty($orderLists) && count($orderLists)>0){
	   foreach($orderLists as $listOrder){
	   $totalAmt+=($listOrder->quantity*$listOrder->unit_price);
	   }
	 }
	 return $totalAmt;
	}
	
	public function getStatistics($type='total'){
	$costPrice=0;$retailPrice=0;$profitPrice=0;
	if($type=="total"){
	$orderLists = DB::table('gwc_orders_details')->where('gwc_orders_details.order_status','completed')
	              ->select(
				  'gwc_orders_details.created_at',
				  'gwc_orders_details.order_id',
				  'gwc_orders.order_id',
				  'gwc_orders.quantity',
				  'gwc_orders.unit_price',
				  'gwc_orders.product_id',
				  'gwc_products.id',
				  'gwc_products.cost_price'
				  )
				  ->join('gwc_orders','gwc_orders.order_id','=','gwc_orders_details.order_id')
				  ->join('gwc_products','gwc_products.id','=','gwc_orders.product_id')
				  ->get();
	}
	if($type=="today"){
	$orderLists = DB::table('gwc_orders_details')->where('gwc_orders_details.order_status','completed')
	              ->select(
				  'gwc_orders_details.created_at',
				  'gwc_orders_details.order_id',
				  'gwc_orders.order_id',
				  'gwc_orders.quantity',
				  'gwc_orders.unit_price',
				  'gwc_orders.product_id',
				  'gwc_products.id',
				  'gwc_products.cost_price'
				  )
				  ->join('gwc_orders','gwc_orders.order_id','=','gwc_orders_details.order_id')
				  ->join('gwc_products','gwc_products.id','=','gwc_orders.product_id')
				  ->whereDate('gwc_orders_details.created_at', Carbon::today())
				  ->get();
	}
	
	if($type=="week"){
	$orderLists = DB::table('gwc_orders_details')->where('gwc_orders_details.order_status','completed')
	              ->select(
				  'gwc_orders_details.created_at',
				  'gwc_orders_details.order_id',
				  'gwc_orders.order_id',
				  'gwc_orders.quantity',
				  'gwc_orders.unit_price',
				  'gwc_orders.product_id',
				  'gwc_products.id',
				  'gwc_products.cost_price'
				  )
				  ->join('gwc_orders','gwc_orders.order_id','=','gwc_orders_details.order_id')
				  ->join('gwc_products','gwc_products.id','=','gwc_orders.product_id')
				  ->whereDate('gwc_orders_details.created_at','>=', Carbon::now()->subWeeks(1))
				  ->get();
	}
	
	if($type=="month"){
	$orderLists = DB::table('gwc_orders_details')->where('gwc_orders_details.order_status','completed')
	              ->select(
				  'gwc_orders_details.created_at',
				  'gwc_orders_details.order_id',
				  'gwc_orders.order_id',
				  'gwc_orders.quantity',
				  'gwc_orders.unit_price',
				  'gwc_orders.product_id',
				  'gwc_products.id',
				  'gwc_products.cost_price'
				  )
				  ->join('gwc_orders','gwc_orders.order_id','=','gwc_orders_details.order_id')
				  ->join('gwc_products','gwc_products.id','=','gwc_orders.product_id')
				  ->whereDate('gwc_orders_details.created_at','>=', Carbon::now()->subDays(30))
				  ->get();
	}				  
				  
	if(!empty($orderLists) && count($orderLists)>0){

	foreach($orderLists as $orderList){ 
	$costPrice+=($orderList->cost_price*$orderList->quantity);
	$retailPrice+=($orderList->unit_price*$orderList->quantity);
	}
	}
	$profitPrice=$retailPrice-$costPrice;
	
	return ['costPrice'=>$costPrice,'retailPrice'=>$retailPrice,'profitPrice'=>$profitPrice];		  
	}
	
	/**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        
		//save logs
		$key_name   = "logout";
		$key_id     = Auth::guard('admin')->user()->id;
		$message    = Auth::guard('admin')->user()->name."(".Auth::guard('admin')->user()->userType.") is logged out from Admin Panel.";
		$created_by = Auth::guard('admin')->user()->id;
		Common::saveLogs($key_name,$key_id,$message,$created_by);
		//end save logs
		if(Auth::guard('admin')->user()->userType=="admin"){
		Auth::guard('admin')->logout();
        return redirect('/gwc/')->with("info","You have successfully logged out from Admin Panel");
		}
		if(Auth::guard('admin')->user()->userType=="vendor"){
		Auth::guard('admin')->logout();
        return redirect('/vendor/')->with("info","You have successfully logged out from Admin Panel");
		}
    }
	
	///get setting details
	public static function getSettingsDetails(){
	 $settings = Settings::where('keyname','setting')->first(); 
	 return $settings;
	}
	
	//get chart for sale
	public static function getChartvalues(){
	$v='';
	for($m=1;$m<=12;$m++){
	$v.=self::Monthlysale($m).',';	
	}
	return $v;
	}
	//
	public static function Monthlysale($m){
	$amt=0;
	if(strlen($m)==1){$m="0".$m;}
	$date = date("Y")."-".$m;
	$soldorders = 	OrdersDetails::where('order_status','completed')->where('created_at','LIKE','%'.$date.'%')->get();
	if(!empty($soldorders)){
	foreach($soldorders as $soldorder){
	$amt+=self::getOrderAmounts($soldorder->id);	
	}
	}
	return $amt;
	}
	
	//get orders
	//get chart for sale
	public static function getChartvalues_Orders(){
	$v='';
	for($m=1;$m<=12;$m++){
	$v.=self::Monthlyorder($m).',';	
	}
	return $v;
	}
	//
	public static function Monthlyorder($m){
	$amt=0;
	if(strlen($m)==1){$m="0".$m;}
	$date = date("Y")."-".$m;
	$soldorders = 	OrdersDetails::where('order_status','completed')->where('created_at','LIKE','%'.$date.'%')->get()->count();
	return $soldorders;
	}
	
	
	//totorder amount
	public static function thisMonthGrow(){
	$cdate = date("Y-m");
	$pdate = date("Y-m",strtotime("-1 months"));
	$currentAmount=0;
	$prevAmount=0;
	$percentChange=0;
	$soldorders_c = 	OrdersDetails::where('order_status','completed')->where('created_at','LIKE','%'.$cdate.'%')->get();
	if(!empty($soldorders_c)){
	foreach($soldorders_c as $soldorder_c){
	$currentAmount+=self::getOrderAmounts($soldorder_c->id);	
	}
	}	
	//
	$soldorders_p = 	OrdersDetails::where('order_status','completed')->where('created_at','LIKE','%'.$pdate.'%')->get();
	if(!empty($soldorders_p)){
	foreach($soldorders_p as $soldorder_p){
	$prevAmount+=self::getOrderAmounts($soldorder_p->id);	
	}
	}
	//get percentage
	if(!empty($prevAmount) && !empty($currentAmount)){
	$percentChange = (1 - $prevAmount / $currentAmount) * 100;	
	}else{
	$percentChange = 0;
	}
    return $percentChange;
	}
	
	//order grow
	public static function thisMonthOrderGrow(){
	$cdate = date("Y-m");
	$pdate = date("Y-m",strtotime("-1 months"));
	$currentAmount=0;
	$prevAmount=0;
	$percentChange=0;
	$soldorders_c = 	OrdersDetails::where('order_status','completed')->where('created_at','LIKE','%'.$cdate.'%')->get();
	if(!empty($soldorders_c)){
	$currentAmount=count($soldorders_c);	
	}	
	//
	$soldorders_p = 	OrdersDetails::where('order_status','completed')->where('created_at','LIKE','%'.$pdate.'%')->get();
	if(!empty($soldorders_p)){
	$prevAmount=count($soldorders_p);	
	}
	//get percentage
	if(!empty($currentAmount)){
	$percentChange = (1 - $prevAmount / $currentAmount) * 100;
	}else{
	$percentChange =0;
	}
    return $percentChange;
	}
	 //get total order amount
   public static function getOrderAmounts($id){
	$totalAmt=0;
	$orderDetails = OrdersDetails::Where('id',$id)->first();
	$listOrders   = Orders::where('oid',$id)->get();	
	if(!empty($listOrders) && count($listOrders)>0){
	foreach($listOrders as $listOrder){
    $totalAmt+=($listOrder->quantity*$listOrder->unit_price);
	}
	//apply coupon if its not free
	if(!empty($orderDetails->coupon_code) && empty($orderDetails->coupon_free)){
	$totalAmt=$totalAmt-$orderDetails->coupon_amount;	
	}
	//apply delivery charges if coupon is empty
	if(empty($orderDetails->coupon_free)){
	$totalAmt=$totalAmt+$orderDetails->delivery_charges;		
	}
	}
	
	return $totalAmt;
	}
	//get unred contact us
	public static function getUnreadContacts(){
	 $contacts = Contactus::where('is_read',0)->orderBy('created_at','DESC')->get(); 
	 return $contacts;
	}
	//get logs
	public static function getLogs(){
	 $contacts = AdminLogs::orderBy('created_at','DESC')->whereDate('created_at', Carbon::today())->get(); 
	 return $contacts;
	}
	///get details
	public static function getContactsLists(){
	 $contacts = Contactus::orderBy('created_at','DESC')->get(); 
	 return $contacts;
	}
	
	
	//ga
	public static function gareport(){
	$settings = Settings::where('keyname','setting')->first();
	if(!empty($settings->gakeys)  && !empty($settings->google_analyticsemail)   && !empty($settings->google_profileid)){
	$p12 = public_path('/uploads/logo/'.$settings->gakeys);
	$ga_profile_id = $settings->google_profileid;
	$ga = new Gapi($settings->google_analyticsemail, $p12);
    $accessToken = $ga->getToken();
	}else{
	$accessToken='';
	}
    return $accessToken;
	}

}
