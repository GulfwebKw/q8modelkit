<?php

namespace App\Http\Controllers\posv1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Response;
use App\Country;
use App\Area;
use App\State;

use App\User;

use App\Settings;
use App\Product;
use App\Warranty;
use App\ProductGallery;
use App\ProductReview;
use App\ProductAttribute;

use App\ProductOptions;
use App\ProductOptionsCustom;
use App\ProductOptionsCustomChild;
use App\ProductOptionsCustomChosen;

use App\Color;
use App\Size;
use App\Categories;

use App\OrdersTemp;
use App\Orders;
use App\OrdersDetails;
use App\OrdersTrack;
use App\OrdersTempOption;
use App\OrdersOption;

use App\Coupon;

use App\NotificationEmails;
use App\Transaction;
use App\Mail\SendGrid;
use App\Mail\SendGridOrder;
use App\ProductCategory;
use Curl;
use Mail;
use DB;
use Common;


//rules
use App\Rules\Name;
use App\Rules\Mobile;
use PhpOffice\PhpSpreadsheet\Calculation\Category;

class PosProductController extends Controller
{
	public $successStatus       = 200;
	public $failedStatus        = 400;
	public $unauthorizedStatus  = 401;

	//knet response
	public function getKnetResponse(Request $request)
	{
		if ($request->trackid) {
			if (!empty(app()->getLocale())) {
				$strLang = app()->getLocale();
			} else {
				$strLang = "en";
			}
			$settingInfo = Settings::where("keyname", "setting")->first();

			$orderDetails = OrdersDetails::where('order_id', $request->trackid)->first();
			if ($orderDetails->id) {
				if ($request->presult == 'CAPTURED') {
					$orderDetails->is_paid = 1;
					$orderDetails->save();
				}
				//update trans	
				$transactionDetails = Transaction::where('trackid', $orderDetails->order_id)->first();
				$transactionDetails->presult = $request->presult;
				if ($request->payment_id) {
					$transactionDetails->payment_id = $request->payment_id;
				}
				if ($request->ref) {
					$transactionDetails->ref = $request->ref;
				}
				if ($request->tranid) {
					$transactionDetails->tranid = $request->tranid;
				}
				if ($request->auth) {
					$transactionDetails->auth = $request->auth;
				}
				if ($request->amount) {
					$transactionDetails->amt = $request->amount;
				}
				if ($request->PayType) {
					$transactionDetails->PayType = $request->PayType;
				}
				$transactionDetails->save();

				$customerDetailsTxt = '<table cellpadding="0" cellspacing="0" border="0">';
				if (!empty($orderDetails->name)) {
					$customerDetailsTxt .= '<tr><td width="150">' . trans('webMessage.name') . '</td><td>' . $orderDetails->name . '</td></tr>';
				}

				if (!empty($orderDetails->area_id)) {
					$areaInfo    = self::get_csa_info($orderDetails->area_id);
					$customerDetailsTxt .= '<tr><td>' . trans('webMessage.area') . '</td><td>' . $areaInfo['name_' . $strLang] . '</td></tr>';
				}
				if (!empty($orderDetails->block)) {
					$customerDetailsTxt .= '<tr><td>' . trans('webMessage.block') . '</td><td>' . $orderDetails->block . '</td></tr>';
				}
				if (!empty($orderDetails->street)) {
					$customerDetailsTxt .= '<tr><td>' . trans('webMessage.street') . '</td><td>' . $orderDetails->street . '</td></tr>';
				}
				if (!empty($orderDetails->avenue)) {
					$customerDetailsTxt .= '<tr><td>' . trans('webMessage.avenue') . '</td><td>' . $orderDetails->avenue . '</td></tr>';
				}
				if (!empty($orderDetails->house)) {
					$customerDetailsTxt .= '<tr><td>' . trans('webMessage.house') . '</td><td>' . $orderDetails->house . '</td></tr>';
				}
				if (!empty($orderDetails->floor)) {
					$customerDetailsTxt .= '<tr><td>' . trans('webMessage.floor') . '</td><td>' . $orderDetails->floor . '</td></tr>';
				}
				if (!empty($orderDetails->landmark)) {
					$customerDetailsTxt .= '<tr><td>' . trans('webMessage.landmark') . '</td><td>' . $orderDetails->landmark . '</td></tr>';
				}

				if (!empty($orderDetails->email)) {
					$customerDetailsTxt .= '<tr><td>' . trans('webMessage.email') . '</td><td>' . $orderDetails->email . '</td></tr>';
				}
				if (!empty($orderDetails->mobile)) {
					$customerDetailsTxt .= '<tr><td>' . trans('webMessage.mobile') . '</td><td>' . $orderDetails->mobile . '</td></tr>';
				}

				$customerDetailsTxt .= '</table>';

				//invoice details
				$invoiceDetailsTxt = '<table cellpadding="0" cellspacing="0" border="0" class="payment">';
				$invoiceDetailsTxt .= '<tr><td>' . trans('webMessage.orderid') . '</td><td>' . $orderDetails->order_id . '</td></tr>';
				$invoiceDetailsTxt .= '<tr><td>' . trans('webMessage.paymentmethod') . '</td><td>' . $orderDetails->pay_mode . '</td></tr>';
				if (!empty($request->presult) && $request->presult == 'CAPTURED') {
					$txtpaid = '<font color="#009900">' . strtoupper(trans('webMessage.paid')) . '</font>';
				} else {
					$txtpaid = '<font color="#FF0000">' . strtoupper(trans('webMessage.notpaid')) . '</font>';
				}
				$invoiceDetailsTxt .= '<tr><td>' . trans('webMessage.payment_status') . '</td><td>' . $txtpaid . '</td></tr>';
				$invoiceDetailsTxt .= '<tr><td>' . trans('webMessage.order_status') . '</td><td>' . strtoupper(trans('webMessage.pending')) . '</td></tr>';

				$invoiceDetailsTxt .= '<tr><td>' . trans('webMessage.date') . '</td><td>' . $orderDetails->created_at . '</td></tr>';

				if ($strLang == "en" && !empty($orderDetails->delivery_time_en)) {
					$invoiceDetailsTxt .= '<tr><td>' . trans('webMessage.deliverytime') . '</td><td>' . $orderDetails->delivery_time_en . '</td></tr>';
				} else if ($strLang == "ar" && !empty($orderDetails->delivery_time_ar)) {
					$invoiceDetailsTxt .= '<tr><td>' . trans('webMessage.deliverytime') . '</td><td>' . $orderDetails->delivery_time_ar . '</td></tr>';
				}

				$invoiceDetailsTxt .= '</table>';

				//list order
				$tempOrders = Orders::where('order_id', $orderDetails->order_id)->get();
				$ordertxt_child = '';
				$subtotalprice = 0;
				$grandtotal = 0;
				$totalprice = 0;
				foreach ($tempOrders as $tempOrder) {
					$productDetails = self::getProductDetails($tempOrder->product_id);
					if ($productDetails->image) {
						$prodImage = url('uploads/product/thumb/' . $productDetails->image);
					} else {
						$prodImage = url('uploads/no-image.png');
					}
					if (!empty($tempOrder->size_id)) {
						$sizeName = self::sizeNameStatic($tempOrder->size_id, $strLang);
						$sizeName = '<br>' . trans('webMessage.size') . ':' . $sizeName;
					} else {
						$sizeName = '';
					}
					if (!empty($tempOrder->color_id)) {
						$colorName = self::colorNameStatic($tempOrder->color_id, $strLang);
						$colorName = '<br>' . trans('webMessage.color') . ':' . $colorName;
						//color image
						$colorImageDetails = self::getColorImage($tempOrder->product_id, $tempOrder->color_id);
						if (!empty($colorImageDetails->color_image)) {
							$prodImage = url('uploads/product/colors/thumb/' . $colorImageDetails->color_image);
						}
					} else {
						$colorName = '';
					}
					$orderOptions = self::getOptionsDtailsOrderBr($tempOrder->id);
					$unitprice = $tempOrder->unit_price;
					$subtotalprice = $unitprice * $tempOrder->quantity;
					$title = $productDetails['title_' . $strLang];

					$warrantyTxt = '';
					if (!empty($productDetails->warranty)) {
						$warrantyDetails = self::getWarrantyDetails($productDetails->warranty);
						$warrantyTxt = $strLang == "en" ? $warrantyDetails->title_en : $warrantyDetails->title_ar;
					}


					$ordertxt_child .= '<tr>
                    <td><a href="' . url('details/' . $productDetails->id . '/' . $productDetails->slug) . '"><img src="' . $prodImage . '" alt="' . $title . '" width="50"><br>' . $productDetails->item_code . '</a>
					</td>
                    <td>' . $title . $sizeName . $colorName . $orderOptions . '<br>' . $warrantyTxt . '</td>
                    <td>' . trans('webMessage.' . $settingInfo->base_currency) . ' ' . $unitprice . '</td>
                    <td align="center">' . $tempOrder->quantity . '</td>
                    <td>' . trans('webMessage.' . $settingInfo->base_currency) . ' ' . $subtotalprice . '</td>
                    </tr>';

					$totalprice += $subtotalprice;
				}
				//order details
				$orderDetailsTxt = '<table cellpadding="0" cellspacing="0" border="0" class="pro_table">
                    <tr>
                    <td class="headertd">' . trans('webMessage.image') . '</td>
                    <td class="headertd">' . trans('webMessage.details') . '</td>
                    <td class="headertd">' . trans('webMessage.unit_price') . '</td>
                    <td class="headertd">' . trans('webMessage.quantity') . '</td>
                    <td class="headertd">' . trans('webMessage.subtotal') . '</td>
                    </tr>';
				$orderDetailsTxt .= $ordertxt_child;
				$orderDetailsTxt .= '<tr><td colspan="4" align="right"><b>' . trans('webMessage.subtotal') . '&nbsp;:&nbsp;&nbsp;</b></td><td>' . trans('webMessage.' . $settingInfo->base_currency) . '' . $totalprice . '</td></tr>';
				//seller discount
				if (!empty($orderDetails->seller_discount)) {
					$orderDetailsTxt .= '<tr><td colspan="4" align="right">' . trans('webMessage.seller_discount') . '&nbsp;:&nbsp;&nbsp;</td><td><font color="#FF0000">-' . trans('webMessage.' . $settingInfo->base_currency) . ' ' . $orderDetails->seller_discount . '</font></td></tr>';
					$totalprice = $totalprice - $orderDetails->seller_discount;
				}
				//show discount if available but not free delivery
				if (!empty($orderDetails->coupon_code) && empty($orderDetails->coupon_free)) {
					$orderDetailsTxt .= '<tr><td colspan="4" align="right">' . trans('webMessage.coupon_discount') . '&nbsp;:&nbsp;&nbsp;</td><td><font color="#FF0000">-' . trans('webMessage.' . $settingInfo->base_currency) . ' ' . $orderDetails->coupon_amount . '</font></td></tr>';
					$totalprice = $totalprice - $orderDetails->coupon_amount;
				}
				if (!empty($orderDetails->coupon_code) && !empty($orderDetails->coupon_free)) {
					$orderDetailsTxt .= '<tr><td colspan="4" align="right">' . trans('webMessage.coupon_discount') . '&nbsp;:&nbsp;&nbsp;</td><td><font color="#FF0000">' . strtoupper(trans('webMessage.free_delivery')) . '</font></td></tr>';
				}

				if (!empty($orderDetails->delivery_charges) && empty($orderDetails->coupon_free)) {
					$deliveryCharge = $orderDetails->delivery_charges;
					$orderDetailsTxt .= '<tr><td colspan="4" align="right">' . trans('webMessage.delivery_charge') . '&nbsp;:&nbsp;&nbsp;</td><td>' . trans('webMessage.' . $settingInfo->base_currency) . ' ' . $deliveryCharge . '</td></tr>';
					$totalprice = $totalprice + $deliveryCharge;
				}
				$orderDetailsTxt .= '<tr><td colspan="4" align="right"><b>' . trans('webMessage.grandtotal') . '</b>&nbsp;:&nbsp;&nbsp;</td><td>' . trans('webMessage.' . $settingInfo->base_currency) . ' ' . $totalprice . '</td></tr>';
				$orderDetailsTxt .= '</table>';

				//payment temp
				$paymentDetails = '';
				if (!empty($request->presult) && $request->presult == 'CAPTURED') {
					$txtpaid = '<font color="#009900">' . strtoupper(trans('webMessage.paid')) . '</font>';
					$knetMessage = trans('webMessage.yourorderisplacedwithsuccess');
				} else {
					$txtpaid = '<font color="#FF0000">' . strtoupper(trans('webMessage.notpaid')) . '</font>';
					$knetMessage = trans('webMessage.yourorderisplacedwithfailed');
				}
				$paymentDetails .= '<table cellpadding="0" cellspacing="0" border="0" class="payment">
	    <tr>
	      <td>' . trans('webMessage.result') . '</td>
	      <td>' . $txtpaid . '</td>
        </tr>
	    <tr>
	      <td>' . trans('webMessage.date') . '</td>
	      <td>' . date('Y-m-d H:i:s') . '</td>
        </tr>
	    <tr>
	      <td>' . trans('webMessage.transid') . '</td>
	      <td>' . $request->tranid . '</td>
        </tr>
	    <tr>
	      <td>' . trans('webMessage.paymentid') . '</td>
	      <td>' . $request->payment_id . '</td>
        </tr>
	    <tr>
	      <td>' . trans('webMessage.amount') . '</td>
	      <td>' . number_format($request->amount, 3) . ' ' . trans('webMessage.kd') . '</td>
        </tr>
      </table>';
				$trackYourOrderTxt = trans('webMessage.trackyourorderhistory') . '<br>' . url('/order-details') . '/' . md5($orderDetails->order_id);
				//send email to admins
				$adminNotifications = NotificationEmails::where('is_active', 1)->get();
				if (!empty($adminNotifications) && count($adminNotifications) > 0) {
					foreach ($adminNotifications as $adminNotification) {
						$deartxt = !empty($adminNotification->name) ? trans('webMessage.dear') . ' ' . $adminNotification->name : trans('webMessage.dear') . ' ' . trans('webMessage.admin');
						$data = [
							'deartxt'         => $deartxt,
							'bodytxt'         => trans('webMessage.admin_order_msg_cod'),
							'customerDetails' => $customerDetailsTxt,
							'invoiceDetails'  => $invoiceDetailsTxt,
							'orderDetails'    => $orderDetailsTxt,
							'paymentDetails'  => $paymentDetails,
							'trackYourOrder'  => $trackYourOrderTxt,
							'subject'         => "Order Notification From " . $settingInfo->name_en . " #" . $orderDetails->order_id,
							'email_from'      => $settingInfo->from_email,
							'email_from_name' => $settingInfo->from_name
						];
						Mail::to($adminNotification->email)->send(new SendGridOrder($data));
					}
				}
				//send email to user
				if (!empty($orderDetails->email)) {
					$deartxt = !empty($orderDetails->name) ? trans('webMessage.dear') . ' ' . $orderDetails->name : trans('webMessage.dear') . ' ' . trans('webMessage.buyer');
					$data = [
						'deartxt'         => $deartxt,
						'bodytxt'         => trans('webMessage.user_order_msg_cod'),
						'customerDetails' => $customerDetailsTxt,
						'invoiceDetails'  => $invoiceDetailsTxt,
						'orderDetails'    => $orderDetailsTxt,
						'paymentDetails'  => $paymentDetails,
						'trackYourOrder'  => $trackYourOrderTxt,
						'subject'         => "Order Notification From " . $settingInfo->name_en . " #" . $orderDetails->order_id,
						'email_from'      => $settingInfo->from_email,
						'email_from_name' => $settingInfo->from_name
					];
					Mail::to($orderDetails->email)->send(new SendGridOrder($data));
				}
				if (!empty($request->presult) && $request->presult == 'CAPTURED') {

					//send sms notification for cod
					$isValidMobile = Common::checkMobile($orderDetails->mobile);
					if (!empty($settingInfo->sms_text_knet_active) && !empty($settingInfo->sms_text_knet_en) && !empty($settingInfo->sms_text_knet_ar) && !empty($isValidMobile)) {
						if ($orderDetails->strLang == "en") {
							$smsMessage = $settingInfo->sms_text_knet_en;
						} else {
							$smsMessage = $settingInfo->sms_text_knet_ar;
						}
						$to      = $orderDetails->mobile;
						$sms_msg = $smsMessage . " #" . $orderDetails->order_id;
						Common::SendSms($to, $sms_msg);
					}
					//end sending sms for cod


					return redirect($settingInfo->pos_result_url . '?orderid=' . md5($orderDetails->order_id) . '&msg=' . $knetMessage);
				} else {
					return redirect($settingInfo->pos_result_url . '?orderid=' . md5($orderDetails->order_id) . '&msg=' . $knetMessage);
				}
			} else { //order exist or not
				return redirect($settingInfo->pos_result_url . '?orderid=&msg=' . trans('webMessage.invalidpayment'));
			}
		} else { //track id not empty
			return redirect($settingInfo->pos_result_url . '?orderid=&msg=' . trans('webMessage.invalidpayment'));
		}
	}




