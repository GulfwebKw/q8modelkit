@php
$settings = App\Http\Controllers\AdminSettingsController::getSetting();
$theme    = $settings->theme;
@endphp
<!DOCTYPE html>
<html lang="en">
	<!-- begin::Head -->
	<head>
		<meta charset="utf-8" />
		<title>{{__('adminMessage.websiteName')}} | {{__('adminMessage.exportimport')}}</title>
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <!--css files -->
		@include('gwc.css.user')
		<!-- token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
	</head>

	<!-- end::Head -->

	<!-- begin::Body -->
	<body class="kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--enabled kt-subheader--fixed kt-subheader--solid kt-aside--enabled kt-aside--fixed  @if(!empty($settings->is_admin_menu_minimize)) kt-aside--minimize @endif  kt-page--loading">

		<!-- begin:: Page -->

		<!-- begin:: Header Mobile -->
		<div id="kt_header_mobile" class="kt-header-mobile  kt-header-mobile--fixed ">
			<div class="kt-header-mobile__logo">
				
                @php
                $settingDetailsMenu = App\Http\Controllers\AdminDashboardController::getSettingsDetails();
                @endphp
                <a href="{{url('/gwc/home')}}">
                @if($settingDetailsMenu['logo'])
				<img alt="{{__('adminMessage.websiteName')}}" src="{!! url('uploads/logo/'.$settingDetailsMenu['logo']) !!}" height="40" />
                @endif
			   </a>
			</div>
			<div class="kt-header-mobile__toolbar">
				<button class="kt-header-mobile__toggler kt-header-mobile__toggler--left" id="kt_aside_mobile_toggler"><span></span></button>
				
				<button class="kt-header-mobile__topbar-toggler" id="kt_header_mobile_topbar_toggler"><i class="flaticon-more"></i></button>
			</div>
		</div>

		<!-- end:: Header Mobile -->
		<div class="kt-grid kt-grid--hor kt-grid--root">
			<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--ver kt-page">

				<!-- begin:: Aside -->
				@include('gwc.includes.leftmenu')

				<!-- end:: Aside -->
				<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-wrapper" id="kt_wrapper">

					<!-- begin:: Header -->
					@include('gwc.includes.header')

					<!-- end:: Header -->
					<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">

						<!-- begin:: Subheader -->
						<div class="kt-subheader   kt-grid__item" id="kt_subheader">
							<div class="kt-container  kt-container--fluid ">
								<div class="kt-subheader__main">
									<h3 class="kt-subheader__title">{{__('adminMessage.systems')}}</h3>
									<span class="kt-subheader__separator kt-hidden"></span>
									<div class="kt-subheader__breadcrumbs">
										<a href="{{url('gwc/home')}}" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
										<span class="kt-subheader__breadcrumbs-separator"></span>
										<a href="javascript:;" class="kt-subheader__breadcrumbs-link">{{__('adminMessage.exportimport')}}</a>
									</div>
								</div>
								
							</div>
						</div>
                        

						<!-- end:: Subheader -->

						<!-- begin:: Content -->
						<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
                        
                          @include('gwc.includes.alert')
                          
                         @if(auth()->guard('admin')->user()->can('export-import-edit'))  
                           
							<div class="row">
								<div class="col-md-6">
                          
									<!--begin::Portlet-->
									<div class="kt-portlet">
										<div class="kt-portlet__head">
											<div class="kt-portlet__head-label">
												<h3 class="kt-portlet__head-title">
													{{__('adminMessage.export')}}
												</h3>
											</div>
										</div>

										<!--begin::Form-->
                                       
											<div class="kt-portlet__body">
												                                                
                                                <!-- sms box -->
                                               <div class="form-group">
                                               <div class="row">
                                               <div class="col-lg-8">
                                                  <div class="form-group">
												  <label>{{__('adminMessage.product_table')}}</label>
                                                   </div>
                                               </div>
                                               
                                                <div class="col-lg-4" align="right">
                                                  <a href="{{url('gwc/export_product')}}" class="btn btn-success btn-sm pull-right">{{__('adminMessage.export')}}</a>
                                                  </div>
                                                 </div>  
                                                 
                                                 <div class="row">
                                               <div class="col-lg-8">
                                                  <div class="form-group">
												  <label>{{__('adminMessage.export_for_facebook')}}</label>
                                                   </div>
                                               </div>
                                               
                                                 <div class="col-lg-2 text-center">
                                                  <a href="{{url('gwc/export_product_facebook/en')}}" class="btn btn-success btn-sm pull-right">{{__('adminMessage.export')}}(En)</a>
                                                  </div>
                                                  <div class="col-lg-2 text-center">
                                                  <a style="margin-left:5px;" href="{{url('gwc/export_product_facebook/ar')}}" class="btn btn-success btn-sm pull-right">{{__('adminMessage.export')}}(Ar)</a>
                                                  </div>
                                                 </div>
                                                 
                                                 <div class="row">
                                               <div class="col-lg-8">
                                                  <div class="form-group">
												  <label>{{__('adminMessage.export_for_google')}}</label>
                                                   </div>
                                               </div>
                                               
                                                 <div class="col-lg-2 text-center">
                                                  <a href="{{url('gwc/export_product_google/en')}}" class="btn btn-success btn-sm pull-right">{{__('adminMessage.export')}}(En)</a>
                                                  </div>
                                                  <div class="col-lg-2 text-center">
                                                  <a style="margin-left:5px;" href="{{url('gwc/export_product_google/ar')}}" class="btn btn-success btn-sm pull-right">{{__('adminMessage.export')}}(Ar)</a>
                                                  </div>
                                                 </div>


												   <div class="row">
													   <div class="col-lg-6">
														   <div class="form-group">
															   <label>Guest Export</label>
														   </div>
													   </div>

													   <div class="col-lg-6">
														   <div class="input-group">
															   <input type="text" class="form-control" id="gustFeedUrl" value="{{ route('guest-export') }}">
															   <div class="input-group-append">
																   <button class="btn btn-default btn-icon" onclick="copyToClipboardLink()" type="button"><i class="fa fa-copy"></i></button>
															   </div>
														   </div>
													   </div>
												   </div>

                                                </div>
                                               <!--end sms box --> 
                                               
                                             
                                               
                                          	
											</div>
											
										

										<!--end::Form-->
									</div>

									<!--end::Portlet-->

								</div>
								<div class="col-md-6">
                          
									<!--begin::Portlet-->
									<div class="kt-portlet">
										<div class="kt-portlet__head">
											<div class="kt-portlet__head-label">
												<h3 class="kt-portlet__head-title">
													{{__('adminMessage.import')}}
												</h3>
											</div>
										</div>

										<!--begin::Form-->
                                       
											<div class="kt-portlet__body">
												     
                                                                                                
                                                <!-- box -->
                                               <form action="{{ route('import_product') }}" name="import_product_form" id="import_product_form" method="POST" enctype="multipart/form-data">
                                                @csrf
                                               <div class="form-group">
                                               <div class="row">
                                               <div class="col-lg-4">
                                                  <div class="form-group"><label>{{__('adminMessage.product_table')}}<br><a href="{{url('admin_assets/assets/demo.xlsx')}}" target="_blank">{{__('adminMessage.demoexample')}}</a></label></div>
                                               </div>
                                               <div class="col-lg-6">
                                                  <div class="form-group">
                                                  <input type="file" name="file_product" class="form-control">                                                  
                                                   </div>
                                               </div>
                                               
                                                <div class="col-lg-2" align="right">
                                                  <button type="submit" class="btn btn-info btn-sm pull-right">{{__('adminMessage.import')}}</button>
                                                  </div>
                                                 </div>  
                                                 <div class="row">
                                                 <div class="col-lg-12">{!!__('adminMessage.importnote')!!}</div>
                                                 </div>
                                                </div>
                                                </form>
                                               <!--end sms box --> 
                                               
                                             
                                               
                                          	
											</div>
											
										

										<!--end::Form-->
									</div>

									<!--end::Portlet-->

								</div>
                                
							</div>
                       
                            @else
                            <div class="alert alert-light alert-warning" role="alert">
								<div class="alert-icon"><i class="flaticon-warning kt-font-brand"></i></div>
								<div class="alert-text">{{__('adminMessage.youdonthavepermission')}}</div>
							</div>
                            @endif
						</div>


						<!-- end:: Content -->
					</div>

					<!-- begin:: Footer -->
					@include('gwc.includes.footer');

					<!-- end:: Footer -->
				</div>
			</div>
		</div>

		<!-- end:: Page -->


		<!-- begin::Scrolltop -->
		<div id="kt_scrolltop" class="kt-scrolltop">
			<i class="fa fa-arrow-up"></i>
		</div>

	
		<!-- js files -->
		@include('gwc.js.user')
        
       
        <!--begin::Page Vendors(used by this page) -->
		<script src="{{url('admin_assets/assets/plugins/custom/ckeditor/ckeditor-classic.bundle.js')}}" type="text/javascript"></script>

		<!--end::Page Vendors -->

		<script>
        ClassicEditor
        .create( document.querySelector( '#kt-ckeditor-1' ) )
        .catch( error => {
            console.error( error );
        } );
		
		ClassicEditor
        .create( document.querySelector( '#kt-ckeditor-2' ) )
        .catch( error => {
            console.error( error );
        } );

		function copyToClipboardLink() {
			var textBox = document.getElementById('gustFeedUrl');
			textBox.select();
			document.execCommand("copy");
			toastr.success("URL copied.");
		}
       </script>

	</body>

	<!-- end::Body -->
</html>