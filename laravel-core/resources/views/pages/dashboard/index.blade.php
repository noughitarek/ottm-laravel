@extends('layouts.main')
@section('subtitle', "Dashboard")
@section('content')
@php
$user = Auth::user();
@endphp

@endsection