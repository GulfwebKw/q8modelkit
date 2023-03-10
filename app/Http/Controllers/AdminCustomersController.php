<?php

namespace App\Http\Controllers;

use App\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cookie;

use App\ProductOptionsCustomChosen;
use App\Customers;
use App\CustomersAddress;
use App\Settings;
use App\Country;
use App\OrdersDetails;
use App\Orders;
use App\Product;
use App\ProductAttribute;
use App\ProductOptions;
use App\ProductOptionsChild;
use App\OrdersTrack;
use App\OrdersOption;
use App\ProductReview;
use App\CustomersWish;
use App\Transaction;
use App\WebPush;
use Image;
use File;
use Response;
use PDF;
use Hash;
use Auth;
use DB;
use Common;

use App\Mail\SendGrid;
use Mail;

class AdminCustomersController extends Controller
{

	public function listmanufactureorders(Request $request)
	{
		if (empty($request->mid)) {
			abort(404);
		}

		$settingInfo = Settings::where("keyname", "setting")->first();

		//check search queries
		if (!empty($request->get('q'))) {
			$q = $request->get('q');
		} else {
			$q = $request->q;
		}

		$orderLists = new Orders;
		$orderLists = $orderLists->select('gwc_orders.*', 'gwc_products.id', 'gwc_products.manufacturer_id', 'gwc_orders_details.*');
		$orderLists = $orderLists->join('gwc_products', 'gwc_products.id', '=', 'gwc_orders.product_id');
		$orderLists = $orderLists->join('gwc_orders_details', 'gwc_orders_details.order_id', '=', 'gwc_orders.order_id');
		$orderLists = $orderLists->where('gwc_products.manufacturer_id', $request->mid);

		if (!empty($q)) {
			$orderLists = $orderLists->where(function ($sq) use ($q) {
				$sq->where('gwc_orders_details.name', 'LIKE', '%' . $q . '%')
					->orwhere('gwc_orders_details.email', 'LIKE', '%' . $q . '%')
					->orwhere('gwc_orders_details.mobile', 'LIKE', '%' . $q . '%')
					->orwhere('gwc_orders_details.order_id', 'LIKE', '%' . $q . '%');
			});
		}
		//filter by date range
		if (!empty(Session::get('order_filter_dates'))) {
			$explodeDates = explode("-", Session::get('order_filter_dates'));
			if (!empty($explodeDates[0]) && !empty($explodeDates[1])) {
				$date1 = date("Y-m-d", strtotime($explodeDates[0]));
				$date2 = date("Y-m-d", strtotime($explodeDates[1]));
				$orderLists = $orderLists->whereBetween('gwc_orders_details.created_at', [$date1, $date2]);
			}
		}
		if (!empty(Session::get('order_filter_status')) && Session::get('order_filter_status') <> "all") {
			$orderLists = $orderLists->where('gwc_orders_details.order_status', '=', Session::get('order_filter_status'));
		}
		if (!empty(Session::get('pay_filter_status')) && Session::get('pay_filter_status') == 'paid') {
			$orderLists = $orderLists->where('gwc_orders_details.is_paid', '=', 1);
		}
		if (!empty(Session::get('pay_filter_status')) && Session::get('pay_filter_status') == 'notpaid') {
			$orderLists = $orderLists->where('gwc_orders_details.is_paid', '!=', 1);
		}
		if (!empty(Session::get('order_customers'))) {
			$orderLists = $orderLists->where('gwc_orders_details.customer_id', '=', Session::get('order_customers'));
		}


		$orderLists = $orderLists->orderBy('gwc_orders.id', 'DESC')->groupBy('gwc_orders.order_id')->paginate($settingInfo->item_per_page_back);

		//collect customers listing for dropdown
		$customersLists = DB::table('gwc_orders_details')
			->select('gwc_orders_details.customer_id', 'gwc_customers.id', 'gwc_orders_details.name')
			->join('gwc_customers', 'gwc_customers.id', '=', 'gwc_orders_details.customer_id')
			->GroupBy('gwc_orders_details.customer_id')
			->get();



		return view('gwc.manufacturer.indexorders', compact('orderLists', 'settingInfo'));
	}


	public static function countmanufactureAmount($orderid, $mid)
	{
		$totalAmt = 0;
		$orderLists = DB::table('gwc_orders')->where('gwc_orders.order_id', $orderid);
		$orderLists = $orderLists->select('gwc_orders.*', 'gwc_products.id', 'gwc_products.manufacturer_id');
		$orderLists = $orderLists->join('gwc_products', 'gwc_products.id', '=', 'gwc_orders.product_id');
		$orderLists = $orderLists->where('gwc_products.manufacturer_id', $mid);
		$orderLists = $orderLists->get();

		if (!empty($orderLists) && count($orderLists) > 0) {
			foreach ($orderLists as $orderList) {
				$totalAmt += ($orderList->quantity * $orderList->unit_price);
			}
		}

		return $totalAmt;
	}

	public static function countmanufactureOrders($mid)
	{
		$orderLists = DB::table('gwc_orders')->where('gwc_products.manufacturer_id', $mid);
		$orderLists = $orderLists->select('gwc_orders.*', 'gwc_products.id', 'gwc_products.manufacturer_id');
		$orderLists = $orderLists->join('gwc_products', 'gwc_products.id', '=', 'gwc_orders.product_id');
		$orderLists = $orderLists->groupBy('gwc_orders.order_id')->get();
		return count($orderLists);
	}

	public function manufactureordersdetails(Request $request)
	{

		$settingInfo = Settings::where("keyname", "setting")->first();

		$orderLists = DB::table('gwc_orders')
			->where('gwc_products.manufacturer_id', $request->mid)
			->where('gwc_orders.oid', $request->oid);
		$orderLists = $orderLists->select('gwc_orders.*', 'gwc_products.id', 'gwc_products.manufacturer_id');
		$orderLists = $orderLists->join('gwc_products', 'gwc_products.id', '=', 'gwc_orders.product_id');
		$orderLists = $orderLists->get();

		$orderDetails = OrdersDetails::where('id', $request->oid)->first();

		return view('gwc.manufacturer.view', compact('orderDetails', 'settingInfo', 'orderLists'));
	}


	public static function loadmodalforordernotification(Request $request)
	{
		$orderid = $request->orderid;
		$type    = $request->type;
		if (!empty($orderid) && $type == "send_email") {
			$orderDetails = OrdersDetails::where('order_id', $orderid)->first();
			$txtMessage = '<div class="row"><div class="col-lg-12"><strong>' . trans('adminMessage.orderid') . ':</strong>' . $orderid . '</div></div>';
		} else if (!empty($orderid) && $type == "send_sms") {
		} else {
			return ["statuc" => 400, "message" => trans('adminMessage.invalidrequest')];
		}
	}


	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */


	public function index(Request $request) //Request $request
	{

		$settingInfo = Settings::where("keyname", "setting")->first();

		//check search queries
		if (!empty($request->get('q'))) {
			$q = $request->get('q');
		} else {
			$q = $request->q;
		}


		//menus records
		if (!empty($q)) {
			$customersLists = Customers::where('name', 'LIKE', '%' . $q . '%')
				->orwhere('email', 'LIKE', '%' . $q . '%')
				->orwhere('mobile', 'LIKE', '%' . $q . '%')
				->orwhere('username', 'LIKE', '%' . $q . '%')
				->orderBy('created_at', 'DESC')
				->paginate($settingInfo->item_per_page_back);
			$customersLists->appends(['q' => $q]);
		} else {
			$customersLists = Customers::orderBy('id', 'DESC')->paginate($settingInfo->item_per_page_back);
		}
		return view('gwc.customers.index', ['customersLists' => $customersLists]);
	}


	/**
	Display the Services listings
	 **/
	public function create()
	{
		return view('gwc.customers.create');
	}



	/**
	Store New Services Details
	 **/
	public function store(Request $request)
	{

		$settingInfo = Settings::where("keyname", "setting")->first();
		if (!empty($settingInfo->image_thumb_w) && !empty($settingInfo->image_thumb_h)) {
			$image_thumb_w = $settingInfo->image_thumb_w;
			$image_thumb_h = $settingInfo->image_thumb_h;
		} else {
			$image_thumb_w = 200;
			$image_thumb_h = 200;
		}

		if (!empty($settingInfo->image_big_w) && !empty($settingInfo->image_big_h)) {
			$image_big_w = $settingInfo->image_big_w;
			$image_big_h = $settingInfo->image_big_h;
		} else {
			$image_big_w = 800;
			$image_big_h = 800;
		}
		//field validation
		$this->validate($request, [
			'name'         => 'required|min:3|max:150|string',
			'email'        => 'required|email|min:3|max:150|string|unique:gwc_customers,email',
			'mobile'       => 'required|min:3|max:10|unique:gwc_customers,mobile',
			'username'     => 'required|min:3|max:20|string|unique:gwc_customers,username',
			'password'     => 'required|min:3|max:150|string',
			'image'        => 'mimes:jpeg,png,jpg,gif,svg|max:2048'
		]);



		try {


			//upload image
			$imageName = "";
			if ($request->hasfile('image')) {
				$imageName = 'c-' . md5(time()) . '.' . $request->image->getClientOriginalExtension();
				$request->image->move(public_path('uploads/customers'), $imageName);
				// open file a image resource
				$imgbig = Image::make(public_path('uploads/customers/' . $imageName));
				//resize image
				$imgbig->resize($image_big_w, $image_big_h, function ($constraint) {
					$constraint->aspectRatio();
				}); //Fixed w,h
				//if($settingInfo->is_watermark==1 && !empty($settingInfo->watermark_img)){
				// insert watermark at bottom-right corner with 10px offset
				//$imgbig->insert(public_path('uploads/logo/'.$settingInfo->watermark_img), 'bottom-right', 10, 10);
				//}
				// save to imgbig thumb
				$imgbig->save(public_path('uploads/customers/' . $imageName));

				//create thumb
				// open file a image resource
				$img = Image::make(public_path('uploads/customers/' . $imageName));
				//resize image
				$img->resize($image_thumb_w, $image_thumb_h, function ($constraint) {
					$constraint->aspectRatio();
				}); //Fixed w,h
				// save to thumb
				$img->save(public_path('uploads/customers/thumb/' . $imageName));
			}

			$customers = new Customers;
			$customers->name = $request->input('name');
			$customers->email = $request->input('email');
			$customers->mobile = $request->input('mobile');
			$customers->username = $request->input('username');
			$customers->password = bcrypt($request->input('password'));
			$customers->is_active = !empty($request->input('is_active')) ? $request->input('is_active') : '0';
			$customers->image = $imageName;
			$customers->save();

			//save logs
			$key_name   = "customers";
			$key_id     = $customers->id;
			$message    = "New customer record is added as (" . $request->input('name') . ")";
			$created_by = Auth::guard('admin')->user()->id;
			Common::saveLogs($key_name, $key_id, $message, $created_by);
			//end save logs

			return redirect('/gwc/customers')->with('message-success', 'Customer is added successfully');
		} catch (\Exception $e) {
			return redirect()->back()->with('message-error', $e->getMessage());
		}
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		$editcustomers = Customers::find($id);
		return view('gwc.customers.edit', compact('editcustomers'));
	}