	public static function get_csa_info($id)
	{
		$country = Country::where('id', $id)->first();
		return $country;
	}

	public static function UpdateOrderAmounts($id, $amount)
	{
		$orderDetails = OrdersDetails::Where('id', $id)->first();
		$orderDetails->total_amount = $amount;
		$orderDetails->save();
	}

	public static function changeOptionQuantity($mode, $ids)
	{
		$explodechildids = explode(",", $ids);
		for ($i = 0; $i < count($explodechildids); $i++) {
			$productChildOption = ProductOptions::where("id", $explodechildids[$i])->first();
			if ($mode == "d") {
				$productChildOption->quantity = ($productChildOption->quantity - 1);
			} else {
				$productChildOption->quantity = ($productChildOption->quantity + 1);
			}
			$productChildOption->save();
		}
	}


	public static function get_delivery_charge($areaid)
	{
		$settingInfo = Settings::where("keyname", "setting")->first();
		$fees = round($settingInfo->flat_rate, 3);
		if (!empty($areaid)) {
			$areaInfo = Country::where('id', $areaid)->first();
			if (!empty($areaInfo->id) && !empty($areaInfo->delivery_fee)) {
				$fees = round($areaInfo->delivery_fee, 3);
			}
		}
		return $fees;
	}

	public function getAreas($parent = 2)
	{
		$State = [];
		$listStates = Country::where('parent_id', $parent)->orderBy('name_en', 'asc')->get();
		if (!empty($listStates) && count($listStates) > 0) {

			foreach ($listStates  as $listState) {
				$State[] = [
					"id" => $listState->id,
					"name" => $listState->name_en,
					"area" => self::getAreasChild($listState->id),
				];
			}
		}

		$response['data'] = $State;
		return response($response, $this->successStatus);
	}

	public function getAreasChild($parent)
	{
		$State = [];
		$listStates = Country::where('parent_id', $parent)->orderBy('name_en', 'asc')->get();
		if (!empty($listStates) && count($listStates) > 0) {

			foreach ($listStates  as $listState) {
				$State[] = [
					"id" => $listState->id,
					"name" => $listState->name_en,
					"delivery_fee" => $listState->delivery_fee,
				];
			}
		}
		return $State;
	}



	//generate serial number with prefix
	public function OrderserialNumber()
	{
		$orderInfo = OrdersDetails::orderBy("id", "desc")->first();
		if (!empty($orderInfo->id)) {
			$lastProdId = ($orderInfo->id + 1);
		} else {
			$lastProdId = 1;
		}
		$seriamNum = $lastProdId;
		return $seriamNum;
	}

	//sid number
	public function OrderSidNumber()
	{
		$orderInfo = OrdersDetails::orderBy("id", "desc")->first();
		if (!empty($orderInfo->id)) {
			$lastProdId = ($orderInfo->id + 1);
		} else {
			$lastProdId = 1;
		}
		return $lastProdId;
	}

	public static function getOptionsDtailsOrderBr($oid)
	{
		$optionDetailstxt = '';
		if (!empty(app()->getLocale())) {
			$strLang = app()->getLocale();
		} else {
			$strLang = "en";
		}
		$optionDetails = OrdersOption::where("oid", $oid)->get();
		if (!empty($optionDetails) && count($optionDetails) > 0) {
			foreach ($optionDetails as $optionDetail) {
				$optionParentDetails = ProductOptionsCustom::where("id", $optionDetail->option_id)->first();
				if (!empty($optionParentDetails->id)) {
					$option_name = $strLang == "en" ? $optionParentDetails->option_name_en : $optionParentDetails->option_name_ar;
					$optionDetailstxt .= '<br>' . $option_name . ':(' . self::getChildOptionsDtailsString($optionDetail->option_child_ids) . ')';
				}
			}
		}
		return $optionDetailstxt;
	}


	//deduct quantity
	public function deductQuantity($product_id, $quantity, $size_id = 0, $color_id = 0)
	{
		$productDetails   = Product::where('id', $product_id)->first();
		if (empty($productDetails['is_attribute'])) {
			$oldquantity = $productDetails['quantity'];
			$productDetails->quantity = $oldquantity - $quantity;
			$productDetails->save();
		} else {
			if (!empty($size_id) && !empty($color_id)) {
				$attributes = ProductAttribute::where('product_id', $product_id)->where('size_id', $size_id)->where('color_id', $color_id)->first();
				if (!empty($attributes->id)) {
					$oldquantity = $attributes->quantity;
					$attributes->quantity = $oldquantity - $quantity;
					$attributes->save();
				}
			} else if (!empty($size_id) && empty($color_id)) {
				$attributes = ProductAttribute::where('product_id', $product_id)->where('size_id', $size_id)->first();
				if (!empty($attributes->id)) {
					$oldquantity = $attributes->quantity;
					$attributes->quantity = $oldquantity - $quantity;
					$attributes->save();
				}
			} else if (empty($size_id) && !empty($color_id)) {
				$attributes = ProductAttribute::where('product_id', $product_id)->where('color_id', $color_id)->first();
				if (!empty($attributes->id)) {
					$oldquantity = $attributes->quantity;
					$attributes->quantity = $oldquantity - $quantity;
					$attributes->save();
				}
			}
		}
		//change qty in product table for attribute only
		self::ChangeUpdateQuantity($product_id);
		//end 
	}

	public static function getColorImage($productid, $colorid)
	{
		$Attributes     = ProductAttribute::where('product_id', $productid)->where('color_id', $colorid)->first();
		return $Attributes;
	}

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

	//order confirmation
	public function checkoutConfirm(Request $request)
	{
		$tempid = 0;
		if (!empty(app()->getLocale())) {
			$strLang = app()->getLocale();
		} else {
			$strLang = "en";
		}
		$settingInfo = Settings::where("keyname", "setting")->first();
		//get cusatomer ID
		$customer_id = !empty($request->customer_id) ? $request->customer_id : '0';
		if (empty($customer_id)) {
			$response['data'] = trans('webMessage.chooseacustomer') . '-01';
			return response($response, $this->failedStatus);
		}

		$userDetails = User::where('id', $customer_id)->first();
		if (empty($userDetails->id)) {
			$response['data'] = trans('webMessage.chooseacustomer') . '-02';
			return response($response, $this->failedStatus);
		}

		if (empty($request->temp_uniqueid)) {
			$response['data'] = trans('webMessage.tempidmissing');
			return response($response, $this->failedStatus);
		} else {
			$tempid = $request->temp_uniqueid;
		}
		$tempOrders = self::loadTempOrders($tempid);
		if (empty($tempOrders) || count($tempOrders) == 0) {
			$response['data'] = trans('webMessage.yourcartisempty');
			return response($response, $this->failedStatus);
		}

		//check quantity exiot or not
		$tempQuantityExist = self::isQuantityExistForOrder($tempid);
		if (empty($tempQuantityExist)) {
			$response['data'] = trans('webMessage.oneoftheitemqtyexceeded');
			return response($response, $this->failedStatus);
		}

		//check fields
		if (empty($request->name)) {
			$response['data'] = trans('webMessage.name_required');
			return response($response, $this->failedStatus);
		}
		if (!empty($request->name) && strlen($request->name) > 150) {
			$response['data'] = trans('webMessage.max_name_chars_required');
			return response($response, $this->failedStatus);
		}
		if (empty($request->mobile)) {
			$response['data'] = trans('webMessage.mobile_required');
			return response($response, $this->failedStatus);
		}
		if (!empty($request->mobile)) {
			$isValidMobile = Common::checkMobile($request->mobile);
			if (empty($isValidMobile)) {
				$response['data'] = trans('webMessage.mobile_invalid');
				return response($response, $this->failedStatus);
			}
		}

		// if(empty($request->area)){
		// $response['data']=trans('webMessage.area_required');
		// return response($response,$this->failedStatus);
		// }
		// if(empty($request->block)){
		// $response['data']=trans('webMessage.block_required');
		// return response($response,$this->failedStatus);
		// }
		// if(empty($request->street)){
		// $response['data']=trans('webMessage.street_required');
		// return response($response,$this->failedStatus);
		// }
		// if(empty($request->house)){
		// $response['data']=trans('webMessage.house_required');
		// return response($response,$this->failedStatus);
		// }
		if (empty($request->payment_method)) {
			$response['data'] = trans('webMessage.payment_method_required');
			return response($response, $this->failedStatus);
		}

		//check min order amount
		// $totalAmtchk = self::getTotalCartAmount($request->temp_uniqueid);
		// if (!empty($settingInfo->min_order_amount) && !empty($totalAmtchk) && $settingInfo->min_order_amount >  $totalAmtchk) {
		// 	$response['data'] = trans('webMessage.minimumordermessage') . ' ' . number_format($settingInfo->min_order_amount, 3) . ' ' . trans('webMessage.kd');
		// 	return response($response, $this->failedStatus);
		// }


		$expectedDate = date("Y-m-d", strtotime(date("Y-m-d") . "+1 day"));

		$orderid      = strtolower($settingInfo->prefix) . $this->OrderserialNumber();
		$ordersid     = $this->OrderSidNumber();
		$orderDetails = new OrdersDetails;
		$uid = 0;
		if (!empty($customer_id)) {
			$orderDetails->customer_id  = $customer_id;
			$uid = $customer_id;
		}

		$areaDetails          = Area::find($request->input('area'));


		$orderDetails->order_id     = $orderid;
		$orderDetails->sid          = $ordersid;
		$orderDetails->order_id_md5 = md5($orderid);
		$orderDetails->latitude     = !empty($request->latitude) ? $request->latitude : '';
		$orderDetails->longitude    = !empty($request->longitude) ? $request->longitude : '';
		$orderDetails->name         = !empty($request->name) ? $request->name : 'Guest';
		$orderDetails->email        = !empty($request->email) ? $request->email : '';
		$orderDetails->mobile       = !empty($request->mobile) ? $request->mobile : '';
		$orderDetails->country_id   = !empty($request->country) ? $request->country : '2';
		$orderDetails->state_id     = !empty($areaDetails->parent_id) ? $areaDetails->parent_id : '0';
		$orderDetails->area_id      = !empty($request->area) ? $request->area : '0';
		$orderDetails->block        = !empty($request->block) ? $request->block : '';
		$orderDetails->street       = !empty($request->street) ? $request->street : '';
		$orderDetails->avenue       = !empty($request->avenue) ? $request->avenue : '';
		$orderDetails->house        = !empty($request->house) ? $request->house : '';
		$orderDetails->floor        = !empty($request->floor) ? $request->floor : '';
		$orderDetails->landmark     = !empty($request->landmark) ? $request->landmark : '';
		$orderDetails->device_type  = 'pos';
		$orderDetails->pay_mode	 = !empty($request->payment_method) ? $request->payment_method : '';
		//coupon 
		if (!empty($request->coupon_code)) {
			$orderDetails->is_coupon_used = 1;
			$orderDetails->coupon_code    = !empty($request->coupon_code) ? $request->coupon_code : '';
			$orderDetails->coupon_amount  = !empty($request->coupon_discount) ? $request->coupon_discount : '0';
			$orderDetails->coupon_free    = !empty($request->coupon_free) ? $request->coupon_free : '0';
		}
		//user discount
		if (!empty($request->user_discount)) {
			$orderDetails->seller_discount = $request->user_discount;
		}

		//delivery charges
		if ($request->delivery_status) {
			$deliveryCharge = self::get_delivery_charge($request->area);
			$orderDetails->delivery_charges = !empty($request->coupon_free) ? 0 : $deliveryCharge;
		}

		$orderDetails->strLang          = $strLang;
		$orderDetails->delivery_date    = $expectedDate;
		$orderDetails->save();
		//import temp order to order table
		$ordertxt_child = '';
		$subtotalprice = 0;
		$grandtotal = 0;
		$totalprice = 0;
		$orderOptions = '';
		foreach ($tempOrders as $tempOrder) {
			$productDetails = self::getProductDetails($tempOrder->product_id);
			if (!empty($tempOrder->size_id)) {
				$sizeName = self::sizeNameStatic($tempOrder->size_id, $strLang);
				$sizeName = '<br>' . trans('webMessage.size') . ':' . $sizeName;
			} else {
				$sizeName = '';
			}
			if (!empty($tempOrder->color_id)) {
				$colorName = self::colorNameStatic($tempOrder->color_id, $strLang);
				$colorName = '<br>' . trans('webMessage.color') . ':' . $colorName;
			} else {
				$colorName = '';
			}
			$orderOptions = self::getOptionsDtailsOrderBr($tempOrder->id);
			//deduct quantity
			$this->deductQuantity($tempOrder->product_id, $tempOrder->quantity, $tempOrder->size_id, $tempOrder->color_id);
			$unitprice     = $tempOrder->unit_price;
			$subtotalprice = $unitprice * $tempOrder->quantity;
			$title = $strLang == "en" ? $productDetails->title_en : $productDetails->title_ar;
			if (!empty($productDetails->sku_no)) {
				$skno = $productDetails->sku_no;
			} else {
				$skno = '';
			}

			$warrantyTxt = '';
			if (!empty($productDetails->warranty)) {
				$warrantyDetails = self::getWarrantyDetails($productDetails->warranty);
				$warrantyTxt     = $strLang == "en" ? $warrantyDetails->title_en : $warrantyDetails->title_ar;
			}

			$ordertxt_child .= '<tr>
						<td><a href="' . url('details/' . $productDetails->id . '/' . $productDetails->slug) . '"><img src="' . url('uploads/product/thumb/' . $productDetails['image']) . '" alt="' . $title . '" width="50"></a><br>' . $productDetails->item_code . '<br>' . $skno . '</td>
						<td>' . $title . $sizeName . $colorName . $orderOptions . '<br>' . $warrantyTxt . '</td>
						<td>' . trans('webMessage.' . $settingInfo->base_currency) . ' ' . $unitprice . '</td>
						<td align="center">' . $tempOrder->quantity . '</td>
						<td>' . trans('webMessage.' . $settingInfo->base_currency) . ' ' . $subtotalprice . '</td>
						</tr>';
			$orders = new Orders;
			$orders->oid       = $orderDetails->id;
			$orders->order_id  = $orderid;
			$orders->product_id = $tempOrder->product_id;
			$orders->size_id   = $tempOrder->size_id;
			$orders->color_id  = $tempOrder->color_id;
			$orders->unit_price = $tempOrder->unit_price;
			$orders->quantity  = $tempOrder->quantity;
			$orders->save();
			//add option
			$tempOrderOptions = OrdersTempOption::where("oid", $tempOrder->id)->get();
			if (!empty($tempOrderOptions) && count($tempOrderOptions) > 0) {
				foreach ($tempOrderOptions as $tempOrderOption) {
					self::changeOptionQuantity('d', $tempOrderOption->option_child_ids); //deduct qty
					$OrderOption = new OrdersOption;
					$OrderOption->product_id       = $tempOrderOption->product_id;
					$OrderOption->oid              = $orders->id;
					$OrderOption->option_id        = $tempOrderOption->option_id;
					$OrderOption->option_child_ids = $tempOrderOption->option_child_ids;
					$OrderOption->save();
					//remove option
					$tempOrds = OrdersTempOption::find($tempOrderOption->id);
					$tempOrds->delete();
				}
			}
			//remove temp record
			$tempOrd = OrdersTemp::find($tempOrder->id);
			$tempOrd->delete();

			//plus sub total price
			$totalprice += $subtotalprice;
		}

		$orderDetailsTxt = '<table cellpadding="0" cellspacing="0" border="0" class="pro_table">
						<tr>
						<td>' . trans('webMessage.image') . '</td>
						<td>' . trans('webMessage.details') . '</td>
						<td>' . trans('webMessage.unit_price') . '</td>
						<td>' . trans('webMessage.quantity') . '</td>
						<td>' . trans('webMessage.subtotal') . '</td>
						</tr>';
		$orderDetailsTxt .= $ordertxt_child;

		$orderDetailsTxt .= '<tr><td colspan="4" align="right"><b>' . trans('webMessage.subtotal') . '&nbsp;:&nbsp;&nbsp;</b></td><td>' . trans('webMessage.' . $settingInfo->base_currency) . '' . $totalprice . '</td></tr>';
		//show discount if available but not free delivery
		// if(!empty($orderDetails->coupon_code) && empty($orderDetails->coupon_free)){
		// $orderDetailsTxt.='<tr><td colspan="4" align="right">'.trans('webMessage.coupon_discount').'&nbsp;:&nbsp;&nbsp;</td><td><font color="#FF0000">-'.trans('webMessage.'.$settingInfo->base_currency).' '.$orderDetails->coupon_amount.'</font></td></tr>';
		// $totalprice=$totalprice-$orderDetails->coupon_amount;
		// }
		//show seller discount
		if (!empty($orderDetails->seller_discount)) {
			$orderDetailsTxt .= '<tr><td colspan="4" align="right">' . trans('Seller Discount') . '&nbsp;:&nbsp;&nbsp;</td><td><font color="#FF0000">-' . $orderDetails->seller_discount . '</font></td></tr>';
			$discounted = 	$totalprice - $orderDetails->seller_discount;
			$totalprice = number_format($discounted, 3);
		}



		if (!empty($orderDetails->coupon_code) && !empty($orderDetails->coupon_free)) {
			$orderDetailsTxt .= '<tr><td colspan="4" align="right">' . trans('webMessage.coupon_discount') . '&nbsp;:&nbsp;&nbsp;</td><td><font color="#FF0000">' . strtoupper(trans('webMessage.free_delivery')) . '</font></td></tr>';
		}

		if (!empty($orderDetails->delivery_charges) && empty($orderDetails->coupon_free)) {
			$deliveryCharge  = $orderDetails->delivery_charges;
			$orderDetailsTxt .= '<tr><td colspan="4" align="right">' . trans('webMessage.delivery_charge') . '&nbsp;:&nbsp;&nbsp;</td><td>' . trans('webMessage.' . $settingInfo->base_currency) . ' ' . $deliveryCharge . '</td></tr>';
			$totalprice = $totalprice + $deliveryCharge;
		}
		$orderDetailsTxt .= '<tr><td colspan="4" align="right"><b>' . trans('webMessage.grandtotal') . '</b>&nbsp;:&nbsp;&nbsp;</td><td>' . trans('webMessage.' . $settingInfo->base_currency) . ' ' . $totalprice . '</td></tr>';
		$orderDetailsTxt .= '</table>';

		$invoiceDetailsTxt = '<table cellpadding="0" cellspacing="0" border="0" class="payment">';
		$invoiceDetailsTxt .= '<tr><td>' . trans('webMessage.orderid') . '</td><td>' . $orderid . '</td></tr>';
		//	$invoiceDetailsTxt.='<tr><td>'.trans('webMessage.paymentmethod').'</td><td>'.$orderDetails->pay_mode.'</td></tr>';
		if (!empty($orderDetails->is_paid)) {
			$txtpaid = '<font color="#009900">' . strtoupper(trans('webMessage.paid')) . '</font>';
		} else {
			$txtpaid = '<font color="#FF0000">' . strtoupper(trans('webMessage.notpaid')) . '</font>';
		}
		//	$invoiceDetailsTxt.='<tr><td>'.trans('webMessage.payment_status').'</td><td>'.$txtpaid.'</td></tr>';
		//	$invoiceDetailsTxt.='<tr><td>'.trans('webMessage.order_status').'</td><td>'.strtoupper(trans('webMessage.pending')).'</td></tr>';
		$invoiceDetailsTxt .= '</table>';

		$customerDetailsTxt = '';
		if (!empty($orderDetails->name)) {
			$customerDetailsTxt .= '<b>' . $orderDetails->name . '</b><br>';
		}
		if (!empty($orderDetails->state_id)) {
			$stateInfo   = self::get_csa_info($orderDetails->state_id);
			$customerDetailsTxt .= $stateInfo['name_' . $strLang] . ',';
		}
		if (!empty($orderDetails->area_id)) {
			$areaInfo    = self::get_csa_info($orderDetails->area_id);
			$customerDetailsTxt .= $areaInfo['name_' . $strLang] . ',<br>';
		}
		if (!empty($orderDetails->block)) {
			$customerDetailsTxt .= '<b>' . trans('webMessage.block') . ' : </b>' . $orderDetails->block . ',';
		}
		if (!empty($orderDetails->street)) {
			$customerDetailsTxt .= '<b>' . trans('webMessage.street') . ' : </b>' . $orderDetails->street . ',';
		}
		if (!empty($orderDetails->avenue)) {
			$customerDetailsTxt .= '<b>' . trans('webMessage.avenue') . ' : </b>' . $orderDetails->avenue . ',<br>';
		}
		if (!empty($orderDetails->house)) {
			$customerDetailsTxt .= '<b>' . trans('webMessage.house') . ' : </b>' . $orderDetails->house . ',';
		}
		if (!empty($orderDetails->floor)) {
			$customerDetailsTxt .= '<b>' . trans('webMessage.floor') . ' : </b>' . $orderDetails->floor . ',';
		}
		if (!empty($orderDetails->landmark)) {
			$customerDetailsTxt .= '<b>' . trans('webMessage.landmark') . ' : </b>' . $orderDetails->landmark;
		}

		if (!empty($orderDetails->email)) {
			$customerDetailsTxt .= '<br><b>' . trans('webMessage.email') . ' : </b>' . $orderDetails->email;
		}
		if (!empty($orderDetails->mobile)) {
			$customerDetailsTxt .= '<br><b>' . trans('webMessage.mobile') . ' : </b>' . $orderDetails->mobile;
		}
		//update total amount 
		self::UpdateOrderAmounts($orderDetails->id, $totalprice);

		//track url	
		$trackYourOrderTxt = trans('webMessage.trackyourorderhistory') . '<br>' . url('/order-details') . '/' . md5($orderid);
		$paymentDetailsTxt = '';
		//send email notification if COD
		if ($request->payment_method == "COD") {
			//send email to admins
			$adminNotifications = NotificationEmails::where('is_active', 1)->get();
			if (!empty($adminNotifications) && count($adminNotifications) > 0) {
				foreach ($adminNotifications as $adminNotification) {
					$deartxt = !empty($adminNotification->name) ? trans('webMessage.dear') . ' ' . $adminNotification->name : trans('webMessage.dear') . ' ' . trans('webMessage.admin');
					$data = [
						'deartxt'         => $deartxt,
						'bodytxt'         => trans('webMessage.admin_order_msg_cod'),
						'customerDetails' => $customerDetailsTxt,
						'invoiceDetails'  => $invoiceDetailsTxt,
						'orderDetails'    => $orderDetailsTxt,
						'paymentDetails'  => $paymentDetailsTxt,
						'trackYourOrder'  => $trackYourOrderTxt,
						'subject'         => "Order Notification From " . $settingInfo->name_en . " #" . $orderid,
						'email_from'      => $settingInfo->from_email,
						'email_from_name' => $settingInfo->from_name
					];
					Mail::to($adminNotification->email)->send(new SendGridOrder($data));
				}
			}
			//send email to user
			if (!empty($orderDetails->email)) {
				$deartxt = !empty($orderDetails->name) ? trans('webMessage.dear') . ' ' . $orderDetails->name : trans('webMessage.dear') . ' ' . trans('webMessage.buyer');
				$data = [
					'deartxt'         => $deartxt,
					'bodytxt'         => trans('webMessage.user_order_msg_cod'),
					'customerDetails' => $customerDetailsTxt,
					'invoiceDetails'  => $invoiceDetailsTxt,
					'orderDetails'    => $orderDetailsTxt,
					'paymentDetails'  => $paymentDetailsTxt,
					'trackYourOrder'  => $trackYourOrderTxt,
					'subject'         => "Order Notification From " . $settingInfo->name_en . " #" . $orderid,
					'email_from'      => $settingInfo->from_email,
					'email_from_name' => $settingInfo->from_name
				];
				Mail::to($orderDetails->email)->send(new SendGridOrder($data));
			}

			//send sms notification for cod
			$isValidMobile = Common::checkMobile($orderDetails->mobile);
			if (!empty($settingInfo->sms_text_cod_active) && !empty($settingInfo->sms_text_cod_en) && !empty($settingInfo->sms_text_cod_ar) && !empty($isValidMobile)) {
				if ($strLang == "en") {
					$smsMessage = $settingInfo->sms_text_cod_en;
				} else {
					$smsMessage = $settingInfo->sms_text_cod_ar;
				}
				$to         = $orderDetails->mobile;
				$sms_msg    = $smsMessage . " #" . $orderDetails->order_id;
				Common::SendSms($to, $sms_msg);
			}

			//end sending sms for cod
			$response['data'] = ['trackid' => $orderid, 'expectedDate' => $expectedDate, 'message' => trans('webMessage.yourorderisplacedsucces')];
			return response($response, $this->successStatus);
		} else { // else for COD

			if ($request->payment_method == "KNET") {
				$payType = 1;
			} else {
				$payType = 2;
			}
			$transaction = new Transaction;
			$transaction->presult  = 'HOST TIMEOUT';
			$transaction->postdate = date("md");
			$transaction->udf1     = $orderid;
			$transaction->udf2     = $totalprice;
			$transaction->udf3     = $strLang;
			$transaction->udf4     = $uid;
			$transaction->udf5     = $settingInfo->name_en;
			$transaction->trackid  = $orderid;
			$transaction->save();

			///prepare payment
			if ($settingInfo->is_knet_live == '1') {
				$paymentgurl = 'https://www.dezsms.com/cbk_pay/api_payment_processing.php';
			} else {
				$paymentgurl = 'https://www.dezsms.com/cbk_pay_demo/api_payment_processing.php';
			}
			$returnurl   = url('api/posv1/knet_response');

			$item_details = "Purchasing from " . $settingInfo->name_en;
			$CurlResponse = Curl::to($paymentgurl)
				->withData([
					'keyword'      => $settingInfo->gulfpay_key,
					'apikey'       => $settingInfo->gulfpay_token,
					'refid'        => $orderid,
					'returnurl'    => $returnurl,
					'amount'       => $totalprice,
					'paytype'      => $payType,
					'item_details' => $item_details
				])->post();
			$jsdecode = json_decode($CurlResponse, true);

			if ($jsdecode['status'] == 'success') {
				$response['data']   = $jsdecode['payurl'];
				return response($response, $this->successStatus);
			} else {
				$emsg = $jsdecode['message'];
				$response['data']   = trans('webMessage.paymentprocessingerrorfound') . '(' . $emsg . ')';
				return response($response, $this->failedStatus);
			}
			//end prepare payment
		}
	}




