@extends('layouts.main')
@section('subtitle', "AIB")
@section('content')
@php
$user = Auth::user();
@endphp
<div class="col-12 col-lg-12 col-xxl-12 d-flex">
  <div class="card flex-fill">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="card-title mb-0">AIB</h5>
        @if($user->Has_Permission("invoicer_consult_product"))
        <div>
            <button data-bs-toggle="modal" data-bs-target="#listProducts" class="btn btn-secondary" > List products </button>
            @if($user->Has_Permission("invoicer_create_product"))
            <button data-bs-toggle="modal" data-bs-target="#createProduct" class="btn btn-primary" > Create a product </button>
            @endif
            @if($user->Has_Permission("invoicer_upload"))
            <button id="uploadButton" type="button" class="btn btn-warning"> Upload an invoice </button>
            @endif
        </div>
        @endif
    </div>
  </div>
</div>

<form id="uploadForm" action="{{route('invoicer_upload')}}" method="POST" enctype="multipart/form-data">
  @csrf
  <input type="file" id="invoice" name="invoice" class="form-control d-none">
</form>
<div class="col-12 col-lg-12 col-xxl-12 d-flex">
  <div class="card flex-fill">
    <div class="table-responsive">
      <table class="table table-hover my-0" id="datatables-orders">
        <thead>
          <tr>
            <th class="d-xl-table-cell">Desk</th>
            <th class="d-xl-table-cell">API</th>
            <th class="d-xl-table-cell">Orders</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
  </div>
</div>
@if($user->Has_Permission('invoicer_create_product'))
<div class="modal fade" id="createProduct" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="{{route('invoicer_products_create')}}" method="POST">
        @csrf
        <div class="modal-header">
            <h5 class="modal-title">Create a product</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body m-3">
          <div class="mb-3">
            <label class="form-label">Product's name: <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" placeholder="Ex: electric water pump">
          </div>
          <div class="mb-3">
            <label class="form-label">Product's slug: <span class="text-danger">*</span></label>
            <input type="text" name="slug" class="form-control" placeholder="Ex: electric-water-pump">
          </div>
          <div class="mb-3">
            <label class="form-label">Purchase Price: <span class="text-danger">*</span></label>
            <input type="number" name="purchase_price" class="form-control" placeholder="Ex:250">
          </div>
          <div class="mb-3">
            <label class="form-label">Product's price: <span class="text-danger">*</span></label>
            <input type="number" name="min_price" class="form-control" placeholder="Minimum price">
            <input type="number" name="max_price" class="form-control" placeholder="Maximum price">
          </div>
          <div class="mb-3">
            <label class="form-label">Product's prices:</label>
            <div class="mb-1">
                <label class="form-label">1 x</label>
                <input type="text" name="quantity_prices[1][title]" class="form-control" placeholder="One/">
                <input type="number" name="quantity_prices[1][price]" class="form-control" placeholder="2500">
            </div>
            <div class="mb-1">
                <label class="form-label">2 x</label>
                <input type="text" name="quantity_prices[2][title]" class="form-control" placeholder="Two/">
                <input type="number" name="quantity_prices[2][price]" class="form-control" placeholder="2500">
            </div>
            <div class="mb-1">
                <label class="form-label">3 x</label>
                <input type="text" name="quantity_prices[3][title]" class="form-control" placeholder="Tree/">
                <input type="number" name="quantity_prices[3][price]" class="form-control" placeholder="2500">
            </div>
            <div class="mb-1">
                <label class="form-label">4 x</label>
                <input type="text" name="quantity_prices[4][title]" class="form-control" placeholder="Tree/">
                <input type="number" name="quantity_prices[4][price]" class="form-control" placeholder="2500">
            </div>
            <div class="mb-1">
                <label class="form-label">5 x</label>
                <input type="text" name="quantity_prices[5][title]" class="form-control" placeholder="Tree/">
                <input type="number" name="quantity_prices[5][price]" class="form-control" placeholder="2500">
            </div>
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
@if($user->Has_Permission('invoicer_consult_product'))
<div class="modal fade" id="listProducts" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <h5 class="modal-title">All products</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body m-3">
      <table class="table">
        <thead>
          <tr>
            <th>Product</th>
            <th>Prices</th>
            <th>Quantity Prices</th>
            <th>Purchase Price</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach($products as $product)
          <tr>
            <td>{!!$product->name.'<br>'.$product->slug!!}</td>
            <td>{{$product->min_price.'-'.$product->max_price}}</td>
            <td>
            @foreach($product->Quantity_Prices() as $index=>$quantity)
                @if(isset($quantity['title']) || isset($quantity['price']))
                {{$index.': '.$quantity['title'].':'.$quantity['price']}}<br>
                @endif
            @endforeach
          </td>
            <td>{{$product->purchase_price}}</td>
            <td>
              @if($user->Has_Permission('invoicer_edit_product'))
              <button data-bs-toggle="modal" data-bs-target="#editProduct{{$product->id}}" class="btn btn-warning">Edit</button>
              @endif
              @if($user->Has_Permission('invoicer_delete_product'))
              <button type="submit" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteProduct{{$product->id}}">Delete</button>
              @endif
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
      </div>
      <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
