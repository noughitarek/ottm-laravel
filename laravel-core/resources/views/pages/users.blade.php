@extends('layouts.main')
@section('subtitle', "Users")
@section('content')
@php
$user = Auth::user();
@endphp
<div class="col-12 col-lg-12 col-xxl-12 d-flex">
  <div class="card flex-fill">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="card-title mb-0">Users</h5>
      @if($user->Has_Permission('users_create'))
      <button data-bs-toggle="modal" data-bs-target="#createUser" class="btn btn-primary" > Create a user </button>
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
            <th class="d-xl-table-cell">Permissions</th>
            <th class="d-xl-table-cell">Orders</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($users as $listuser)
          <tr>
              <td class="d-xl-table-cell">
                  <i class="align-middle me-2 fas fa-fw fa-hashtag"></i> {{$listuser->id}}<br>
                  <i class="align-middle me-2 fas fa-fw fa-box"></i> {{$listuser->name}}<br>
                  <i class="align-middle me-2 fas fa-fw fa-at"></i> {{$listuser->email}}<br>
              </td>
              <td>
              <i class="align-middle me-2 fas fa-fw fa-users-cog"></i> {{$listuser->role}}<br>
              @foreach(config('sidemenu') as $menu)
                @foreach(explode(',', $listuser->permissions) as $permission)
                  @if(isset($menu['section']) && $menu['section'] == explode('_',$permission)[0])
                  <span title="{{$permission}}" data-bs-toggle="tooltip" data-bs-placement="left">
                    <i class="align-middle text-success" data-feather="{{$menu['icon']['content']}}"></i>
                  </span>
                  @elseif(isset($menu['section']) && $menu['section'] == explode('_',$permission)[0])
                  <span title="{{$permission}}" data-bs-toggle="tooltip" data-bs-placement="left">
                    <i class="align-middle text-danger" data-feather="{{$menu['icon']['content']}}"></i>
                  </span>
                  @break
                  @endif
                @endforeach
              @endforeach
              </td>
              <td>
                  <span class="text-primary">0</span> |
                  <span class="text-success">0</span> |
                  <span class="text-danger">0</span> 
              </td>
              <td>
                @if($user->Has_Permission('users_edit'))
                  <button data-bs-toggle="modal" data-bs-target="#editUser{{$listuser->id}}" class="btn btn-warning" >
                    Edit
                  </button>
                  @endif
                @if($user->Has_Permission('users_delete'))
                  <button data-bs-toggle="modal" data-bs-target="#deleteUser{{$listuser->id}}" class="btn btn-danger" >
                    Delete
                  </button>
                  @endif
              </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
@if($user->Has_Permission('users_create'))
<div class="modal fade" id="createUser" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="{{route('users_create')}}" method="POST">
        @csrf
        <div class="modal-header">
            <h5 class="modal-title">Create a user</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body m-3">
          <div class="mb-3">
            <label class="form-label">Name <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" placeholder="Ex: Tarek">
          </div>
          <div class="mb-3">
            <label class="form-label">Email <span class="text-danger">*</span></label>
            <input type="email" name="email" class="form-control" placeholder="Ex: noughitarek@gmail.com">
          </div>
          <div class="mb-3">
            <label class="form-label">Password <span class="text-danger">*</span></label>
            <input type="password" name="password" class="form-control" placeholder="Ex: password">
          </div>
          <div class="mb-3">
            <label class="form-label">Role <span class="text-danger">*</span></label>
            <input type="text" name="role" class="form-control" placeholder="Ex: Chief Executive Officer">
          </div>
          <div class="mb-3">
            <label class="form-label">Permissions <span class="text-danger">*</span></label>
            @foreach(config('permissions') as $section => $permissions)
              <br>
              {{ucfirst($section)}}:
              @foreach($permissions as $permission)
              <label class="form-label" class="form-check m-0">
                <input type="checkbox" name="permissions[]" id="permissions" value="{{$section}}_{{$permission}}" class="form-check-input" checked>
                <span class="form-check-label">{{$permission}}</span>
              </label>
              @endforeach
            @endforeach
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
@if($user->Has_Permission('users_edit'))
@foreach($users as $listuser)
<div class="modal fade" id="editUser{{$listuser->id}}" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="{{route('users_edit', $listuser->id)}}" method="POST">
        @csrf
        @method("PUT")
        <div class="modal-header">
            <h5 class="modal-title">Edit a user</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body m-3">
          <div class="mb-3">
            <label class="form-label">Name <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" placeholder="Ex: Tarek" value="{{$listuser->name}}">
          </div>
          <div class="mb-3">
            <label class="form-label">Email <span class="text-danger">*</span></label>
            <input type="email" name="email" class="form-control" placeholder="Ex: noughitarek@gmail.com" value="{{$listuser->email}}">
          </div>
          <div class="mb-3">
            <label class="form-label">Role <span class="text-danger">*</span></label>
            <input type="text" name="role" class="form-control" placeholder="Ex: Chief Executive Officer" value="{{$listuser->role}}">
          </div>
          <div class="mb-3">
            <label class="form-label">Permissions <span class="text-danger">*</span></label>
            @foreach(config('permissions') as $section => $permissions)
              <br>
              {{ucfirst($section)}}:
              @foreach($permissions as $permission)
              <label class="form-label" class="form-check m-0">
                <input type="checkbox" name="permissions[]" id="permissions" value="{{$section}}_{{$permission}}" class="form-check-input" {{$listuser->Has_Permission($section.'_'.$permission)?"checked":""}}>
                <span class="form-check-label">{{$permission}}</span>
              </label>
              @endforeach
            @endforeach
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
@if($user->Has_Permission('users_delete'))
@foreach($users as $listuser)
<div class="modal fade" id="deleteUser{{$listuser->id}}" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="{{route('users_delete', $listuser->id)}}" method="POST">
        @csrf
        @method('delete')
        <div class="modal-header">
            <h5 class="modal-title">Delete user {{$listuser->name}} ?</h5>
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