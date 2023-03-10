<?php
  
namespace App\Exports;
  
use App\Product;
use App\ProductAttribute;
use App\ProductGallery;
use App\ProductOptions;
use App\Settings;
use App\Brand;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
  
class ProductExportGoogleMerchantAr implements FromCollection,WithHeadings
{
    
	
	/**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
	  
	
	   $strLang="ar";
	   $prods =[];
       $products =   Product::where('is_active',1)->where('is_export_active',1)->get();
	   if(!empty($products) && count($products)>0){
	   $brand='';
	   foreach($products as $product){
	   
			if(!empty($product->image)){ 
			$imageUrl = url('uploads/product/thumb/'.$product->image);
			}else{
			$imageUrl = url('uploads/no-image.png');
			}
		
			if($strLang=="en"){
			$title       = substr(strip_tags($product->title_en),0,150);
			$description = substr(strip_tags($product->sdetails_en),0,5000);
			$htmldescription = substr($product->details_en,0,5000);
			}else{
			$title       = substr(strip_tags($product->title_ar),0,150);
			$description = substr(strip_tags($product->sdetails_ar),0,5000);
			$htmldescription = substr($product->details_ar,0,5000);
			}
			
			if(!empty($product->countdown_datetime) && strtotime($product->countdown_datetime)>strtotime(date('Y-m-d'))){
			$retail_price    = (float)$product->countdown_price;
			$old_price       = (float)$product->retail_price;
			}else{
			$retail_price    = (float)$product->retail_price;
			$old_price       = (float)$product->old_price;
			}
			
			$link = url('ar/directdetails/'.$product->id.'/'.$product->slug);	
			if(!empty($product->brand_id)){
			$brandInfo   = Brand::where('id',$product->brand_id)->first();
			if(!empty($brandInfo->title_en) || !empty($brandInfo->title_ar)){
			$brand = $strLang=="en"?$brandInfo->title_en:$brandInfo->title_ar;
			}else{
			$brand = '';
			}
			}
			
			$aquantity = self::IsAvailableQuantity($product->id);	
			$stocktxt='in stock';
			if(empty($aquantity)){
			$stocktxt='out of stock';
			$aquantity = 0;
			}		
			$prods[]=[
					  'id'             => $product->id,
					  'title'          => $title,
					  'description'    => $description,
					  'availability'   => $stocktxt,
					  'condition'      => 'new',
					  'price'          => $retail_price.' KWD',
					  'link'           => $link,
					  'image_link'     => (string)$imageUrl,
					  'brand'          => $brand,
					  'additional_image_link' =>self::getGalleries($product->id),
					  'inventory'      => $aquantity,
					  'gtin'           =>'',	
					  'mpn'            =>'',
					  'google_product_category' =>''			  
					 ];
			 
	      }	   
	   }
	   return collect($prods);
    }


	public function headings(): array
    {    
	return ['id','title','description','availability','condition','price','link','image_link','brand','additional_image_link','inventory','gtin','mpn','google_product_category'];
    }
	
	
	public static function getGalleries($product_id){
	$imageUrl='';
	$settingInfo   = Settings::where("keyname","setting")->first();
	$galleryLists   = ProductGallery::where('product_id',$product_id)->orderBy('display_order',$settingInfo->default_sort)->get();
	if(!empty($galleryLists) && count($galleryLists)>0){
	foreach($galleryLists as $galleryList){
	if(!empty($galleryList->image)){ 
		 $imageUrl .= url('uploads/product/thumb/'.$galleryList->image).',';
		}
	 }		
	}
	return trim($imageUrl,',');
	}
	
	public static function IsAvailableQuantity($product_id){
	$qty=0;
	$productDetails   = Product::where('id',$product_id)->first();
	if(empty($productDetails['is_attribute'])){
	$qty   = $productDetails['quantity'];
	}else{
	$qty   = ProductAttribute::where('product_id',$product_id)->get()->sum('quantity');
	}
	$optyQty = ProductOptions::where('product_id',$product_id)->get()->sum('quantity');//option
	$qty     = $qty+$optyQty;
	
	if(empty($qty)){$qty=0;}
	return $qty;
	}
	
}