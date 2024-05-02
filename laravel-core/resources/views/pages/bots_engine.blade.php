@extends('layouts.main')
@section('subtitle', "Bots engine")
@section('content')
@php
$user = Auth::user();
@endphp
@if($engine)
<div ><h1>The engine is <span class="text-success">on</span></h1></div>
@else
<div ><h1>The engine is <span class="text-danger">off</span></h1></div>
@endif
<div class="col-12 col-lg-12 col-xxl-12 d-flex">
  <div class="card flex-fill bg-dark text-light rounded">
    <div class="card-header bg-secondary text-light px-2 py-1 rounded">
      <span class="bg-dark pb-3 px-2 pt-1 rounded">
        <span>Bots engine</span>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <span>X</span>
      </span>

    </div>
    <div class="card-body" id="terminal" style="font-family: 'Consolas', monospace; overflow-x: auto; padding: 10px; height: 400px; overflow-y: auto;">
ITCentre bots [Version 1.0]<br>
(c) ITCentre Corporation. All rights reserved.<br><br>
@foreach($logs as $log)
<span class="my-10">{{$log->created_at}} [{{$log->ip}}]: {{$log->content}}<br></span>
@endforeach
<span style="animation: blink 1s infinite;">|</span>
</div>
  </div>
</div>
<style>
@keyframes blink {
    0% { opacity: 1.0; }
    50% { opacity: 0.0; }
    100% { opacity: 1.0; }
}
@-webkit-keyframes blink {
    0% { opacity: 1.0; }
    50% { opacity: 0.0; }
    100% { opacity: 1.0; }
}
@-moz-keyframes blink {
    0% { opacity: 1.0; }
    50% { opacity: 0.0; }
    100% { opacity: 1.0; }
}
</style>
@endsection
@section('script')
<script>
    var terminal = document.getElementById("terminal");
    terminal.scrollTop = terminal.scrollHeight;
</script>
@endsection