	public static function isQuantityExistForOrder($tempid)
	{
		$flag = 0;
		$tempOrders = self::loadTempOrders($tempid);
		if (!empty($tempOrders) && count($tempOrders) > 0) {
			foreach ($tempOrders as $tempOrder) {
				$existQty = self::getProductQuantity($tempOrder->product_id, $tempOrder->size_id, $tempOrder->color_id);
				if ($existQty >= $tempOrder->quantity) {
					$flag = 1;
				}
			}
		}
		return $flag;
	}

	public function getPaymentMethod()
	{
		if (!empty(app()->getLocale())) {
			$strLang = app()->getLocale();
		} else {
			$strLang = "en";
		}
		$settingInfo = Settings::where("keyname", "setting")->first();
		$paymentLists = explode(",", $settingInfo->payments);

		$pay = [];

		foreach ($paymentLists as $paymentList) {
			$pay[] = [
				"name"      => trans('webMessage.payment_' . $paymentList),
				"key_name"  => strtoupper($paymentList),
				"image"     => url('uploads/paymenticons/' . strtolower($paymentList) . '.png')
			];
		}
		$response['data'] = $pay;
		return response($response, $this->successStatus);
	}

	//get category
	public function category(Request $request)
	{
		if (!empty(app()->getLocale())) {
			$strLang = app()->getLocale();
		} else {
			$strLang = "en";
		}
		$parent_id = !empty($request->parent_id) ? trim($request->parent_id) : 0;

		$category = DB::table('gwc_categories')->where('gwc_categories.is_active', 1)->where('gwc_categories.parent_id', $parent_id)
			->select('gwc_products_category.*', 'gwc_categories.*')
			->join('gwc_products_category', 'gwc_products_category.category_id', '=', 'gwc_categories.id')
			->groupBy('gwc_products_category.category_id')->get();
		if (!empty($category) && count($category) > 0) {
			$cats = [];
			foreach ($category as $cat) {
				$title = $strLang == "en" ? $cat->name_en : $cat->name_ar;
				$imageUrl = !empty($cat->image) ? url('uploads/category/thumb/' . $cat->image) : url('uploads/no-image.png');
				$caty[] = [
					"id"   => $cat->category_id,
					"name" => $title,
					"image" => $imageUrl,
					"childCategories" => $this->childCategory($cat->category_id)
				];
			}
			$response['data'] = $caty;

			return response($response, $this->successStatus);
		} else {
			$response['data'] = trans('webMessage.recordnotfound');
			return response($response, $this->failedStatus);
		}
	}


	//get child category
	public function childCategory($parent_id)
	{
		if (!empty(app()->getLocale())) {
			$strLang = app()->getLocale();
		} else {
			$strLang = "en";
		}

		$category = DB::table('gwc_categories')->where('gwc_categories.is_active', 1)->where('gwc_categories.parent_id', $parent_id)
			->select('gwc_products_category.*', 'gwc_categories.*')
			->join('gwc_products_category', 'gwc_products_category.category_id', '=', 'gwc_categories.id')
			->groupBy('gwc_products_category.category_id')->get();

		if (!empty($category) && count($category) > 0) {
			$cats = [];
			foreach ($category as $cat) {
				$title = $strLang == "en" ? $cat->name_en : $cat->name_ar;
				$imageUrl = !empty($cat->image) ? url('uploads/category/thumb/' . $cat->image) : url('uploads/no-image.png');
				$caty[] = [
					"id"   => $cat->category_id,
					"name" => $title,
					"image" => $imageUrl,
					"child" => $this->childCategory($cat->category_id)
				];
			}
			return $caty;
		}
	}



	//apply coupon
	public function apply_coupon_to_cart(Request $request)
	{
		if (!empty(app()->getLocale())) {
			$strLang = app()->getLocale();
		} else {
			$strLang = "en";
		}
		$settingInfo = Settings::where("keyname", "setting")->first();

		if (empty($request->temp_uniqueid)) {
			$response['data'] = trans('webMessage.idmissing');
			return response($response, $this->failedStatus);
		}

		$total = self::getTotalCartAmount($request->temp_uniqueid);
		if (empty($request->coupon_code)) {
			$response['data'] = trans('webMessage.coupon_required');
			return response($response, $this->failedStatus);
		}
		if (empty($total)) {
			$response['data'] = trans('webMessage.yourcartisempty');
			return response($response, $this->failedStatus);
		}

		$curDate = date("Y-m-d");
		$coupon = Coupon::where('is_active', 1)
			->where('coupon_code', $request->coupon_code)
			->where('is_for', 'app')
			->first();
		if (empty($coupon->id)) {
			$response['data'] = trans('webMessage.invalid_coupon_code');
			return response($response, $this->failedStatus);
		}
		if (!empty($coupon->id) && strtotime($curDate) < strtotime($coupon->start_date)) {
			$response['data'] = trans('webMessage.coupon_can_be_used_from') . $coupon->start_date;
			return response($response, $this->failedStatus);
		}
		if (!empty($coupon->id) && strtotime($curDate) > strtotime($coupon->end_date)) {
			$response['data'] = trans('webMessage.coupon_is_expired_on') . $coupon->end_date;
			return response($response, $this->failedStatus);
		}
		if (!empty($coupon->id) && ($total < $coupon->price_start || $total > $coupon->price_end)) {

			$response['data'] = trans('webMessage.coupon_can_be_apply_for_price_range') . trans('webMessage.' . $settingInfo->base_currency) . ' ' . $coupon->price_start . ' - ' . trans('webMessage.' . $settingInfo->base_currency) . ' ' . $coupon->price_end;
			return response($response, $this->failedStatus);
		}
		if (!empty($coupon->id) && empty($coupon->usage_limit)) {
			$response['data'] = trans('webMessage.usage_limit_exceeded');
			return response($response, $this->failedStatus);
		}

		if (!empty($coupon->id) && !empty($coupon->is_free)) {
			$response['data'] = [
				'coupon_free' => 1,
				'coupon_code' => $request->coupon_code,
				'coupon_discount' => 0,
				'coupon_discount_text' => trans('webMessage.free_home_delivery')
			];
			return response($response, $this->successStatus);
		}

		if (!empty($coupon->id) && $coupon->coupon_type == "amt") {
			$discountAmt    = $coupon->coupon_value;
			$discountAmttxt = trans('webMessage.' . $settingInfo->base_currency) . ' ' . $discountAmt;
		} else {
			$discountAmt    = round(($total * $coupon->coupon_value) / 100, 3);
			$discountAmttxt = trans('webMessage.' . $settingInfo->base_currency) . ' ' . $discountAmt;
		}

		$response['data'] = [
			'coupon_free' => 0,
			'coupon_code' => $request->coupon_code,
			'coupon_discount' => $discountAmt,
			'coupon_discount_text' => $discountAmttxt
		];
		return response($response, $this->successStatus);
	}

