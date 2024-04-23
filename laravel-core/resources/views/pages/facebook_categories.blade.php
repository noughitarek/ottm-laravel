@extends('layouts.main')
@section('subtitle', "Accounts")
@section('content')
@php
$user = Auth::user();
@endphp
<div class="col-12 col-lg-12 col-xxl-12 d-flex">
  <div class="card flex-fill">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="card-title mb-0">Accounts</h5>
        @if($user->Has_Permission("accounts_create"))
        <div>
            <button data-bs-toggle="modal" data-bs-target="#createCategory" class="btn btn-secondary" > Create a category </button>
            <button data-bs-toggle="modal" data-bs-target="#createAccount" class="btn btn-primary" > Create an account </button>
        </div>
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
            <th class="d-xl-table-cell">Account</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
            @foreach($categories as $category)
            <tr>
                <td class="d-xl-table-cell">
                    <a href="{{route('accounts_category', $category->id)}}"><i class="align-middle me-2 fas fa-fw fa-hashtag"></i> {{$category->id}}</a><br>
                    <i class="align-middle me-2 fas fa-fw fa-box"></i> {{$category->name}}<br>
                    <i class="align-middle me-2 fas fa-fw fa-calendar"></i> {{$category->created_at}}<br>
                </td>
                <td>
                  @if($user->Has_Permission('accounts_create'))
                  <button data-bs-toggle="modal" data-bs-target="#editCategory{{$category->id}}" class="btn btn-warning" >
                    Edit
                  </button>
                  @endif
                  @if($user->Has_Permission('accounts_create'))
                  <button data-bs-toggle="modal" data-bs-target="#deleteCategory{{$category->id}}" class="btn btn-danger" >
                    Delete
                  </button>
                  @endif
                </td>
            </tr>
            @endforeach
        </tbody>
      </table>
    </div>
    {{ $categories->links('components.pagination') }}
  </div>
</div>
@if($user->Has_Permission('accounts_create'))
<div class="modal fade" id="createCategory" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="{{route('accounts_category_create')}}" method="POST">
        @csrf
        <div class="modal-header">
            <h5 class="modal-title">Create a category</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body m-3">
          <div class="mb-3">
            <label class="form-label">Name: </label>
            <input type="text" name="name" class="form-control" placeholder="Ex: rb-livraison">
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
<div class="modal fade" id="createAccount" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="{{route('accounts_create')}}" method="POST">
        @csrf
        <div class="modal-header">
            <h5 class="modal-title">Create an account</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body m-3">
          <div class="mb-3">
            <label class="form-label">ID: </label>
            <input type="text" name="id" class="form-control" placeholder="Ex: 10005465456421">
          </div>
          <div class="mb-3">
            <label class="form-label">Name: </label>
            <input type="text" name="name" class="form-control" placeholder="Ex: Hamza">
          </div>
          <div class="mb-3">
            <label class="form-label">Category: <span class="text-danger">*</span></label>
            <select name="category" class="form-control">
              <option value>Select category</option>
              @foreach($all_categories as $category)
              <option value="{{$category->id}}">{{$category->name}}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Username: <span class="text-danger">*</span></label>
            <input type="text" name="username" class="form-control" placeholder="Ex: hamza@proton.me">
          </div>
          <div class="mb-3">
            <label class="form-label">Password: <span class="text-danger">*</span></label>
            <input type="password" name="pwd" class="form-control" placeholder="Ex: ********">
          </div>
          <div class="mb-3">
            <label class="form-label">Email's password:</label>
            <input type="password" name="email_pwd" class="form-control" placeholder="Ex: ********">
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
@if($user->Has_Permission('accounts_edit'))
@foreach($categories as $category)
<div class="modal fade" id="editCategory{{$category->id}}" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="{{route('accounts_category_edit', $category->id)}}" method="POST">
        @csrf
        @method('put')
        <div class="modal-header">
            <h5 class="modal-title">Edit account {{$category->name}}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body m-3">
          <div class="mb-3">
            <label class="form-label">Name: </label>
            <input type="text" name="name" value="{{$category->name}}" class="form-control" placeholder="Ex: rb-livraison">
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
@if($user->Has_Permission('accounts_delete'))
@foreach($categories as $category)
<div class="modal fade" id="deleteCategory{{$category->id}}" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="{{route('accounts_category_delete', $category->id)}}" method="POST">
        @csrf
        @method('delete')
        <div class="modal-header">
            <h5 class="modal-title">Delete account {{$category->name}} ?</h5>
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