@extends('layouts.main')
@section('content')
<div class="row">
  <div class="col-12 col-lg-12 col-xxl-12 d-flex">
    <div class="card flex-fill">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Products</h5>
        <a href="" class="btn btn-primary" > Create a product </a>
      </div>
    </div>
  </div>
  @foreach($products as $product)
  
  @endforeach
  <div>
  {{ $data["products"]->links('components.pagination') }}
  </div>
</div>
@endsection
