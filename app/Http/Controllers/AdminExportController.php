<?php

namespace App\Http\Controllers;

use App\Exports\ProductGuestExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Spatie\Permission\Models\Role;
use Maatwebsite\Excel\Facades\Excel;
use App\Admin;//model
use App\Menus;//model
use App\Customers; //model
use App\Contactus; //model
use App\Settings; //model
use App\Categories; //model
use App\Product; //model
use App\Brand; //model
use App\AdminLogs; //model
use App\OrdersDetails; //model
use App\Orders;
use App\Transaction;
use App\Exports\ProductExport;
use App\Exports\ProductExportFacebookEn;
use App\Exports\ProductExportFacebookAr;
use App\Exports\ProductExportGoogleMerchantEn;
use App\Exports\ProductExportGoogleMerchantAr;
use App\Imports\ProductImport;
use DB;
use Common;
use Carbon;
//gapi
use App\Gapi\Gapi;


class AdminExportController  extends Controller
{
    
   //view export/import page		
	public function ViewExportImportForm(){
	 return view('gwc.setting.adminExportImportForm');
	}
	
  
    /** Export Product
    * @return \Illuminate\Support\Collection
    */
    public function export_product(Request $request) 
    {
        
		return Excel::download(new ProductExport($request->brand), 'product.xlsx');
    }

    /** Export Product
    * @return \Illuminate\Support\Collection
    */
    public function export_guest(Request $request)
    {

		return Excel::download(new ProductGuestExport($request), 'product.xlsx');
    }
	
	/** Export Product
    * @return \Illuminate\Support\Collection
    */
    public function export_product_facebook(Request $request) 
    {
	    $lang = !empty($request->lang)?$request->lang:'en';
		if($lang=="en"){
        return Excel::download(new ProductExportFacebookEn, 'product_facebook_en.xlsx');
		}else{
		return Excel::download(new ProductExportFacebookAr, 'product_facebook_ar.xlsx');
		}
    }
	
	public function export_product_google(Request $request) 
    {
	    $lang = !empty($request->lang)?$request->lang:'en';
		if($lang=="en"){
        return Excel::download(new ProductExportGoogleMerchantEn, 'product_google_en.xlsx');
		}else{
		return Excel::download(new ProductExportGoogleMerchantAr, 'product_google_ar.xlsx');
		}
    }
	
	/** Import Product
    * @return \Illuminate\Support\Collection
    */
    public function import_product() 
    {
        Excel::import(new ProductImport,request()->file('file_product'));
        return back()->with('message-success','Product data is imported successfully');
    }	

}
