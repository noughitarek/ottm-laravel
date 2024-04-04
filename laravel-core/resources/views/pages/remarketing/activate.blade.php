@extends('layouts.main')
@section('subtitle', "Activate message")
@section('content')
@php
$user = Auth::user();
@endphp

@foreach ($errors->all() as $title=>$error)
  <li>{{ $title.'-'.$error }}</li>
@endforeach
<form method="POST" action="{{route('remarketing_activate', $remarketing->id)}}" enctype="multipart/form-data">
  @csrf
  @method('PUT')
  <div class="row">
    <div class="col-12 col-lg-12 col-xxl-12 d-flex">
      <div class="card flex-fill">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="card-title mb-0">Activate message</h5>
          <button type="submit" class="btn btn-primary">Activate</button>
        </div>
      </div>
    </div>
  </div>
</form>

<div class="col-12 col-lg-12 col-xxl-12 d-flex">
  <div class="card flex-fill">
    <div class="table-responsive">
      <table class="table table-hover my-0" id="datatables-orders">
        <thead>
          <tr>
            <th class="d-xl-table-cell">User</th>
            <th class="d-xl-table-cell">Total messages</th>
            <th class="d-xl-table-cell">Orders</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
            @foreach($remarketing->Get_Supported_Conversations() as $facebook_user)
            <tr>
                <td class="d-xl-table-cell">
                    
                    <a href="{{route('conversations_conversation', $facebook_user->Conversation()->facebook_conversation_id)}}"><i class="align-middle me-2 fas fa-fw fa-hashtag"></i> {{$facebook_user->facebook_user_id}}</a><br>
                    <i class="align-middle me-2 fas fa-fw fa-user"></i> {{$facebook_user->name}}<br>
                    <i class="align-middle me-2 fas fa-fw fa-at"></i> {{$facebook_user->email}}<br>
                    <i class="align-middle me-2 fas fa-fw fa-calendar"></i> {{$facebook_user->Conversation()->Messages()->first()->created_at}}<br>
                    <i class="align-middle me-2 fas fa-fw fa-calendar-plus"></i> {{$facebook_user->Conversation()->Messages()->last()->created_at}}<br>
                </td>
                <td>
                    <i class="align-middle me-2 fas fa-fw fa-user-cog"></i> {{$facebook_user->Conversation()->Page()->name}}<br>
                    <i class="align-middle me-2 fas fa-fw fa-envelop-open"></i> {{$facebook_user->Conversation()->Messages()->count()}}<br>
                </td>
                <td>
                    <span class="text-primary">0</span> |
                    <span class="text-success">0</span> |
                    <span class="text-danger">0</span> 
                </td>
                <td>
                  @if($user->Has_Permission('orders_create'))
                  <a href="{{route('orders_create_conversation', $facebook_user)}}" class="btn btn-primary" >
                    New order
                  </a>
                  @endif
                </td>
            </tr>
            @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection