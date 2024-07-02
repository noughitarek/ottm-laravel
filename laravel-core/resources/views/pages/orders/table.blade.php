@extends('layouts.main')
@section('subtitle', $title)
@section('content')
@php
$user = Auth::user();
@endphp
<div class="col-12 col-lg-12 col-xxl-12 d-flex">
  <div class="card flex-fill">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="card-title mb-0">{{$title}}</h5>
        @if($user->Has_Permission("orders_create"))
        <a href="{{route('orders_create')}}" class="btn btn-primary" > Create an order </a>
        @endif
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
                @if($order->Conversation() != null)
                <i class="align-middle me-2 fas fa-fw fa-envelope-open-text"></i> <a href="{{$order->conversation!=null?route('conversations_conversation', $order->conversation):'#'}}">{{$order->Conversation()->User()->name}}</a><br>
                @endif
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
                @foreach($order->Product() as $product)
                <i class="align-middle me-2 fas fa-fw fa-box"></i> {{$product->quantity.' X '.$product->Product()->name}}<br>  
                @endforeach
                <i class="align-middle me-2 fas fa-fw fa-pallet"></i>
                <span class="badge bg-success">
                  {{$order->State()}}
                </span>  
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
            <td>
              @if($order->tracking == null)
              <a href="{{route('orders_addtoecotrack', $order->id)}}" class="btn btn-success">Add to ecotrack</a>
              @endif
              <!--<a href=""class="btn btn-success">Validate</a>-->
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  {{ $orders->links('components.pagination') }}
  </div>
</div>
@endsection