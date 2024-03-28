@extends('layouts.main')
@section('subtitle', "Wilayas")
@section('content')
@php
$user = Auth::user();
@endphp
<form action="{{route('wilayas_edit')}}" method="POST">
  @csrf
  @method('PUT')
  <div class="col-12 col-lg-12 col-xxl-12 d-flex">
    <div class="card flex-fill">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Wilayas</h5>
        @if($user->Has_Permission('wilayas_edit'))
        <div>
        <button type="submit" class="btn btn-success" > Auto Update prices (API) </button>
        <button type="submit" class="btn btn-primary" > Save </button>
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
              <th class="d-xl-table-cell">Wilaya</th>
              <th class="d-xl-table-cell">Price</th>
              <th class="d-xl-table-cell">Orders</th>
            </tr>
          </thead>
          <tbody>
            @foreach($wilayas as $wilaya)
            <tr>
                <td class="d-xl-table-cell">
                    <i class="align-middle me-2 fas fa-fw fa-hashtag"></i> {{$wilaya->id}}<br>
                    <i class="align-middle me-2 fas fa-fw fa-box"></i> {{$wilaya->name}}<br>
                    @if($user->Has_Permission('wilayas_edit'))
                    <label>Arabic :</label>
                    <input type="text" class="form-control" value="{{$wilaya->name_ar}}" name="wilayas[{{$wilaya->id}}][name_ar]">
                    @else
                    <i class="align-middle me-2 fas fa-fw fa-box"></i> {{$wilaya->name_ar}}<br>
                    @endif
                </td>
                <td>
                    @if($user->Has_Permission('wilayas_edit'))
                    <label>Price :</label>
                    <input type="text" class="form-control" value="{{$wilaya->delivery_price}}" name="wilayas[{{$wilaya->id}}][delivery_price]">
                    <label>Desk :</label>
                    <select class="form-control" name="wilayas[{{$wilaya->id}}][desk]">
                      <option value disabled selected>Select  the desk</a>
                      @foreach($desks as $desk)
                      <option value="{{$desk->id}}" {{$desk->id==$wilaya->desk?"selected":""}}>{{$desk->name}}</option>
                      @endforeach
                    </select>
                    @else
                    <i class="align-middle me-2 fas fa-fw fa-dollar-sign"></i> {{$wilaya->delivery_price}}<br>
                    <i class="align-middle me-2 fas fa-fw fa-tv"></i> {{$wilaya->Desk()->name}}<br>
                    @endif
                </td>
                <td>
                    <span class="text-primary">0</span> |
                    <span class="text-success">0</span> |
                    <span class="text-danger">0</span> 
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