	////////////////////////////////////////////////////////////////add/remove qty///////////////
	public function addremovequantity(Request $request)
	{
		if (!empty(app()->getLocale())) {
			$strLang = app()->getLocale();
		} else {
			$strLang = "en";
		}

		if (empty($request->tempid)) {
			$response['data'] = trans('webMessage.tempidmissing') . '(ID)';
			return response($response, $this->failedStatus);
		}
		if (empty($request->temp_uniqueid)) {
			$response['data'] = trans('webMessage.tempidmissing') . '(TEMP UNIQUE ID)';
			return response($response, $this->failedStatus);
		}
		if (empty($request->quantity)) {
			$response['data'] = trans('webMessage.quantity_required');
			return response($response, $this->failedStatus);
		}

		$session_id = $request->temp_uniqueid;

		$tempOrder  = OrdersTemp::where('id', $request->tempid)->where('unique_sid', '=', $session_id)->first();
		if (!empty($tempOrder->id)) {
			$productDetails   = Product::where('id', $tempOrder->product_id)->first();

			if (!empty($productDetails->is_attribute) && (!empty($tempOrder->size_id) || !empty($tempOrder->color_id))) {
				$aquantity = self::getProductQuantity($tempOrder->product_id, $tempOrder->size_id, $tempOrder->color_id);
				if (!empty($request->quantity) && $request->quantity > $aquantity) {
					$response['data'] = trans('webMessage.quantity_is_exceeded');
					return response($response, $this->failedStatus);
				}
			} else {
				if (empty($productDetails->is_attribute) && !empty($request->quantity) && $request->quantity > $productDetails->quantity) {
					$response['data'] = trans('webMessage.quantity_is_exceeded');
					return response($response, $this->failedStatus);
				}
			}

			$tempOrder->quantity   = !empty($request->quantity) ? $request->quantity : '1';
			$tempOrder->save();
			$totalAmount = self::getTotalCartAmount($session_id);
			$countitems  = self::countTempOrders($session_id);
			$item_text   = str_replace('[QTY]', $countitems, trans('webMessage.item_text_message'));
			$response['data'] = ['total_amount' => round($totalAmount, 3), 'items_in_cart' => $countitems, 'cart_text' => $item_text, 'message' => trans('webMessage.quantity_is_updated')];
			return response($response, $this->successStatus);
		} else {
			$response['data'] = trans('webMessage.norecordfound');
			return response($response, $this->failedStatus);
		}
	}
	////////////////////////////////Get Temp Orders/////////////////////
	public function getTempOrders(Request $request){
	if(!empty(app()->getLocale())){ $strLang = app()->getLocale();}else{$strLang="en";}
	if(empty($request->temp_uniqueid)){
	$response['data']=trans('webMessage.idmissing');
	return response($response,$this->failedStatus);
	}else{
	$tempid = $request->temp_uniqueid;
	$tempOrders = self::loadTempOrders($tempid);
	if(empty($tempOrders) || count($tempOrders)==0){
	$response['data']=trans('webMessage.yourcartisempty');
	return response($response,$this->failedStatus);
	}
	
	if(!empty($tempOrders) && count($tempOrders)>0){
	$totalAmount =0;$grandtotal =0;$subtotalprice=0;$attrtxt='';$t=1;
	$attribute_txt=[];$coupon_discount=0;$delivery_charges=0;
	$tempSub=[];
	foreach($tempOrders as $tempOrder){
	    $productDetails =self::getProductDetails($tempOrder->product_id);
		
		if(!empty($tempOrder->size_id)){
		$sizeName = self::sizeNameStatic($tempOrder->size_id,$strLang);
		$attribute_txt['size_id']  =$tempOrder->size_id;
		$attribute_txt['size_name']=$sizeName;
		}		
		if(!empty($tempOrder->color_id)){
		$colorName = self::colorNameStatic($tempOrder->color_id,$strLang);
		$attribute_txt['color_id']=$tempOrder->color_id;
		$attribute_txt['color_name']=$colorName;
		}
		
		$orderOptions = self::getOptionsDtailsOrder($tempOrder->id);
		if(!empty($orderOptions)){
		$attribute_txt['options']= $orderOptions;
		}
		
		$unitprice     = $tempOrder->unit_price;
		$subtotalprice = $unitprice*$tempOrder->quantity;
		$title         = $productDetails['title_'.$strLang];
		if(!empty($productDetails['image'])){
		$imageUrl = url('uploads/product/thumb/'.$productDetails['image']);
		}else{
		$imageUrl = url('uploads/no-image.png');
		}
		//available quantity
		$aquantity = self::getProductQuantity($tempOrder->product_id,$tempOrder->size_id,$tempOrder->color_id);
	   
	    $tempSub[]=[
	              "id"=>$tempOrder->id,
				  "product_id"=>$tempOrder->product_id,
				  "title"=>$title,
				  "imageUrl"=>$imageUrl,
				  "attribute_txt"=>$attribute_txt,
				  "unitprice"=>$unitprice,
				  "color_id"=>(string)$tempOrder->color_id,
				  "size_id"=>(string)$tempOrder->size_id,
				  "quantity"=>$tempOrder->quantity,
				  "unique_sid"=>$tempOrder->unique_sid,
				  "available_quantity"=>$aquantity,
				  "subtotal"=>$subtotalprice,
				 ];	
				 
		//sum sub total to grand total
		$totalAmount+=$subtotalprice;	
		
		$attribute_txt=[];	 
	}

	}	
	}
	$userDiscount =$request->user_discount?$request->user_discount:0;
	if(!empty($tempSub) && count($tempSub)>0){
	$grandtotal = $totalAmount;

    if ($userDiscount > 0) {
	$discounted = $totalAmount - $userDiscount;
	$grandtotal = number_format($discounted, 3);
			}
			
	//check coupon discount
	if(!empty($request->coupon_discount)){
	$coupon_discount = (float)$request->coupon_discount;
	$grandtotal      = (float)($grandtotal-$request->coupon_discount);
	}
	//check delivery charges
	if(!empty($request->area_id)){
	$delivery_charges = self::get_delivery_charge($request->area_id);
	$grandtotal       = (float)($grandtotal+$delivery_charges);
	}
	
	$response['data']=[
	                  'temoOrders'=>$tempSub,
					  'total'=>$totalAmount,
					  'coupon_discount'=>$coupon_discount,
					  'user_discount'=>$userDiscount,
					  'delivery_charges'=>$delivery_charges,
					  'grandtotal'=>$grandtotal
					 ];
	return response($response,$this->successStatus);
	}else{
	$response['data']=trans('webMessage.yourcartisempty');
	return response($response,$this->failedStatus);
	}
	}

	//get product details
	public static function getProductDetails($id)
	{
		$prodDetails = Product::where('id', $id)->first();
		return $prodDetails;
	}

	//get Size Name
	public function sizeName($id, $strLang)
	{
		$txt = '--';
		$Details   = Size::where('id', $id)->first();
		if (!empty($Details['title_' . $strLang])) {
			$txt = $Details['title_' . $strLang];
		}
		return $txt;
	}
	//get color name
	public function colorName($id, $strLang)
	{
		$txt = '--';
		$Details   = Color::where('id', $id)->first();
		if (!empty($Details['title_' . $strLang])) {
			$txt = $Details['title_' . $strLang];
		}
		return $txt;
	}

	//get Size Name
	public static function sizeNameStatic($id, $strLang)
	{
		$txt = '--';
		$Details   = Size::where('id', $id)->first();
		if (!empty($Details['title_' . $strLang])) {
			$txt = $Details['title_' . $strLang];
		}
		return $txt;
	}
	//get color name
	public static function colorNameStatic($id, $strLang)
	{
		$txt = '--';
		$Details   = Color::where('id', $id)->first();
		if (!empty($Details['title_' . $strLang])) {
			$txt = $Details['title_' . $strLang];
		}
		return $txt;
	}
	//get Color Name
	public function colorDetails($id)
	{
		$Details   = Color::where('id', $id)->first();
		return $Details;
	}
	public static function colorDetailsStatic($id)
	{
		$Details   = Color::where('id', $id)->first();
		return $Details;
	}

	public static function getOptionsDtailsOrder($oid)
	{
		$options = [];
		$option_name = '';
		if (!empty(app()->getLocale())) {
			$strLang = app()->getLocale();
		} else {
			$strLang = "en";
		}
		$optionDetails = OrdersTempOption::where("oid", $oid)->get();
		if (!empty($optionDetails) && count($optionDetails) > 0) {
			foreach ($optionDetails as $optionDetail) {
				$optionParentDetails = ProductOptionsCustom::where("id", $optionDetail->option_id)->first();
				$option_name = $strLang == "en" ? $optionParentDetails->option_name_en : $optionParentDetails->option_name_ar;
				$options[] = [
					"custom_option_id" => $optionParentDetails->id,
					"custom_option_name" => $option_name,
					"child_options" => self::getChildOptionsDtails($optionDetail->option_child_ids)
				];
			}
		}
		return $options;
	}


	//get child
	public static function getChildOptionsDtailsString($ids)
	{

		$optxt = '';
		$explode = explode(",", $ids);
		if (count($explode) > 0) {
			for ($i = 0; $i < count($explode); $i++) {
				$optxt .= self::getJoinOptions($explode[$i]);
			}
		} else {
			$optxt .= self::getJoinOptions($ids);
		}
		return $optxt;
	}

	public static function getChildOptionsDtails($ids)
	{

		$optxt = [];
		$explode = explode(",", $ids);
		if (count($explode) > 0) {
			for ($i = 0; $i < count($explode); $i++) {
				$optxt[] = self::getJoinOptions($explode[$i]);
			}
		} else {
			$optxt[] = self::getJoinOptions($ids);
		}
		return $optxt;
	}

	//
	public static function getJoinOptions($id)
	{
		$optionsy = '';
		$optionName = '';
		if (!empty(app()->getLocale())) {
			$strLang = app()->getLocale();
		} else {
			$strLang = "en";
		}
		$options = ProductOptions::where("gwc_products_options.id", $id);
		$options = $options->select('gwc_products_options.*', 'gwc_products_option_custom_child.id as oid', 'gwc_products_option_custom_child.option_value_name_en', 'gwc_products_option_custom_child.option_value_name_ar');
		$options = $options->join('gwc_products_option_custom_child', 'gwc_products_option_custom_child.id', '=', 'gwc_products_options.option_value_id');
		$options = $options->orderBy('gwc_products_options.option_value_id', 'ASC')->get();
		if (!empty($options) && count($options) > 0) {
			foreach ($options as $option) {
				$optionName = ($strLang == "en" ? $option->option_value_name_en : $option->option_value_name_ar);
				$optionsy .= $optionName . ',';
			}
		}
		return trim($optionsy, ",");
	}

	//delete record from temp order
	public function removeTempOrder(Request $request)
	{
		if (!empty(app()->getLocale())) {
			$strLang = app()->getLocale();
		} else {
			$strLang = "en";
		}

		if (empty($request->temp_uniqueid) || empty($request->id)) {
			$response['data'] = trans('webMessage.idmissing');
			return response($response, $this->failedStatus);
		}

		$tempOrder = OrdersTemp::where('unique_sid', $request->temp_uniqueid)->where('id', $request->id)->first();
		if (empty($tempOrder->id)) {
			$response['data'] = trans('webMessage.norecordfound');
			return response($response, $this->failedStatus);
		}
		//remove option if

		$optionsboxs = OrdersTempOption::where("oid", $request->id)->get();
		if (!empty($optionsboxs) && count($optionsboxs) > 0) {
			foreach ($optionsboxs as $optionsbox) {
				$tempOrdersOption = OrdersTempOption::find($optionsbox->id);
				$tempOrdersOption->delete();
			}
		}

		$tempOrder->delete();
		$response['data'] = trans('webMessage.itemsareremovedfromcart');
		return response($response, $this->successStatus);
	}
	
		//delete all record from temp order
	public function removeAllTempOrder(Request $request)
	{
		if (!empty(app()->getLocale())) {
			$strLang = app()->getLocale();
		} else {
			$strLang = "en";
		}

		if (empty($request->temp_uniqueid)) {
			$response['data'] = trans('webMessage.idmissing');
			return response($response, $this->failedStatus);
		}

		$tempOrder = OrdersTemp::where('unique_sid', $request->temp_uniqueid)->first();
		if (empty($tempOrder->id)) {
			$response['data'] = trans('webMessage.norecordfound');
			return response($response, $this->failedStatus);
		}

		$tempOrders = self::loadTempOrders($request->temp_uniqueid);

		if (!empty($tempOrders) && count($tempOrders) > 0) {
			foreach ($tempOrders as $tempOrder) {
				self::removeOptions($tempOrder->id);
				$tempOrder->delete();
			}
		}
		$response['data'] = trans('webMessage.itemsareremovedfromcart');
		return response($response, $this->successStatus);
	}

	//Removing all options from OrdersTempOption based oid
	public static function removeOptions($oid)
	{
		$optionsboxs = OrdersTempOption::where("oid", $oid)->get();
		if (!empty($optionsboxs) && count($optionsboxs) > 0) {
			foreach ($optionsboxs as $optionsbox) {
				$tempOrdersOption = OrdersTempOption::find($optionsbox->id);
				$tempOrdersOption->delete();
			}
		}
	}
	
	
	///////////////////////////////////////////////Add to Cart//////////////////////////////
	public function addtocart(Request $request)
	{
		if (!empty(app()->getLocale())) {
			$strLang = app()->getLocale();
		} else {
			$strLang = "en";
		}


		if (empty($request->product_id)) {
			$response['data'] = trans('webMessage.product_id_required');
			return response($response, $this->failedStatus);
		}

		if (empty($request->price)) {
			$response['data'] = trans('webMessage.price_required');
			return response($response, $this->failedStatus);
		}

		if (empty($request->quantity)) {
			$response['data'] = trans('webMessage.quantity_required');
			return response($response, $this->failedStatus);
		}

		if (empty($request->temp_uniqueid)) {
			$response['data'] = trans('webMessage.tempidmissing');
			return response($response, $this->failedStatus);
		}

		$productDetails   = Product::where('id', $request->product_id)->first();
		if (empty($productDetails->id)) {
			$response['data'] = trans('webMessage.item_not_found');
			return response($response, $this->failedStatus);
		}

		//check size/color attribute
		if (isset($request->option_sc) && !empty($request->option_sc) && $request->option_sc == 3) {
			if (empty($request->size_attribute)) {
				$response['data'] = trans('webMessage.size_required');
				return response($response, $this->failedStatus);
			}
			if (empty($request->color_attribute)) {
				$response['data'] = trans('webMessage.color_required');
				return response($response, $this->failedStatus);
			}
			//check size color attr
			$aquantity = self::getProductQuantity($request->product_id, $request->size_attribute, $request->color_attribute);
			if (!empty($request->quantity) && $request->quantity > $aquantity) {
				$response['data'] = trans('webMessage.quantity_is_exceeded');
				return response($response, $this->failedStatus);
			}
			//end size color attr
		} elseif (isset($request->option_sc) && !empty($request->option_sc) && $request->option_sc == 1) {
			if (empty($request->size_attribute)) {
				$response['data'] = trans('webMessage.size_required');
				return response($response, $this->failedStatus);
			}
			//check size color attr
			$aquantity = self::getProductQuantity($request->product_id, $request->size_attribute, 0);
			if (!empty($request->quantity) && $request->quantity > $aquantity) {
				$response['data'] = trans('webMessage.quantity_is_exceeded');
				return response($response, $this->failedStatus);
			}
			//end size color attr
		} elseif (isset($request->option_sc) && !empty($request->option_sc) && $request->option_sc == 2) {
			if (empty($request->color_attribute)) {
				$response['data'] = trans('webMessage.color_required');
				return response($response, $this->failedStatus);
			}
			//check size color attr
			$aquantity = self::getProductQuantity($request->product_id, 0, $request->color_attr);
			if (!empty($request->quantity) && $request->quantity > $aquantity) {
				$response['data'] = trans('webMessage.quantity_is_exceeded');
				return response($response, $this->failedStatus);
			}
			//end size color attr
		}

		//check other field validation
		$flag = self::checkOptionsFields($request);
		if (!empty($flag) && $flag > 0) {
			$response['data'] = trans('webMessage.options_required');
			return response($response, $this->failedStatus);
		}
		//end check other field validation

		$session_id    = $request->temp_uniqueid;
		$whereClause[] = ["product_id", "=", $request->product_id];
		$whereClause[] = ["unique_sid", "=", $session_id];
		//size
		if (!empty($request->size_attribute)) {
			$whereClause[] = ["size_id", "=", $request->size_attribute];
		}
		//size
		if (!empty($request->color_attribute)) {
			$whereClause[] = ["color_id", "=", $request->color_attribute];
		}

		//check countdown price
		if (!empty($productDetails->countdown_datetime) && strtotime($productDetails->countdown_datetime) > strtotime(date('Y-m-d'))) {
			$price = round($productDetails->countdown_price, 3);
		} else {
			$price = self::getProductPrice($request->product_id, $request->size_attribute, $request->color_attribute);
			if (empty($price)) {
				$price = $request->price;
			}
			//check option price
			$price = self::getOptionsPrice($request, $price);
		}


		$tempOrder  = OrdersTemp::where($whereClause)->first();
		if (!empty($tempOrder->id)) {
			$tempOrder->unit_price = $price;
			$tempOrder->quantity   = $request->quantity;
			$tempOrder->save();
			$totalAmount = self::getTotalCartAmount($session_id);
			$countitems  = self::countTempOrders($session_id);
			$item_text   = str_replace('[QTY]', $countitems, trans('webMessage.item_text_message'));

			$response['data'] = ['total_amount' => round($totalAmount, 3), 'items_in_cart' => $countitems, 'cart_text' => $item_text, 'message' => trans('webMessage.quantity_is_updated')];
			//end
		} else {

			$tempOrder  = new OrdersTemp;
			$tempOrder->product_id = $request->product_id;
			$tempOrder->size_id    = $request->size_attribute;
			$tempOrder->color_id   = $request->color_attribute;
			$tempOrder->quantity   = $request->quantity;
			$tempOrder->unit_price = $price;
			$tempOrder->unique_sid = $session_id;
			$tempOrder->save();
			//add options
			self::detailsTempOrders($request, $tempOrder->id);
			//end
			$totalAmount = self::getTotalCartAmount($session_id);
			$countitems  = self::countTempOrders($session_id);
			$item_text   = str_replace('[QTY]', $countitems, trans('webMessage.item_text_message'));

			$response['data'] = ['total_amount' => round($totalAmount, 3), 'items_in_cart' => $countitems, 'cart_text' => $item_text, 'message' => trans('webMessage.item_is_added')];
			//end
		}

		return response($response, $this->successStatus);
	}


	//get temp orders 
	public static function loadTempOrders($tempid)
	{
		$session_id = $tempid;
		$tempOrders = OrdersTemp::where('unique_sid', $session_id)->orderBy('created_at', 'DESC')->get();
		return $tempOrders;
	}

	public static function getProductPrice($product_id, $size_id = 0, $color_id = 0)
	{
		$price = 0;
		$productDetails   = Product::where('id', $product_id)->first();
		if (!empty($productDetails->countdown_datetime) && strtotime($productDetails->countdown_datetime) > strtotime(date('Y-m-d'))) {
			$price = $productDetails['countdown_price'];
		} else {
			if (empty($productDetails['is_attribute'])) {
				$price = $productDetails['retail_price'];
			} else {
				if (!empty($size_id) && !empty($color_id)) {
					$attributes = ProductAttribute::where('product_id', $product_id)->where('size_id', $size_id)->where('color_id', $color_id)->first();
					if (!empty($attributes->id)) {
						$price = $attributes->retail_price;
					}
				} else if (!empty($size_id) && empty($color_id)) {
					$attributes = ProductAttribute::where('product_id', $product_id)->where('size_id', $size_id)->first();
					if (!empty($attributes->id)) {
						$price = $attributes->retail_price;
					}
				} else if (empty($size_id) && !empty($color_id)) {
					$attributes = ProductAttribute::where('product_id', $product_id)->where('color_id', $color_id)->first();
					if (!empty($attributes->id)) {
						$price = $attributes->retail_price;
					}
				}
			}
		}
		return $price;
	}

	//count temp order
	public static function countTempOrders($tempid)
	{
		$session_id = $tempid;
		$tempOrders = OrdersTemp::where('unique_sid', $session_id)->get()->count();
		return $tempOrders;
	}

	public static function getTotalCartAmount($tempid)
	{
		$total = 0;
		$tempOrders = self::loadTempOrders($tempid);
		if (!empty($tempOrders) && count($tempOrders) > 0) {
			foreach ($tempOrders as $tempOrder) {
				$total += ($tempOrder->quantity * $tempOrder->unit_price);
			}
		}
		return $total;
	}

	//add options
	public static function detailsTempOrders($request, $oid)
	{
		$productid = $request->product_id;
		$productoptions = ProductOptionsCustomChosen::where('product_id', $productid)
			->where('custom_option_id', '>=', 4)
			->orderBy('custom_option_id', 'ASC')->get();

		if (!empty($productoptions) && count($productoptions) > 0) {
			foreach ($productoptions as $productoption) {
				//option
				if (!empty($request->input('option-' . $productid . '-' . $productoption->custom_option_id))) {
					$child_option = $request->input('option-' . $productid . '-' . $productoption->custom_option_id);
					$tempOrderOption = new OrdersTempOption;
					$tempOrderOption->product_id       = $productid;
					$tempOrderOption->oid              = $oid;
					$tempOrderOption->option_id        = $productoption->custom_option_id;
					$tempOrderOption->option_child_ids = $child_option;
					$tempOrderOption->save();
				}
				//select
				if (!empty($request->input('select-' . $productid . '-' . $productoption->custom_option_id))) {
					$child_option = $request->input('select-' . $productid . '-' . $productoption->custom_option_id);
					$tempOrderOption = new OrdersTempOption;
					$tempOrderOption->product_id       = $productid;
					$tempOrderOption->oid              = $oid;
					$tempOrderOption->option_id        = $productoption->custom_option_id;
					$tempOrderOption->option_child_ids = $child_option;
					$tempOrderOption->save();
				}
				//checkbox
				if (!empty($request->input('checkbox-' . $productid . '-' . $productoption->custom_option_id))) {
					$child_option = $request->input('checkbox-' . $productid . '-' . $productoption->custom_option_id);
					$tempOrderCheck = new OrdersTempOption;
					$tempOrderCheck->product_id       = $productid;
					$tempOrderCheck->oid              = $oid;
					$tempOrderCheck->option_id        = $productoption->custom_option_id;
					$tempOrderCheck->option_child_ids = implode(",", $child_option);
					$tempOrderCheck->save();
				}
			}
		}
	}
	//warranty
	public static function getWarrantyDetails($id)
	{
		$w = Warranty::where('id', $id)->first();
		return $w;
	}

