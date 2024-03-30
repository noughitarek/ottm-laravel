@extends('layouts.main')
@section('subtitle', "Stock")
@section('content')
@php
$user = Auth::user();
@endphp
<div class="col-12 col-lg-12 col-xxl-12 d-flex">
  <div class="card flex-fill">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="card-title mb-0">Stock</h5>
      @if($user->Has_Permission('stock_create'))
      <button data-bs-toggle="modal" data-bs-target="#createStock" class="btn btn-primary" > Create a stock </button>
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
            <th class="d-xl-table-cell">Product</th>
            <th class="d-xl-table-cell">Stock</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($stock as $restock)
          <tr>
              <td class="d-xl-table-cell">
                  <i class="align-middle me-2 fas fa-fw fa-hashtag"></i> {{$restock->id}}<br>
                  <i class="align-middle me-2 fas fa-fw fa-box"></i> {{$restock->Product()->name}}<br>
                  <i class="align-middle me-2 fas fa-fw fa-tv"></i> {{$restock->Desk()->name}}<br>
              </td>
              <td>
              <i class="align-middle me-2 fas fa-fw fa-boxes"></i> {{$restock->quantity}}<br>
              <i class="align-middle me-2 fas fa-fw fa-dollar-sign"></i> {{$restock->total_amount}} DZD<br>
              </td>
              <td>
                @if($user->Has_Permission('stock_edit'))
                  <button data-bs-toggle="modal" data-bs-target="#editStock{{$restock->id}}" class="btn btn-warning" >
                    Edit
                  </button>
                  @endif
                @if($user->Has_Permission('stock_delete'))
                  <button data-bs-toggle="modal" data-bs-target="#deleteStock{{$restock->id}}" class="btn btn-danger" >
                    Delete
                  </button>
                  @endif
              </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
@if($user->Has_Permission('stock_create'))
<div class="modal fade" id="createStock" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="{{route('stock_create')}}" method="POST">
        @csrf
        <div class="modal-header">
            <h5 class="modal-title">Create a stock</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body m-3">
          <div class="mb-3">
            <label class="form-label">Product<span class="text-danger">*</span></label>
            <select name="product" class="form-control product-select" required>
                <option value disabled selected>Select the product</option>
                @foreach($products as $productSelect)
                <option value="{{$productSelect->id}}">{{$productSelect->name}}</option>
                @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Desk<span class="text-danger">*</span></label>
            <select name="desk" class="form-control desk-select" required>
                <option value disabled selected>Select the desk</option>
                @foreach($desks as $desk)
                <option value="{{$desk->id}}">{{$desk->name}}</option>
                @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Amount <span class="text-danger">*</span></label>
            <input type="number" name="total_amount" class="form-control" placeholder="Ex: 86000">
          </div>
          <div class="mb-3">
            <label class="form-label">Quantity <span class="text-danger">*</span></label>
            <input type="number" min="1" name="quantity" class="form-control" placeholder="Ex: 100">
          </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Create</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endif
@if($user->Has_Permission('stock_edit'))
@foreach($stock as $restock)
<div class="modal fade" id="editStock{{$restock->id}}" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="{{route('stock_create')}}" method="POST">
        @csrf
        <div class="modal-header">
            <h5 class="modal-title">Create a stock</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body m-3">
          <div class="mb-3">
            <label class="form-label">Product<span class="text-danger">*</span></label>
            <select name="product" class="form-control product-select" required>
                <option value disabled>Select the product</option>
                @foreach($products as $productSelect)
                <option value="{{$productSelect->id}}" {{$productSelect->id==$restock->product?'selected':''}}>{{$productSelect->name}}</option>
                @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Desk<span class="text-danger">*</span></label>
            <select name="desk" class="form-control desk-select" required>
                <option value disabled>Select the desk</option>
                @foreach($desks as $desk)
                <option value="{{$desk->id}}" {{$desk->id==$restock->desk?'selected':''}}>{{$desk->name}}</option>
                @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Amount <span class="text-danger">*</span></label>
            <input type="number" name="total_amount" class="form-control" value="{{$restock->total_amount}}" placeholder="Ex: 86000">
          </div>
          <div class="mb-3">
            <label class="form-label">Quantity <span class="text-danger">*</span></label>
            <input type="number" min="1" name="quantity" class="form-control" value="{{$restock->quantity}}" placeholder="Ex: 100">
          </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Create</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endforeach
@endif
@if($user->Has_Permission('stock_delete'))
@foreach($stock as $restock)
<div class="modal fade" id="deleteStock{{$restock->id}}" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="{{route('stock_delete', $restock->id)}}" method="POST">
        @csrf
        @method('delete')
        <div class="modal-header">
            <h5 class="modal-title">Delete stock {{$restock->name}} ?</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const products = document.querySelectorAll(".product-select");
        const desks = document.querySelectorAll(".desk-select");
        products.forEach(product => {
            new Choices(product, {shouldSort: false});
        });
        desks.forEach(desk => {
            new Choices(desk, {shouldSort: false});
        });
    });
</script>
@endsection