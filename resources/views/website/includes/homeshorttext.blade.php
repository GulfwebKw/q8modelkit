@if(!empty($settingInfo->social_instagram))
<div class="container-indent">
		<div class="container-fluid">
			<div class="tt-block-title">
				<h2 class="tt-title"><a target="_blank" href="{{$settingInfo->social_instagram}}">@ {{trans('webMessage.followuson')}}</a></h2>
				<div class="tt-description">{{strtoupper(trans('webMessage.instagram'))}}</div>
			</div>
            @if(!empty($settingInfo->instagram_token))
			<div class="row">
				<div id="instafeed" class="instafeed-fluid" data-clientId="{{$settingInfo->instagram_clientId}}" data-userId="{{$settingInfo->instagram_userId}}" data-access-token="{{$settingInfo->instagram_token}}" data-limitimg="6"></div>
			</div>
            @endif
		</div>
	</div>

@endif

@php
if(!empty(app()->getLocale())){ $strLang = app()->getLocale();}else{$strLang="en";}
@endphp
<div class="container-indent">
		<div class="container">
			<div class="row tt-services-listing">
            @if(!empty($settingInfo['home_note1_title_'.$strLang]))
				<div class="col-xs-12 col-md-6 col-lg-3">
					<a href="javascript:;" class="tt-services-block">
						<div class="tt-col-icon">
							<i class="icon-f-48"></i>
						</div>
						<div class="tt-col-description">
							<h4 class="tt-title">{{$settingInfo['home_note1_title_'.$strLang]}}</h4>
							<p>{{$settingInfo['home_note1_details_'.$strLang]}}</p>
						</div>
					</a>
				</div>
            @endif
            @if(!empty($settingInfo['home_note2_title_'.$strLang]))
				<div class="col-xs-12 col-md-6 col-lg-3">
					<a href="javascript:;" class="tt-services-block">
						<div class="tt-col-icon">
							<i class="icon-f-35"></i>
						</div>
						<div class="tt-col-description">
							<h4 class="tt-title">{{$settingInfo['home_note2_title_'.$strLang]}}</h4>
							<p>{{$settingInfo['home_note2_details_'.$strLang]}}</p>
						</div>
					</a>
				</div>
              @endif
              @if(!empty($settingInfo['home_note3_title_'.$strLang]))
				<div class="col-xs-12 col-md-6 col-lg-3">
					<a href="javascript:;" class="tt-services-block">
						<div class="tt-col-icon">
							<i class="icon-e-09"></i>
						</div>
						<div class="tt-col-description">
							<h4 class="tt-title">{{$settingInfo['home_note3_title_'.$strLang]}}</h4>
							<p>{{$settingInfo['home_note3_details_'.$strLang]}}</p>
						</div>
					</a>
				</div>
                @endif
                @if(!empty($settingInfo['home_note4_title_'.$strLang]))
				<div class="col-xs-12 col-md-6 col-lg-3">
					<a href="javascript:;" class="tt-services-block">
						<div class="tt-col-icon">
							<i class="icon-f-76"></i>
						</div>
						<div class="tt-col-description">
							<h4 class="tt-title">{{$settingInfo['home_note4_title_'.$strLang]}}</h4>
							<p>{{$settingInfo['home_note4_details_'.$strLang]}}</p>
						</div>
					</a>
				</div>
                @endif
			</div>
		</div>
	</div>
