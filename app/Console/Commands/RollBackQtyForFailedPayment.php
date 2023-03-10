<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Orders;
use App\ProductOptions;
use App\OrdersOption;
use App\OrdersDetails;
use App\Product;
use App\ProductAttribute;
use App\Settings; //model
use Common;

class RollBackQtyForFailedPayment extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'rollbackqty:cron';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Roll back quantity for failed payment';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	//aut change status after 5 days
	public function changeorderstatusauto()
	{

		$date = \Carbon\Carbon::now()->subDays(5)->format('Y-m-d');

		$setting = Settings::where("keyname", "setting")->first();
		if (!empty($setting->auto_change_order_status)) {
			\Log::info('Auto change order status start - ' . date('Y-m-d H:i:s'));
			$orderLists = OrdersDetails::whereIn('order_status', ['pending', 'outfordelivery'])
				->where('created_at', '<', $date)
				->get();
			if (!empty($orderLists) && count($orderLists) > 0) {
				foreach ($orderLists as $orderList) {
					$orderDt = OrdersDetails::find($orderList->id);
					$orderDt->order_status = 'completed';
					$orderDt->save();
					\Log::info('Auto order status changes = ' . $orderList->order_id);
				}
			}
			\Log::info('Auto change order status end - ' . date('Y-m-d H:i:s'));
		}
	}
	///
	public function rollbackknetfailedorder()
	{
		\Log::info('Qty rolled back Start');
		$orderLists = OrdersDetails::where('is_paid', 0)
			->where('is_qty_rollbacked', 0)
			->where('order_status', '!=', 'completed')
			->where('pay_mode', '!=', ['COD', 'POSTKNET'])
			->whereRaw('created_at >= now() - interval 15 minute')
			->get();
		if (!empty($orderLists) && count($orderLists) > 0) {
			foreach ($orderLists as $orderList) {
				$this->rollbackknetfailedorderlist($orderList->id);
			}
		}
		\Log::info('Qty rolled back End');
	}

	public function rollbackknetfailedorderlist($oid)
	{

		$orderDetails = OrdersDetails::find($oid);
		$orderDetails->is_qty_rollbacked = 1;
		$orderDetails->save();

		\Log::info('Qty rolled back For ' . $oid);

		$orderLists   = Orders::where("oid", $oid)->get();
		if (!empty($orderLists) && count($orderLists) > 0) {
			foreach ($orderLists as $orderList) {
				//option
				$OrderOptions = OrdersOption::where("oid", $orderList->id)->get();
				if (!empty($OrderOptions) && count($OrderOptions) > 0) {
					foreach ($OrderOptions as $OrderOption) {
						$this->changeOptionQuantity('a', $OrderOption->option_child_ids, $orderList->quantity); //add qty
					}
				}
				//end option
				$this->rollbackedQuantity($orderList->product_id, $orderList->quantity, $orderList->size_id, $orderList->color_id);
			}
		}
	}


	public function rollbackedQuantity($product_id, $quantity, $size_id = 0, $color_id = 0)
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
			$this->ChangeUpdateQuantity($product_id);
			//end
		}
	}

	public function ChangeUpdateQuantity($product_id)
	{
		$qty = 0;
		$productUpdate   = Product::where('id', $product_id)->first();
		if (!empty($productUpdate->is_attribute)) {
			$qty   = ProductAttribute::where('product_id', $productUpdate->id)->get()->sum('quantity');
			$productUpdate->quantity = $qty;
			$productUpdate->save();
		}
	}

	//change qty from option
	public function changeOptionQuantity($mode, $ids, $quantity)
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

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{

		$this->rollbackknetfailedorder();
		$this->changeorderstatusauto();
	}
}
