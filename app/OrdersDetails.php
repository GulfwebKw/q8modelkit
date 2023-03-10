<?php

namespace App;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class OrdersDetails extends Model
{
    use Notifiable;
		
	public $table = "gwc_orders_details";
	
	
	public function area(){
	return $this->hasOne(Area::class,'id','area_id');
	}
	
	
}
