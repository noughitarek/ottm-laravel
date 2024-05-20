@extends('layouts.main')
@section('subtitle', "Create a message")
@section('content')
@php
$user = Auth::user();
@endphp
<form method="POST" action="{{route('remarketing_create')}}" enctype="multipart/form-data">
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
              <label class="form-label" for="send_after">Send after <span class="text-danger">*</span></label>
              <input type="number" name="send_after" value="{{old('send_after')}}" id="send_after" class="form-control">
              @error('send_after')
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
            <div class="mb-3 col-md-4">
              <label class="form-label" for="last_message_from">If last message were from <span class="text-danger">*</span></label>
              <select name="last_message_from" id="last_message_from" class="form-control">
                <option {{old('last_message_from')=='user'?'selected':''}} value="user">Customer</option>
                <option {{old('last_message_from')=='page'?'selected':''}} value="page">Page</option>
                <option {{old('last_message_from')=='any'?'selected':''}} value="any">Any</option>
              </select>
              @error('last_message_from')
                <div class="text-danger">{{ $message }}</div>
              @enderror
            </div>
            <div class="mb-3 col-md-4">
              <label class="form-label" for="make_order">If customer <span class="text-danger">*</span></label>
              <select name="make_order" id="make_order" class="form-control">
                <option {{old('make_order')?'selected':''}} value="1">Any</option>
                <option {{old('make_order')?'':'selected'}} value="0" selected>Didn't make an order</option>
              </select>
              @error('make_order')
                <div class="text-danger">{{ $message }}</div>
              @enderror
            </div>
            <div class="mb-3 col-md-4">
              <label class="form-label" for="since">Since <span class="text-danger">*</span></label>
              <select class="form-control" name="since" id="since">
                <option {{old('since')=="last_from_user_at"?'selected':''}} value="last_from_user_at">Last message from costumer</option>
                <option {{old('since')=="last_from_page_at"?'selected':''}} value="last_from_page_at">Last message from page</option>
                <option {{old('since')=="ended_at"?'selected':''}} value="ended_at" selected>Conversation ending</option>
                <option {{old('since')=="started_at"?'selected':''}} value="started_at" selected>Conversation starting</option>
              </select>
              @error('since')
                <div class="text-danger">{{ $message }}</div>
              @enderror
            </div>
            <div class="mb-3 col-md-6">
              <label class="form-label" for="expire_after">Expire after</label>
              <input type="number" name="expire_after" value="{{old('expire_after')}}" id="expire_after" class="form-control">
              @error('expire_after')
                <div class="text-danger">{{ $message }}</div>
              @enderror
            </div>
            <div class="mb-3 col-md-6">
              <label class="form-label" for="expire_time_unit">Time unit</label>
              <select name="expire_time_unit" id="expire_time_unit" class="form-control">
                <option {{old('expire_time_unit') == 1?'selected':''}} value="1">Seconds</option>
                <option {{old('expire_time_unit') == 60 ?'selected':''}} value="60">Minutes</option>
                <option {{old('expire_time_unit') == 3600 ?'selected':''}} value="3600">Hours</option>
                <option {{old('expire_time_unit') == 86400 ?'selected':''}} value="86400">Days</option>
              </select>
              @error('expire_time_unit')
                <div class="text-danger">{{ $message }}</div>
              @enderror
            </div>
            <div class="mb-3 col-md-6">
              <label class="form-label" for="resend_after">Resend after <span class="text-danger">*</span></label>
              <input type="number" name="resend_after" value="{{old('resend_after')}}" id="resend_after" class="form-control">
              @error('resend_after')
                <div class="text-danger">{{ $message }}</div>
              @enderror
            </div>
            <div class="mb-3 col-md-6">
              <label class="form-label" for="resend_time_unit">Time unit <span class="text-danger">*</span></label>
              <select name="resend_time_unit" id="resend_time_unit" class="form-control">
                <option {{old('resend_time_unit') == 1?'selected':''}} value="1">Seconds</option>
                <option {{old('resend_time_unit') == 60 ?'selected':''}} value="60">Minutes</option>
                <option {{old('resend_time_unit') == 3600 ?'selected':''}} value="3600">Hours</option>
                <option {{old('resend_time_unit') == 86400 ?'selected':''}} value="86400">Days</option>
              </select>
              @error('resend_time_unit')
                <div class="text-danger">{{ $message }}</div>
              @enderror
            </div>
            <!--
            <div class="mb-3 col-md-6">
              <label class="form-label" for="start_time">Execute it between</label>
              <input type="text" name="start_time"  data-inputmask-regex="^(?:[01][0-9]|2[0-3]):[0-5][0-9]$" required value="{{old('start_time')}}" id="start_time" class="form-control">
              @error('start_time')
                <div class="text-danger">{{ $message }}</div>
              @enderror
            </div>
            <div class="mb-3 col-md-6">
              <label class="form-label" for="end_time">And</label>
              <input type="text" name="end_time"  data-inputmask-regex="^(?:[01][0-9]|2[0-3]):[0-5][0-9]$" required value="{{old('end_time')}}" id="end_time" class="form-control">
              @error('end_time')
                <div class="text-danger">{{ $message }}</div>
              @enderror
            </div>-->
          </div>
        </div>
      </div>
    </div>
    <div class="col-12 col-lg-12 col-xxl-12 d-flex">
      <div class="card flex-fill">
        <div class="card-header">
          <h5 class="card-title">Message</h5>
        </div>
        <div class="card-body">
          <div class="mb-3">
            <label class="form-label" for="template">Template <span class="text-danger">*</span></label>
            <select name="template" id="template" class="form-control" required>
                <option value selected>Select the template</option>
                @foreach($templates as $template)
                <option value="{{$template->id}}">{{$template->name}}</option>
                @endforeach
            </select>
            @error('template')
              <div class="text-danger">{{ $message }}</div>
            @enderror
          </div>
          <!--<div id="NotTemplate">
            <div class="row">
              <div class="mb-3 col-md-6">
                <label class="form-label" for="photos">Photos</label>
                <input type="file" name="photos[]" id="photos" class="form-control" multiple accept="image/jpeg">
                @error('photos')
                  <div class="text-danger">{{ $message }}</div>
                @enderror
              </div>
              <div class="mb-3 col-md-6">
                <label class="form-label" for="videos">Videos</label>
                <input type="file" name="videos[]" id="videos" class="form-control" accept="video/mp4" multiple>
                @error('videos')
                  <div class="text-danger">{{ $message }}</div>
                @enderror
              </div>
            </div>
            <div class="mb-3">
              <label class="form-label" for="message">Message</label>
              <textarea name="message" id="message" class="form-control">{{old('message')}}</textarea>
              @error('message')
                <div class="text-danger">{{ $message }}</div>
              @enderror
            </div>
          </div>-->
          <div class="mb-3">
            <button type="submit" class="btn btn-primary">Create</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</form>

@endsection
@section('script')
<!--
<script>
const template = document.getElementById('template');
const NotTemplate = document.getElementById('NotTemplate');
template.addEventListener('change', function(){
  if(template.value!=""){
    NotTemplate.classList.add("d-none")
  }else{
    NotTemplate.classList.remove("d-none")
  }
});
</script>
-->
@endsection