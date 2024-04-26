@extends('layouts.main')
@section('subtitle', "Edit a joiner")
@section('content')
@php
$user = Auth::user();
@endphp
<form method="POST" action="{{route('group_joiner_edit', $joiner->id)}}" enctype="multipart/form-data">
  @csrf
  @method('put')
  <div class="row">
    <div class="col-12 col-lg-12 col-xxl-12 d-flex">
      <div class="card flex-fill">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="card-title mb-0">Edit a joiner</h5>
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
            <input type="text" name="name" id="name" value="{{old('name')??$joiner->name}}" class="form-control">
            @error('name')
              <div class="text-danger">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-3">
            <label class="form-label" for="keywords">Keywords <span class="text-danger">*</span></label>
            <input type="text" name="keywords" id="keywords" value="{{old('keywords')??$joiner->keywords}}" class="form-control">
            @error('keywords')
              <div class="text-danger">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-3">
            <label class="form-label" for="category">Category <span class="text-danger">*</span></label>
            <select name="category[]" id="category" class="form-control choices-multiple" required multiple>
                @foreach($categories as $category)
                <option {{$joiner->In_Category($category->id)?'selected':''}} value="{{$category->id}}" >{{$category->name}}</option>
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
            <div class="mb-3">
              <label class="form-label" for="join">Join <span class="text-danger">*</span></label>
              <input type="number" name="join" value="{{old('join')??$joiner->join}}" id="join" class="form-control">
              @error('join')
                <div class="text-danger">{{ $message }}</div>
              @enderror
            </div>
            <div class="mb-3 col-md-6">
              <label class="form-label" for="each">Each <span class="text-danger">*</span></label>
              <input type="number" name="each" value="{{old('each')??$joiner->each}}" id="each" class="form-control">
              @error('each')
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
            <div class="mb-3 col-md-6">
              <label class="form-label" for="start_at">Between</label>
              <input type="text" name="start_at"  data-inputmask-regex="^(?:[01][0-9]|2[0-3]):[0-5][0-9]$" required value="{{old('start_at')??$joiner->start_at}}" id="start_at" class="form-control">
              @error('start_at')
                <div class="text-danger">{{ $message }}</div>
              @enderror
            </div>
            <div class="mb-3 col-md-6">
              <label class="form-label" for="end_at">And</label>
              <input type="text" name="end_at"  data-inputmask-regex="^(?:[01][0-9]|2[0-3]):[0-5][0-9]$" required value="{{old('end_at')??$joiner->end_at}}" id="end_at" class="form-control">
              @error('end_at')
                <div class="text-danger">{{ $message }}</div>
              @enderror
            </div>
            <div class="mb-3">
              <label class="form-label" for="max_join">At maximum join <span class="text-danger">*</span></label>
              <input type="number" name="max_join" value="{{old('max_join')??$joiner->max_join}}" id="max_join" class="form-control">
              @error('max_join')
                <div class="text-danger">{{ $message }}</div>
              @enderror
            </div>
          </div>
          <div class="mb-3">
            <button type="submit" class="btn btn-primary">Edit</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</form>

@endsection
@section('script')
<script>
document.addEventListener("DOMContentLoaded", function() {
    var choices = new Choices('.choices-multiple', {
      removeItemButton: true,
    });
    var textRemove = new Choices(
          document.getElementById('keywords'),
          {
            allowHTML: true,
            delimiter: ',',
            editItems: true,
            maxItemCount: 5,
            removeItemButton: true,
          }
        );
});
</script>
@endsection