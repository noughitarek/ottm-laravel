@extends('layouts.main')
@section('subtitle', "Users")
@section('content')
@php
$user = Auth::user();
@endphp
<div class="col-12 col-lg-12 col-xxl-12 d-flex">
  <div class="card flex-fill">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="card-title mb-0">{{$user->name}}</h5>
    </div>
  </div>
</div>
<div class="col-md-12">
  <div class="card">
    <div class="card-header">
      <h5 class="card-title">Profile information</h5>
    </div>
    <div class="card-body">
      <form method="post" action="{{ route('profile.update') }}">
        @csrf
        @method('patch')
        <div class="mb-3">
          <label class="form-label">Name</label>
          <input type="text" name="name" class="form-control" placeholder="Ex: Tarek" value="{{$user->name}}">
        </div>
        <div class="mb-3">
          <label class="form-label">Email</label>
          <input type="text" name="email" class="form-control" placeholder="Ex: noughitarek@gmail.com" value="{{$user->email}}">
        </div>
        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>
<div class="col-md-12">
  <div class="card">
    <div class="card-header">
      <h5 class="card-title">Profile password</h5>
    </div>
    <div class="card-body">
      <form method="post" action="{{ route('profile.update') }}">
        @csrf
        @method('patch')
        <div class="mb-3">
          <label class="form-label">Current Password</label>
          <input type="password" name="current_password" class="form-control" placeholder="Ex: password">
        </div>
        <div class="mb-3">
          <label class="form-label">New Password</label>
          <input type="password" name="password" class="form-control" placeholder="Ex: new password">
        </div>
        <div class="mb-3">
          <label class="form-label">Confirm Password</label>
          <input type="password" name="password_confirmation" class="form-control" placeholder="Ex: new password">
        </div>
        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection