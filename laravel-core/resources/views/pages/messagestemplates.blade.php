@extends('layouts.main')
@section('subtitle', "Messages Templates")
@section('content')
@php
$user = Auth::user();
@endphp

<div class="col-12 col-lg-12 col-xxl-12 d-flex">
  <div class="card flex-fill">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="card-title mb-0">Messages Templates</h5>
      @if($user->Has_Permission('messagestemplates_create'))
      <button data-bs-toggle="modal" data-bs-target="#createTemplate" class="btn btn-primary" > Create a template </button>
      @endif
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
            <th class="d-xl-table-cell">Assets</th>
            <th class="d-xl-table-cell">Rates</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($messagestemplates as $template)
          <tr>
              <td class="d-xl-table-cell">
                  <i class="align-middle me-2 fas fa-fw fa-hashtag"></i> {{$template->id}}<br>
                  <i class="align-middle me-2 fas fa-fw fa-box"></i> {{$template->name}}<br>
              </td>
              <td class="single-line">
                @foreach(explode(',',$template->photos) as $photo)
                @if($photo!= null && $photo != "")
                  <a href="{{$photo}}" target="_blank"><i class="align-middle me-2 fas fa-fw fa-file-image"></i>{{explode('/', $photo)[count(explode('/', $photo))-1]}}</a><br>
                @endif
                @endforeach
                @foreach(explode(',',$template->video) as $video)
                @if($video!= null && $video != "")
                  <a href="{{$video}}" target="_blank"><i class="align-middle me-2 fas fa-fw fa-file-video"></i>{{explode('/', $video)[count(explode('/', $video))-1]}}</a><br>
                @endif
                @endforeach
                @foreach(explode(',',$template->audios) as $audio)
                @if($audio!= null && $audio != "")
                  <a href="{{$audio}}" target="_blank"><i class="align-middle me-2 fas fa-fw fa-file-audio"></i>{{explode('/', $audio)[count(explode('/', $audio))-1]}}</a><br>
                @endif
                @endforeach
                @if($template->message!="")
                  <i class="align-middle me-2 fas fa-fw fa-file-text"></i><p id="message{{$template->id}}">{{$template->message}}</p>
                @endif
              </td>
              <td class="single-line">
                Total: <span class="text-danger">{{$template->Total()}} (100%)</span><br>
                Responses: <span class="text-primary">{{$template->ResponseRate()[1]}} ({{$template->ResponseRate()[0]}}%)</span> <br>
                Orders: <span class="text-success">{{$template->OrderRate()[1]}} ({{$template->OrderRate()[0]}}%)</span>
              </td>
              <td>
                  <button onclick="copyText('message{{$template->id}}')" class="btn btn-success" >
                    Copy
                  </button>
                @if($user->Has_Permission('messagestemplates_edit'))
                  <button data-bs-toggle="modal" data-bs-target="#editTemplate{{$template->id}}" class="btn btn-warning" >
                    Edit
                  </button>
                  @endif
                @if($user->Has_Permission('messagestemplates_delete'))
                  <button data-bs-toggle="modal" data-bs-target="#deleteTemplate{{$template->id}}" class="btn btn-danger" >
                    Delete
                  </button>
                  @endif
              </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    {{ $messagestemplates->links('components.pagination') }}
  </div>
