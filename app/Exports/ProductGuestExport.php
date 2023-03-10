<?php
  
namespace App\Exports;
  
use App\Brand;
use App\Product;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductGuestExport implements FromCollection, WithHeadings
{
    
	public function __construct($request)
    {
        $this->request = $request;

    }
	
	/**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        /** @var Request $request */
        $request = $this->request;
        $products =  Product::query()
            ->when($request->get('brand' , false ) , function ($query) use ($request) {
                $query->where('brand_id',$request->brand);
            })
            ->when($request->get('brand_id' , false ) , function ($query) use ($request) {
                $query->where('brand_id',$request->brand_id);
            })
            ->when($request->get('manufacturer_id' , false ) , function ($query) use ($request) {
                $query->where('manufacturer_id',$request->manufacturer_id);
            })
            ->when($request->get('item_status' , false ) , function ($query) use ($request) {
                $query->where('is_active', $request->item_status >= 0 ? $request->item_status : 0 );
            })
            ->when($request->get('category' , false ) , function ($query) use ($request) {
                $query->whereHas('categories', function($q) use ($request) {
                    $q->where('category_id', $request->category );
                });
            })
            ->when($request->get('q' , false ) , function ($query) use ($request) {
                $query->where(function ($sq) use ($request) {
                    $sq->where('gwc_products.item_code', 'LIKE', '%' . $request->q . '%')
                        ->orwhere('gwc_products.title_en', 'LIKE', '%' . $request->q . '%')
                        ->orwhere('gwc_products.title_ar', 'LIKE', '%' . $request->q . '%')
                        ->orwhere('gwc_products.sku_no', 'LIKE', '%' . $request->q . '%');
                });
            })->get();

        if (!empty($products) && count($products) > 0) {
            foreach ($products as $product) {
                $link = url( 'en/directdetails/' . $product->id . '/' . $product->slug);
                if (!empty($product->countdown_datetime) && strtotime($product->countdown_datetime) > strtotime(date('Y-m-d'))) {
                    $retail_price = (float)$product->countdown_price;
                    $old_price = (float)$product->retail_price;
                } else {
                    $retail_price = (float)$product->retail_price;
                    $old_price = (float)$product->old_price;
                }

                $brand = ['en' => ''  , 'ar' => ''];
                if (!empty($product->brand_id)) {
                    $brandInfo = Brand::where('id', $product->brand_id)->first();
                    if (!empty($brandInfo->title_en) || !empty($brandInfo->title_ar)) {
                        $brand['en'] =  $brandInfo->title_en;
                        $brand['ar'] =  $brandInfo->title_ar;
                    }
                }

                $aquantity = \App\Http\Controllers\AdminProductController::getQuantity($product->id);
                if (! $aquantity) {
                    $aquantity = 0;
                }

                $resultOne = [
                    'SKU' => $product->item_code, // sku_no
//                    'Title(En)' => strip_tags($product->title_en),
//                    'Title(Ar)' => strip_tags($product->title_ar),
//                    'Brand(En)' => $brand['en'],
//                    'Brand(Ar)' => $brand['ar'],
                    'Title' => strip_tags($product->title_en),
                    'Brand' => $brand['en'],
                    'Number Of Items In Stock' => $aquantity,
                    'Cost Of Items' => $retail_price,
                    'Hyperlink Of The Product' => $link,
                ];
                $prods[] = $resultOne;
            }
        }
        return collect($prods);
    }


    public function headings(): array
    {
//        $headers = ['SKU', 'Title(En)', 'Title(Ar)', 'Brand(En)', 'Brand(Ar)', 'Number Of Items In Stock','Cost Of Items', 'Hyperlink Of The Product'];
        $headers = ['SKU', 'Title', 'Brand', 'Number Of Items In Stock','Cost Of Items', 'Hyperlink Of The Product'];
        return $headers;
    }

}