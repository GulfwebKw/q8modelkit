<?php

namespace App;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class OrdersTemp extends Model
{
    use Notifiable;
	
	
	public $table = "gwc_orders_temp";
	
	
}
