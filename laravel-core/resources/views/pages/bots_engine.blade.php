@extends('layouts.main')
@section('subtitle', "Bots engine")
@section('content')
@php
$user = Auth::user();
@endphp
@if($engine)
<div ><h1>The engine is currently <span class="text-success">on</span></h1></div>
@else
<div ><h1>The engine is currently <span class="text-danger">off</span></h1></div>
@endif
<div class="col-12 col-lg-12 col-xxl-12 d-flex">
  <div class="card flex-fill bg-dark text-light rounded">
    <div class="card-header bg-secondary text-light px-2 py-1 rounded">
      <span class="bg-dark pb-3 px-2 pt-1 rounded">
        <span>Bots engine</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <span>X</span>
      </span>

    </div>
    <div class="card-body" id="terminal" style="font-family: 'Consolas', monospace; overflow-x: auto; padding: 10px; height: 500px; overflow-y: auto;">
      <p>ITCentre bots [Version 1.0]<br>(c) ITCentre Corporation. All rights reserved.</p>
      <div class="container">
        @foreach($logs as $index=>$log)
        <div class="my-2">
          {{ $log->created_at }} [{{ $log->ip }}]: {!! htmlspecialchars($log->content, ENT_QUOTES, 'UTF-8') !!}
          @if($index + 1 == count($logs))
            <span class="blink">|</span>
          @endif
        </div>
        @endforeach
      </div>
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