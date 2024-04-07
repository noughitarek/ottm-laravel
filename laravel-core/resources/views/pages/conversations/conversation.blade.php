@extends('layouts.main')
@section('subtitle', "Conversations")
@section('content')
@php
$user = Auth::user();
@endphp
<div class="col-12 col-lg-12 col-xxl-12 d-flex">
  <div class="card flex-fill">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="card-title mb-0">Conversation with {{ $conversation->User()->name}}</h5>
      <a href="{{url()->previous()}}" class="btn btn-secondary">Back</a>
    </div>
  </div>
</div>

<div class="container-fluid p-0">
  <div class="card">
    <div class="row g-0">
      <div class="col-12 col-lg-12 col-xl-12">
        <div class="py-2 px-4 border-bottom d-none d-lg-block">
          <div class="d-flex align-items-center py-1">
            <div class="position-relative">
              <img src="{{asset('assets/img/avatars/unknown.png')}}" class="rounded-circle me-1" alt="{{$conversation->User()->name}}" width="40" height="40">
            </div>
            <div class="flex-grow-1 ps-3">
              <strong>{{$conversation->User()->name}}</strong>
            </div>
          </div>
        </div>
        <div class="position-relative">
          <div class="chat-messages p-4">
            @foreach($conversation->Messages() as $message)
            @if($message->sented_from != 'page')
            <div class="chat-message-left pb-4">
              <div>
                <img src="{{asset('assets/img/avatars/unknown.png')}}" class="rounded-circle me-1" alt="{{$conversation->User()->name}}" width="40" height="40">
                <div class="text-muted small text-nowrap mt-2">{{$message->created_at}}</div>
              </div>
              <div class="flex-shrink-1 bg-light rounded py-2 px-3 ms-3">
                <div class="font-weight-bold mb-1">{{$conversation->User()->name}}</div> {{$message->message}}
              </div>
            </div>
            @else
            <div class="chat-message-right pb-4">
              <div>
                <img src="{{asset('assets/img/avatars/unknown.png')}}" class="rounded-circle me-1" alt="{{$conversation->User()->name}}" width="40" height="40">
                <div class="text-muted small text-nowrap mt-2">{{$message->created_at}}</div>
              </div>
              <div dir="rtl" class="flex-shrink-1 bg-primary text-white rounded py-2 px-3 me-3">
                <div class="font-weight-bold mb-1"><h4 class="text-white"><b>{{$conversation->Page()->name}}</b></h4></div> {{$message->message}}
              </div>
            </div>
            @endif
            @endforeach
          </div>
        </div>
        <div class="flex-grow-0 py-3 px-4 border-top">
          <form method="POST" action="{{route('conversations_sendmessage', $conversation->facebook_conversation_id)}}">
            @csrf
            <div class="input-group">
              <input type="text" name="message" class="form-control" placeholder="Type your message">
              <button class="btn btn-primary">Send</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection