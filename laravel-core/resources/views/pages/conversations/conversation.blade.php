@php
$fb_page = config('settings.access_token')->Page()->Get_User();
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

<div class="container-fluid p-0">
  <div class="card">
    <div class="row g-0">
      <div class="col-12 col-lg-5 col-xl-3 border-end list-group">
        @foreach($users as $user)
        <a href="{{route('conversations_conversation', $user->facebook_user_id)}}?page={{isset($_GET['page'])?$_GET['page']:1}}" class="list-group-item list-group-item-action border-0 {{$user==$suser?'active':''}}">
          <div class="badge bg-success float-end">{{count($user->Conversation())}}</div>
          <div class="d-flex align-items-start">
            <img src="{{asset('assets/img/avatars/unknown.png')}}" class="rounded-circle me-1" alt="Vanessa Tucker" width="40" height="40">
            <div class="flex-grow-1 ms-3"> {{$user->name}} <div class="small">
                <span class="fas fa-circle chat-{{$user->can_reply?'online':'offline'}}"></span> {{$user->can_reply?'Can reply':'Can\'t reply'}}
              </div>
            </div>
          </div>
        </a>
        @endforeach
        <hr class="d-block d-lg-none mt-1 mb-0" />
      </div>
      <div class="col-12 col-lg-7 col-xl-9">
        <div class="py-2 px-4 border-bottom d-none d-lg-block">
          <div class="d-flex align-items-center py-1">
            <div class="position-relative">
              <img src="{{asset('assets/img/avatars/unknown.png')}}" class="rounded-circle me-1" alt="{{$suser->name}}" width="40" height="40">
            </div>
            <div class="flex-grow-1 ps-3">
              <strong>{{$suser->name}}</strong>
            </div>
          </div>
        </div>
        <div class="position-relative">
          <div class="chat-messages p-4">
            @foreach($suser->Conversation() as $message)
            @if($message->sented_by == $suser->facebook_user_id)
            <div class="chat-message-left pb-4">
              <div>
                <img src="{{asset('assets/img/avatars/unknown.png')}}" class="rounded-circle me-1" alt="{{$suser->name}}" width="40" height="40">
                <div class="text-muted small text-nowrap mt-2">{{$message->created_at}}</div>
              </div>
              <div class="flex-shrink-1 bg-light rounded py-2 px-3 ms-3">
                <div class="font-weight-bold mb-1">{{$suser->name}}</div> {{$message->content}}
              </div>
            </div>
            @else
            <div class="chat-message-right pb-4">
              <div>
                <img src="{!!$fb_page['picture']['data']['url']!!}" class="rounded-circle me-1" alt="{{$fb_page['name']}}" width="40" height="40">
                <div class="text-muted small text-nowrap mt-2">{{$message->created_at}}</div>
              </div>
              <div class="flex-shrink-1 bg-light rounded py-2 px-3 me-3">
                <div class="font-weight-bold mb-1">You</div> {{$message->content}}
              </div>
            </div>
            @endif
            @endforeach
          </div>
        </div>
        <div class="flex-grow-0 py-3 px-4 border-top">
          <div class="input-group">
            <input type="text" class="form-control" placeholder="Type your message">
            <button class="btn btn-primary">Send</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="col-12 col-lg-12 col-xxl-12 d-flex">
  <div class="card flex-fill">
    <div class="card-header d-flex justify-content-between align-items-center">
    {{ $users->links('components.pagination') }}
    </div>
  </div>
</div>
@endsection