	//get option price
	public static function getOptionsPrice($request, $price)
	{

		$retailPrice = 0;
		$retailPriceCheck = 0;
		$retailPriceOption = 0;
		$retailPriceSelect = 0;

		$productoptions = ProductOptionsCustomChosen::where('product_id', $request->product_id)
			->where('custom_option_id', '>=', 4)
			->orderBy('custom_option_id', 'ASC')->get();

		if (!empty($productoptions) && count($productoptions) > 0) {
			foreach ($productoptions as $productoption) {
				//option start
				$oidOps = $request->input('option-' . $request->product_id . '-' . $productoption->custom_option_id);
				if (!empty($oidOps)) {
					$prodOption  = ProductOptions::where('id', $oidOps)->first();
					if ($prodOption->is_price_add == 1) {
						$retailPriceOption += $prodOption->retail_price;
					} else if ($prodOption->is_price_add == 2) {
						$retailPriceOption -= $prodOption->retail_price;
					}
				}
				//end option
				//select start
				$oidSel = $request->input('select-' . $request->product_id . '-' . $productoption->custom_option_id);

				if (!empty($oidSel)) {
					$explodeSelect = $oidSel; //explode("-",$oidSel);
					$prodSelect  = ProductOptions::where('id', $oidSel)->first();
					if ($prodSelect->is_price_add == 1) {
						$retailPriceSelect += $prodSelect->retail_price;
					} else if ($prodSelect->is_price_add == 2) {
						$retailPriceSelect -= $prodSelect->retail_price;
					}
				}
				//end select
				//check start
				$oidChks = $request->input('checkbox-' . $request->product_id . '-' . $productoption->custom_option_id);
				if (!empty($oidChks)) {
					$retailPriceCheck += self::checkPrices($oidChks);
				}
				//end check
			}
		}

		$optionPrice = $price + $retailPriceOption + $retailPriceCheck + $retailPriceSelect;

		return $optionPrice;
	}


	public static function checkPrices($oidChks)
	{
		$retailPriceCheck = 0;
		foreach ($oidChks as $oidChk) {
			$prodOption  = ProductOptions::where('id', $oidChk)->first();
			if ($prodOption->is_price_add == 1) {
				$retailPriceCheck += $prodOption->retail_price;
			} else if ($prodOption->is_price_add == 2) {
				$retailPriceCheck -= $prodOption->retail_price;
			}
		}
		return $retailPriceCheck;
	}


	//check option validation
	public static function checkOptionsFields($request)
	{

		$flag = 0;

		$productoptions = ProductOptionsCustomChosen::where('gwc_products_option_custom_chosen.product_id', $request->product_id)
			->where('gwc_products_option_custom_chosen.custom_option_id', '>=', 4);
		$productoptions = $productoptions->select('gwc_products_option_custom.id', 'gwc_products_option_custom.option_type', 'gwc_products_option_custom_chosen.*');
		$productoptions = $productoptions->join('gwc_products_option_custom', 'gwc_products_option_custom.id', '=', 'gwc_products_option_custom_chosen.custom_option_id');
		$productoptions = $productoptions->get();

		if (!empty($productoptions) && count($productoptions) > 0) {
			foreach ($productoptions as $productoption) {
				//option
				if (!empty($productoption->is_required) && $productoption->option_type == 'radio' && empty($request->input('option-' . $productoption->product_id . '-' . $productoption->custom_option_id))) {
					return 1;
				}
				if (!empty($productoption->is_required) && $productoption->option_type == 'checkbox' && empty($request->input('checkbox-' . $productoption->product_id . '-' . $productoption->custom_option_id))) {
					return 1;
				}
				if (!empty($productoption->is_required) && $productoption->option_type == 'select' && empty($request->input('select-' . $productoption->product_id . '-' . $productoption->custom_option_id))) {
					return 1;
				}
			}
		}
		return $flag;
	}

	public static function getProductQuantity($product_id, $size_id = 0, $color_id = 0)
	{
		$quantity = 0;
		$productDetails   = Product::where('id', $product_id)->first();
		if (empty($productDetails['is_attribute'])) {
			$quantity = $productDetails['quantity'];
		} else {
			if (!empty($size_id) && !empty($color_id)) {
				$attributes = ProductAttribute::where('product_id', $product_id)->where('size_id', $size_id)->where('color_id', $color_id)->first();
				if (!empty($attributes->id)) {
					$quantity = $attributes->quantity;
				}
			} else if (!empty($size_id) && empty($color_id)) {
				$attributes = ProductAttribute::where('product_id', $product_id)->where('size_id', $size_id)->first();
				if (!empty($attributes->id)) {
					$quantity = $attributes->quantity;
				}
			} else if (empty($size_id) && !empty($color_id)) {
				$attributes = ProductAttribute::where('product_id', $product_id)->where('color_id', $color_id)->first();
				if (!empty($attributes->id)) {
					$quantity = $attributes->quantity;
				}
			}
			$quantity = $quantity + self::getOptionsQuantityTemp($product_id);
		}
		return $quantity;
	}

	//main search
	public function products(Request $request)
	{
		if (!empty(app()->getLocale())) {
			$strLang = app()->getLocale();
		} else {
			$strLang = "en";
		}
		$totalItems = [];

		$limit = 20;
		if (!empty($request->offset)) {
			$offset = $request->offset;
		} else {
			$offset = 0;
		}

		$settingInfo     = Settings::where("keyname", "setting")->first();


		$search = trim($request->keyword);
		$catid  = !empty($request->catid)?$request->catid:'0';

		

		if(!empty($catid)) {
		$productLists = Product::where('gwc_products.is_active', '!=', 0)->where('gwc_products_category.category_id', $catid);
		$productLists = $productLists->select('gwc_products.*','gwc_products_category.product_id','gwc_products_category.category_id');
		$productLists = $productLists->join('gwc_products_category','gwc_products_category.product_id','=','gwc_products.id');
		
		if (!empty($search)) {
			$explode_search = explode(' ', $search);
			$productLists = $productLists->where(function ($q) use ($search, $strLang) {
				$explode_search = explode(' ', $search);
				if (!empty(app()->getLocale())) {
					$strLang = app()->getLocale();
				} else {
					$strLang = "en";
				}
				$q->where('gwc_products.title_' . $strLang, 'like', '%' . $search . '%')
					->orwhere('gwc_products.details_' . $strLang, 'like', '%' . $search . '%')
					->orwhere('gwc_products.item_code', 'like', '%' . $search . '%');
				if (count($explode_search) > 1 && !empty($productLists)) {
					foreach ($explode_search as $searchword) {
						$productLists = $productLists->orwhere('title_' . $strLang, 'like', '%' . $searchword . '%')
							->orwhere('gwc_products.details_' . $strLang, 'like', '%' . $searchword . '%')
							->orwhere('gwc_products.item_code', 'like', '%' . $searchword . '%');
					}
				}
			});
		  }	
		}else{		
		$productLists = Product::where('gwc_products.is_active', '!=', 0);
		if (!empty($search)) {
			$explode_search = explode(' ', $search);
			$productLists = $productLists->where(function ($q) use ($search, $strLang) {
				$explode_search = explode(' ', $search);
				if (!empty(app()->getLocale())) {
					$strLang = app()->getLocale();
				} else {
					$strLang = "en";
				}
				$q->where('gwc_products.title_' . $strLang, 'like', '%' . $search . '%')
					->orwhere('gwc_products.details_' . $strLang, 'like', '%' . $search . '%')
					->orwhere('gwc_products.item_code', 'like', '%' . $search . '%');
				if (count($explode_search) > 1 && !empty($productLists)) {
					foreach ($explode_search as $searchword) {
						$productLists = $productLists->orwhere('title_' . $strLang, 'like', '%' . $searchword . '%')
							->orwhere('gwc_products.details_' . $strLang, 'like', '%' . $searchword . '%')
							->orwhere('gwc_products.item_code', 'like', '%' . $searchword . '%');
					}
				}
			});
		  }
        }



		//count total records
		$totalItems   = $productLists->get()->count();

		$productLists = $productLists->orderBy('gwc_products.id', 'DESC')->offset($offset)->limit($limit)->get();



		///customize product listising
		$prods = [];
		if (!empty($productLists) && count($productLists) > 0) {

			foreach ($productLists as $productList) {
				if (!empty($productList->image)) {
					$imageUrl = url('uploads/product/thumb/' . $productList->image);
				} else {
					$imageUrl = url('uploads/no-image.png');
				}

				if ($strLang == "en") {
					$title = $productList->title_en;
					$caption_title = (string)$productList->caption_en;
				} else {
					$title = $productList->title_ar;
					$caption_title = (string)$productList->caption_ar;
				}

				if (!empty($productList->countdown_datetime) && strtotime($productList->countdown_datetime) > strtotime(date('Y-m-d'))) {
					$retail_price    = (float)$productList->countdown_price;
					$old_price       = (float)$productList->retail_price;
				} else {
					$retail_price    = (float)$productList->retail_price;
					$old_price       = (float)$productList->old_price;
				}


				$prods[] = [
					'id'             => $productList->id,
					'title'          => $title,
					'is_attribute'   => $productList->is_attribute,
					'category_id' => "",
					'category' => "",
					'attributes'     => self::getAttributes($productList->id),
					'is_stock'       => self::IsAvailableQuantity($productList->id),
					'caption_title'  => $caption_title,
					'caption_color'  => (string)$productList->caption_color,
					'is_attribute'   => $productList->is_attribute,
					'sku_no'         => (string)$productList->sku_no,
					'quantity'       => (string)$productList->quantity,
					'item_code'      => $productList->item_code,
					'sku_no'         => (string)$productList->sku_no,
					'retail_price'   => $retail_price,
					'old_price'      => $old_price,
					'image'          => (string)$imageUrl
				];
			}
			$prods = $prods;
		}
		//end


		$response['data'] = ['productLists' => $prods, 'totalItems' => $totalItems];
		return response($response, $this->successStatus);
	}


