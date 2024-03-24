@php
$user = Auth::user();
@endphp

<nav id="sidebar" class="sidebar js-sidebar">
  <div class="sidebar-content js-simplebar">
    <a class="sidebar-brand" href="index.html">
      <span class="align-middle">{{config('settings.title')}}</span>
    </a>
    <ul class="sidebar-nav">
	  @foreach(config('sidemenu') as $menu)
	  	@if($menu['type'] == "text")
		  @if($user->Has_Permission($menu['permissions']))
          <li class="sidebar-header"> {{$menu['content']}} </li>
		  @endif
		@elseif($menu['type'] == "link")
		  @if($user->Has_Permission($menu['permissions']))
      <li class="sidebar-item {{explode('_', Route::currentRouteName())[0]==$menu['section']?'active':''}}">
        <a class="sidebar-link" href="{{route($menu['route'])}}">
          @if($menu['icon']['type'] == 'feather')
            <i class="align-middle" data-feather="{{$menu['icon']['content']}}"></i>
          @else
            <i class="align-middle me-2 fas fa-fw fa-{{$menu['icon']['content']}}"></i>
          @endif
          <span class="align-middle">{{$menu['content']}}</span>
        </a>
      </li>
      @endif
		@endif
	  @endforeach
    </ul>
  </div>
</nav>