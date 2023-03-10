<?php

namespace App;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class ProductAttribute extends Model
{
    use Notifiable;
	
	public $table = "gwc_products_attribute";
	
}
