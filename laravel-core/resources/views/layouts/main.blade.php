@extends('layouts.base')
@section('head')
<title>@yield('subtitle') - {{config('settings.title')}}</title>
<link href="{{asset('assets/css/app.css')}}" rel="stylesheet">
<link href="{{asset('assets/css/custom.css')}}" rel="stylesheet">
@endsection
@section('body')
<div class="wrapper">
    @include('components.sidebar')
    <div class="main">
        @include('components.navbar')
        <main class="content">
            @yield('content')
        </main>
        @include('components.footer')
    </div>
</div>
<script src="{{asset('assets/js/app.js')}}"></script>
@yield('script')
@endsection