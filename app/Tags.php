<?php

namespace App;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Tags extends Model
{
    use Notifiable;
	
	
	public $table = "gwc_products_tags";
	
	
}
