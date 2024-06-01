@extends('layouts.main')
@section('subtitle', "Import orders")
@section('content')
@php
$user = Auth::user();
@endphp
<div class="col-12 col-lg-12 col-xxl-12 d-flex">
  <div class="card flex-fill">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="card-title mb-0">Import orders</h5>
        <div>
            @if($user->Has_Permission("orders_import"))
            <button id="uploadButton" type="button" class="btn btn-primary"> Upload Orders </button>
            @endif
        </div>
    </div>
  </div>
</div>

<form id="uploadForm" action="{{route('orders_import')}}" method="POST" enctype="multipart/form-data">
  @csrf
  <input type="file" id="orders" name="orders" class="form-control d-none">
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
          
        @foreach($ordersimports as $order)
          <tr>
            <td class="d-xl-table-cell single-line">
              <p>
                <i class="align-middle me-2 fas fa-fw fa-hashtag"></i>{{$order->id}}<br>
                <i class="align-middle me-2 fas fa-fw fa-calendar"></i> {{$order->created_at}}<br>
                <i class="align-middle me-2 fas fa-fw fa-barcode"></i> <a target="_blank" href="https://suivi.ecotrack.dz/suivi/{{$order->tracking}}">{{$order->tracking}}</a><br>
                <i class="align-middle me-2 fas fa-fw fa-barcode"></i> {{$order->intern_tracking}}
              </p>
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
                <i class="align-middle me-2 fas fa-fw fa-box"></i>{{$order->products}}<br>  
                <i class="align-middle me-2 fas fa-fw fa-pallet"></i>
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