	/**
	 * Show the form for change password the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function changepass($id)
	{
		$editcustomers = Customers::find($id);
		return view('gwc.customers.changepass', compact('editcustomers'));
	}



	/**
	 * Show the details of the services.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function view($id)
	{
		$customerDetails = Customers::find($id);
		$listCountries   = Country::where('parent_id', '0')->where('is_active', 1)->get();
		$listaddresss    = CustomersAddress::where('customer_id', $id)->get();
		return view('gwc.customers.view', compact('customerDetails', 'listCountries', 'listaddresss'));
	}



	public function editchangepass(Request $request, $id)
	{

		$v = Validator::make($request->all(), [
			'new_password'      => 'required',
			'confirm_password'  => 'required|same:new_password',
		]);

		if ($v->fails()) {
			return redirect()->back()->withErrors($v->errors())->withInput();
		}


		try {



			$customers = Customers::where("id", $id)->first();

			//save logs
			$key_name   = "customers";
			$key_id     = $customers->id;
			$message    = "Customer Password is changed for " . $customers['name'];
			$created_by = Auth::guard('admin')->user()->id;
			Common::saveLogs($key_name, $key_id, $message, $created_by);
			//end save logs

			$customers->password   = bcrypt($request->new_password);
			$customers->updated_at = date("Y-m-d H:i:s");
			$customers->save();
			return redirect()->back()->with('message-success', 'Password is changed successfully.');
		} catch (\Exception $e) {
			return redirect()->back()->with('message-error', $e->getMessage());
		}
	}
	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{

		$settingInfo = Settings::where("keyname", "setting")->first();
		if (!empty($settingInfo->image_thumb_w) && !empty($settingInfo->image_thumb_h)) {
			$image_thumb_w = $settingInfo->image_thumb_w;
			$image_thumb_h = $settingInfo->image_thumb_h;
		} else {
			$image_thumb_w = 200;
			$image_thumb_h = 200;
		}

		if (!empty($settingInfo->image_big_w) && !empty($settingInfo->image_big_h)) {
			$image_big_w = $settingInfo->image_big_w;
			$image_big_h = $settingInfo->image_big_h;
		} else {
			$image_big_w = 800;
			$image_big_h = 800;
		}

		//field validation  
		$this->validate($request, [
			'name'         => 'required|min:3|max:150|string',
			'email'        => 'required|email|min:3|max:150|string|unique:gwc_customers,email,' . $id,
			'mobile'       => 'required|min:3|max:10|unique:gwc_customers,email,' . $id,
			'image'        => 'mimes:jpeg,png,jpg,gif,svg|max:2048'
		]);



		try {


			$customers = Customers::find($id);
			$imageName = '';
			//upload image
			if ($request->hasfile('image')) {
				//delete image from folder
				if (!empty($customers->image)) {
					$web_image_path = "/uploads/customers/" . $customers->image;
					$web_image_paththumb = "/uploads/customers/thumb/" . $customers->image;
					if (File::exists(public_path($web_image_path))) {
						File::delete(public_path($web_image_path));
						File::delete(public_path($web_image_paththumb));
					}
				}
				//
				$imageName = 'c-' . md5(time()) . '.' . $request->image->getClientOriginalExtension();

				$request->image->move(public_path('uploads/customers'), $imageName);
				//create thumb
				// open file a image resource
				$imgbig = Image::make(public_path('uploads/customers/' . $imageName));
				//resize image
				$imgbig->resize($image_big_w, $image_big_h, function ($constraint) {
					$constraint->aspectRatio();
				}); //Fixed w,h

				//if($settingInfo->is_watermark==1 && !empty($settingInfo->watermark_img)){
				// insert watermark at bottom-right corner with 10px offset
				//$imgbig->insert(public_path('uploads/logo/'.$settingInfo->watermark_img), 'bottom-right', 10, 10);
				//}
				// save to imgbig thumb
				$imgbig->save(public_path('uploads/customers/' . $imageName));

				//create thumb
				// open file a image resource
				$img = Image::make(public_path('uploads/customers/' . $imageName));
				//resize image
				$img->resize($image_thumb_w, $image_thumb_h, function ($constraint) {
					$constraint->aspectRatio();
				}); //Fixed w,h
				// save to thumb
				$img->save(public_path('uploads/customers/thumb/' . $imageName));
			} else {
				$imageName = $customers->image;
			}

			$customers->name = $request->input('name');
			$customers->email = $request->input('email');
			$customers->mobile = $request->input('mobile');
			$customers->is_active = !empty($request->input('is_active')) ? $request->input('is_active') : '0';
			$customers->image = $imageName;
			$customers->save();

			//save logs
			$key_name   = "customers";
			$key_id     = $customers->id;
			$message    = "Customer details are updated for " . $request->input('name');
			$created_by = Auth::guard('admin')->user()->id;
			Common::saveLogs($key_name, $key_id, $message, $created_by);
			//end save logs


			return redirect('/gwc/customers')->with('message-success', 'Information is updated successfully');
		} catch (\Exception $e) {
			return redirect()->back()->with('message-error', $e->getMessage());
		}
	}

	/**
	 * Delete the Image.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */

	public function deleteImage($id)
	{
		$customers = Customers::find($id);
		//delete image from folder
		if (!empty($customers->image)) {
			$web_image_path = "/uploads/customers/" . $customers->image;
			$web_image_paththumb = "/uploads/customers/thumb/" . $customers->image;
			if (File::exists(public_path($web_image_path))) {
				File::delete(public_path($web_image_path));
				File::delete(public_path($web_image_paththumb));
			}
		}

		//save logs
		$key_name   = "customers";
		$key_id     = $customers->id;
		$message    = "Customer image is removed for " . $customers->name;
		$created_by = Auth::guard('admin')->user()->id;
		Common::saveLogs($key_name, $key_id, $message, $created_by);
		//end save logs

		$customers->image = '';
		$customers->save();
		return redirect()->back()->with('message-success', 'Image is deleted successfully');
	}