	//category search
	public function productsByCategory(Request $request)
	{
		if (!empty(app()->getLocale())) {
			$strLang = app()->getLocale();
		} else {
			$strLang = "en";
		}
		$totalItems = [];
		$settingInfo     = Settings::where("keyname", "setting")->first();


		$search = trim($request->keyword);
		$catid  = !empty($request->catid) ? $request->catid : '0';



		if (!empty($catid)) {
			$productLists = Product::where('gwc_products.is_active', '!=', 0)->where('gwc_products_category.category_id', $catid);
			$productLists = $productLists->select('gwc_products.*', 'gwc_products_category.product_id', 'gwc_products_category.category_id');
			$productLists = $productLists->join('gwc_products_category', 'gwc_products_category.product_id', '=', 'gwc_products.id');

			if (!empty($search)) {
				$explode_search = explode(' ', $search);
				$productLists = $productLists->where(function ($q) use ($search, $strLang) {
					$explode_search = explode(' ', $search);
					if (!empty(app()->getLocale())) {
						$strLang = app()->getLocale();
					} else {
						$strLang = "en";
					}
					$q->where('gwc_products.title_' . $strLang, 'like', '%' . $search . '%')
						->orwhere('gwc_products.details_' . $strLang, 'like', '%' . $search . '%')
						->orwhere('gwc_products.item_code', 'like', '%' . $search . '%');
					if (count($explode_search) > 1 && !empty($productLists)) {
						foreach ($explode_search as $searchword) {
							$productLists = $productLists->orwhere('title_' . $strLang, 'like', '%' . $searchword . '%')
								->orwhere('gwc_products.details_' . $strLang, 'like', '%' . $searchword . '%')
								->orwhere('gwc_products.item_code', 'like', '%' . $searchword . '%');
						}
					}
				});
			}
		} else {
			$productLists = Product::where('gwc_products.is_active', '!=', 0);
			if (!empty($search)) {
				$explode_search = explode(' ', $search);
				$productLists = $productLists->where(function ($q) use ($search, $strLang) {
					$explode_search = explode(' ', $search);
					if (!empty(app()->getLocale())) {
						$strLang = app()->getLocale();
					} else {
						$strLang = "en";
					}
					$q->where('gwc_products.title_' . $strLang, 'like', '%' . $search . '%')
						->orwhere('gwc_products.details_' . $strLang, 'like', '%' . $search . '%')
						->orwhere('gwc_products.item_code', 'like', '%' . $search . '%');
					if (count($explode_search) > 1 && !empty($productLists)) {
						foreach ($explode_search as $searchword) {
							$productLists = $productLists->orwhere('title_' . $strLang, 'like', '%' . $searchword . '%')
								->orwhere('gwc_products.details_' . $strLang, 'like', '%' . $searchword . '%')
								->orwhere('gwc_products.item_code', 'like', '%' . $searchword . '%');
						}
					}
				});
			}
		}



		//count total records
		$totalItems   = $productLists->get()->count();

		$productLists = $productLists->orderBy('gwc_products.id', 'DESC')->get();



		///customize product listising
		$prods = [];
		if (!empty($productLists) && count($productLists) > 0) {

			foreach ($productLists as $productList) {
				if (!empty($productList->image)) {
					$imageUrl = url('uploads/product/thumb/' . $productList->image);
				} else {
					$imageUrl = url('uploads/no-image.png');
				}

				if ($strLang == "en") {
					$title = $productList->title_en;
					$caption_title = (string)$productList->caption_en;
				} else {
					$title = $productList->title_ar;
					$caption_title = (string)$productList->caption_ar;
				}

				if (!empty($productList->countdown_datetime) && strtotime($productList->countdown_datetime) > strtotime(date('Y-m-d'))) {
					$retail_price    = (float)$productList->countdown_price;
					$old_price       = (float)$productList->retail_price;
				} else {
					$retail_price    = (float)$productList->retail_price;
					$old_price       = (float)$productList->old_price;
				}


				$prods[] = [
					'id'             => $productList->id,
					'title'          => $title,
					'is_attribute'   => $productList->is_attribute,
					'category_id' => "",
					'category' => "",
					'attributes'     => self::getAttributes($productList->id),
					'is_stock'       => self::IsAvailableQuantity($productList->id),
					'caption_title'  => $caption_title,
					'caption_color'  => (string)$productList->caption_color,
					'is_attribute'   => $productList->is_attribute,
					'sku_no'         => (string)$productList->sku_no,
					'quantity'       => (string)$productList->quantity,
					'item_code'      => $productList->item_code,
					'sku_no'         => (string)$productList->sku_no,
					'retail_price'   => $retail_price,
					'old_price'      => $old_price,
				];
			}
			$prods = $prods;
		}
		//end


		$response['data'] = ['productLists' => $prods, 'totalItems' => $totalItems];
		return response($response, $this->successStatus);
	}



	//get attributes

	public static function getAttributes($id)
	{
		$responsedata = [];
		$productDetails = Product::where('id', $id)->first();
		if (!empty($productDetails->is_attribute)) {

			$productoptions = ProductOptionsCustomChosen::where('product_id', $id)->orderBy('custom_option_id', 'ASC')->get();
			if (!empty($productoptions) && count($productoptions) > 0) {
				$option_name = '';
				$option_type = '';
				$attr_sizes = [];
				$attr_colors = [];
				$attr_sizescolors = [];
				$attr_other = [];
				foreach ($productoptions as $productoption) {
					$cutomOptions = DB::table('gwc_products_option_custom')->where('id', $productoption->custom_option_id)->first();
					$option_name = !empty($cutomOptions->option_name_en) ? $cutomOptions->option_name_en : '--';
					$option_type = !empty($cutomOptions->option_type) ? $cutomOptions->option_type : '--';
					//size
					if ($productoption->custom_option_id == 1) {
						$attr_sizes       =  self::getSizeByCustomIdProductId($productoption->custom_option_id, $id);
					} else {
						$attr_sizes = [];
					}
					//colors
					if ($productoption->custom_option_id == 2) {
						$attr_colors      = self::getColorByCustomIdProductId($productoption->custom_option_id, $id);
					} else {
						$attr_colors = [];
					}
					//size colors
					if ($productoption->custom_option_id == 3) {
						$attr_sizescolors = self::getColorSizeByCustomIdProductId($productoption->custom_option_id, $id);
					} else {
						$attr_sizescolors = [];
					}
					//other option
					if ($productoption->custom_option_id > 3) {
						$attr_other = self::getCustomOptions($productoption->custom_option_id, $id);
					} else {
						$attr_other = [];
					}

					$responsedata[] = [
						"name" => $option_name, "type" => $option_type, "Sizes" => $attr_sizes, "Colors" => $attr_colors, "SizesColors" => $attr_sizescolors, "Others" => $attr_other
					];
				}
			}
		}

		return $responsedata;
	}


	public static function getSizeByCustomIdProductId($custom_option_id, $product_id)
	{

		$Attributes = ProductAttribute::where('gwc_products_attribute.product_id', $product_id)->where('gwc_products_attribute.custom_option_id', $custom_option_id);
		$Attributes = $Attributes->select(
			'gwc_sizes.*',
			'gwc_sizes.id as sizeid',
			'gwc_products_attribute.size_id',
			'gwc_products_attribute.product_id',
			'gwc_products_attribute.custom_option_id',
			'gwc_products_attribute.quantity',
			'gwc_products_attribute.retail_price',
			'gwc_products_attribute.old_price',
			'gwc_products_attribute.is_qty_deduct'
		);
		$Attributes = $Attributes->join("gwc_sizes", "gwc_sizes.id", "=", "gwc_products_attribute.size_id");
		$Attributes = $Attributes->where('gwc_products_attribute.size_id', '!=', 0)
			->where('gwc_products_attribute.quantity', '>', 0)
			->groupBy('gwc_products_attribute.size_id')
			->get();
		//return $Attributes;
		$attr = [];
		if (!empty($Attributes) && count($Attributes) > 0) {
			foreach ($Attributes as $Attribute) {
				$attr[] = [
					"id"               => $Attribute->id,
					"size_id"          => $Attribute->size_id,
					"size_name"        => $Attribute->title_en,
					"product_id"       => $Attribute->product_id,
					"custom_option_id" => $Attribute->custom_option_id,
					"quantity"         => $Attribute->quantity,
					"retail_price"     => $Attribute->retail_price,
					"old_price"        => $Attribute->old_price,
					"is_qty_deduct"    => $Attribute->is_qty_deduct
				];
			}
		}
		return $attr;
	}


	public static function getColorByCustomIdProductId($custom_option_id, $product_id)
	{

		$Attributes = ProductAttribute::where('product_id', $product_id)->where('custom_option_id', $custom_option_id);
		$Attributes = $Attributes->select(
			'gwc_colors.*',
			'gwc_colors.id as colorid',
			'gwc_products_attribute.color_id',
			'gwc_products_attribute.product_id',
			'gwc_products_attribute.custom_option_id',
			'gwc_products_attribute.*'
		);
		$Attributes = $Attributes->join("gwc_colors", "gwc_colors.id", "=", "gwc_products_attribute.color_id");
		$Attributes = $Attributes->where('gwc_products_attribute.color_id', '!=', 0)
			->where('gwc_products_attribute.quantity', '>', 0)
			->groupBy('gwc_products_attribute.color_id')
			->get();
		return $Attributes;
	}


	public static function getColorSizeByCustomIdProductId($custom_option_id, $product_id)
	{

		$Attributes = ProductAttribute::where('gwc_products_attribute.product_id', $product_id)->where('gwc_products_attribute.custom_option_id', $custom_option_id);
		$Attributes = $Attributes->select(
			'gwc_sizes.*',
			'gwc_sizes.id as sizeid',
			'gwc_sizes.title_en as size_name',
			'gwc_products_attribute.size_id',
			'gwc_products_attribute.product_id',
			'gwc_products_attribute.custom_option_id',
			'gwc_products_attribute.*'
		);
		$Attributes = $Attributes->join("gwc_sizes", "gwc_sizes.id", "=", "gwc_products_attribute.size_id")
			->where('gwc_products_attribute.size_id', '!=', 0)
			->where('gwc_products_attribute.quantity', '>', 0)
			->groupBy('gwc_products_attribute.size_id')->get();

		$attr = [];
		if (!empty($Attributes) && count($Attributes) > 0) {
			foreach ($Attributes as $Attribute) {
				$attr[] = [
					"id"               => $Attribute->id,
					"size_id"          => $Attribute->size_id,
					"size_name"        => $Attribute->title_en,
					"product_id"       => $Attribute->product_id,
					"custom_option_id" => $Attribute->custom_option_id,
					"quantity"         => $Attribute->quantity,
					"retail_price"     => $Attribute->retail_price,
					"old_price"        => $Attribute->old_price,
					"is_qty_deduct"    => $Attribute->is_qty_deduct,
					"colors"           => self::getAttributesColors($Attribute->product_id, $Attribute->size_id, $Attribute->custom_option_id)
				];
			}
		}
		return $attr;
	}

	public static function getAttributesColors($product_id, $size_id, $custom_option_id)
	{

		$Attributes = ProductAttribute::where('gwc_products_attribute.product_id', $product_id)
			->where('gwc_products_attribute.custom_option_id', $custom_option_id)
			->where('gwc_products_attribute.size_id', $size_id);
		$Attributes = $Attributes->select(
			'gwc_colors.*',
			'gwc_products_attribute.color_id',
			'gwc_products_attribute.product_id',
			'gwc_products_attribute.custom_option_id',
			'gwc_products_attribute.*'
		);
		$Attributes = $Attributes->join("gwc_colors", "gwc_colors.id", "=", "gwc_products_attribute.color_id")
			->where('gwc_products_attribute.color_id', '!=', 0)
			->where('gwc_products_attribute.quantity', '>', 0)
			->get();

		return $Attributes;
	}


	public static function getCustomOptions($custom_option_id, $product_id)
	{
		if (!empty(app()->getLocale())) {
			$strLang = app()->getLocale();
		} else {
			$strLang = "en";
		}
		$customOptionDetails = ProductOptionsCustom::where('id', $custom_option_id)->first();

		$customOptionChilds  = ProductOptions::where('gwc_products_options.product_id', $product_id)
			->where('gwc_products_options.quantity', '>', 0)
			->where('gwc_products_options.custom_option_id', $custom_option_id);
		$customOptionChilds  = $customOptionChilds->select(
			'gwc_products_option_custom_chosen.custom_option_id',
			'gwc_products_option_custom_chosen.product_id',
			'gwc_products_option_custom_chosen.is_required',
			'gwc_products_option_custom_child.*',
			'gwc_products_option_custom_child.id as pocid',
			'gwc_products_options.*'
		);
		$customOptionChilds  = $customOptionChilds->join('gwc_products_option_custom_child', 'gwc_products_option_custom_child.id', '=', 'gwc_products_options.option_value_id');

		$customOptionChilds  = $customOptionChilds->join('gwc_products_option_custom_chosen', ['gwc_products_option_custom_chosen.product_id' => 'gwc_products_options.product_id', 'gwc_products_option_custom_chosen.custom_option_id' => 'gwc_products_options.custom_option_id']);



		$customOptionChilds  = $customOptionChilds->get();

		if ($strLang == "en" && !empty($customOptionDetails->option_name_en)) {
			$option_name  = $customOptionDetails->option_name_en;
		} else if ($strLang == "ar" && !empty($customOptionDetails->option_name_ar)) {
			$option_name  = $customOptionDetails->option_name_ar;
		} else {
			$option_name  = 'No Name';
		}

		if (!empty($customOptionDetails->option_type)) {
			$option_type = $customOptionDetails->option_type;
		} else {
			$option_type = 'NONE';
		}
		return ['CustomOptionName' => $option_name, 'CustomOptionType' => $option_type, 'childs' => $customOptionChilds];
	}

	public static function IsAvailableQuantity($product_id)
	{
		$qty = 0;
		$productDetails   = Product::where('id', $product_id)->first();
		if (empty($productDetails['is_attribute'])) {
			$qty   = $productDetails['quantity'];
		} else {
			$qty   = ProductAttribute::where('product_id', $product_id)->get()->sum('quantity');
		}

		$qty = $qty + self::getOptionsQuantityTemp($product_id);

		return $qty;
	}

	///get option quantty
	public static function getOptionsQuantityTemp($productid)
	{
		$strOptions = ProductOptions::where("product_id", $productid)->sum("quantity");
		return $strOptions;
	}
}
