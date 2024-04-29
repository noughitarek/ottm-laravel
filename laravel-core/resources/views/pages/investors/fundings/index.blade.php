@extends('layouts.main')
@section('subtitle', "Fundings from ".$investor->name)
@section('content')
@php
$user = Auth::user();
@endphp
<div class="col-12 col-lg-12 col-xxl-12 d-flex">
  <div class="card flex-fill">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="card-title mb-0">Fundings from {{$investor->name}}</h5>
        @if($user->Has_Permission("accounting_fundings_create"))
        <div>
                    <a href="{{route('accountinginvestors', $investor->id)}}" class="btn btn-secondary">Back</a>
            <a href="{{route('accountingfundings_create', $investor->id)}}" class="btn btn-primary">Add a funding</a>
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
          @foreach($investor->Fundings() as $funding)
          <tr>
            <td>
                <i class="align-middle me-2 fas fa-fw fa-hashtag"></i>{{$funding->id}}<br>
                <i class="align-middle me-2 fas fa-fw fa-user-tag"></i>{{$funding->name}}<br>
                <i class="align-middle me-2 fas fa-fw fa-calendar"></i>{{$funding->created_at}}<br>
            </td>
            <td>
                {{$funding->total_amount}} DZD
            </td>
            <td>
            </td>
            <td>
              @if($user->Has_Permission('accounting_fundings_edit'))
              <a href="{{route('accountingfundings_edit', [$investor->id, $funding->id])}}" class="btn btn-warning">Edit</a>
              @endif
              @if($user->Has_Permission('accounting_fundings_delete'))
              <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteFunding{{$funding->id}}">Delete</button>
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
@foreach($investor->Fundings() as $funding)
<div class="modal fade" id="deleteFunding{{$funding->id}}" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="{{route('accountingfundings_delete', [$investor->id, $funding->id])}}" method="POST">
        @csrf
        @method('delete')
        <div class="modal-header">
            <h5 class="modal-title">Delete a funding</h5>
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