</div>
@if($user->Has_Permission('messagestemplates_create'))
<div class="modal fade" id="createTemplate" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="{{route('messagestemplates_create')}}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-header">
            <h5 class="modal-title">Create a template</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body m-3">
            <div class="mb-3">
                <label class="form-label" for="name">Name <span class="text-danger">*</span></label>
                <input type="text" name="name" id="name" class="form-control" value="{{old('name')}}" required>
                @error('name')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="row">
            <div class="mb-3 col-md-4">
                <label class="form-label" for="photos">Photos</label>
                <input type="file" name="photos[]" id="photos" class="form-control" multiple accept="image/jpeg">
                @error('photos')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3 col-md-4">
                <label class="form-label" for="videos">Videos</label>
                <input type="file" name="videos[]" id="videos" class="form-control" accept="video/mp4" multiple>
                @error('videos')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3 col-md-4">
                <label class="form-label" for="audios">Audios</label>
                <input type="file" name="audios[]" id="audios" class="form-control" accept="audio/mp3" multiple>
                @error('audios')
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
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Create</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endif
@if($user->Has_Permission('messagestemplates_edit'))
@foreach($messagestemplates as $template)
<div class="modal fade" id="editTemplate{{$template->id}}" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="{{route('messagestemplates_edit', $template->id)}}" method="POST" enctype="multipart/form-data">
        @csrf
        @method("PUT")
        <div class="modal-header">
            <h5 class="modal-title">Edit a template</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body m-3">
            <div class="mb-3">
                <label class="form-label" for="name">Name <span class="text-danger">*</span></label>
                <input type="text" name="name" id="name" class="form-control" value="{{old('name')??$template->name}}" required>
                @error('name')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="row">
            <div class="mb-3 col-md-4">
                <label class="form-label" for="photos">Photos</label><br>
                @foreach(explode(',',$template->photos) as $photo)
                  @if($photo != null && $photo != '')
                  <label class="form-label" class="form-check m-0">
                    <input type="checkbox" name="oldPhotos[]" class="form-check-input" value="{{$photo}}" checked>
                    <span class="form-check-label">
                      <a href="{{$photo}}" target="_blank"><i class="align-middle me-2 fas fa-fw fa-file-image"></i>{{explode('/', $photo)[count(explode('/', $photo))-1]}}</a>
                    </span>
                  </label><br>
                  @endif
                @endforeach
                <input type="file" name="photos[]" id="photos" class="form-control" multiple accept="image/jpeg">
                @error('photos')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3 col-md-4">
                <label class="form-label" for="videos">Videos</label><br>
                @foreach(explode(',',$template->videos) as $video)
                  @if($video != null && $video != '')
                  <label class="form-label" class="form-check m-0">
                    <input type="checkbox" name="oldVideos[]" class="form-check-input" value="{{$video}}" checked>
                    <span class="form-check-label">
                      <a href="{{$video}}" target="_blank"><i class="align-middle me-2 fas fa-fw fa-file-image"></i>{{explode('/', $video)[count(explode('/', $video))-1]}}</a>
                    </span>
                  </label><br>
                  @endif
                @endforeach
                <input type="file" name="videos[]" id="videos" class="form-control" accept="video/mp4" multiple>
                @error('videos')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3 col-md-4">
                <label class="form-label" for="audios">Audios</label><br>
                @foreach(explode(',',$template->audios) as $audio)
                  @if($photo != null && $photo != '')
                  <label class="form-label" class="form-check m-0">
                    <input type="checkbox" name="oldAudios[]" class="form-check-input" value="{{$audio}}" checked>
                    <span class="form-check-label">
                      <a href="{{$photo}}" target="_blank"><i class="align-middle me-2 fas fa-fw fa-file-image"></i>{{explode('/', $audio)[count(explode('/', $audio))-1]}}</a>
                    </span>
                  </label><br>
                  @endif
                @endforeach
                <input type="file" name="audios[]" id="audios" class="form-control" multiple accept="image/jpeg">
                @error('audios')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            </div>
            <div class="mb-3">
            <label class="form-label" for="message">Message</label>
            <textarea name="message" id="message" class="form-control">{{old('message')??$template->message}}</textarea>
            @error('message')
                <div class="text-danger">{{ $message }}</div>
            @enderror
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-warning">Edit</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endforeach
@endif
@if($user->Has_Permission('messagestemplates_delete'))
@foreach($messagestemplates as $template)
<div class="modal fade" id="deleteTemplate{{$template->id}}" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="{{route('messagestemplates_delete', $template->id)}}" method="POST">
        @csrf
        @method('delete')
        <div class="modal-header">
            <h5 class="modal-title">Delete template {{$template->name}} ?</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-danger">Delete</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endforeach
@endif
@endsection
@section('script')
<script>
  function copyText(textID) {
    var textElement = document.getElementById(textID);
    var range = document.createRange();
    range.selectNode(textElement);
    window.getSelection().removeAllRanges();
    window.getSelection().addRange(range);
    document.execCommand("copy");
    window.getSelection().removeAllRanges();
    var message = 'Copied the text';
    var type = "success";
    var duration = 5000;
    var ripple = false;
    var dismissible = true;
    var positionX = "right";
    var positionY = "top";
    window.notyf.open({type, message, duration, ripple, dismissible, position:{x: positionX, y: positionY}});
}

</script>
@endsection