@extends('layouts.main')
@section('subtitle', "Settings")
@section('content')
@php
$user = Auth::user();
@endphp
<form action="{{route('settings_edit')}}" method="POST" enctype="multipart/form-data">
@csrf
<div class="row">
  <div class="col-12 col-lg-12 col-xxl-12 d-flex">
    <div class="card flex-fill">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Settings</h5>
        @if($user->Has_Permission('users_edit'))
		<button type="submit" class="btn btn-primary">Save changes</button>
		@endif
      </div>
    </div>
  </div>
    <div class="col-md-3 col-xl-2">
        <div class="card">
			<div class="card-header">
				<h5 class="card-title mb-0">Settings</h5>
			</div>
			<div class="list-group list-group-flush" role="tablist">
				<a class="list-group-item list-group-item-action active" data-bs-toggle="list" href="#general" role="tab">
					General
				</a>
				<a class="list-group-item list-group-item-action" data-bs-toggle="list" href="#facebook" role="tab">
					Facebook
				</a>
				<a class="list-group-item list-group-item-action" data-bs-toggle="list" href="#notifications" role="tab">
					Notifications
				</a>
			</div>
		</div>
	</div>
	<div class="col-md-9 col-xl-10">
		<div class="tab-content">
			<div class="tab-pane fade show active" id="general" role="tabpanel">
				<div class="card">
					<div class="card-header">
						<h5 class="card-title mb-0">General</h5>
					</div>
					<div class="card-body">
						<div class="row mb-2">
							<div class="col-md-12">
								<div class="mb-3">
									<label class="form-label" for="id">ID</label>
									<input type="text" class="form-control" name="id" id="id" value="{{config('settings.id')}}">
								</div>
								<div class="mb-3">
									<label class="form-label" for="title">Title</label>
									<input type="text" class="form-control" name="title" id="title" value="{{config('settings.title')}}">
								</div>
							</div>
						</div>
                        @if($user->Has_Permission('settings_edit'))
						<button type="submit" class="btn btn-primary">Save changes</button>
						@endif
					</div>
				</div>
			</div>
			<div class="tab-pane fade" id="facebook" role="tabpanel">
				<div class="card">
					<div class="card-header">
						<h5 class="card-title mb-0">Facebook</h5>
					</div>
					<div class="card-body mb-2">
						<div class="row mb-2 d-flex align-items-center">
							<div class="col-md-12">
								<div class="mb-3">
									<label for="username">Username</label>
									<input type="text" class="form-control" name="notifications_username" id="username" value="{{config('settings.notifications_username')}}">
								</div>
								<div class="mb-3">
									<label for="password">Password</label>
									<input type="text" class="form-control" name="notifications_password" id="password" value="{{config('settings.notifications_password')}}">
								</div>
								<div class="mb-3">
									<label for="api_token">Api token</label>
									<input type="text" class="form-control" name="notifications_api_token" id="api_token" value="{{config('settings.notifications_api_token')}}">
								</div>
								<div class="mb-3">
									<label for="package">Package</label>
									<input type="text" class="form-control" name="notifications_package" id="package" value="{{config('settings.notifications_package')}}">
								</div>
							</div>
						</div>
					</div>
				</div>
                @if($user->Has_Permission('settings_edit'))
				<button type="submit" class="btn btn-primary">Save changes</button>
				@endif
			</div>
			<div class="tab-pane fade" id="notifications" role="tabpanel">
				<div class="card">
					<div class="card-header">
						<h5 class="card-title mb-0">Notifications</h5>
					</div>
					<div class="card-body mb-2">
						<div class="row mb-2 d-flex align-items-center">
							<div class="col-md-12">
								<div class="mb-3">
									<label for="username">Username</label>
									<input type="text" class="form-control" name="notifications_username" id="username" value="{{config('settings.notifications_username')}}">
								</div>
								<div class="mb-3">
									<label for="password">Password</label>
									<input type="text" class="form-control" name="notifications_password" id="password" value="{{config('settings.notifications_password')}}">
								</div>
								<div class="mb-3">
									<label for="api_token">Api token</label>
									<input type="text" class="form-control" name="notifications_api_token" id="api_token" value="{{config('settings.notifications_api_token')}}">
								</div>
								<div class="mb-3">
									<label for="package">Package</label>
									<input type="text" class="form-control" name="notifications_package" id="package" value="{{config('settings.notifications_package')}}">
								</div>
							</div>
						</div>
					</div>
				</div>
                @if($user->Has_Permission('settings_edit'))
				<button type="submit" class="btn btn-primary">Save changes</button>
				@endif
			</div>
		</div>
	</div>
</form>
@endsection