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
            <button data-bs-toggle="modal" data-bs-target="#createProduct" class="btn btn-primary" > Save </button>
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
            <th class="d-xl-table-cell">Order</th>
            <th class="d-xl-table-cell">Customer</th>
            <th class="d-xl-table-cell">Address</th>
            <th class="d-xl-table-cell">Information</th>
            <th class="d-xl-table-cell">Price</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td colspan="2"><h1>Total:</h1></td>
            <td colspan="3"><h1><b>{{$invoice->total_orders}}</b></h1></td>
          </tr>
          <tr>
            <td colspan="5"><h1>Amount:</h1></td>
          </tr>
          <tr>
            <td colspan="2"><h1>Total:</h1></td>
            <td colspan="3"><h1><b>{{$total}} DZD</b></h1></td>
          </tr>
          <tr>
            <td colspan="2"><h1>Delivery fees:</h1></td>
            <td colspan="3"><h1><b>{{$delivery}} DZD</b></h1></td>
          </tr>
          <tr>
            <td colspan="2"><h1>Clean amount:</h1></td>
            <td colspan="3"><h1><b>{{$clean}} DZD</b></h1></td>
          </tr>
          @foreach($orders as $order)
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
                @foreach($order->Product() as $product)
                <i class="align-middle me-2 fas fa-fw fa-box"></i> {{$product->quantity.' X '.$product->Product()->name}}<br>  
                @endforeach
                <i class="align-middle me-2 fas fa-fw fa-pallet"></i>
                <span class="badge bg-success">
                </span>  
              </p>
            </td>
            <td class="d-xl-table-cell single-line">
              <p>
                <i class="align-middle me-2 fas fa-fw fa-wallet"></i> {{$order->total_price}} DZD<br>
                <i class="align-middle me-2 fas fa-fw fa-truck-loading"></i> {{$order->delivery_price}} DZD<br>
                <i class="align-middle me-2 fas fa-fw fa-dollar-sign"></i> {{$order->clean_price}} DZD
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