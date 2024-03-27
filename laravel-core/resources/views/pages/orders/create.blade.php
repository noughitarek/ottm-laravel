@extends('layouts.main')
@section('subtitle', "Create an order")
@section('content')
@php
$user = Auth::user();
@endphp
<div class="row">
  <div class="col-12 col-lg-12 col-xxl-12 d-flex">
    <div class="card flex-fill">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Create an order</h5>
      </div>
    </div>
  </div>

<form method="POST" action="{{route('orders_create')}}">
@csrf
  <div class="col-12 col-lg-12 col-xxl-12 d-flex">
    <div class="card flex-fill">
      <div class="card-header">
        <h5 class="card-title">General information</h5>
    </div>
      <div class="card-body">
        <div class="mb-3">
          <label class="form-label">Conversation <span class="text-danger">*</span></label>
          @if(isset($conversation))
            <br>{{$conversation->name}}
            <input type="hidden" name="conversation" value="{{$conversation->id}}">
            <b><a href="{{route('orders_create')}}" class="text-danger">X</a></b>
          @else
          <select name="conversation" class="form-control conversation-select" required>
              <option value disabled selected>Select the conversation</option>
              @foreach($conversations as $conversationSelect)
              <option value="{{$conversationSelect->id}}">{{$conversationSelect->name}}</option>
              @endforeach
          </select>
          @endif
        </div>
        <div class="mb-3">
          <label class="form-label">Product <span class="text-danger">*</span></label>
          @if(isset($product))
            <br>{{$product->name}}
            <input type="hidden" name="product" value="{{$product->id}}">
            <b><a href="{{route('orders_create')}}" class="text-danger">X</a></b>
          @else
          <select name="product" class="form-control product-select" required>
              <option value disabled selected>Select the product</option>
              @foreach($products as $productSelect)
              <option value="{{$productSelect->id}}">{{$productSelect->name}}</option>
              @endforeach
          </select>
          @endif
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title">Package information</h5>
      </div>
      <div class="card-body">
        <div class="mb-3">
          <label class="form-label" for="name">Name <span class="text-danger">*</span></label>
          <input type="text" class="form-control" id="name" name="name" placeholder="Ex: Ahmed" required>
        </div>
        <div class="row">
          <div class="mb-3 col-md-6">
            <label class="form-label" for="phone">Phone <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="phone" name="phone" placeholder="Ex: 0699894417" data-inputmask-regex="^0[5-7][0-9]{8}$" required>
          </div>
          <div class="mb-3 col-md-6">
            <label class="form-label" for="phone2">Phone 2</label>
            <input type="text" class="form-control" id="phone2" name="phone2" placeholder="Ex: 0699894417" data-inputmask-regex="^0[5-7][0-9]{8}$">
          </div>
        </div>
        <div class="row">
          <div class="mb-3 col-md-6">
            <label class="form-label" for="wilaya">Wilaya <span class="text-danger">*</span></label>
            <select name="wilaya" id="wilaya" class="form-control wilaya-select" required>
                <option value disabled selected>Select the wilaya</option>
                @foreach($wilayas as $wilaya)
                <option value="{{$wilaya->id}}">{{$wilaya->name}}</option>
                @endforeach
            </select>
          </div>
          <div class="mb-3 col-md-6">
            <label class="form-label" for="commune">Commune <span class="text-danger">*</span></label>
            <select name="commune" id="commune" class="form-control commune-select" required>
                <option value disabled selected>Select the commune</option>
            </select>
          </div>
        </div>
        <div class="mb-3">
          <label class="form-label" for="address">Address <span class="text-danger">*</span></label>
          <input type="text" class="form-control" name="address" id="address" placeholder="5 Boulevard Said TOUATI" required>
        </div>
        <div class="mb-3">
          <label class="form-label" class="form-check m-0">
            <input type="checkbox" name="fragile" id="fragile" class="form-check-input">
            <span class="form-check-label">Fragile</span>
          </label>&nbsp;
          <label class="form-label" class="form-check m-0">
            <input type="checkbox" name="stopdesk" id="stopdesk" class="form-check-input">
            <span class="form-check-label">Stopdesk</span>
          </label>
        </div>
      </div>
    </div>
  </div>
  <div class="col-12 col-lg-12 col-xxl-12 d-flex">
    <div class="card flex-fill">
      <div class="card-header">
        <h5 class="card-title">Pricing information</h5>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="mb-3 col-md-6">
            <label class="form-label">Quantity <span class="text-danger">*</span></label>
            <input name="quantity" type="number" min="1" value="1" class="form-control" required>
          </div>
          <div class="mb-3 col-md-6">
            <label class="form-label">Total price <span class="text-danger">*</span></label>
            <input name="total_price" id="total_price" type="number" min="0" class="form-control" required>
          </div>
        </div>
        <div class="mb-3">
          <label class="form-label">Delivery price</label>
          <input name="delivery_price_show" id="delivery_price_show" type="number" class="form-control" disabled>
          <input name="delivery_price" id="delivery_price" type="hidden" class="form-control">
        </div>
        <div class="mb-3">
          <label class="form-label">Clean price</label>
          <input name="clean_price_show" id="clean_price_show" type="number" min="0" class="form-control" disabled>
          <input name="clean_price" id="clean_price" type="hidden" class="form-control">
        </div>
        <div class="mb-3">
          <button type="submit" class="btn btn-primary">Create</button>
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
        new Choices(".product-select", {shouldSort: false});
        @endif
        
        @if(!isset($conversation))
        new Choices(".conversation-select", {shouldSort: false});
        @endif
    });

</script>
<script>
    function updateCommunes(){
        var selectedWilayaId = document.getElementById('wilaya').value;
        var communeSelect = document.getElementById('commune');
        communeSelect.innerHTML = '<option value="" disabled selected>Loading...</option>';
        
        fetch('/orders/'+selectedWilayaId+'/getCommunes')
            .then(response => response.json())
            .then(data => {
                communeSelect.innerHTML = '<option value="" disabled selected>Select the commune</option>';
                data.forEach(commune => {
                    communeSelect.innerHTML += `<option value="${commune.id}">${commune.name}</option>`;
                });
                communeSelect.dispatchEvent(new Event('change'));
            })
            .catch(error => {
                console.error('Error fetching commune data:', error);
                communeSelect.innerHTML = '<option value="" disabled selected>Error loading communes</option>';
            });
    }
    function updateDelvieryPrice()
    {
        var selectedWilayaId = document.getElementById('wilaya').value;
        var delivery_price = document.getElementById('delivery_price');
        var delivery_price_show = document.getElementById('delivery_price_show');
        fetch('/orders/'+selectedWilayaId+'/getDelivery')
            .then(response => response.json())
            .then(data => {
                delivery_price.value = data.delivery_price
                delivery_price_show.value = data.delivery_price
            })
            .catch(error => {
                console.error('Error fetching delivery price data:', error);
                delivery_price.value = 0
            });
    }
    function updatePrices()
    {
        var total_price = document.getElementById('total_price');
        var delivery_price = document.getElementById('delivery_price');
        var clean_price = document.getElementById('clean_price');
        var clean_price_show = document.getElementById('clean_price_show');
        clean_price.value = total_price.value-delivery_price.value;
        clean_price_show.value = total_price.value-delivery_price.value;
    }
    document.getElementById('wilaya').addEventListener('change', function() {
        updateCommunes()
        updateDelvieryPrice()
        updatePrices()
    });
    document.getElementById('total_price').addEventListener('input', updatePrices);
    document.getElementById('delivery_price').addEventListener('input', updatePrices);
</script>
@endsection