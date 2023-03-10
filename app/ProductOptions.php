<?php

namespace App;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class ProductOptions extends Model
{
    use Notifiable;
	
	public $table = "gwc_products_options";
	
}