@endif
@if($user->Has_Permission('invoicer_edit_product'))
@foreach($products as $product)
<div class="modal fade" id="editProduct{{$product->id}}" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="{{route('invoicer_products_edit', $product->id)}}" method="POST">
        @csrf
        @method('put')
        <div class="modal-header">
            <h5 class="modal-title">Create a product</h5>
            <button type="button" class="btn-close" data-bs-toggle="modal" data-bs-target="#listProducts" aria-label="Close"></button>
        </div>
        <div class="modal-body m-3">
          <div class="mb-3">
            <label class="form-label">Product's name: <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" value="{{$product->name}}" placeholder="Ex: electric water pump">
          </div>
          <div class="mb-3">
            <label class="form-label">Product's slug: <span class="text-danger">*</span></label>
            <input type="text" name="slug" class="form-control" value="{{$product->slug}}" placeholder="Ex: electric-water-pump">
          </div>
          <div class="mb-3">
            <label class="form-label">Purchase Price: <span class="text-danger">*</span></label>
            <input type="number" name="purchase_price" class="form-control" value="{{$product->purchase_price}}"  placeholder="Ex:250" >
          </div>
          <div class="mb-3">
            <label class="form-label">Product's price: <span class="text-danger">*</span></label>
            <input type="number" name="min_price" class="form-control" value="{{$product->min_price}}" placeholder="Minimum price">
            <input type="number" name="max_price" class="form-control" value="{{$product->max_price}}" placeholder="Maximum price">
          </div>
          <div class="mb-3">
            <label class="form-label">Product's prices:</label>
            <div class="mb-1">
                <label class="form-label">1 x</label>
                <input type="text" name="quantity_prices[1][title]" value="{{$product->Quantity_Prices()[1]['title']}}" class="form-control" placeholder="One/">
                <input type="number" name="quantity_prices[1][price]" value="{{$product->Quantity_Prices()[1]['price']}}" class="form-control" placeholder="2500">
            </div>
            <div class="mb-1">
                <label class="form-label">2 x</label>
                <input type="text" name="quantity_prices[2][title]" value="{{$product->Quantity_Prices()[2]['title']}}" class="form-control" placeholder="Two/">
                <input type="number" name="quantity_prices[2][price]" value="{{$product->Quantity_Prices()[2]['price']}}" class="form-control" placeholder="2500">
            </div>
            <div class="mb-1">
                <label class="form-label">3 x</label>
                <input type="text" name="quantity_prices[3][title]" value="{{$product->Quantity_Prices()[3]['title']}}" class="form-control" placeholder="Tree/">
                <input type="number" name="quantity_prices[3][price]" value="{{$product->Quantity_Prices()[3]['price']}}" class="form-control" placeholder="2500">
            </div>
            <div class="mb-1">
                <label class="form-label">4 x</label>
                <input type="text" name="quantity_prices[4][title]" value="{{$product->Quantity_Prices()[4]['title']}}" class="form-control" placeholder="Tree/">
                <input type="number" name="quantity_prices[4][price]" value="{{$product->Quantity_Prices()[4]['price']}}" class="form-control" placeholder="2500">
            </div>
            <div class="mb-1">
                <label class="form-label">5 x</label>
                <input type="text" name="quantity_prices[5][title]" value="{{$product->Quantity_Prices()[5]['title']}}" class="form-control" placeholder="Tree/">
                <input type="number" name="quantity_prices[5][price]" value="{{$product->Quantity_Prices()[5]['price']}}" class="form-control" placeholder="2500">
            </div>
          </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#listProducts">Cancel</button>
            <button type="submit" class="btn btn-primary">Edit</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endforeach
@endif
@if($user->Has_Permission('invoicer_delete_product'))
@foreach($products as $product)
<div class="modal fade" id="deleteProduct{{$product->id}}" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="{{route('invoicer_products_delete', $product->id)}}" method="POST">
        @csrf
        @method('delete')
        <div class="modal-header">
            <h5 class="modal-title">Delete a product</h5>
            <button type="button" class="btn-close" data-bs-toggle="modal" data-bs-target="#listProducts" aria-label="Close"></button>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#listProducts">Cancel</button>
            <button type="submit" class="btn btn-danger">Delete</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endforeach
@endif
@endsection
@section('script')
<script>
  document.getElementById('invoice').addEventListener('change', function() {
    var form = document.getElementById('uploadForm');
    form.submit();
  });

  document.getElementById('uploadButton').addEventListener('click', function() {
    document.getElementById('invoice').click();
  });
</script>
@endsection