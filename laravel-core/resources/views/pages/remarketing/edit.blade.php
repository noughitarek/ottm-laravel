@extends('layouts.main')
@section('subtitle', "Edit message")
@section('content')
@php
$user = Auth::user();
@endphp

  @foreach ($errors->all() as $title=>$error)
                <li>{{ $title.'-'.$error }}</li>
            @endforeach
<form method="POST" action="{{route('remarketing_edit', $remarketing->id)}}" enctype="multipart/form-data">
@csrf
@method('PUT')
<div class="row">
  <div class="col-12 col-lg-12 col-xxl-12 d-flex">
    <div class="card flex-fill">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Edit message</h5>
        <button type="submit" class="btn btn-primary">Edit</button>
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
          <input type="text" name="name" id="name" value="{{old('name') ?? $remarketing->name}}" class="form-control">
          @error('name')
            <div class="text-danger">{{ $message }}</div>
          @enderror
        </div>
        <div class="mb-3">
          <label class="form-label" for="pages">Page <span class="text-danger">*</span></label>
          <select name="pages[]" id="pages" class="form-control page-select" required>
              <option value disabled selected>Select the page</option>
              @foreach($pages as $page)
              <option {{(old('pages')!=null && in_array($page->facebook_page_id, old('pages')))||(in_array($page->facebook_page_id, explode(',', $remarketing->facebook_page_id)))?'selected':''}} value="{{$page->facebook_page_id}}">{{$page->name}}</option>
              @endforeach
          </select>
          @error('pages')
            <div class="text-danger">{{ $message }}</div>
          @enderror
        </div>
        <div class="row">
          <div class="mb-3 col-md-6">
            <label class="form-label" for="send_after">Send after <span class="text-danger">*</span></label>
            <input type="number" name="send_after" value="{{old('send_after')??$remarketing->send_after}}" id="send_after" class="form-control">
            @error('send_after')
              <div class="text-danger">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-3 col-md-6">
            <label class="form-label" for="time_unit">Time unit <span class="text-danger">*</span></label>
            <select name="time_unit" id="time_unit" class="form-control">
              <option value="1" {{old('time_unit')==null?'selected':''}}>Seconds</option>
              <option {{old('time_unit') == 60 ?'selected':''}} value="60">Minutes</option>
              <option {{old('time_unit') == 3600 ?'selected':''}} value="3600">Hours</option>
              <option {{old('time_unit') == 86400 ?'selected':''}} value="86400">Days</option>
            </select>
            @error('page')
              <div class="text-danger">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-3 col-md-4">
            <label class="form-label" for="last_message_from">If last message were from <span class="text-danger">*</span></label>
            <select name="last_message_from" id="last_message_from" class="form-control">
              <option {{old('last_message_from')==null&&$remarketing->last_message_from=='user'?'selected':''}} {{old('last_message_from')=='user'?'selected':''}} value="user">Customer</option>
              <option {{old('last_message_from')==null&&$remarketing->last_message_from=='page'?'selected':''}} {{old('last_message_from')=='page'?'selected':''}} value="page">Page</option>
              <option {{old('last_message_from')==null&&$remarketing->last_message_from=='any'?'selected':''}} {{old('last_message_from')=='any'?'selected':''}} value="any">Any</option>
            </select>
            @error('page')
              <div class="text-danger">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-3 col-md-4">
            <label class="form-label" for="make_order">If customer <span class="text-danger">*</span></label>
            <select name="make_order" id="make_order" class="form-control">
              <option {{old('make_order')==null&&$remarketing->make_order?'selected':''}} {{old('make_order')!=null&&old('make_order')?'selected':''}} value="1">Any</option>
              <option {{old('make_order')==null&&$remarketing->make_order?'':'selected'}} {{old('make_order')!=null&&old('make_order')?'':'selected'}} value="0">Didn't make an order</option>
            </select>
            @error('make_order')
              <div class="text-danger">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-3 col-md-4">
            <label class="form-label" for="since">Since <span class="text-danger">*</span></label>
            <select class="form-control" name="since" id="since">
              <option {{old('since')==null&&$remarketing->since=='last_from_user'?'selected':''}} {{old('since')=="last_from_user"?'selected':''}} value="last_from_user">Last message from costumer</option>
              <option {{old('since')==null&&$remarketing->since=='last_from_page'?'selected':''}} {{old('since')=="last_from_page"?'selected':''}} value="last_from_page">Last message from page</option>
              <option {{old('since')==null&&$remarketing->since=='conversation_start'?'selected':''}} {{old('since')=="conversation_start"?'selected':''}} value="conversation_start">Conversation starting</option>
            </select>
            @error('page')
              <div class="text-danger">{{ $message }}</div>
            @enderror
          </div>
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
        <div class="row">
          <div class="mb-3 col-md-6">
            <label class="form-label" for="photos">Photos</label><br>
            @foreach(explode(',',$remarketing->photos) as $photo)
              @if($photo != null && $photo != '')
              <label class="form-label" class="form-check m-0">
                <input type="checkbox" name="oldPhotos[]" class="form-check-input" value="{{$photo}}" checked>
                <span class="form-check-label">
                  <a href="{{$photo}}" target="_blank"><i class="align-middle me-2 fas fa-fw fa-file-image"></i>Open</a>
                </span>
              </label><br>
              @endif
            @endforeach
            <input type="file" name="photos[]" id="photos" class="form-control" multiple accept="image/*">
            @error('photos')
              <div class="text-danger">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-3 col-md-6">
            <label class="form-label" for="videos">Video</label><br>
            @foreach(explode(',',$remarketing->video) as $video)
              @if($video != null && $video != '')
              <label class="form-label" class="form-check m-0">
                <input type="checkbox" name="oldVideos[]" class="form-check-input" value="{{$video}}" checked>
                <span class="form-check-label">
                  <a href="{{$video}}" target="_blank"><i class="align-middle me-2 fas fa-fw fa-file-image"></i>Open</a>
                </span>
              </label><br>
              @endif
            @endforeach
            <input type="file" name="videos[]" id="videos" class="form-control" accept="video/*" multiple>
            @error('videos')
              <div class="text-danger">{{ $message }}</div>
            @enderror
          </div>
        </div>
        <div class="mb-3">
          <label class="form-label" for="message">Message</label>
          <textarea name="message" id="message" class="form-control">{{old('message')??$remarketing->message}}</textarea>
          @error('page')
            <div class="text-danger">{{ $message }}</div>
          @enderror
        </div>
        <div class="mb-3">
          <button type="submit" class="btn btn-primary">Edit</button>
        </div>
      </div>
    </div>
  </div>
</form>

@endsection