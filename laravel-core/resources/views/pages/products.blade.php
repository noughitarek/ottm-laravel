@extends('layouts.main')
@section('subtitle', "Products")
@section('content')
@php
$user = Auth::user();
@endphp
<div class="col-12 col-lg-12 col-xxl-12 d-flex">
  <div class="card flex-fill">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="card-title mb-0">Products</h5>
        @if($user->Has_Permission('products_create'))
        <button data-bs-toggle="modal" data-bs-target="#createProduct" class="btn btn-primary" > Create a product </button>
        @endif
    </div>
  </div>
</div>
<div class="col-12 col-lg-12 col-xxl-12 d-flex">
  <div class="card flex-fill">
    <div class="table-responsive">
      <table class="table table-hover my-0">
        <thead>
          <tr>
            <th class="d-xl-table-cell">Product</th>
            @foreach($desks as $desk)
            <th class="d-xl-table-cell">{{$desk->name}}</th>
            @endforeach
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr>
                <td class="d-xl-table-cell">
                    <i class="align-middle me-2 fas fa-fw fa-hashtag"></i> {{$product->id}}<br>
                    <i class="align-middle me-2 fas fa-fw fa-box"></i> {{$product->name}}<br>
                    <i class="align-middle me-2 fas fa-fw fa-barcode"></i> {{$product->slug}}<br>
                </td>
                @foreach($desks as $desk)
                <td class="d-xl-table-cell">{{$desk->Stock($product)}}</td>
                @endforeach
                <td>
                
                  @if($user->Has_Permission('orders_create'))
                  <a href="{{route('orders_create_product', $product->id)}}" class="btn btn-primary" >
                    New order
                  </a>
                  @endif
                  @if($user->Has_Permission('products_edit'))
                    <button data-bs-toggle="modal" data-bs-target="#editProduct{{$product->id}}" class="btn btn-warning" >
                      Edit
                    </button>
                    @endif
                  @if($user->Has_Permission('products_delete'))
                  <button data-bs-toggle="modal" data-bs-target="#deleteProduct{{$product->id}}" class="btn btn-danger" >
                    Delete
                  </button>
                  @endif
                </td>
            </tr>
            @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
@if($user->Has_Permission('products_create'))
<div class="modal fade" id="createProduct" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="{{route('products_create')}}" method="POST">
        @csrf
        <div class="modal-header">
            <h5 class="modal-title">Create a product</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body m-3">
          <div class="mb-3">
            <label class="form-label">Product's name: </label>
            <input type="text" name="name" class="form-control" placeholder="Ex: electric water pump">
          </div>
          <div class="mb-3">
            <label class="form-label">Product's slug: </label>
            <input type="text" name="slug" class="form-control" placeholder="Ex: electric-water-pump">
          </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Create</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endif
@if($user->Has_Permission('products_edit'))
@foreach($products as $product)
<div class="modal fade" id="editProduct{{$product->id}}" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="{{route('products_edit', $product->id)}}" method="POST">
        @csrf
        @method('put')
        <div class="modal-header">
            <h5 class="modal-title">Edit product {{$product->id}}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body m-3">
          <div class="mb-3">
            <label class="form-label">Product's name: </label>
            <input type="text" name="name" value="{{$product->name}}" class="form-control" placeholder="Ex: electric water pump">
          </div>
          <div class="mb-3">
            <label class="form-label">Product's slug: </label>
            <input type="text" name="slug" value="{{$product->slug}}" class="form-control" placeholder="Ex: electric-water-pump">
          </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-warning">Edit</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endforeach
@endif
@if($user->Has_Permission('products_delete'))
@foreach($products as $product)
<div class="modal fade" id="deleteProduct{{$product->id}}" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="{{route('products_delete', $product->id)}}" method="POST">
        @csrf
        @method('delete')
        <div class="modal-header">
            <h5 class="modal-title">Delete product {{$product->name}} ?</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-danger">Delete</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endforeach
@endif
@endsection