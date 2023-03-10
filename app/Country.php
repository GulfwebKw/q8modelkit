<?php

namespace App;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use Notifiable;
	
	
	public $table = "gwc_country";
	
	public function zones(){
	return $this->hasOne(Zone::class,'id','zone_id');
	}
}
