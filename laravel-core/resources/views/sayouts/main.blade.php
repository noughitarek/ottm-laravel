@extends('layouts.base')
@section('head')
<title>{{config('settings.title')}}</title>
<link href="{{asset('assets/css/app.css')}}" rel="stylesheet">
@endsection
@section('body')
<div class="wrapper">
    @include('components.Sidebar')
    <div class="main">
        @include('components.Navbar')
        <main class="content">
            @yield('content')
        </main>
        @include('components.Footer')
    </div>
</div>
<script src="{{asset('assets/js/app.js')}}"></script>
@yield('script')
@endsection