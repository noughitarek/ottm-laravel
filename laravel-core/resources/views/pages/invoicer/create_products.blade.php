@extends('layouts.main')
@section('subtitle', "Create invoice")
@section('content')
@php
$user = Auth::user();
@endphp
<div class="col-12 col-lg-12 col-xxl-12 d-flex">
  <div class="card flex-fill">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="card-title mb-0">Create invoice</h5>
        <div>
            @if($user->Has_Permission("invoicer_create_product"))
            <button onclick="document.getElementById('invoicer_products_create_all_form').submit()" class="btn btn-primary" > Save </button>
            @endif
        </div>
    </div>
  </div>
</div>
<div class="col-12 col-lg-12 col-xxl-12 d-flex">
  <div class="card flex-fill">
    <div class="table-responsive">
      <table class="table table-hover my-0" id="datatables-orders">
        <thead>
          <tr>
            <th class="d-xl-table-cell">Name</th>
            <th class="d-xl-table-cell">Same as</th>
            <th class="d-xl-table-cell">Purchase Price</th>
            <th class="d-xl-table-cell">Product's price</th>
          </tr>
        </thead>
        <tbody>
          <form method="POST" id="invoicer_products_create_all_form" action="{{route('invoicer_products_create_all')}}">
          <input type="hidden" name="invoice" value="{{$invoice->id}}">
          @csrf
          @foreach($products as $product)
          <input type="hidden" name="products[{{$product->id}}][id]" value="{{$product->id}}">
          <input type="hidden" name="products[{{$product->id}}][slug]" value="{{$product->slug}}">
          <tr>
            <td>{{$product->name}}</td>
            <td>
              <select id="{{$product->id}}" name="products[{{$product->id}}][same_as]" class="form-control productsSelects">
                @foreach($all_products as $dbproduct)
                <option value="{{$dbproduct->id}}" {{$dbproduct->id==$product->id?"selected":""}}>{{$dbproduct->name}}</option>
                @endforeach
              </select>
            </td>
            <td class="productDetail{{$product->id}}">
                <div class="mb-3">
                    <label class="form-label">Purchase Price: <span class="text-danger">*</span></label>
                    <input type="number" name="products[{{$product->id}}][purchase_price]" class="form-control" placeholder="Ex:250">
                </div>
            </td>
            <td class="productDetail{{$product->id}}">
                <div class="mb-3">
                    <label class="form-label">Product's price: <span class="text-danger">*</span></label>
                    <input type="number" name="products[{{$product->id}}][min_price]" class="form-control" placeholder="Minimum price">
                    <input type="number" name="products[{{$product->id}}][max_price]" class="form-control" placeholder="Maximum price">
                </div>
            </td>
          </tr>
          @endforeach
          </form>
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
@section('script')
<script>
    document.querySelectorAll('.productsSelects').forEach(function(productSelect) {
        productSelect.addEventListener('change', function() {
            if(this.id==this.value)
            {
                document.querySelectorAll('.productDetail'+this.id).forEach(function(productDetail){
                    productDetail.classList.remove('d-none')
                })
            }
            else
            {
                document.querySelectorAll('.productDetail'+this.id).forEach(function(productDetail){
                    productDetail.classList.add('d-none')
                })
            }
        });
    });
</script>
@endsection