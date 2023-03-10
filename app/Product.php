<?php

namespace App;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use App\CustomersWish;
class Product extends Model
{
    use Notifiable;
	
	public $table = "gwc_products";
	protected $fillable = [ 'slug','sku_no','item_code','title_en','title_ar','details_en','details_ar','retail_price','old_price','quantity','image','rollover_image'];
	//get brand
	public function brand(){
	return $this->hasOne(Brand::class,'id','brand_id');
	}
	
	public function productcat(){
	return $this->hasMany(ProductCategory::class,'product_id','id')
	            ->select('gwc_categories.*','gwc_products_category.*')
	            ->join('gwc_categories', 'gwc_categories.id', '=', 'gwc_products_category.category_id');
	}


    /**
     * check product is bundle or no
     * @param bool $checkActivity
     * @return bool
     */
    public function isBundle($checkActivity = true): bool
    {
	    if ( ! ( new bundleSetting())->is_active and $checkActivity)
	        return false;
	    if ( ! intval(parent::getAttribute('id')) > 0 )
	        return false;
	    $count = ProductBundleCategory::where('product_id' , parent::getAttribute('id'))->count();
	    return ( $count > 0 ) ? true : false ;
    }
}
