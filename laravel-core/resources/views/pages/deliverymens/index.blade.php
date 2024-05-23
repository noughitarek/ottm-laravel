@extends('layouts.main')
@section('subtitle', "Wilayas")
@section('content')
@php
$user = Auth::user();
@endphp
<form action="{{route('wilayas_edit')}}" method="POST">
  @csrf
  @method('PUT')
  <div class="col-12 col-lg-12 col-xxl-12 d-flex">
    <div class="card flex-fill">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Wilayas</h5>
      </div>
    </div>
  </div>
  <div class="col-12 col-lg-12 col-xxl-12 d-flex">
    <div class="card flex-fill">
      <div class="table-responsive">
        <ul class="mt-2 list-unstyled row">
            @foreach($wilayas as $wilaya)
            <li class="col-4 mb-2">
                <a href="{{ route('deliverymens_edit', $wilaya->id) }}" class="text-decoration-none d-flex align-items-center">
                    <i class="align-middle me-2 fas fa-fw fa-box"></i> 
                    <span>{{ $wilaya->name }}</span>
                </a>
            </li>
            @endforeach
        </ul>
      </div>
    </div>
  </div>
</form>

@endsection