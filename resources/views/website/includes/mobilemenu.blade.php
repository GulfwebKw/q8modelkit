@if(!empty($category->childs) && count($category->childs)>0)

<li>
<a href="{{url(app()->getLocale().'/products/'.$category->id.'/'.$category->friendly_url)}}">@if(app()->getLocale()=="en") {{$category->name_en}} @else {{$category->name_ar}} @endif</a>
      <ul>
        @foreach($category->childs as $category)
            @include('website.includes.mobilemenu', $category)
        @endforeach
        </ul>
 </li>
 
 @else
 
<li>
<a href="{{url(app()->getLocale().'/products/'.$category->id.'/'.$category->friendly_url)}}">@if(app()->getLocale()=="en") {{$category->name_en}} @else {{$category->name_ar}} @endif</a>
 </li>
  
 @endif