	/**
	 * Delete customers along with childs via ID.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		//check param ID
		if (empty($id)) {
			return redirect('/gwc/customers')->with('message-error', 'Param ID is missing');
		}
		//get cat info
		$customers = Customers::find($id);
		//check cat id exist or not
		if (empty($customers->id)) {
			return redirect('/gwc/customers')->with('message-error', 'No record found');
		}

		//delete parent cat mage
		if (!empty($customers->image)) {
			$web_image_path = "/uploads/customers/" . $customers->image;
			$web_image_paththumb = "/uploads/customers/thumb/" . $customers->image;
			if (File::exists(public_path($web_image_path))) {
				File::delete(public_path($web_image_path));
				File::delete(public_path($web_image_paththumb));
			}
		}
		//save logs
		$key_name   = "customers";
		$key_id     = $customers->id;
		$message    = "Customer account is removed for " . $customers->name;
		$created_by = Auth::guard('admin')->user()->id;
		Common::saveLogs($key_name, $key_id, $message, $created_by);
		//end save logs

		//remove belongings
		$this->deleteBelongsAddress($customers->id);

		$customers->delete();
		return redirect()->back()->with('message-success', 'customers is deleted successfully');
	}

	//delete address
	public function deleteBelongsAddress($id)
	{
		$totalAddress = CustomersAddress::where('customer_id', $id)->get();
		if (count($totalAddress) > 1) {
			foreach ($totalAddress as $myaddress) {
				$PrevDetails  = CustomersAddress::where('id', $myaddress->id)->first();
				if (!empty($PrevDetails->id)) {
					$PrevDetails->delete();
				}
			}
		}
	}

	//download pdf

	public function downloadPDF()
	{
		$customers = Customers::get();
		$pdf = PDF::loadView('gwc.customers.pdf', compact('customers'));
		return $pdf->download('customers.pdf');
	}

	//update status
	public function updateStatusAjax(Request $request)
	{
		$recDetails = Customers::where('id', $request->id)->first();
		if ($recDetails['is_active'] == 1) {
			$active = 0;
		} else {
			$active = 1;
		}
		//save logs
		$key_name   = "customers";
		$key_id     = $recDetails->id;
		$message    = "Customer status is changed to " . $active . " for " . $recDetails->name;
		$created_by = Auth::guard('admin')->user()->id;
		Common::saveLogs($key_name, $key_id, $message, $created_by);
		//end save logs

		$recDetails->is_active = $active;
		$recDetails->save();
		return ['status' => 200, 'message' => 'Status is modified successfully'];
	}

	public function updateSellerStatusAjax(Request $request)
	{
		$recDetails = Customers::where('id', $request->id)->first();
		if ($recDetails['is_seller'] == 1) {
			$active = 0;
		} else {
			$active = 1;
		}
		//save logs
		$key_name   = "customers";
		$key_id     = $recDetails->id;
		$message    = "Customer seller status is changed to " . $active . " for " . $recDetails->name;
		$created_by = Auth::guard('admin')->user()->id;
		Common::saveLogs($key_name, $key_id, $message, $created_by);
		//end save logs

		$recDetails->is_seller = $active;
		$recDetails->save();
		return ['status' => 200, 'message' => 'Status is modified successfully'];
	}

	///add address
	public function addAddress(Request $request, $id)
	{
		$this->validate($request, [
			'title'        => 'required|min:3|max:150|string',
			'country'      => 'required|numeric|gt:0',
			'state'        => 'required|numeric|gt:0',
			'area'         => 'required|numeric|gt:0',
			'block'        => 'required'
		]);

		try {


			$customer = Customers::find($id);

			$address = new CustomersAddress;
			$address->customer_id = $id;
			$address->title = $request->input('title');
			$address->country_id = $request->input('country');
			$address->state_id = $request->input('state');
			$address->area_id = $request->input('area');
			$address->block = $request->input('block');
			$address->street = $request->input('street');
			$address->avenue = $request->input('avenue');
			$address->house = $request->input('house');
			$address->floor = $request->input('floor');
			$address->save();

			//save logs
			$key_name   = "customers";
			$key_id     = $address->id;
			$message    = "New Address (" . $address->title . ") added for " . $customer->name;
			$created_by = Auth::guard('admin')->user()->id;
			Common::saveLogs($key_name, $key_id, $message, $created_by);
			//end save logs


			return redirect()->back()->with('message-success', 'Record is added successfully');
		} catch (\Exception $e) {
			return redirect()->back()->with('message-error', $e->getMessage());
		}
	}
	//get address
	public static function getCustAddress($addressid)
	{
		$address = CustomersAddress::find($addressid);
		$addr = '';
		$country = Country::find($address->country_id);
		$state   = Country::find($address->state_id);
		$area = Country::find($address->area_id);

		if (!empty($country->name_en)) {
			$addr .= '<p><b>Country : </b>' . $country->name_en . '</p>';
		}
		if (!empty($state->name_en)) {
			$addr .= '<p><b>State : </b>' . $state->name_en . '</p>';
		}
		if (!empty($area->name_en)) {
			$addr .= '<p><b>Area : </b>' . $area->name_en . '</p>';
		}
		if (!empty($address->block)) {
			$addr .= '<p><b>Block : </b>' . $address->block . '</p>';
		}
		if (!empty($address->street)) {
			$addr .= '<p><b>Street : </b>' . $address->street . '</p>';
		}
		if (!empty($address->avenue)) {
			$addr .= '<p><b>Avenue : </b>' . $address->avenue . '</p>';
		}
		if (!empty($address->house)) {
			$addr .= '<p><b>House : </b>' . $address->house . '</p>';
		}
		if (!empty($address->floor)) {
			$addr .= '<p><b>Floor : </b>' . $address->floor . '</p>';
		}
		return $addr;
	}

	//choose default address
	public function chooseDefaultAddress($id)
	{
		$recDetails  = CustomersAddress::where('id', $id)->first();
		//reset previous status
		$totalAddress = CustomersAddress::where('customer_id', $recDetails->customer_id)->get();
		if (count($totalAddress) > 1) {
			foreach ($totalAddress as $myaddress) {
				$PrevDetails  = CustomersAddress::where('id', $myaddress->id)->first();
				if (!empty($PrevDetails)) {
					$PrevDetails->is_default = 0;
					$PrevDetails->save();
				}
			}
		}


		$custDetails = Customers::where('id', $recDetails->customer_id)->first();
		if ($recDetails['is_default'] == 1) {
			$active = 0;
		} else {
			$active = 1;
		}
		//save logs
		$key_name   = "customers";
		$key_id     = $recDetails->id;
		$message    = "Default Address is changed to " . $active . " for " . $custDetails->name;
		$created_by = Auth::guard('admin')->user()->id;
		Common::saveLogs($key_name, $key_id, $message, $created_by);
		//end save logs

		$recDetails->is_default = $active;
		$recDetails->save();

		return ['status' => 200, 'message' => 'Status is modified successfully'];
	}
	//remove address
	public function deleteAddress($cid, $id)
	{

		//check param ID
		if (empty($id)) {
			return redirect('/gwc/customers/' . $cid . '/view')->with('message-error', 'Param ID is missing');
		}
		//get cat info
		$customersAdd = CustomersAddress::find($id);
		//check cat id exist or not
		if (empty($customersAdd->id)) {
			return redirect('/gwc/customers' . $cid . '/view')->with('message-error', 'No record found');
		}

		//save logs
		$key_name   = "customers";
		$key_id     = $customersAdd->id;
		$message    = "Customer Address is removed for " . $customersAdd->title;
		$created_by = Auth::guard('admin')->user()->id;
		Common::saveLogs($key_name, $key_id, $message, $created_by);
		//end save logs
		//end deleting parent cat image
		$customersAdd->delete();
		return redirect()->back()->with('message-success', 'Address is deleted successfully');
	}

	////////////////////////////////////////customers orders ////////////////////////////
	public function listVendorOrders(Request $request)
	{

		$paymodelist = DB::table('gwc_orders_details')->groupBy('pay_mode')->get();

		$settingInfo = Settings::where("keyname", "setting")->first();

		$orderLists  = OrdersDetails::with('area');
		$orderLists  = $orderLists->select('gwc_orders_details.*', 'gwc_orders.*', 'gwc_products.*');
		$orderLists  = $orderLists->join('gwc_orders', 'gwc_orders.order_id', '=', 'gwc_orders_details.order_id');
		$orderLists  = $orderLists->join('gwc_products', 'gwc_products.id', '=', 'gwc_orders.product_id');
		$orderLists  = $orderLists->where('gwc_products.manufacturer_id', Auth::guard('admin')->user()->id);

		//check search queries
		if (!empty($request->get('q'))) {
			$q = $request->get('q');
		} else {
			$q = $request->q;
		}

		if (!empty($q)) {
			$orderLists = $orderLists->where(function ($sq) use ($q) {
				$sq->where('gwc_orders_details.name', 'LIKE', '%' . $q . '%')
					->orwhere('gwc_orders_details.email', 'LIKE', '%' . $q . '%')
					->orwhere('gwc_orders_details.mobile', 'LIKE', '%' . $q . '%')
					->orwhere('gwc_orders_details.order_id', 'LIKE', '%' . $q . '%');
			});
		}
		//filter by date range
		if (!empty(Session::get('order_filter_dates'))) {
			$explodeDates = explode("-", Session::get('order_filter_dates'));
			if (!empty($explodeDates[0]) && !empty($explodeDates[1])) {
				$date1 = date("Y-m-d", strtotime($explodeDates[0]));
				$date2 = date("Y-m-d", strtotime($explodeDates[1]));
				$orderLists = $orderLists->whereBetween('gwc_orders_details.created_at', [$date1, $date2]);
			}
		}
		if (!empty(Session::get('order_filter_status')) && Session::get('order_filter_status') <> "all") {
			$orderLists = $orderLists->where('gwc_orders_details.order_status', '=', Session::get('order_filter_status'));
		}
		if (!empty(Session::get('pay_filter_status')) && Session::get('pay_filter_status') == 'paid') {
			$orderLists = $orderLists->where('gwc_orders_details.is_paid', '=', 1);
		}
		if (!empty(Session::get('pay_filter_status')) && Session::get('pay_filter_status') == 'notpaid') {
			$orderLists = $orderLists->where('gwc_orders_details.is_paid', '!=', 1);
		}
		if (!empty(Session::get('order_customers'))) {
			$orderLists = $orderLists->where('gwc_orders_details.customer_id', '=', Session::get('order_customers'));
		}

		if (!empty(Session::get('pay_mode'))) {
			$orderLists = $orderLists->where('gwc_orders_details.pay_mode', '=', Session::get('pay_mode'));
		}

		if (!empty($request->pmode) && $request->pmode == "COD") {
			$orderLists = $orderLists->where('gwc_orders_details.pay_mode', '=', 'COD')->where('gwc_orders_details.is_paid', 1)->where('gwc_orders_details.order_status', 'completed');
		} else if (!empty($request->pmode) && $request->pmode == "COD_KNET") {
			$orderLists = $orderLists->where('gwc_orders_details.is_paid', 1)->where('gwc_orders_details.order_status', 'completed');
		} else if (!empty($request->pmode) && $request->pmode == "KNET") {
			$orderLists = $orderLists->where('gwc_orders_details.pay_mode', '!=', 'COD')->where('gwc_orders_details.is_paid', 1)->where('gwc_orders_details.order_status', 'completed');
		}




		$orderLists  = $orderLists->orderBy('gwc_orders_details.id', 'DESC')->groupBy('gwc_orders_details.order_id')->paginate($settingInfo->item_per_page_back);

		//collect customers listing for dropdown
		$customersLists = DB::table('gwc_orders_details')
			->select('gwc_orders_details.customer_id', 'gwc_customers.id', 'gwc_orders_details.name')
			->join('gwc_customers', 'gwc_customers.id', '=', 'gwc_orders_details.customer_id')
			->GroupBy('gwc_orders_details.customer_id')
			->get();

		return view('gwc.vendor.orders.index', compact('orderLists', 'settingInfo', 'customersLists', 'paymodelist'));
	}

	public function listVendorPayment(Request $request)
	{

		$paymodelist = DB::table('gwc_orders_details')->groupBy('pay_mode')->get();

		$settingInfo = Settings::where("keyname", "setting")->first();

		$orderLists  = OrdersDetails::with('area');
		$orderLists  = $orderLists->select('gwc_orders_details.*', 'gwc_orders.*', 'gwc_products.*', 'gwc_orders_details.created_at as cdate');
		$orderLists  = $orderLists->join('gwc_orders', 'gwc_orders.order_id', '=', 'gwc_orders_details.order_id');
		$orderLists  = $orderLists->join('gwc_products', 'gwc_products.id', '=', 'gwc_orders.product_id');
		$orderLists  = $orderLists->where('gwc_products.manufacturer_id', Auth::guard('admin')->user()->id);

		//check search queries
		if (!empty($request->get('q'))) {
			$q = $request->get('q');
		} else {
			$q = $request->q;
		}

		if (!empty($q)) {
			$orderLists = $orderLists->where(function ($sq) use ($q) {
				$sq->where('gwc_orders_details.name', 'LIKE', '%' . $q . '%')
					->orwhere('gwc_orders_details.email', 'LIKE', '%' . $q . '%')
					->orwhere('gwc_orders_details.mobile', 'LIKE', '%' . $q . '%')
					->orwhere('gwc_orders_details.order_id', 'LIKE', '%' . $q . '%');
			});
		}
		//filter by date range
		if (!empty(Session::get('vpayment_filter_dates'))) {
			$explodeDates = explode("-", Session::get('vpayment_filter_dates'));
			if (!empty($explodeDates[0]) && !empty($explodeDates[1])) {
				$date1 = date("Y-m-d", strtotime($explodeDates[0]));
				$date2 = date("Y-m-d", strtotime($explodeDates[1]));
				$orderLists = $orderLists->whereBetween('gwc_orders_details.created_at', [$date1, $date2]);
			}
		}

		if (!empty(Session::get('vpay_status')) && Session::get('vpay_status') == 'paid') {
			$orderLists = $orderLists->where('gwc_orders_details.is_paid', '=', 1);
		}
		if (!empty(Session::get('vpay_status')) && Session::get('vpay_status') == 'notpaid') {
			$orderLists = $orderLists->where('gwc_orders_details.is_paid', '!=', 1);
		}

		if (!empty(Session::get('vpayment_customers'))) {
			$orderLists = $orderLists->where('gwc_orders_details.customer_id', '=', Session::get('vpayment_customers'));
		}

		if (!empty(Session::get('vpay_mode'))) {
			$orderLists = $orderLists->where('gwc_orders_details.pay_mode', '=', Session::get('vpay_mode'));
		}

		$orderLists  = $orderLists->orderBy('gwc_orders_details.created_at', 'DESC')->groupBy('gwc_orders_details.order_id')->paginate($settingInfo->item_per_page_back);

		//collect customers listing for dropdown
		$customersLists = DB::table('gwc_orders_details')
			->select('gwc_orders_details.customer_id', 'gwc_customers.id', 'gwc_orders_details.name')
			->join('gwc_customers', 'gwc_customers.id', '=', 'gwc_orders_details.customer_id')
			->GroupBy('gwc_orders_details.customer_id')
			->get();

		return view('gwc.vendor.orders.payments', compact('orderLists', 'settingInfo', 'customersLists', 'paymodelist'));
	}

	public static function vendorTotalAmount($orderid)
	{
		$totalAmt = 0;
		$orderLists  = DB::table('gwc_orders')->where('gwc_orders.order_id', $orderid);
		$orderLists  = $orderLists->select('gwc_orders.*', 'gwc_products.id', 'gwc_products.manufacturer_id');
		$orderLists  = $orderLists->join('gwc_products', 'gwc_products.id', '=', 'gwc_orders.product_id');
		$orderLists  = $orderLists->where('gwc_products.manufacturer_id', Auth::guard('admin')->user()->id);
		$orderLists  = $orderLists->groupBy('gwc_orders.id')->get();
		if (!empty($orderLists) && count($orderLists) > 0) {
			foreach ($orderLists as $listOrder) {
				$totalAmt += ($listOrder->quantity * $listOrder->unit_price);
			}
		}
		return $totalAmt;
	}


	public function ViewVendorOrder(Request $request, $oid)
	{
		$settingInfo = Settings::where("keyname", "setting")->first();
		$orderDetails = OrdersDetails::where('order_id', $oid)->first();
		//get order items
		$orderLists  = DB::table('gwc_orders')->where('gwc_orders.order_id', $oid);
		$orderLists  = $orderLists->select('gwc_orders.*', 'gwc_products.id', 'gwc_products.manufacturer_id');
		$orderLists  = $orderLists->join('gwc_products', 'gwc_products.id', '=', 'gwc_orders.product_id');
		$orderLists  = $orderLists->where('gwc_products.manufacturer_id', Auth::guard('admin')->user()->id);
		$orderLists  = $orderLists->groupBy('gwc_orders.id')->get();

		return view('gwc.vendor.orders.view', compact('orderDetails', 'settingInfo', 'orderLists'));
	}

	//get customer orders
	public function listCustomersOrders(Request $request)
	{

		$paymodelist = DB::table('gwc_orders_details')->groupBy('pay_mode')->get();

		$settingInfo = Settings::where("keyname", "setting")->first();

		//check search queries
		if (!empty($request->get('q'))) {
			$q = $request->get('q');
		} else {
			$q = $request->q;
		}

		$orderLists = OrdersDetails::with('area')->where('order_status', '!=', '');
		//search keywords
		if (!empty($q)) {
			$orderLists = $orderLists->where(function ($sq) use ($q) {
				$sq->where('name', 'LIKE', '%' . $q . '%')
					->orwhere('email', 'LIKE', '%' . $q . '%')
					->orwhere('mobile', 'LIKE', '%' . $q . '%')
					->orwhere('order_id', 'LIKE', '%' . $q . '%');
			});
		}
		//filter by date range
		if (!empty(Session::get('order_filter_dates'))) {
			$explodeDates = explode("-", Session::get('order_filter_dates'));
			if (!empty($explodeDates[0]) && !empty($explodeDates[1])) {
				$date1 = date("Y-m-d", strtotime($explodeDates[0]));
				$date2 = date("Y-m-d", strtotime($explodeDates[1]));
				$orderLists = $orderLists->whereBetween('created_at', [$date1, $date2]);
			}
		}
		if (!empty(Session::get('order_filter_status')) && Session::get('order_filter_status') <> "all") {
			$orderLists = $orderLists->where('order_status', '=', Session::get('order_filter_status'));
		}
		if (!empty(Session::get('pay_filter_status')) && Session::get('pay_filter_status') == 'paid') {
			$orderLists = $orderLists->where('is_paid', '=', 1);
		}
		if (!empty(Session::get('pay_filter_status')) && Session::get('pay_filter_status') == 'notpaid') {
			$orderLists = $orderLists->where('is_paid', '!=', 1);
		}
		if (!empty(Session::get('order_customers'))) {
			$orderLists = $orderLists->where('customer_id', '=', Session::get('order_customers'));
		}

		if (!empty(Session::get('pay_mode'))) {
			$orderLists = $orderLists->where('pay_mode', '=', Session::get('pay_mode'));
		}

		if (!empty($request->pmode) && $request->pmode == "COD") {
			$orderLists = $orderLists->where('pay_mode', '=', 'COD')->where('is_paid', 1)->where('order_status', 'completed');
		} else if (!empty($request->pmode) && $request->pmode == "COD_KNET") {
			$orderLists = $orderLists->where('is_paid', 1)->where('order_status', 'completed');
		} else if (!empty($request->pmode) && $request->pmode == "KNET") {
			$orderLists = $orderLists->where('pay_mode', '!=', 'COD')->where('is_paid', 1)->where('order_status', 'completed');
		}

		$orderLists = $orderLists->orderBy('id', 'DESC')->paginate($settingInfo->item_per_page_back);

		if (!empty($request->pmode)) {
			$orderLists->appends(['pmode' => $request->pmode]);
		}

		//collect customers listing for dropdown
		$customersLists = DB::table('gwc_orders_details')
			->select('gwc_orders_details.customer_id', 'gwc_customers.id', 'gwc_orders_details.name')
			->join('gwc_customers', 'gwc_customers.id', '=', 'gwc_orders_details.customer_id')
			->GroupBy('gwc_orders_details.customer_id')
			->get();

		return view('gwc.orders.index', compact('orderLists', 'settingInfo', 'customersLists', 'paymodelist'));
	}
	//remove order 
	public function deleteOrder($id)
	{
		$orderdetails = OrdersDetails::where("id", $id)->first();
		$orderLists   = Orders::where("oid", $id)->get();
		if (!empty($orderLists) && count($orderLists) > 0) {
			foreach ($orderLists as $orderList) {
				//option
				$OrderOptions = OrdersOption::where("oid", $orderList->id)->get();
				if (!empty($OrderOptions) && count($OrderOptions) > 0) {
					if (empty($orderdetails->is_removed) && empty($orderdetails->is_qty_rollbacked)) {
						foreach ($OrderOptions as $OrderOption) {
							$optionsDt = OrdersOption::where('id', $OrderOption->id)->first();
							self::changeOptionQuantity('a', $OrderOption->option_child_ids, $orderList->quantity); //add qty
							$optionsDt->delete();
						}
					}
				}
				$order = Orders::where("id", $orderList->id)->first();
				if (empty($orderdetails->is_removed) && empty($orderdetails->is_qty_rollbacked)) {
					self::rollbackedQuantity($orderList->product_id, $orderList->quantity, $orderList->size_id, $orderList->color_id);
				}
				$order->delete();
			}
		}
		//remove track
		$orderListsTracks   = OrdersTrack::where("oid", $id)->get();
		if (!empty($orderListsTracks) && count($orderListsTracks) > 0) {
			foreach ($orderListsTracks as $orderListsTrack) {
				$ordertrack = OrdersTrack::where("id", $orderListsTrack->id)->first();
				$ordertrack->delete();
			}
		}

		$orderdetails->delete();
		return redirect()->back()->with('message-success', 'Order is deleted successfully');
	}
	//rollbacked qty
	public static function rollbackedQuantity($product_id, $quantity, $size_id = 0, $color_id = 0)
	{
		$productDetails   = Product::where('id', $product_id)->first();
		if (!empty($productDetails->id)) {

			if (empty($productDetails['is_attribute'])) {
				$oldquantity = $productDetails['quantity'];
				$productDetails->quantity = $oldquantity + $quantity;
				$productDetails->save();
			} else {
				if (!empty($size_id) && !empty($color_id)) {
					$attributes = ProductAttribute::where('product_id', $product_id)->where('size_id', $size_id)->where('color_id', $color_id)->first();
					if (!empty($attributes->id)) {
						$oldquantity = $attributes->quantity;
						$attributes->quantity = $oldquantity + $quantity;
						$attributes->save();
					}
				} else if (!empty($size_id) && empty($color_id)) {
					$attributes = ProductAttribute::where('product_id', $product_id)->where('size_id', $size_id)->first();
					if (!empty($attributes->id)) {
						$oldquantity = $attributes->quantity;
						$attributes->quantity = $oldquantity + $quantity;
						$attributes->save();
					}
				} else if (empty($size_id) && !empty($color_id)) {
					$attributes = ProductAttribute::where('product_id', $product_id)->where('color_id', $color_id)->first();
					if (!empty($attributes->id)) {
						$oldquantity = $attributes->quantity;
						$attributes->quantity = $oldquantity + $quantity;
						$attributes->save();
					}
				}
			}
			//change qty to prod table for attr
			self::ChangeUpdateQuantity($product_id);
			//end
		}
	}
	//ajax 
	public static function storeValuesInCookies(Request $request)
	{
		//date range
		if (!empty($request->dates)) {
			Session::put('order_filter_dates', $request->dates);
		}
		if (!empty($request->vdates)) {
			Session::put('vpayment_filter_dates', $request->vdates);
		}

		if (!empty($request->vpay_status)) {
			Session::put('vpay_status', $request->vpay_status);
		}


		//date range payment
		if (!empty($request->payment_dates)) {
			Session::put('payment_filter_dates', $request->payment_dates);
		}
		//order status
		if (!empty($request->order_status)) {
			Session::put('order_filter_status', $request->order_status);
		}
		//order status
		if (!empty($request->payment_status)) {
			Session::put('payment_filter_status', $request->payment_status);
		}
		//payment status
		if (!empty($request->pay_status)) {
			Session::put('pay_filter_status', $request->pay_status);
		}


		return ["status" => 200, "message" => ""];
	}

	public static function orderResetFilter()
	{
		Session::forget('vpayment_filter_dates');
		Session::forget('vpayment_filter_status');


		Session::forget('payment_filter_status');
		Session::forget('payment_filter_dates');
		Session::forget('order_filter_dates');
		Session::forget('order_filter_status');
		Session::forget('pay_filter_status');
		return ["status" => 200, "message" => ""];
	}
	//view customer order details
	public function ViewCustomerOrder(Request $request, $oid)
	{
		$settingInfo = Settings::where("keyname", "setting")->first();
		$orderDetails = OrdersDetails::find($oid);
		//get order items
		$orderLists = Orders::where('oid', $oid)->orderBy('created_at', 'DESC')->get();

		return view('gwc.orders.view', compact('orderDetails', 'settingInfo', 'orderLists'));
	}





	//delete product from order
	public function deleteProductFromOrder($orderid, $productid)
	{
		$order = Orders::where('order_id', $orderid)->where('product_id', $productid)->first();
		if ($order) {
			$oid = $order->oid;
			$order->delete();
			return redirect('/gwc/orders/' . $oid . '/view')->with('message-success', 'Product is deleted successfully');
		}
		return redirect()->back()->with('message-error', 'Operation failed!');
	}

	//update product quantity of order
	public function updateProductQtyOfOrder(Request $request)
	{
		$orderId   = $request->orderId;
		$productId = $request->productId;
		$quantity  = $request->quantity;

		$order   = Orders::where('order_id', $orderId)->where('product_id', $productId)->first();
		$product = Product::where('id', $productId)->where('is_active', '!=', 0)->first();

		//calculate available quantity
		$productQuantity = 0;
		if (empty($product->is_attribute)) {
			$productQuantity = $product->quantity;
		} else {
			$productQuantity = ProductAttribute::where('product_id', $product->id)->get()->sum('quantity');
			$optionQty       = ProductOptions::where('product_id', $product->id)->get()->sum('quantity'); //option
			$productQuantity = $productQuantity + $optionQty;
		}

		$orderQuantity = $order->quantity;
		$change        = $quantity - $orderQuantity;

		if ($quantity <= $productQuantity) {

			//$product->quantity = $productQuantity - $change;
			//$product->save();

			$order->quantity = $quantity;
			$order->save();

			$orders = Orders::where('order_id', $orderId)->get();
			$totalAmount = 0;
			foreach ($orders as $order) {
				$totalAmount += $order->unit_price * $order->quantity;
			}

			$orderDetails = OrdersDetails::where('order_id', $orderId)->first();
			$totalAmount = $totalAmount + $orderDetails->delivery_charges - $orderDetails->seller_discount;
			$orderDetails->total_amount = $totalAmount;
			$orderDetails->save();

			return ["status" => 200, "message" => "Quantity updated successfully 1234"];
		}
		return ["status" => 400, "message" => "There is not enough products available"];
	}


	//search item code of the product for adding to invoice
	public function searchItemCode(Request $request)
	{
		$results = '';
		$itemCode = $request->itemCode;
		$orderId = $request->orderId;

		$product = Product::where('item_code', $itemCode)->where('is_active', '!=', 0)->first();
		if ($product) {
			$productId = $product->id;
			$existing = Orders::where('order_id', $orderId)->where('product_id', $productId)->first();
			if ($existing) {
				return ["status" => 400, "message" => "This product is added to order before"];
			} else {
				//calculate available quantity
				$qty = 0;
				if (empty($product->is_attribute)) {
					$qty = $product->quantity;
				} else {
					$qty = ProductAttribute::where('product_id', $product->id)->get()->sum('quantity');
					$optionQty = ProductOptions::where('product_id', $product->id)->get()->sum('quantity'); //option
					$qty = $qty + $optionQty;
					//save qty
					$product->quantity = $qty;
					$product->save();
				}

				$checkDiscount = $this->checkBrandDiscount($product->brand_id);
				//getting product details
				$results .= '<form>';
				$results .= '<div class="row">';
				$results .= '<div class="col-2"><img src="' . url("uploads/product/thumb/" . $product->image) . '" style="width: 100px;height: 100px" ></div>';
				$results .= '<div class="col-4">' . $product->title_en . '</div>';
				//price
				$results .= '<div class="col-2">';
				if (!empty($product->countdown_datetime) && strtotime($product->countdown_datetime) > strtotime(date('Y-m-d'))) {
					if ($product->old_price) $results .= '<span class="new-price" style="color:red" >';
					else $results .= '<span class="new-price" >';
					$results .= '<span id="display_price">' . round($product->countdown_price, 3) . '</span>';
					$results .= __('webMessage.kd');
					$results .= '</span>';
					$results .= '&nbsp;';
					$results .= '<span class="old-price price_black" id="oldprices"><small><span id="">' . round($product->retail_price, 3) . '</span>' . __('webMessage.kd') . '</small></span>';
				}
				if ($checkDiscount) {
					$discountedPrice=$this->calByBrandDiscount($product->brand_id,$product->retail_price);
					if ($product->old_price) $results .= '<span class="new-price" style="color:red" >';
					else $results .= '<span class="new-price" >';
					$results .= '<span id="display_price">' . round((float) $discountedPrice->price, 3) . '</span>';
					$results .= __('webMessage.kd');
					$results .= '</span>';
					$results .= '&nbsp;';
					$results .= '<span class="old-price price_black" id="oldprices"><small><span id="display_oldprice">' . round($product->old_price, 3) . '</span>' . __('webMessage.kd') . '</small></span>';
				} else {
					if ($product->old_price) $results .= '<span class="new-price" style="color:red" >';
					else $results .= '<span class="new-price" >';
					$results .= '<span id="display_price">' . round($product->retail_price, 3) . '</span>';
					$results .= __('webMessage.kd');
					$results .= '</span>';
					$results .= '&nbsp;';
					$results .= '<span class="old-price price_black" id="oldprices"><small><span id="display_oldprice">' . round($product->old_price, 3) . '</span>' . __('webMessage.kd') . '</small></span>';
				}
				$results .= '<br><br>';
				$results .= '<span id="available-qty">' . $qty . '</span> Items Available';
				$results .= '<span id="product-to-add-id" style="visibility: hidden">' . $product->id . '</span>';
				$results .= '</div>';

				$results .= '<div class="col-3">';
				//getting the options
				$productOption = ProductOptionsCustomChosen::where('product_id', $product->id)->first();
				if (!empty($product->is_attribute) && $qty > 0) {
					$results .= '<div class="tt-swatches-container">';
					$results .= '<img id="loader-gif" src="' . url("assets/images/loader.svg") . '" style="position:absolute;margin-left:30%;display:none;margin-top:-40px;">';
					if (!empty($productOption)) {
						//size
						if ($productOption->custom_option_id == 1) {
							$results .= '<input type="hidden" name="option_sc" id="option_sc_' . $productOption->id . '" value="' . $productOption->custom_option_id . '">';
							$SizeAttributes = webCartController::getSizeByCustomIdProductId($productOption->custom_option_id, $product->id);
							if (!empty($SizeAttributes) && count($SizeAttributes) > 0) {
								$results .= '<div class="tt-wrapper">';
								$results .= '<div class="tt-title-options">' . __('webMessage.size') . ' *: </div>';
								$results .= '<div class="form-group">';
								$results .= '<select class="form-control size_attr" name="size_attr" style="height:auto" id="size_attr_' . $product->id . '">';
								foreach ($SizeAttributes as $SizeAttribute) {
									$sizeName = $SizeAttribute->title_en;
									$results .= '<option value="' . $SizeAttribute->size_id . '">' . $sizeName . '</option>';
								}
								$results .= '</select>';
								$results .= '</div>';
								$results .= '</div>';
							}
						}
						//color
						elseif ($productOption->custom_option_id == 2) {
							$results .= '<input type="hidden" name="option_sc" id="option_sc_' . $productOption->id . '" value="' . $productOption->custom_option_id . '">';
							$ColorAttributes = webCartController::getColorByCustomIdProductId($productOption->custom_option_id, $product->id);
							if (!empty($ColorAttributes) && count($ColorAttributes) > 0) {
								$results .= '<input type="hidden" name="is_color" id="is_color" value="1">';
								$results .= '<input type="hidden" name="color_attr" id="color_attr" value="' . $ColorAttributes[0]->color_id . '">';
								$results .= '<span id="color_box">';
								$results .= '<div class="tt-wrapper">';
								$results .= '<div class="tt-title-options">' . __('webMessage.texture') . ' : </div>';
								$results .= '<ul class="tt-options-swatch options-large">';
								$i = 0;
								foreach ($ColorAttributes as $ColorAttribute) {
									$colorCode = $ColorAttribute->color_code ?: 'none';
									if (!empty($ColorAttribute->image)) {
										if ($i == 0) $results .= '<li id="li-' . $ColorAttribute->color_id . '" class="active color-to-choose">';
										else $results .= '<li id="li-' . $ColorAttribute->color_id . '" class="color-to-choose">';
										$results .= '<a class="options-color" onclick="setColorAttr(' . $ColorAttribute->color_id . ')" id="' . $ColorAttribute->color_id . '">';
										$results .= '<span class="swatch-img">';
										$results .= '<img src="' . url("uploads/color/thumb/" . $ColorAttribute->image) . '" alt="">';
										$results .= '</span>';
										$results .= '<span class="swatch-label color-black"></span>';
										$results .= '</a>';
										$results .= '</li>';
									} else {
										if ($i == 0) $results .= '<li id="li-' . $ColorAttribute->color_id . '" class="active color-to-choose">';
										else $results .= '<li id="li-' . $ColorAttribute->color_id . '" class="color-to-choose">';
										$results .= '<a onclick="setColorAttr(' . $ColorAttribute->color_id . ')" class="options-color" style="background-color:' . $colorCode . ';" id="' . $ColorAttribute->color_id . '"></a>';
										$results .= '</li>';
									}
									$i++;
								}
								$results .= '</ul>';
								$results .= '<br clear="all">';
								$results .= '</div>';
								$results .= '</span>';
							}
						}
						//size and color
						elseif ($productOption->custom_option_id == 3) {
							$results .= '<input type="hidden" name="option_sc" id="option_sc_' . $productOption->id . '" value="' . $productOption->custom_option_id . '">';
							//first getting the size
							$SizeAttributes = webCartController::getSizeByCustomIdProductId($productOption->custom_option_id, $product->id);
							if (!empty($SizeAttributes) && count($SizeAttributes) > 0) {
								$results .= '<div class="tt-wrapper">';
								$results .= '<div class="tt-title-options">' . __('webMessage.size') . ' *: </div>';
								$results .= '<div class="form-group">';
								$results .= '<select class="form-control size_attr" name="size_attr" style="height:auto" id="size_attr_' . $product->id . '">';
								foreach ($SizeAttributes as $SizeAttribute) {
									$sizeName = $SizeAttribute->title_en;
									$results .= '<option value="' . $SizeAttribute->size_id . '">' . $sizeName . '</option>';
								}
								$results .= '</select>';
								$results .= '</div>';
								$results .= '</div>';
							}
							//then getting the color
							$ColorAttributes = webCartController::getColorByCustomIdProductId($productOption->custom_option_id, $product->id);
							if (!empty($ColorAttributes) && count($ColorAttributes) > 0) {
								$results .= '<input type="hidden" name="is_color" id="is_color" value="1">';
								$results .= '<input type="hidden" name="color_attr" id="color_attr" value="' . $ColorAttributes[0]->color_id . '">';
								$results .= '<span id="color_box">';
								$results .= '<div class="tt-wrapper">';
								$results .= '<div class="tt-title-options">' . __('webMessage.texture') . ' : </div>';
								$results .= '<ul class="tt-options-swatch options-large">';
								$i = 0;
								foreach ($ColorAttributes as $ColorAttribute) {
									$colorCode = $ColorAttribute->color_code ?: 'none';
									if (!empty($ColorAttribute->image)) {
										if ($i == 0) $results .= '<li id="li-' . $ColorAttribute->color_id . '" class="active color-to-choose">';
										else $results .= '<li id="li-' . $ColorAttribute->color_id . '" class="color-to-choose">';
										$results .= '<a class="options-color" onclick="setColorAttr(' . $ColorAttribute->color_id . ')" id="' . $ColorAttribute->color_id . '">';
										$results .= '<span class="swatch-img">';
										$results .= '<img src="' . url("uploads/color/thumb/" . $ColorAttribute->image) . '" alt="">';
										$results .= '</span>';
										$results .= '<span class="swatch-label color-black"></span>';
										$results .= '</a>';
										$results .= '</li>';
									} else {
										if ($i == 0) $results .= '<li id="li-' . $ColorAttribute->color_id . '" class="active color-to-choose">';
										else $results .= '<li id="li-' . $ColorAttribute->color_id . '" class="color-to-choose">';
										$results .= '<a onclick="setColorAttr(' . $ColorAttribute->color_id . ')" class="options-color" style="background-color:' . $colorCode . ';" id="' . $ColorAttribute->color_id . '"></a>';
										$results .= '</li>';
									}
									$i++;
								}
								$results .= '</ul>';
								$results .= '<br clear="all">';
								$results .= '</div>';
								$results .= '</span>';
							}
						}
						//other custom options
						else {
							$customOptions = webCartController::getCustomOptions($productOption->custom_option_id, $product->id);
							//radio box
							if (!empty($customOptions['CustomOptionName']) && $customOptions['CustomOptionType'] == "radio") {
								$results .= '<div class="tt-wrapper">';
								$results .= '<div class="tt-title-options">';
								$results .= $customOptions['CustomOptionName'];
								if (!empty($productOption->is_required)) {
									$results .= '*';
								}
								$results .= '</div>';
								$results .= '<ul class="optionradio">';
								if (!empty($customOptions['childs']) && count($customOptions['childs']) > 0) {
									$is_cadd_txt = '';
									foreach ($customOptions['childs'] as $child) {
										if (!empty($child->retail_price) && !empty($child->is_price_add) && $child->is_price_add == 1) {
											$is_cadd = "+";
											$is_cadd_txt = $is_cadd . ' ' . $child->retail_price . ' ' . trans('webMessage.kd');
										} else if (!empty($child->retail_price) && !empty($child->is_price_add) && $child->is_price_add == 2) {
											$is_cadd = "-";
											$is_cadd_txt = $is_cadd . ' ' . $child->retail_price . ' ' . trans('webMessage.kd');
										} else if (!empty($child->retail_price) && empty($child->is_price_add)) {
											$is_cadd = "";
											$is_cadd_txt = $child->retail_price . ' ' . trans('webMessage.kd');
										} else {
											$is_cadd = "";
											$is_cadd_txt = "";
										}
										$option_value_name = $child->option_value_name_en;
										$results .= '<li>';
										$results .= '<label for="option-' . $product->id . '-' . $productOption->custom_option_id . '-' . $child->id . '">';
										$results .= '<input class="checkOptionPrice" type="radio" name="option-' . $product->id . '-' . $productOption->custom_option_id . '" id="option-' . $product->id . '-' . $productOption->custom_option_id . '-' . $child->id . '" value="' . $child->id . '">';
										$results .= $option_value_name . '(' . $is_cadd_txt . ')';
										$results .= '</label>';
										$results .= '</li>';
									}
								}
								$results .= '</ul>';
								$results .= '</div>';
							}
							//check box
							if (!empty($customOptions['CustomOptionName']) && $customOptions['CustomOptionType'] == "checkbox") {
								$results .= '<div class="tt-wrapper">';
								$results .= '<div class="tt-title-options">';
								$results .= $customOptions['CustomOptionName'];
								if (!empty($productOption->is_required)) {
									$results .= '*';
								}
								$results .= '</div>';
								$results .= '<ul class="optionradio">';
								if (!empty($customOptions['childs']) && count($customOptions['childs']) > 0) {
									$is_cadd_txt = '';
									foreach ($customOptions['childs'] as $child) {
										if (!empty($child->retail_price) && !empty($child->is_price_add) && $child->is_price_add == 1) {
											$is_cadd = "+";
											$is_cadd_txt = $is_cadd . ' ' . $child->retail_price . ' ' . trans('webMessage.kd');
										} else if (!empty($child->retail_price) && !empty($child->is_price_add) && $child->is_price_add == 2) {
											$is_cadd = "-";
											$is_cadd_txt = $is_cadd . ' ' . $child->retail_price . ' ' . trans('webMessage.kd');
										} else if (!empty($child->retail_price) && empty($child->is_price_add)) {
											$is_cadd = "";
											$is_cadd_txt = $child->retail_price . ' ' . trans('webMessage.kd');
										} else {
											$is_cadd = "";
											$is_cadd_txt = "";
										}
										$option_value_name = $child->option_value_name_en;
										$results .= '<li>';
										$results .= '<label for="checkbox-' . $product->id . '-' . $productOption->custom_option_id . '-' . $child->id . '">';
										$results .= '<input class="checkOptionPricechk" type="checkbox" name="checkbox-' . $product->id . '-' . $productOption->custom_option_id . '[]" id="checkbox-' . $product->id . '-' . $productOption->custom_option_id . '-' . $child->id . '" value="' . $child->id . '">';
										$results .= $option_value_name . '(' . $is_cadd_txt . ')';
										$results .= '</label>';
										$results .= '</li>';
									}
								}
								$results .= '</ul>';
								$results .= '</div>';
							}
							//select box
							if (!empty($customOptions['CustomOptionName']) && $customOptions['CustomOptionType'] == "select") {
								$results .= '<div class="tt-wrapper">';
								$results .= '<div class="tt-title-options">';
								$results .= $customOptions['CustomOptionName'];
								if (!empty($productOption->is_required)) {
									$results .= '*';
								}
								$results .= '</div>';
								$results .= '<div class="form-group">';
								$results .= '<select class="form-control choose_select_options" name="select-' . $product->id . '-' . $productOption->custom_option_id . '" id="select-' . $product->id . '-' . $productOption->custom_option_id . '">';
								$results .= '<option value="0">---</option>';
								if (!empty($customOptions['childs']) && count($customOptions['childs']) > 0) {
									$is_cadd_txt = '';
									foreach ($customOptions['childs'] as $child) {
										if (!empty($child->retail_price) && !empty($child->is_price_add) && $child->is_price_add == 1) {
											$is_cadd = "+";
											$is_cadd_txt = $is_cadd . ' ' . $child->retail_price . ' ' . trans('webMessage.kd');
										} else if (!empty($child->retail_price) && !empty($child->is_price_add) && $child->is_price_add == 2) {
											$is_cadd = "-";
											$is_cadd_txt = $is_cadd . ' ' . $child->retail_price . ' ' . trans('webMessage.kd');
										} else if (!empty($child->retail_price) && empty($child->is_price_add)) {
											$is_cadd = "";
											$is_cadd_txt = $child->retail_price . ' ' . trans('webMessage.kd');
										} else {
											$is_cadd = "";
											$is_cadd_txt = "";
										}
										$option_value_name = $child->option_value_name_en;
										$results .= '<option value="select-' . $product->id . '-' . $productOption->custom_option_id . '-' . $child->id . '">';
										$results .= $option_value_name . '(' . $is_cadd_txt . ')';
										$results .= '</option>';
									}
								}
								$results .= '</select>';
								$results .= '</div>';
								$results .= '</div>';
							}
						}
					}
					$results .= '</div>';
				}
				$results .= '</div>';
				$results .= '<div class="col-1">';
				$results .= '<button type="button" class="btn btn-success" id="add-product-form" onclick="addProductToOrder()">Add</button><br>';
				$results .= '</div>';
				$results .= '</div>';
				$results .= '</form>';
				return $results;
			}
		}
		return ["status" => 404, "message" => "Product Not Found!"];
	}

	////////////////////////////////orders track history//////////////////////////////////////////////////
	public function listorderhistory(Request $request, $oid)
	{
		$trackhistoryLists = OrdersTrack::where('oid', $oid)->orderBy('display_order', 'DESC')->paginate();
		return view('gwc.orders-track.index', compact('trackhistoryLists'));
	}
	//show create form
	public function createTrackHistory($oid)
	{
		$settingInfo = Settings::where("keyname", "setting")->first();
		$OrderInfo     = OrdersDetails::where('id', $oid)->first();
		$lastOrderInfo = OrdersTrack::OrderBy('display_order', 'desc')->first();
		if (!empty($lastOrderInfo->display_order)) {
			$lastOrder = ($lastOrderInfo->display_order + 1);
		} else {
			$lastOrder = 1;
		}



		return view('gwc.orders-track.create', compact('lastOrder', 'OrderInfo', 'settingInfo'));
	}

	public function postTrackHistory(Request $request, $oid)
	{
		$settingInfo = Settings::where("keyname", "setting")->first();

		if (empty($oid)) {
			die('Invalid request');
		}
		//field validation
		$this->validate($request, [
			'details_en'   => 'required|min:3|string',
			'details_ar'   => 'required|min:3|string',
			'details_date' => 'required|min:3|string',
		]);

		$tracks = new OrdersTrack;
		$tracks->oid    = $oid;
		$tracks->details_en    = $request->details_en;
		$tracks->details_ar    = $request->details_ar;
		$tracks->details_date  = $request->details_date;
		$tracks->is_active     = !empty($request->input('is_active')) ? $request->input('is_active') : '0';
		$tracks->display_order = !empty($request->input('display_order')) ? $request->input('display_order') : '0';
		$tracks->save();

		//change order status
		$tracksOrder = OrdersDetails::where('id', $oid)->first();
		$tracksOrder->order_status = $request->input('order_status');
		$tracksOrder->save();
		//send email notification

		$name  = $tracksOrder->name;
		$email = $tracksOrder->email;
		$orderid = $tracksOrder->order_id;
		$trackmessage = $tracks->details_en . '<br><br>ORDER ID #' . $orderid;
		if (!empty($email)) {
			self::sendEmailNotificationForOrderStatus($name, $email, $trackmessage, $orderid);
		}
		//send push notification
		if (!empty($tracksOrder->customer_id) && !empty($settingInfo->pushy_api_token)) {
			$deviceLists = WebPush::where('user_id', $tracksOrder->customer_id);
			$deviceLists = $deviceLists->where(function ($sq) {
				$sq->where('device_type', 'android')->orwhere('device_type', 'ios');
			});
			$deviceLists = $deviceLists->get();
			$token = [];
			if (!empty($deviceLists) && count($deviceLists) > 0) {
				foreach ($deviceLists as $deviceList) {
					$token[] = $deviceList->device_token;
				}
				if (!empty($token) && count($token) > 0) {
					$title   = "Order tacking for #" . $tracksOrder->order_id;
					$message = $tracks->details_en . " #" . $tracksOrder->order_id;
					Common::sendMobilePush($token, $title, $message, 'order');
				}
			}
		}
		//save logs
		$key_name   = "ordetrack";
		$key_id     = $tracks->id;
		$message    = "A new track history is added. (" . $request->details_en . ")";
		$created_by = Auth::guard('admin')->user()->id;
		Common::saveLogs($key_name, $key_id, $message, $created_by);
		//end save logs
		//send sms notification
		$isValidMobile = Common::checkMobile($tracksOrder->mobile);
		if (!empty($request->is_sms_active) && !empty($isValidMobile)) {
			if ($tracksOrder->strLang == "en") {
				$smsMessage = $request->details_en;
			} else {
				$smsMessage = $request->details_ar;
			}
			$to      = $tracksOrder->mobile;
			$sms_msg = $smsMessage . " #" . $tracksOrder->order_id;
			Common::SendSms($to, $sms_msg);
		}
		//end sms notification

		return redirect('/gwc/orders-track/' . $oid)->with('message-success', 'Tracking message is added successfully');
	}

	//change track status
	//update status
	public function updateOrderStatusAjax(Request $request)
	{
		$recDetails = OrdersTrack::where('id', $request->id)->first();
		if ($recDetails['is_active'] == 1) {
			$active = 0;
		} else {
			$active = 1;
		}

		//save logs
		$key_name   = "ordertrack";
		$key_id     = $recDetails->id;
		$message    = "Order Track history status is changed to " . $active . " (" . $recDetails->details_en . ")";
		$created_by = Auth::guard('admin')->user()->id;
		Common::saveLogs($key_name, $key_id, $message, $created_by);
		//end save logs


		$recDetails->is_active = $active;
		$recDetails->save();
		return ['status' => 200, 'message' => 'Status is modified successfully'];
	}


	public function deletePayment(Request $request)
	{
		$recDetails = Transaction::where('id', $request->id)->first();

		//save logs
		$key_name   = "payment";
		$key_id     = $recDetails->id;
		$message    = "Payment is removed (" . $recDetails->trackid . ")";
		$created_by = Auth::guard('admin')->user()->id;
		Common::saveLogs($key_name, $key_id, $message, $created_by);
		//end save logs

		$recDetails->delete();
		return redirect()->back()->with('message-success', 'Record is removed successfully');
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edittrack($id)
	{
		$edittrack  = OrdersTrack::where('id', $id)->first();
		$OrderInfo  = OrdersDetails::where('id', $edittrack->oid)->first();
		return view('gwc.orders-track.edit', compact('edittrack', 'OrderInfo'));
	}


	public function updatetrack(Request $request, $id)
	{
		if (empty($id)) {
			die('Invalid request');
		}
		//field validation
		$this->validate($request, [
			'details_en'   => 'required|min:3|string',
			'details_ar'   => 'required|min:3|string',
			'details_date' => 'required|min:3|string',
		]);

		try {


			$tracks = OrdersTrack::find($id);
			$tracks->details_en    = $request->details_en;
			$tracks->details_ar    = $request->details_ar;
			$tracks->details_date  = $request->details_date;
			$tracks->is_active     = !empty($request->input('is_active')) ? $request->input('is_active') : '0';
			$tracks->display_order = !empty($request->input('display_order')) ? $request->input('display_order') : '0';
			$tracks->save();
			//change order status
			$tracksOrder = OrdersDetails::where('id', $tracks->oid)->first();
			$tracksOrder->order_status = $request->input('order_status');
			$tracksOrder->save();

			//send email notification
			$name         = $tracksOrder->name;
			$email        = $tracksOrder->email;
			$orderid      = $tracksOrder->order_id;
			$trackmessage = $tracks->details_en . '<br><br>ORDER ID #' . $orderid;
			self::sendEmailNotificationForOrderStatus($name, $email, $trackmessage, $orderid);

			//send push notification
			if (!empty($tracksOrder->customer_id) && !empty($settingInfo->pushy_api_token)) {
				$deviceLists = WebPush::where('user_id', $tracksOrder->customer_id);
				$deviceLists = $deviceLists->where(function ($sq) {
					$sq->where('device_type', 'android')->orwhere('device_type', 'ios');
				});
				$deviceLists = $deviceLists->get();
				$token = [];
				if (!empty($deviceLists) && count($deviceLists) > 0) {
					foreach ($deviceLists as $deviceList) {
						$token[] = $deviceList->device_token;
					}
					if (!empty($token) && count($token) > 0) {
						$title   = "Order tacking for #" . $tracksOrder->order_id;
						$message = $tracks->details_en . " #" . $tracksOrder->order_id;
						Common::sendMobilePush($token, $title, $message, 'order');
					}
				}
			}

			//save logs
			$key_name   = "ordetrack";
			$key_id     = $tracks->id;
			$message    = "A track history is edited. (" . $request->details_en . ")";
			$created_by = Auth::guard('admin')->user()->id;
			Common::saveLogs($key_name, $key_id, $message, $created_by);
			//end save logs
			return redirect('/gwc/orders-track/' . $tracks->oid)->with('message-success', 'Tracking message is updated successfully');
		} catch (\Exception $e) {
			return redirect()->back()->with('message-error', $e->getMessage());
		}
	}

	/**
	 * Delete manufacturer along with childs via ID.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroyTrack($id)
	{
		//check param ID
		if (empty($id)) {
			return redirect()->back()->with('message-error', 'Param ID is missing');
		}
		//get cat info
		$order = OrdersTrack::find($id);
		//check cat id exist or not
		if (empty($order->id)) {
			return redirect()->back()->with('message-error', 'No record found');
		}


		//save logs
		$key_name   = "ordertrack";
		$key_id     = $order->id;
		$message    = "A record is removed. (" . $order->details_en . ")";
		$created_by = Auth::guard('admin')->user()->id;
		Common::saveLogs($key_name, $key_id, $message, $created_by);
		//end save logs


		//end deleting parent cat image
		$order->delete();
		return redirect()->back()->with('message-success', 'Record is deleted successfully');
	}


	//change qty from option
	public static function changeOptionQuantity($mode, $ids, $quantity)
	{
		$explodechildids = explode(",", $ids);
		for ($i = 0; $i < count($explodechildids); $i++) {
			$productChildOption = ProductOptions::where("id", $explodechildids[$i])->first();
			if ($mode == "d") {
				$productChildOption->quantity = ($productChildOption->quantity - $quantity);
			} else {
				$productChildOption->quantity = ($productChildOption->quantity + $quantity);
			}
			$productChildOption->save();
		}
	}

	//change order status
	public static function orderStatus(Request $request)
	{
		if (empty($request->id)) {
			return ["status" => 400, "message" => "Invalid id"];
		}
		$order = OrdersDetails::find($request->id);
		$order->order_status = $request->order_status;
		$order->is_paid = $request->pay_status;
		$order->extra_comment = !empty($request->extra_comment) ? $request->extra_comment : '';

		//rollbacked quantity if status is not rollbacked
		if (empty($order->is_qty_rollbacked) && ($request->order_status == "canceled" || $request->order_status == "returned")) {
			$orderLists   = Orders::where("oid", $request->id)->get();

			if (!empty($orderLists) && count($orderLists) > 0) {
				foreach ($orderLists as $orderList) {
					//option
					$OrderOptions = OrdersOption::where("oid", $orderList->id)->get();
					if (!empty($OrderOptions) && count($OrderOptions) > 0) {
						foreach ($OrderOptions as $OrderOption) {
							self::changeOptionQuantity('a', $OrderOption->option_child_ids, $orderList->quantity); //add qty
						}
					}
					//end option
					self::rollbackedQuantity($orderList->product_id, $orderList->quantity, $orderList->size_id, $orderList->color_id);
				}
			}
			$order->is_qty_rollbacked = 1;
		} else if (!empty($order->is_qty_rollbacked) && $request->order_status == "returned") {
			$orderLists   = Orders::where("oid", $request->id)->get();

			if (!empty($orderLists) && count($orderLists) > 0) {
				foreach ($orderLists as $orderList) {
					//option
					$OrderOptions = OrdersOption::where("oid", $orderList->id)->get();
					if (!empty($OrderOptions) && count($OrderOptions) > 0) {
						foreach ($OrderOptions as $OrderOption) {
							self::changeOptionQuantity('a', $OrderOption->option_child_ids, $orderList->quantity); //add qty
						}
					}
					//end option
					self::rollbackedQuantity($orderList->product_id, $orderList->quantity, $orderList->size_id, $orderList->color_id);
				}
			}
			$order->is_qty_rollbacked = 1;
		}

		$order->save();

		//send push notification
		if (!empty($order->customer_id)) {
			$token = [];
			$deviceLists = WebPush::where('user_id', $order->customer_id);
			$deviceLists = $deviceLists->where(function ($sq) {
				$sq->where('device_type', 'android')->orwhere('device_type', 'ios');
			});
			$deviceLists = $deviceLists->get();
			if (!empty($deviceLists) && count($deviceLists) > 0) {
				foreach ($deviceLists as $deviceList) {
					$token[]   = $deviceList->device_token;
				}
				if (!empty($token) && count($token) > 0) {
					$title   = "Order tacking for #" . $order->order_id;
					$message = (!empty($order->extra_comment) ? $order->extra_comment : 'Your order status is changed to ' . $request->order_status) . "#" . $order->order_id;
					Common::sendMobilePush($token, $title, $message, 'order');
				}
			}
		}


		//save logs
		$key_name   = "orders";
		$key_id     = $order->id;
		$message    = "Order/Payment status is changed to " . $request->order_status . "/" . $request->pay_status . " (" . $order->order_id . ")";
		$created_by = Auth::guard('admin')->user()->id;
		Common::saveLogs($key_name, $key_id, $message, $created_by);
		//end save logs
		return ["status" => 200, "message" => "Order status is updated successfully"];
	}
	//get country  state area
	public static function getCountryStatesArea($id)
	{
		$data = Country::find($id);
		return $data['name_en'];
	}
	//get total order amount
	public static function getOrderAmounts($id)
	{
		$totalAmt = 0;
		$orderDetails = OrdersDetails::Where('id', $id)->first();
		$listOrders   = Orders::where('oid', $id)->get();
		if (!empty($listOrders) && count($listOrders) > 0) {
			foreach ($listOrders as $listOrder) {
				$totalAmt += ($listOrder->quantity * $listOrder->unit_price);
			}
			//apply coupon if its not free
			if (!empty($orderDetails->coupon_code) && empty($orderDetails->coupon_free)) {
				$totalAmt = $totalAmt - $orderDetails->coupon_amount;
			}
			//apply delivery charges if coupon is empty
			if (empty($orderDetails->coupon_free)) {
				$totalAmt = $totalAmt + $orderDetails->delivery_charges;
			}
			//apply delivery charges if coupon is empty
			if (!empty($orderDetails->seller_discount)) {
				$totalAmt = $totalAmt - $orderDetails->seller_discount;
			}
		}

		return $totalAmt;
	}

	//view customers wish items
	public function viewCustomerWishItems(Request $request)
	{
		$settingInfo = Settings::where("keyname", "setting")->first();
		//check search queries
		if (!empty($request->get('q'))) {
			$q = $request->get('q');
		} else {
			$q = $request->q;
		}
		if (!empty($q)) {
			$wishLists = DB::table('gwc_customers_wish')
				->select('gwc_products.image', 'gwc_customers.name', 'gwc_customers_wish.created_at', 'gwc_customers_wish.id', 'gwc_products.title_en', 'gwc_products.retail_price', 'gwc_products.item_code')
				->join('gwc_products', 'gwc_products.id', '=', 'gwc_customers_wish.product_id')
				->join('gwc_customers', 'gwc_customers.id', '=', 'gwc_customers_wish.customer_id')
				->where(['gwc_products.is_active' => 1])
				->where(function ($sq) use ($q) {
					$sq->where('gwc_customers.name', 'LIKE', '%' . $q . '%')
						->orwhere('gwc_products.title_en', 'LIKE', '%' . $q . '%')
						->orwhere('gwc_products.item_code', 'LIKE', '%' . $q . '%');
				});

			if (!empty(Session::get('wish_customers'))) {
				$wishLists = $wishLists->where('gwc_customers_wish.customer_id', '=', Session::get('wish_customers'));
			}

			$wishLists = $wishLists->orderBy('gwc_customers_wish.id', 'DESC')
				->paginate($settingInfo->item_per_page_back);
			$wishLists->appends(['q' => $q]);
		} else {
			$wishLists = DB::table('gwc_customers_wish')
				->select('gwc_products.image', 'gwc_customers.name', 'gwc_customers_wish.created_at', 'gwc_customers_wish.id', 'gwc_products.title_en', 'gwc_products.retail_price', 'gwc_products.item_code')
				->join('gwc_products', 'gwc_products.id', '=', 'gwc_customers_wish.product_id')
				->join('gwc_customers', 'gwc_customers.id', '=', 'gwc_customers_wish.customer_id')
				->where(['gwc_products.is_active' => 1]);

			if (!empty(Session::get('wish_customers'))) {
				$wishLists = $wishLists->where('gwc_customers_wish.customer_id', '=', Session::get('wish_customers'));
			}

			$wishLists = $wishLists->orderBy('gwc_customers_wish.id', 'DESC')
				->paginate($settingInfo->item_per_page_back);
		}

		$customersLists = DB::table('gwc_customers_wish')
			->select('gwc_customers_wish.customer_id', 'gwc_customers.id', 'gwc_customers.name')
			->join('gwc_customers', 'gwc_customers.id', '=', 'gwc_customers_wish.customer_id')
			->GroupBy('gwc_customers_wish.customer_id')
			->get();


		return view('gwc.customers.wishitems', compact('wishLists', 'customersLists'));
	}
	//delete wish item
	public function deleteWishItem($id)
	{
		$wish = CustomersWish::find($id);
		$wish->delete();
		return redirect()->back()->with('message-success', 'Record is deleted successfully');
	}

	/////payments
	public function listPayments(Request $request)
	{

		$settingInfo = Settings::where("keyname", "setting")->first();

		//check search queries
		if (!empty($request->get('q'))) {
			$q = $request->get('q');
		} else {
			$q = $request->q;
		}


		//menus records
		if (!empty($q)) {

			$paymentsLists = Transaction::Where('gwc_transaction.id', '!=', '0')
				->join('gwc_orders_details', 'gwc_orders_details.order_id', '=', 'gwc_transaction.trackid')
				->select(
					'gwc_transaction.*',
					'gwc_orders_details.order_id',
					'gwc_orders_details.name',
					'gwc_orders_details.email',
					'gwc_orders_details.mobile',
					'gwc_orders_details.area_id',
					'gwc_orders_details.block',
					'gwc_orders_details.pay_mode',
					'gwc_orders_details.order_id_md5',
					'gwc_orders_details.id as oid'
				)
				->where(function ($sq) use ($q) {
					$sq->where('gwc_orders_details.name', 'LIKE', '%' . $q . '%');
					$sq->orwhere('gwc_orders_details.email', 'LIKE', '%' . $q . '%');
					$sq->orwhere('gwc_orders_details.mobile', 'LIKE', '%' . $q . '%');
					$sq->orwhere('payment_id', 'LIKE', '%' . $q . '%');
					$sq->orwhere('trackid', 'LIKE', '%' . $q . '%');
					$sq->orwhere('tranid', 'LIKE', '%' . $q . '%');
					$sq->orwhere('ref', 'LIKE', '%' . $q . '%');
					$sq->orwhere('auth', 'LIKE', '%' . $q . '%');
					$sq->orwhere('presult', 'LIKE', '%' . $q . '%');
				});
			//filter by date range
			if (!empty(Session::get('payment_filter_dates'))) {
				$explodeDates = explode("-", Session::get('payment_filter_dates'));
				if (!empty($explodeDates[0]) && !empty($explodeDates[1])) {
					$date1 = date("Y-m-d", strtotime($explodeDates[0]));
					$date2 = date("Y-m-d", strtotime($explodeDates[1]));
					$paymentsLists = $paymentsLists->whereBetween('gwc_orders_details.created_at', [$date1, $date2]);
				}
			}
			//
			if (!empty(Session::get('payment_filter_status')) && Session::get('payment_filter_status') == 'paid') {
				$paymentsLists = $paymentsLists->where('presult', 'CAPTURED');
			} else if (!empty(Session::get('payment_filter_status')) && Session::get('payment_filter_status') == 'notpaid') {
				$paymentsLists = $paymentsLists->where('presult', '!=', 'CAPTURED');
			} else if (!empty(Session::get('payment_filter_status')) && Session::get('payment_filter_status') == 'release') {
				$paymentsLists = $paymentsLists->where('release_pay', '=', 1);
			} else if (!empty(Session::get('payment_filter_status')) && Session::get('payment_filter_status') == 'nrelease') {
				$paymentsLists = $paymentsLists->where('release_pay', '!=', 1);
			}

			$paymentsLists = $paymentsLists->orderBy('created_at', 'DESC')
				->paginate($settingInfo->item_per_page_back);
			$paymentsLists->appends(['q' => $q]);
		} else {
			$paymentsLists = Transaction::Where('gwc_transaction.id', '!=', '0')
				->join('gwc_orders_details', 'gwc_orders_details.order_id', '=', 'gwc_transaction.trackid')
				->select(
					'gwc_transaction.*',
					'gwc_orders_details.order_id',
					'gwc_orders_details.name',
					'gwc_orders_details.email',
					'gwc_orders_details.mobile',
					'gwc_orders_details.area_id',
					'gwc_orders_details.block',
					'gwc_orders_details.pay_mode',
					'gwc_orders_details.order_id_md5',
					'gwc_orders_details.id as oid'
				);
			//filter by date range
			if (!empty(Session::get('payment_filter_dates'))) {
				$explodeDates = explode("-", Session::get('payment_filter_dates'));
				if (!empty($explodeDates[0]) && !empty($explodeDates[1])) {
					$date1 = date("Y-m-d", strtotime($explodeDates[0]));
					$date2 = date("Y-m-d", strtotime($explodeDates[1]));
					$paymentsLists = $paymentsLists->whereBetween('gwc_orders_details.created_at', [$date1, $date2]);
				}
			}

			if (!empty(Session::get('payment_filter_status')) && Session::get('payment_filter_status') == 'paid') {
				$paymentsLists = $paymentsLists->where('presult', 'CAPTURED');
			} else if (!empty(Session::get('payment_filter_status')) && Session::get('payment_filter_status') == 'notpaid') {
				$paymentsLists = $paymentsLists->where('presult', '!=', 'CAPTURED');
			} else if (!empty(Session::get('payment_filter_status')) && Session::get('payment_filter_status') == 'release') {
				$paymentsLists = $paymentsLists->where('release_pay', '=', 1);
			} else if (!empty(Session::get('payment_filter_status')) && Session::get('payment_filter_status') == 'nrelease') {
				$paymentsLists = $paymentsLists->where('release_pay', '!=', 1);
			}

			$paymentsLists = $paymentsLists->orderBy('created_at', 'DESC')
				->paginate($settingInfo->item_per_page_back);
		}
		return view('gwc.orders.payments', ['paymentLists' => $paymentsLists, 'settingInfo' => $settingInfo]);
	}

	///send email notification once qty is updated
	public static function sendEmailNotificationForOrderStatus($name, $email, $trackmessage, $orderid)
	{
		$settingInfo      = Settings::where("keyname", "setting")->first();
		$data = [
			'dear'            => trans('webMessage.dear') . ' ' . $name . ',',
			'footer'          => trans('webMessage.email_footer'),
			'message'         => $trackmessage,
			'subject'         => "Order Track Notification,#" . $orderid,
			'email_from' => $settingInfo->from_email,
			'email_from_name' => $settingInfo->from_name
		];
		Mail::to($email)->send(new SendGrid($data));
	}


	//store value to cookie
	public static function storetocookie(Request $request)
	{
		if (!empty($request->val)) {
			Session::put($request->key, $request->val);
		} else {
			Session::forget($request->key);
		}

		return ['status' => 200, 'message' => '', "userType" => Auth::guard('admin')->user()->userType];
	}
	//change qty in prodruct table for attribute
	public static function ChangeUpdateQuantity($product_id)
	{
		$qty = 0;
		$productUpdate   = Product::where('id', $product_id)->first();
		if (!empty($productUpdate->is_attribute)) {
			$qty   = ProductAttribute::where('product_id', $productUpdate->id)->get()->sum('quantity');
			$productUpdate->quantity = $qty;
			$productUpdate->save();
		}
	}

	//apply discount amount
	public function applydiscountAmount(Request $request)
	{
		if (empty($request->oid)) {
			return ['status' => 400, 'message' => 'Order ID is missing'];
		}
		$orderDetails    = OrdersDetails::where('id', $request->oid)->first();
		$orderDetails->delivery_date = $request->delivery_date;
		$orderDetails->seller_discount = $request->seller_discount;
		$orderDetails->save();
		return ['status' => 200, 'message' => 'Discount amount is applied'];
	}

	//get customer details
	public static function getCustomerDetails($id)
	{
		$customersLists = [];
		if (!empty($id)) {
			$customersLists = Customers::where('id', $id)->first();
			return $customersLists;
		}
	}

	public static function checkBrandDiscount($brand_id)
	{
		if (!empty($brand_id)) {
			$brand = Brand::where('id', $brand_id)->first();
			$isDisc = $brand->is_discount;
			$discVal = $brand->discount;
			if ($isDisc == '1' && $discVal > 0) {
				return true;
			}
		}
		return false;
	}

	public static function calByBrandDiscount($brand_id, $productPrice)
	{
		if (!empty($brand_id)) {
			$brand = Brand::where('id', $brand_id)->first();
			$isDisc = $brand->is_discount;
			$discVal = $brand->discount;
			if ($isDisc == 1 && $discVal > 0) {
				$disc = $discVal / 100 * $productPrice;
				$discPrice = $productPrice - $disc;
				return (object) ['price' => round($discPrice, 3), 'oldPrice' => $productPrice];
			}
			return (object) ['price' => $productPrice];
		}
		return (object) ['price' => $productPrice];
	}
}
