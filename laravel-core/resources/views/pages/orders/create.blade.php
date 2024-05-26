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

  @foreach ($errors->all() as $title=>$error)
                <li>{{ $title.'-'.$error }}</li>
            @endforeach
<form method="POST" action="{{route('orders_create')}}">
@csrf
  <div class="col-12 col-lg-12 col-xxl-12 d-flex">
    <div class="card flex-fill">
      <div class="card-header">
        <h5 class="card-title">General information</h5>
    </div>
      <div class="card-body">
        <div class="mb-3">
          <label class="form-label">Conversation</label>
          @if(isset($conversation))
            <br>{{$conversation->name}}
            <input type="hidden" name="conversation" value="{{$conversation->facebook_conversation_id}}">
            <b><a href="{{route('orders_create')}}" class="text-danger">X</a></b>
          @else
          <select name="conversation" class="form-control conversation-select" required>
              <option value selected>Select the conversation</option>
              @foreach($conversations as $conversationSelect)
              <option {{ old('conversation')==$conversationSelect->facebook_conversation_id?'selected':'' }} value="{{$conversationSelect->facebook_conversation_id}}">{{$conversationSelect->User()->name}} - {{$conversationSelect->Page()->name}}</option>
              @endforeach
          </select>
          @endif
          @error('conversation')
            <div class="text-danger">{{ $message }}</div>
          @enderror
        </div>
        <div class="mb-3">
          <label class="form-label">Reference <span class="text-danger">*</span></label>
          <input type="text" class="form-control" id="intern_tracking" name="intern_tracking" placeholder="Ex: SSD5456" value="{{ old('intern_tracking') }}" required>
          @error('intern_tracking')
            <div class="text-danger">{{ $message }}</div>
          @enderror
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
          <input type="text" class="form-control" id="name" name="name" placeholder="Ex: Ahmed" value="{{ old('name') }}" required>
          @error('name')
            <div class="text-danger">{{ $message }}</div>
          @enderror
        </div>
        <div class="row">
          <div class="mb-3 col-md-6">
            <label class="form-label" for="phone">Phone <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="phone" name="phone" placeholder="Ex: 0699894417" value="{{ old('phone') }}" data-inputmask-regex="^0[5-7][0-9]{8}$" required>
            @error('phone')
              <div class="text-danger">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-3 col-md-6">
            <label class="form-label" for="phone2">Phone 2</label>
            <input type="text" class="form-control" id="phone2" name="phone2" placeholder="Ex: 0699894417" value="{{ old('phone2') }}" data-inputmask-regex="^0[5-7][0-9]{8}$">
            @error('phone2')
              <div class="text-danger">{{ $message }}</div>
            @enderror
          </div>
        </div>
        <div class="row">
          <div class="mb-3 col-md-6">
            <label class="form-label" for="wilaya">Wilaya <span class="text-danger">*</span></label>
            <select name="wilaya" id="wilaya" class="form-control wilaya-select" required>
                <option value disabled selected>Select the wilaya</option>
                @foreach($wilayas as $wilaya)
                <option {{ old('wilaya')==$wilaya->id?'selected':'' }} value="{{$wilaya->id}}">{{$wilaya->name}}</option>
                @endforeach
            </select>
            @error('wilaya')
              <div class="text-danger">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-3 col-md-6">
            <label class="form-label" for="commune">Commune <span class="text-danger">*</span></label>
            <select name="commune" id="commune" class="form-control commune-select" required>
                <option value disabled selected>Select the commune</option>
            </select>
            @error('commune')
              <div class="text-danger">{{ $message }}</div>
            @enderror
          </div>
        </div>
        <div class="mb-3">
          <label class="form-label" for="address">Address <span class="text-danger">*</span></label>
          <input type="text" class="form-control" value="{{old('address')}}" name="address" id="address" placeholder="5 Boulevard Said TOUATI" required>
          @error('address')
            <div class="text-danger">{{ $message }}</div>
          @enderror
        </div>
        <div class="mb-3">
          <label class="form-label" class="form-check m-0">
            <input type="checkbox" name="fragile" id="fragile" class="form-check-input" {{ old('fragile')?'checked':''}}>
            <span class="form-check-label">Fragile</span>
            @error('fragile')
              <div class="text-danger">{{ $message }}</div>
            @enderror
          </label>&nbsp;
          <label class="form-label" class="form-check m-0">
            <input type="checkbox" name="stopdesk" id="stopdesk" class="form-check-input" {{ old('stopdesk')?'checked':''}}>
            <span class="form-check-label">Stopdesk</span>
            @error('stopdesk')
              <div class="text-danger">{{ $message }}</div>
            @enderror
          </label>
        </div>
      </div>
    </div>
  </div>
  <div class="col-12 col-lg-12 col-xxl-12 d-flex">
    <div class="card flex-fill">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title">Pricing information</h5>
        <button type="button" id="moreProducts" class="btn btn-primary">More products</button>
      </div>
      <div class="card-body">
        <div id="productsRows">
          @foreach($products as $productIndex=>$productRow)
          <div class="row productsRow {{$productIndex!=0?'d-none':''}}" id="product-{{$productIndex}}">
            <div class="mb-3 col-md-6">
              @if(isset($product))
              <label class="form-label">Product <span class="text-danger">*</span></label>
                <br>{{$product->name}}
                <input type="hidden" name="products[{{$productIndex}}][id]" value="{{$product->id}}">
                <b><a href="{{route('orders_create')}}" class="text-danger">X</a></b>
              @else
              <label class="form-label">Product <span class="text-danger">*</span></label>
              <select row-id="{{$productIndex}}" name="products[{{$productIndex}}][id]" class="form-control product-select" {{$productIndex==0?'required':''}}>
                  <option value disabled selected>Select the product</option>
                  @foreach($products as $productSelect)
                  <option value="{{$productSelect->id}}">{{$productSelect->name}}</option>
                  @endforeach
              </select>
              @endif
            </div>
            <div class="mb-3 col-md-5">
              <label class="form-label">Quantity <span class="text-danger">*</span></label>
              <input name="products[{{$productIndex}}][quantity]" type="number" min="1" class="form-control" value="{{old('quantity')}}">
            </div>
            <div class="mb-3 col-md-1">
              <label class="form-label"></label>
              <button type="button" row-id="{{$productIndex}}" class="btn btn-danger w-100 removeButton" {{$productIndex==0?'disabled':''}}>X</button>
            </div>
            <div class="mb-3 col-md-12">
              <p id="stock_{{$productIndex}}" ></p>
            </div>
          </div>
          @endforeach
          @error('products')
            <div class="text-danger">{{ $message }}</div>
          @enderror
        </div>
          
        <div class="mb-3">
          <label class="form-label">Total price <span class="text-danger">*</span></label>
          <input name="total_price" id="total_price" type="number" min="0" value="{{old('total_price')}}" class="form-control" required>
          @error('total_price')
            <div class="text-danger">{{ $message }}</div>
          @enderror
        </div>
        <!--
        <div class="mb-3">
          <label class="form-label">Delivery price</label>
          <input name="delivery_price_show" id="delivery_price_show" type="number" value="{{old('delivery_price')}}" class="form-control" disabled>-->
          <input name="delivery_price" id="delivery_price" type="hidden" value="{{old('delivery_price')}}" class="form-control">
          <!--@error('delivery_price')
            <div class="text-danger">{{ $message }}</div>
          @enderror
        </div>
        <div class="mb-3">
          <label class="form-label">Clean price</label>
          <input name="clean_price_show" id="clean_price_show" type="number" min="0" value="{{old('clean_price')}}" class="form-control" disabled>-->
          <input name="clean_price" id="clean_price" type="hidden" class="form-control" value="{{old('clean_price')}}">
          <!--@error('clean_price')
            <div class="text-danger">{{ $message }}</div>
          @enderror
        </div>-->
      </div>
    </div>
  </div>
  
  <div class="col-12 col-lg-12 col-xxl-12 d-flex">
    <div class="card flex-fill">
      <div class="card-header">
        <h5 class="card-title">Confirmation</h5>
        <div class="mb-3">
          <label class="form-label">Desk</label>

          <select class="form-control" name="desk" id="desk_select" required>
            <option disabled value selected>Select the desk</option>
            @foreach($desks as $desk)
            <option value="{{$desk->id}}">{{$desk->name}}</option>
            @endforeach
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label">Created by</label>
          <input type="text" value="{{$user->name}}" class="form-control" disabled>
        </div>
        <div class="mb-3">
          <label class="form-label">Description</label>
          <textarea name="description" maxlength="200" id="description" class="form-control">{{old('description')}}</textarea>
          @error('description')
            <div class="text-danger">{{ $message }}</div>
          @enderror
        </div>
        <div class="mb-3">
          <label class="form-label" class="form-check m-0">
            <input type="checkbox" name="add_to_ecotrack" id="add_to_ecotrack" class="form-check-input" checked>
            <span class="form-check-label">Add to Ecotrack</span>
          </label>&nbsp;
          <label class="form-label" class="form-check m-0">
            <input type="checkbox" name="validate" id="validate" class="form-check-input" checked>
            <span class="form-check-label">Validate shipping</span>
          </label>
          <label class="form-label" class="form-check m-0">
            <input type="checkbox" name="from_stock" id="from_stock" class="form-check-input" checked>
            <span class="form-check-label">From stock</span>
          </label>
        </div>
      </div>
      <div class="card-body">
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
        var productSelects = document.querySelectorAll(".product-select")
        productSelects.forEach(function (select) {
            select.addEventListener('change', function () {
              selectedProduct = select.value
              selectedDesk = document.getElementById('desk_select').value
              if(selectedDesk == "")
              {
                var url = '/orders/stock/'+selectedProduct+'/get'
              }
              else 
              {
                var url ='/orders/stock-desk/'+selectedProduct+'/'+selectedDesk+'/get'
              }
              stockHTML = document.getElementById('stock_'+select.getAttribute('row-id'))
              stockHTML.innerHTML = "<p>Loading Stock</p>"
              fetch(url)
                .then(response => response.json())
                .then(data => {
                  stockHTML.innerHTML = "<p>Remaining stock :</p><ul>";
                  data.forEach(function(stock){
                    stockHTML.innerHTML += "<li>"+stock.desk.name+": "+stock.stock+"</li>"
                  })
                  stockHTML.innerHTML += "</ul>";
                })
                .catch(error => {
                    console.error('Error fetching stock:', error);
                });
            });
            /*new Choices(select, {shouldSort: false});*/
        });
        @endif
        
        @if(!isset($conversation))
        new Choices(".conversation-select", {shouldSort: false});
        @endif
        {{old("commune")?"updateCommunes();":""}}
    });

</script>
<script>
    var product = 1
    var productsRows = document.getElementById('productsRows');
    function updateCommunes(){
        var selectedWilayaId = document.getElementById('wilaya').value;
        var communeSelect = document.getElementById('commune');
        communeSelect.innerHTML = '<option value="" disabled selected>Loading...</option>';
        
        fetch('/orders/'+selectedWilayaId+'/getCommunes')
            .then(response => response.json())
            .then(data => {
                let selected = {{ old("commune")?old("commune"):'0' }};
                communeSelect.innerHTML = '<option value="" disabled selected>Select the commune</option>';
                data.forEach(commune => {
                    communeSelect.innerHTML += `<option value="${commune.id}" ${selected==commune.id?'selected':''}>${commune.name}</option>`;
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
        var desk_select = document.getElementById('desk_select');
        //var delivery_price_show = document.getElementById('delivery_price_show');
        fetch('/orders/'+selectedWilayaId+'/getDelivery')
            .then(response => response.json())
            .then(data => {
                delivery_price.value = data.delivery_price
                desk_select.value = data.desk
                //delivery_price_show.value = data.delivery_price
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
        //var clean_price_show = document.getElementById('clean_price_show');
        clean_price.value = total_price.value-delivery_price.value;
        //clean_price_show.value = total_price.value-delivery_price.value;
    }
    function updateStopdesk()
    {
        var selectedWilayaId = document.getElementById('wilaya').value;
        fetch('/orders/'+selectedWilayaId+'/getDelivery')
            .then(response => response.json())
            .then(data => {
              let stopdesk_checkbox = document.getElementById('stopdesk');
              stopdesk_checkbox.disabled = true;
              stopdesk_checkbox.checked = false;
              stopdesk_checkbox.style.cursor = 'not-allowed';
              if(data.stopdesk == 1)
              {
                stopdesk_checkbox.style.cursor = '';
                stopdesk_checkbox.disabled = false;
              }
            })
            .catch(error => {
              stopdesk_checkbox.disabled = true;
            });
    }
    function lessProduct(id)
    {
      var toshowElem = document.getElementById("product-"+id);
      toshowElem.classList.add('d-none')
    }
    document.getElementById('wilaya').addEventListener('change', function() {
        updateCommunes()
        updateDelvieryPrice()
        updatePrices()
        updateStopdesk()
    });
    document.getElementById('moreProducts').addEventListener('click', function() {
      var toshowElem = document.querySelector('.productsRow.d-none');
      toshowElem.classList.remove('d-none')
    });
    document.querySelectorAll('.removeButton').forEach((button) => {
      button.addEventListener('click', function() {
          var rowId = button.getAttribute('row-id');
          var tohideElem = document.getElementById("product-"+rowId);
          tohideElem.classList.add('d-none')
      });
    });



    document.getElementById('total_price').addEventListener('input', updatePrices);
    document.getElementById('delivery_price').addEventListener('input', updatePrices);
</script>
@endsection