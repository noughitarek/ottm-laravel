@extends('layouts.main')
@section('subtitle', "Import orders")
@section('content')
@php
$user = Auth::user();
@endphp
<form id="uploadForm" action="{{route('orders_import')}}" method="POST">
  @method('put')
  @csrf
  <div class="col-12 col-lg-12 col-xxl-12 d-flex">
    <div class="card flex-fill">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Imported orders</h5>
          <button id="uploadButton" type="submit" class="btn btn-primary"> Save </button>
      </div>
    </div>
  </div>

  
  <div class="col-12 col-lg-12 col-xxl-12 d-flex">
    <div class="card flex-fill">
      <div class="table-responsive">
        <table class="table table-hover my-0" id="datatables-orders">
          <thead>
            <tr>
              <th class="d-xl-table-cell">Stock/Upload/Validate</th>
              <th class="d-xl-table-cell">Desk</th>
              <th class="d-xl-table-cell">API</th>
              <th class="d-xl-table-cell">Orders</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            
          @foreach($ordersimports as $order)
            <tr>
              <td class="d-xl-table-cell single-line">
                <input type="hidden" name="order[{{$order->id}}]" value="0">
                <input class="form-check-input" type="checkbox" name="order[{{$order->id}}][from_stock]" {{$order->from_stock?"checked":""}}>
                <input class="form-check-input" type="checkbox" name="order[{{$order->id}}][upload]" checked>
                <input class="form-check-input" type="checkbox" name="order[{{$order->id}}][validate]" checked>
              </td>
              <td class="d-xl-table-cell single-line">
                <p>
                  <i class="align-middle me-2 fas fa-fw fa-user-tie"></i> {{$order->name}}<br>
                  <i class="align-middle me-2 fas fa-fw fa-phone"></i> <a href="tel:{{$order->phone}}">{{$order->phone}}</a><br>
                  <i class="align-middle me-2 fas fa-fw"></i> <a href="tel:{{$order->phone2}}">{{$order->phone2}}</a>
                </p>
              </td>
              <td class="d-xl-table-cell single-line">
                <p>
                  <i class="align-middle me-2 fas fa-fw fa-map-pin"></i> {{$order->address}}<br>
                  <i class="align-middle me-2 fas fa-fw fa-map"></i> {{$order->Commune()->name}} - {{$order->Commune()->Wilaya()->name}}<br>
                  <i class="align-middle me-2 fas fa-fw fa-globe"></i> {{$order->IP}}
                </p>
              </td>
              <td class="d-xl-table-cell single-line">
                <p>
                  <i class="align-middle me-2 fas fa-fw fa-user-cog"></i> {{$order->Created_by()->name}}<br>
                  @foreach($order->Products() as $productIndex=>$product)
                  <i class="align-middle me-2 fas fa-fw fa-box"></i>{{$product['qte']}} x {{$product['name']}}<br> 

                  <i class="align-middle me-2 fas fa-fw fa-box"></i>
                  <label>Choose the correct quantity</label>
                  <select name="order[{{$order->id}}][quantities][{{$productIndex}}]" class="form-control">
                    <option value="1" selected>1</option>
                    @foreach(config('settings.quantities') as $index=>$quantity)
                    @if($quantity!="")
                    <option value="{{$index}}" {{$index==$product['qte']?'selected':''}}>{{$index}}</option>
                    @endif
                    @endforeach
                  </select>
                  <label>Choose the correct product</label>
                  <select name="order[{{$order->id}}][products][{{$productIndex}}]" class="form-control" required>
                    <option value selected disabled>--{{$product['name']}}--</option>
                    @foreach($products as $productt)
                    <option value="{{$productt->id}}" {{$product['name']==$productt->name?'selected':''}}>{{$productt->name}}</option>
                    @endforeach
                  </select> 
                  @endforeach
                </p>
              </td>
              <td class="d-xl-table-cell single-line">
                <p>
                  <i class="align-middle me-2 fas fa-fw fa-wallet"></i> {{$order->total_price}} DZD<br>
                  <i class="align-middle me-2 fas fa-fw fa-truck-loading"></i> {{$order->delivery_price}} DZD<br>
                  <i class="align-middle me-2 fas fa-fw fa-dollar-sign"></i> {{$order->clean_price}} DZD<br>
                  <i class="align-middle me-2 fas fa-fw fa-tv"></i> {{$order->Desk()->name}}
                </p>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</form>


@endsection
@section('script')
<script>
  document.getElementById('orders').addEventListener('change', function() {
    var form = document.getElementById('uploadForm');
    form.submit();
  });

  document.getElementById('uploadButton').addEventListener('click', function() {
    document.getElementById('orders').click();
  });
</script>
@endsection