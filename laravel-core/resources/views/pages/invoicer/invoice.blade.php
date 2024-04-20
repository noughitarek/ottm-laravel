@extends('layouts.main')
@section('subtitle', "Invoice")
@section('content')
@php
$user = Auth::user();
@endphp
<div class="col-12 col-lg-12 col-xxl-12 d-flex">
  <div class="card flex-fill">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="card-title mb-0">Invoice</h5>
        <div>
            @if($user->Has_Permission("invoicer_create_product"))
            <button onclick="document.getElementById('invoicer_edit').submit()" class="btn btn-primary" > Save </button>
            @endif
        </div>
    </div>
  </div>
</div>
<div class="col-12 col-lg-12 col-xxl-12 d-flex">
  <div class="card flex-fill">
    <form id="invoicer_edit" action="{{route('invoicer_edit', $invoice->id)}}" method="POST" class="m-4">
      @csrf
      @method('put')
      <label class="form-label">Desk: </label>
      <select name="desk" class="form-control">
        <option >Select the desk</option>
        @foreach($desks as $desk)
        <option value="{{$desk->id}}" {{$invoice->desk == $desk->id?'selected':''}}>{{$desk->name}}</option>
        @endforeach
      </select>
    </form>
    <div class="table-responsive">
      <table class="table table-hover my-0" id="datatables-orders">
        <thead>
          <tr>
            <th class="d-xl-table-cell">Product</th>
            <th class="d-xl-table-cell">Unit price</th>
            <th class="d-xl-table-cell">Quantity</th>
            <th class="d-xl-table-cell">Capital</th>
            <th class="d-xl-table-cell">Total</th>
            <th class="d-xl-table-cell">Benefits</th>
            <th class="d-xl-table-cell">Delivery extra</th>
            <th class="d-xl-table-cell">Desk extra</th>
          </tr>
        </thead>
        <tbody>
          @foreach($invoice->Products() as $product)
          <tr>
            <td>{{$product->name}}</td>
            <td>{{$product->purchase_price}} DZD</td>
            <td>{{$product->total_quantity}}</td>
            <td>{{$product->capital}} DZD</td>
            <td>{{$product->clean}} DZD</td>
            <td><b class="text-success">{{$product->benefits}} DZD</b></td>
            <td>{{$product->delivery_extra}} DZD</td>
            <td>{{$product->desk_extra}} DZD</td>
          </tr>
          @endforeach
          <tr></tr>
          <tr>
            <td colspan="1"></td>
            <td>Total:</td>
            <td>{{$invoice->Total_orders()}}</td>
            <td>{{$invoice->Total_capital()}} DZD</td>
            <td>{{$invoice->Total_clean()}} DZD</td>
            <td><b class="text-success">{{$invoice->Total_benefits()}} DZD</b></td>
            <td>{{$invoice->Total_delivery_extra()}} DZD</td>
            <td>{{$invoice->Total_desk_extra()}} DZD</td>
          </tr>
          <tr>
            <td colspan="5"><h1>Amount:</h1></td>
          </tr>
          <tr>
            <td></td>
            <td colspan="2"><h1>Total:</h1></td>
            <td colspan="2"><h1><b>{{$invoice->Total()}} DZD</b></h1></td>
          </tr>
          <tr>
            <td></td>
            <td colspan="2"><h1>Delivery fees:</h1></td>
            <td colspan="2"><h1><b>{{$invoice->Delivery()}} DZD</b></h1></td>
          </tr>
          <tr>
            <td></td>
            <td colspan="2"><h1>Clean amount:</h1></td>
            <td colspan="2"><h1><b>{{$invoice->Clean()}} DZD</b></h1></td>
          </tr>
      </tbody>
    </table>
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
          @foreach($invoice->Orders() as $order)
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
                <i class="align-middle me-2 fas fa-fw fa-map"></i> {{$order->delivery_extra}} DZD<br>
                <i class="align-middle me-2 fas fa-fw fa-building"></i> {{$order->desk_extra}} DZD<br>
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