@extends('layouts.main')
@section('subtitle', "Create a message")
@section('content')
@php
$user = Auth::user();
@endphp
<form method="POST" action="{{route('responder_create')}}" >
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