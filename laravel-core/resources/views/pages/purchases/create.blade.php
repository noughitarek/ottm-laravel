@extends('layouts.main')
@section('subtitle', "Create an purchases")
@section('content')
@php
$user = Auth::user();
@endphp
  @foreach ($errors->all() as $title=>$error)
                <li>{{ $title.'-'.$error }}</li>
            @endforeach
<form action="{{route('accountingpurchases_create')}}" method="POST">
@csrf
    <div class="col-12 col-lg-12 col-xxl-12 d-flex">
        <div class="card flex-fill">
            <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Create a purchase</h5>
                @if($user->Has_Permission("accounting_purchases_create"))
                <div>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-12 col-xxl-12 d-flex">
        <div class="col-12 col-lg-12 col-xxl-12 d-flex">
            <div class="card flex-fill">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title">Funding information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Products funding</label>
                        <select class="form-control" id="products_funding" name="products_funding">
                            <option value selected disabled>Select the products funding</option>
                            @foreach($productsFunding as $product)
                            <option value="{{$product->id}}" {{old('products_funding')==$product->id?'selected':''}}>{{$product->name}}</option>
                            @endforeach
                        </select>
                        @error('name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tests funding</label>
                        <select class="form-control" id="tests_funding" name="tests_funding">
                            <option value selected disabled>Select the products funding</option>
                            @foreach($testsFunding as $test)
                            <option value="{{$test->id}}" {{old('tests_funding')==$test->id?'selected':''}}>{{$test->name}}</option>
                            @endforeach
                        </select>
                        @error('name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Total amount <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="total_amount" name="total_amount" placeholder="Ex: 38000" value="{{old('total_amount')}}" required>
                        @error('total_amount')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-12 col-xxl-12 d-flex">
        <div class="col-12 col-lg-12 col-xxl-12 d-flex">
            <div class="card flex-fill">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title">Products</h5>
                    <button type="button" id="moreProducts" class="btn btn-primary">More products</button>
                </div>
                <div class="card-body">
                    <div id="productsRows">
                    @foreach($products as $productIndex=>$productRow)
                    <div class="row productsRow {{$productIndex!=0?'d-none':''}}" id="product-{{$productIndex}}">
                        <div class="mb-3">
                            <label class="form-label">Product <span class="text-danger">*</span></label>
                            <select name="products[{{$productIndex}}][id]" class="form-control product-select" id="product-{{$productIndex}}-select" {{$productIndex==0?'required':''}}>
                                <option value disabled selected>Select the product</option>
                                @foreach($products as $productSelect)
                                <option value="{{$productSelect->id}}">{{$productSelect->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3 col-md-4">
                            <label class="form-label">Unit price <span class="text-danger">*</span></label>
                            <input name="products[{{$productIndex}}][unit_price]" type="number" min="1" class="form-control" value="{{old('unit_price')}}">
                        </div>
                        <div class="mb-3 col-md-3">
                            <label class="form-label">Quantity <span class="text-danger">*</span></label>
                            <input name="products[{{$productIndex}}][quantity]" type="number" min="1" class="form-control" value="{{old('quantity')}}">
                        </div>
                        <div class="mb-3 col-md-4">
                            <label class="form-label">Desk <span class="text-danger">*</span></label>
                            <select name="products[{{$productIndex}}][desk]" class="form-control">
                                <option value disabled selected>Select the desk</option>
                            @foreach($desks as $desk)
                                <option value="{{$desk->id}}">{{$desk->name}}</option>
                            @endforeach
                            </select>
                        </div>
                        <div class="mb-3 col-md-1">
                            <label class="form-label"></label>
                            <button type="button" row-id="{{$productIndex}}" class="btn btn-danger w-100 removeButton" {{$productIndex==0?'disabled':''}}>X</button>
                        </div>
                    </div>
                    @endforeach
                    @error('products')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if(!isset($product))
        var productSelects = document.querySelectorAll(".product-select")
        productSelects.forEach(function (select) {
            select.addEventListener('change', function () {
              
                var selectedProductId = this.value;
                productSelects.forEach(function (otherSelect) {
                    if (otherSelect !== select) {
                        var options = otherSelect.options;
                        for (var i = 0; i < options.length; i++) {
                            if (options[i].value === selectedProductId) {
                                options[i].disabled = true;
                            } else {
                                options[i].disabled = false;
                            }
                        }
                    }
                });
            });
            /*new Choices(select, {shouldSort: false});*/
        });
        @endif
    });

    document.getElementById('moreProducts').addEventListener('click', function() {
      var toshowElem = document.querySelector('.productsRow.d-none');
      toshowElem.classList.remove('d-none')
    });
    document.querySelectorAll('.removeButton').forEach((button) => {
      button.addEventListener('click', function() {
          var rowId = button.getAttribute('row-id');
          var tohideElem = document.getElementById("product-"+rowId);
          var hidedSelect = document.getElementById("product-"+rowId+"-select");
          hidedSelect.value = ""
          tohideElem.classList.add('d-none')
      });
    });
</script>
@endsection