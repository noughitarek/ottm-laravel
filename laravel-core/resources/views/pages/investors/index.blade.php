@extends('layouts.main')
@section('subtitle', "Investors")
@section('content')
@php
$user = Auth::user();
@endphp
<div class="col-12 col-lg-12 col-xxl-12 d-flex">
  <div class="card flex-fill">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="card-title mb-0">Investors</h5>
        @if($user->Has_Permission("accounting_investors_create"))
        <div>
            <a href="{{route('accountinginvestors_create')}}" class="btn btn-primary">Create an investor</a>
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
            <th class="d-xl-table-cell">Investor</th>
            <th class="d-xl-table-cell">Amount</th>
            <th class="d-xl-table-cell">Orders</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach($investors as $investor)
          <tr>
            <td>
                <i class="align-middle me-2 fas fa-fw fa-hashtag"></i>{{$investor->id}}<br>
                <i class="align-middle me-2 fas fa-fw fa-user-tag"></i>{{$investor->name}}<br>
                <i class="align-middle me-2 fas fa-fw fa-at"></i>{{$investor->email}}<br>
                <i class="align-middle me-2 fas fa-fw fa-calendar"></i>{{$investor->created_at}}<br>
            </td>
            <td>
                0 DZD
            </td>
            <td>
            </td>
            <td>
              
              @if($user->Has_Permission('accounting_fundings_consult'))
              <a href="{{route('accountingfundings', $investor->id)}}" class="btn btn-success">Fundings</a>
              @endif
              @if($user->Has_Permission('accounting_investors_edit'))
              <a href="{{route('accountinginvestors_edit', $investor->id)}}" class="btn btn-warning">Edit</a>
              @endif
              @if($user->Has_Permission('accounting_investors_delete'))
              <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteInvestor{{$investor->id}}">Delete</button>
              @endif
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
@if($user->Has_Permission('accounting_investors_delete'))
@foreach($investors as $investor)
<div class="modal fade" id="deleteInvestor{{$investor->id}}" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="{{route('accountinginvestors_delete', $investor->id)}}" method="POST">
        @csrf
        @method('delete')
        <div class="modal-header">
            <h5 class="modal-title">Delete a investor</h5>
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