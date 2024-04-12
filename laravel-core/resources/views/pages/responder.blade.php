@extends('layouts.main')
@section('subtitle', "OTTM")
@section('content')
@php
$user = Auth::user();
@endphp
<form action="{{route('responder_edit')}}" method="POST" enctype="multipart/form-data">
@csrf
<div class="col-12 col-lg-12 col-xxl-12 d-flex">
  <div class="card flex-fill">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="card-title mb-0">ARM</h5>
        @if($user->Has_Permission('responder_edit'))
            <button type="submit" class="btn btn-primary">Save changes</button>
        @endif
    </div>
  </div>
</div>
<div class="col-12 col-lg-12 col-xxl-12 d-flex">
  <div class="card flex-fill">
	<div class="card-header">
	  <h5 class="card-title mb-0">Welcom Messages template</h5>
	</div>
	<div class="card-body mb-2">
	  <div class="row mb-2 d-flex align-items-center">
	    <div class="col-md-12">
          @foreach($pages as $page)
		  <div class="mb-3">
		    <label for="validating">{{$page->name}}</label>
            <select class="form-control">
              <option>s</option>
            </select>
		  </div>
          @endforeach
        </div>
	  </div>
    @if($user->Has_Permission('responder_edit'))
	    <button type="submit" class="btn btn-primary">Save changes</button>
	@endif
	</div>
  </div>
</div>
</form>
@endsection