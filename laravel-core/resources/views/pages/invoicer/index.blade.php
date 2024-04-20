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
            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#delivery_fees"><i class="align-middle" data-feather="map"></i></button>
            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#settings"><i class="align-middle" data-feather="settings"></i></button>
            <button data-bs-toggle="modal" data-bs-target="#listProducts" class="btn btn-secondary" > Products </button>
            @if($user->Has_Permission("invoicer_upload"))
            <button id="uploadButton" type="button" class="btn btn-primary"> Invoice </button>
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
          @foreach($invoices as $invoice)
          <tr>
            <td>
                <i class="align-middle me-2 fas fa-fw fa-hashtag"></i><a href="{{route('invoicer_invoice', $invoice->id)}}" >{{$invoice->id}}</a><br>
                <i class="align-middle me-2 fas fa-fw fa-calendar"></i>{{$invoice->created_at}}<br>
                <i class="align-middle me-2 fas fa-fw fa-tv" ></i>{{$invoice->Desk()->name}}<br>
            </td>
            <td>
              <i class="align-middle me-2 fas fa-fw fa-box"></i>{{$invoice->Total_orders()}} ({{$invoice->Products()->count()}})<br>
              <i class="align-middle me-2 fas fa-fw fa-box"></i>{{$invoice->Total_benefits()}} DZD<br>
            </td>
            <td>
            <i class="align-middle me-2 fas fa-fw fa-dolly-flatbed"></i>{{$invoice->Total()}} DZD<br>
            <i class="align-middle me-2 fas fa-fw fa-dolly"></i>{{$invoice->Delivery()}} DZD<br>
            <i class="align-middle me-2 fas fa-fw fa-dollar-sign" ></i>{{$invoice->Clean()}} DZD<br>
            </td>
            <td>
          </tr>
          @endforeach
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
          <div>
            @if($user->Has_Permission("invoicer_create_product"))
            <button data-bs-toggle="modal" data-bs-target="#createProduct" class="btn btn-primary" > Create a product </button>
            @endif
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
      </div>
      <div class="modal-body m-3">
      <table class="table">
        <thead>
          <tr>
            <th>Product</th>
            <th>Prices</th>
            <th>Purchase Price</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach($products as $product)
          <tr>
            <td>{!!$product->name.'<br>'.$product->slug!!}</td>
            <td>{{$product->min_price.'-'.$product->max_price}}</td>
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
<div class="modal fade" id="settings" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="{{route('settings_edit')}}" method="POST">
        @csrf
        <div class="modal-header">
            <h5 class="modal-title">AIB Settings</h5>
            <div>
              <button type="submit" class="btn btn-primary">Save</button>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
        </div>
        <div class="modal-body m-3">
          <div class="mb-3">
            <label class="form-label">Product's prices:</label>
            @for($i=1;$i<=100;$i++)
            <div class="mb-1 row gx-1">
              <label class="form-label col-auto">{{$i}} x</label>
              <input type="text" name="settings-quantities-{{$i}}" class="form-control col" value="{{config('settings.quantities.'.$i)}}"  placeholder="..">
          </div>
            @endfor
          </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>
<div class="modal fade" id="delivery_fees" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="{{route('settings_edit')}}" method="POST">
        @csrf
        <div class="modal-header">
            <h5 class="modal-title">AIB Wilayas fees</h5>
            <div>
              <button type="submit" class="btn btn-primary">Save</button>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
        </div>
        <div class="modal-body m-3">
          <div class="mb-3">
            <label class="form-label">Product's prices:</label>
            @foreach($wilayas as $wilaya)
            <div class="mb-1 row gx-1">
              <label class="form-label col-auto">{{$wilaya->name}}</label>
              <input type="text" name="settings-delivery_fees-{{$wilaya->id}}" class="form-control col" value="{{config('settings.delivery_fees.'.$wilaya->id)??$wilaya->delivery_price}}"  placeholder="..">
          </div>
            @endforeach
          </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>
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