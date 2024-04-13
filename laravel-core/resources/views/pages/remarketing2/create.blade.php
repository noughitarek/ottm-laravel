@extends('layouts.main')
@section('subtitle', "Create a message")
@section('content')
@php
$user = Auth::user();
@endphp
<form method="POST" action="{{route('remarketing_interval_create')}}" enctype="multipart/form-data">
@csrf
  <div class="row">
    <div class="col-12 col-lg-12 col-xxl-12 d-flex">
      <div class="card flex-fill">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="card-title mb-0">Create a message</h5>
          <button type="submit" class="btn btn-primary">Create</button>
        </div>
      </div>
    </div>
    <div class="col-12 col-lg-12 col-xxl-12 d-flex">
      <div class="card flex-fill">
        <div class="card-header">
          <h5 class="card-title">General information</h5>
        </div>
        <div class="card-body">
          <div class="mb-3">
            <label class="form-label" for="name">Name <span class="text-danger">*</span></label>
            <input type="text" name="name" id="name" value="{{old('name')}}" class="form-control">
            @error('name')
              <div class="text-danger">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-3">
            <label class="form-label" for="pages">Page <span class="text-danger">*</span></label>
            <select name="pages[]" id="pages" class="form-control page-select" required>
                <option value disabled selected>Select the page</option>
                @foreach($pages as $page)
                <option {{old('pages')!=null && in_array($page->facebook_page_id, old('pages'))?'selected':''}} value="{{$page->facebook_page_id}}">{{$page->name}}</option>
                @endforeach
            </select>
            @error('pages')
              <div class="text-danger">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-3">
            <label class="form-label" for="category">Category <span class="text-danger">*</span></label>
            <select name="category" id="category" class="form-control" required>
                <option value selected>No category</option>
                @foreach($categories as $category)
                <option value="{{$category->id}}">{{$category->name}}</option>
                @endforeach
            </select>
            @error('category')
              <div class="text-danger">{{ $message }}</div>
            @enderror
          </div>
        </div>
      </div>
    </div>
    <div class="col-12 col-lg-12 col-xxl-12 d-flex">
      <div class="card flex-fill">
        <div class="card-header">
          <h5 class="card-title">Algorithme</h5>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="mb-3 col-md-6">
              <label class="form-label" for="start_after">Start after <span class="text-danger">*</span></label>
              <input type="number" name="start_after" value="{{old('start_after')}}" id="start_after" class="form-control">
              @error('start_after')
                <div class="text-danger">{{ $message }}</div>
              @enderror
            </div>
            <div class="mb-3 col-md-6">
              <label class="form-label" for="start_time_unit">Time unit <span class="text-danger">*</span></label>
              <select name="start_time_unit" id="start_time_unit" class="form-control">
                <option {{old('start_time_unit') == 1?'selected':''}} value="1">Seconds</option>
                <option {{old('start_time_unit') == 60 ?'selected':''}} value="60">Minutes</option>
                <option {{old('start_time_unit') == 3600 ?'selected':''}} value="3600">Hours</option>
                <option {{old('start_time_unit') == 86400 ?'selected':''}} value="86400">Days</option>
              </select>
              @error('start_time_unit')
                <div class="text-danger">{{ $message }}</div>
              @enderror
            </div>
            <div class="mb-3 col-md-12">
              <label class="form-label" for="devide_by">Devide messages by <span class="text-danger">*</span></label>
              <input type="number" name="devide_by" value="{{old('devide_by')}}" id="devide_by" class="form-control">
              @error('devide_by')
                <div class="text-danger">{{ $message }}</div>
              @enderror
            </div>
            <div class="mb-3 col-md-6">
              <label class="form-label" for="send_after_each">Send after each <span class="text-danger">*</span></label>
              <input type="number" name="send_after_each" value="{{old('send_after_each')}}" id="send_after_each" class="form-control">
              @error('send_after_each')
                <div class="text-danger">{{ $message }}</div>
              @enderror
            </div>
            <div class="mb-3 col-md-6">
              <label class="form-label" for="time_unit">Time unit <span class="text-danger">*</span></label>
              <select name="time_unit" id="time_unit" class="form-control">
                <option {{old('time_unit') == 1?'selected':''}} value="1">Seconds</option>
                <option {{old('time_unit') == 60 ?'selected':''}} value="60">Minutes</option>
                <option {{old('time_unit') == 3600 ?'selected':''}} value="3600">Hours</option>
                <option {{old('time_unit') == 86400 ?'selected':''}} value="86400">Days</option>
              </select>
              @error('time_unit')
                <div class="text-danger">{{ $message }}</div>
              @enderror
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-12 col-lg-12 col-xxl-12 d-flex">
      <div class="card flex-fill">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="card-title">Message</h5>
          <button type="button" id="moreTemplates" class="btn btn-primary">More templates</button>
        </div>
        <div class="card-body">
          <div class="templates">
          @for($i=0;$i<10;$i++)
          <div class="mb-3 template {{$i==0?'':'d-none'}}">
            <label class="form-label" for="template">Template {{$i+1}} {!!$i==0?'<span class="text-danger">*</span>':''!!}</label>
            <select name="template[{{$i+1}}]" id="template" class="form-control" {{$i==0?'required':''}}>
                <option value selected>Select the template</option>
                @foreach($templates as $template)
                <option value="{{$template->id}}">{{$template->name}}</option>
                @endforeach
            </select>
          </div>
          @endfor
            @error('template')
              <div class="text-danger">{{ $message }}</div>
            @enderror
            </div>
          <div class="mb-3">
            <button type="submit" class="btn btn-primary">Create</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</form>

@endsection
@section("script")
<script>
  document.getElementById('moreTemplates').addEventListener('click', function() {
      var toshowElem = document.querySelector('.template.d-none');
      toshowElem != null && toshowElem.classList.remove('d-none')
    });
</script>
@endsection