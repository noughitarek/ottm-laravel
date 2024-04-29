@extends('layouts.main')
@section('subtitle', "Purchases")
@section('content')
@php
$user = Auth::user();
@endphp
<div class="col-12 col-lg-12 col-xxl-12 d-flex">
  <div class="card flex-fill">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="card-title mb-0">Purchases</h5>
        @if($user->Has_Permission("accounting_purchases_create"))
        <div>
            <a href="{{route('accountingpurchases_create')}}" class="btn btn-primary">Create an purchase</a>
        </div>
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
            <th class="d-xl-table-cell">Purchase</th>
            <th class="d-xl-table-cell">Products</th>
            <th class="d-xl-table-cell">Orders</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach($purchases as $purchase)
          <tr>
            <td>
                <i class="align-middle me-2 fas fa-fw fa-hashtag"></i>{{$purchase->id}}<br>
                <i class="align-middle me-2 fas fa-fw fa-boxes"></i>{{$purchase->Products_funding()->name}} | {{$purchase->Products_funding()->Funder()->name}}<br>
                <i class="align-middle me-2 fas fa-fw fa-volume-up"></i>{{$purchase->Tests_funding()->name}} | {{$purchase->Tests_funding()->Funder()->name}}<br>
                <i class="align-middle me-2 fas fa-fw fa-calendar"></i>{{$purchase->created_at}}<br>
            </td>
            <td>
              @foreach($purchase->Products() as $product)
                <i class="align-middle me-2 fas fa-fw fa-boxes"></i>{{$purchase->Product_Buy_Price($product)."DZD".' '.$purchase->Product_Quantity($product)." X ".$product->name}} <br>
              @endforeach
              <i class="align-middle me-2 fas fa-fw fa-dollar"></i>{{$purchase->total_amount}} DZD
            </td>
            <td>
              <span class="text-primary">0</span> |
              <span class="text-success">0</span> |
              <span class="text-danger">0</span>
            </td>
            <td>
              @if($user->Has_Permission('accounting_purchases_edit'))
              <a href="{{route('accountingpurchases_edit', $purchase->id)}}" class="btn btn-warning">Edit</a>
              @endif
              @if($user->Has_Permission('accounting_purchases_delete'))
              <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deletePurchase{{$purchase->id}}">Delete</button>
              @endif
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
@if($user->Has_Permission('invoicer_delete_product'))
@foreach($purchases as $purchase)
<div class="modal fade" id="deletePurchase{{$purchase->id}}" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="{{route('accountingpurchases_delete', $purchase->id)}}" method="POST">
        @csrf
        @method('delete')
        <div class="modal-header">
            <h5 class="modal-title">Delete a purchase</h5>
            <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-danger">Delete</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endforeach
@endif
@endsection