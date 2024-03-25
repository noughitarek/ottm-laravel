@php
$fb_page = config('settings.access_token')->Page()->Get_Conversations();
@endphp
@extends('layouts.main')
@section('content')
<div class="col-12 col-lg-12 col-xxl-12 d-flex">
  <div class="card flex-fill">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="card-title mb-0">Conversations</h5>
    </div>
  </div>
</div>
<div class="col-12 col-lg-12 col-xxl-12 d-flex">
  <div class="card flex-fill">
    <div class="table-responsive">
      <table class="table table-hover my-0" id="datatables-orders">
        <thead>
          <tr>
            <th class="d-xl-table-cell">User</th>
            <th class="d-xl-table-cell">Messages</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td class="d-xl-table-cell">
                    <i class="align-middle me-2 fas fa-fw fa-hashtag"></i> <a href="{{route('conversations_conversation', $user->facebook_user_id)}}">{{$user->facebook_user_id}}</a><br>
                    <i class="align-middle me-2 fas fa-fw fa-at"></i> {{$user->email}}<br>
                    <i class="align-middle me-2 fas fa-fw fa-user"></i> {{$user->name}}
                </td>
                <td class="d-xl-table-cell">
                    <i class="align-middle me-2 fas fa-fw fa-calendar"></i> {{$user->Conversation()[0]->created_at}}<br>
                    <i class="align-middle me-2 fas fa-fw fa-envelope-open"></i> {{count($user->Conversation())}}<br>
                    <i class="align-middle me-2 fas fa-fw fa-shopping-cart"></i> 0
                </td>
            </tr>
            @endforeach
        </tbody>
      </table>
    </div>
    {{ $users->links('components.pagination') }}
  </div>
</div>
@endsection