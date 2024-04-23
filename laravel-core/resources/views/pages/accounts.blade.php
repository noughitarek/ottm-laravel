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
            <th class="d-xl-table-cell">Credentials</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
            @foreach($accounts as $account)
            <tr>
                <td class="d-xl-table-cell">
                    <i class="align-middle me-2 fas fa-fw fa-hashtag"></i> {{$account->id}}<br>
                    <i class="align-middle me-2 fas fa-fw fa-box"></i> {{$account->name}}<br>
                    <i class="align-middle me-2 fas fa-fw fa-calendar"></i> {{$account->created_at}}<br>
                </td>
                <td class="d-xl-table-cell">
                    <i class="align-middle me-2 fas fa-fw fa-user-alt"></i> {{$account->username}}<br>
                    <i class="align-middle me-2 fas fa-fw fa-user-shield"></i> {{md5($account->pwd)}}<br>
                    <i class="align-middle me-2 fas fa-fw fa-store"></i> {{$account->marketplace_at==null?"W":"I"}}
                </td>
                <td>
                  @if($user->Has_Permission('accounts_edit'))
                  <button data-bs-toggle="modal" data-bs-target="#editAccount{{$account->id}}" class="btn btn-warning" >
                    Edit
                  </button>
                  @endif
                  @if($user->Has_Permission('accounts_delete'))
                  <button data-bs-toggle="modal" data-bs-target="#deleteAccount{{$account->id}}" class="btn btn-danger" >
                    Delete
                  </button>
                  @endif
                </td>
            </tr>
            @endforeach
        </tbody>
      </table>
    </div>
    {{ $accounts->links('components.pagination') }}
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
@foreach($accounts as $account)
<div class="modal fade" id="editAccount{{$account->id}}" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="{{route('accounts_edit', $account->id)}}" method="POST">
        @csrf
        @method('put')
        <div class="modal-header">
            <h5 class="modal-title">Edit account {{$account->name}}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body m-3">
          <div class="mb-3">
            <label class="form-label">ID: </label>
            <input type="text" name="id" class="form-control" value="{{$account->id}}" placeholder="Ex: 10005465456421">
          </div>
          <div class="mb-3">
            <label class="form-label">Name: </label>
            <input type="text" name="name" class="form-control" value="{{$account->name}}" placeholder="Ex: Hamza">
          </div>
          <div class="mb-3">
            <label class="form-label">Category: <span class="text-danger">*</span></label>
            <select name="category" class="form-control">
              <option value>Select category</option>
              @foreach($all_categories as $category)
              <option {{$category->id==$account->category?'selected':''}} value="{{$category->id}}">{{$category->name}}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Username: <span class="text-danger">*</span></label>
            <input type="text" name="username" class="form-control" value="{{$account->username}}" placeholder="Ex: hamza@proton.me">
          </div>
          <div class="mb-3">
            <label class="form-label">Password: <span class="text-danger">*</span></label>
            <input type="password" name="pwd" class="form-control" value="{{$account->pwd}}" placeholder="Ex: ********">
          </div>
          <div class="mb-3">
            <label class="form-label">Email's password:</label>
            <input type="password" name="email_pwd" class="form-control" value="{{$account->email_pwd}}" placeholder="Ex: ********">
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
@foreach($accounts as $account)
<div class="modal fade" id="deleteAccount{{$account->id}}" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="{{route('accounts_delete', $account->id)}}" method="POST">
        @csrf
        @method('delete')
        <div class="modal-header">
            <h5 class="modal-title">Delete account {{$account->name}} ?</h5>
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