@extends('layouts.main')
@section('subtitle', "ARM history")
@section('content')
@php
$user = Auth::user();
@endphp
<div class="col-12 col-lg-12 col-xxl-12 d-flex">
  <div class="card flex-fill">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="card-title mb-0">ARM history</h5>
      <a href="{{route('responder')}}" class="btn btn-secondary">Back</a>
    </div>
  </div>
</div>
<div class="col-12 col-lg-12 col-xxl-12 d-flex">
  <div class="card flex-fill">
    <div class="table-responsive">
      <table class="table table-hover my-0" id="datatables-orders">
        <thead>
          <tr>
            <th class="d-xl-table-cell">Desk</th>
            <th class="d-xl-table-cell">Total messages</th>
            <th class="d-xl-table-cell">Orders</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
            @foreach($responder->History() as $conversation)
            <tr>
                <td class="d-xl-table-cell">
                    
                    <a href="{{route('conversations_conversation', $conversation->facebook_conversation_id)}}"><i class="align-middle me-2 fas fa-fw fa-hashtag"></i> {{$conversation->facebook_conversation_id}}</a><br>
                    <i class="align-middle me-2 fas fa-fw fa-user"></i> {{$conversation->Conversation()->User()->name}}<br>
                    <i class="align-middle me-2 fas fa-fw fa-at"></i> {{$conversation->Conversation()->User()->email}}<br>
                </td>
                <td>
                    <i class="align-middle me-2 fas fa-fw fa-user-cog"></i> {{$conversation->Page()->name}}<br>
                </td>
                <td>
                    <span class="text-primary">0</span> |
                    <span class="text-success">0</span> |
                    <span class="text-danger">0</span> 
                </td>
                <td>
                  @if($user->Has_Permission('orders_create'))
                  <a href="{{route('orders_create_conversation', $conversation)}}" class="btn btn-primary" >
                    New order
                  </a>
                  @endif
                </td>
            </tr>
            @endforeach
        </tbody>
      </table>
    </div>
  {{ $responder->History()->links('components.pagination') }}
  </div>
</div>
@endsection