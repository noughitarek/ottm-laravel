@extends('layouts.main')
@section('subtitle', "Desks")
@section('content')
@php
$user = Auth::user();
@endphp
<div class="col-12 col-lg-12 col-xxl-12 d-flex">
  <div class="card flex-fill">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="card-title mb-0">Desks</h5>
        @if($user->Has_Permission("desks_create"))
        <button data-bs-toggle="modal" data-bs-target="#createDesk" class="btn btn-primary" > Create a desk </button>
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
            <th class="d-xl-table-cell">Desk</th>
            <th class="d-xl-table-cell">API</th>
            <th class="d-xl-table-cell">Orders</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
            @foreach($desks as $desk)
            <tr>
                <td class="d-xl-table-cell">
                    <i class="align-middle me-2 fas fa-fw fa-hashtag"></i> {{$desk->id}}<br>
                    <i class="align-middle me-2 fas fa-fw fa-box"></i> {{$desk->name}}<br>
                </td>
                <td>
                    <i class="align-middle me-2 fas fa-fw fa-hashtag"></i> {{$desk->ecotrack_link}}<br>
                    <i class="align-middle me-2 fas fa-fw fa-box"></i> {{$desk->ecotrack_token}}<br>
                </td>
                <td>
                    <span class="text-primary">0</span> |
                    <span class="text-success">0</span> |
                    <span class="text-danger">0</span> 
                </td>
                <td>
                  @if($user->Has_Permission('desks_edit'))
                  <button data-bs-toggle="modal" data-bs-target="#editDesk{{$desk->id}}" class="btn btn-warning" >
                    Edit
                  </button>
                  @endif
                  @if($user->Has_Permission('desks_delete'))
                  <button data-bs-toggle="modal" data-bs-target="#deleteDesk{{$desk->id}}" class="btn btn-danger" >
                    Delete
                  </button>
                  @endif
                </td>
            </tr>
            @endforeach
        </tbody>
      </table>
    </div>
    {{ $desks->links('components.pagination') }}
  </div>
</div>
@if($user->Has_Permission('desks_create'))
<div class="modal fade" id="createDesk" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="{{route('desks_create')}}" method="POST">
        @csrf
        <div class="modal-header">
            <h5 class="modal-title">Create a desk</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body m-3">
          <div class="mb-3">
            <label class="form-label">Name: </label>
            <input type="text" name="name" class="form-control" placeholder="Ex: rb-livraison">
          </div>
          <div class="mb-3">
            <label class="form-label">Ecotrack link: </label>
            <input type="text" name="ecotrack_link" class="form-control" placeholder="Ex: rblivraison.ecotrack.dz">
          </div>
          <div class="mb-3">
            <label class="form-label">Ecotrack token: </label>
            <input type="text" name="ecotrack_token" class="form-control" placeholder="Ex: 5et45ssdg135rd1gv23df">
          </div>
          <div class="mb-3 form-check">
              <input type="checkbox" name="default_stock" class="form-check-input" id="default_stock">
              <label class="form-check-label" for="default_stock">Default from stock</label>
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
@if($user->Has_Permission('desks_edit'))
@foreach($desks as $desk)
<div class="modal fade" id="editDesk{{$desk->id}}" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="{{route('desks_edit', $desk->id)}}" method="POST">
        @csrf
        @method('put')
        <div class="modal-header">
            <h5 class="modal-title">Edit desk {{$desk->name}}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body m-3">
          <div class="mb-3">
            <label class="form-label">Name: </label>
            <input type="text" name="name" value="{{$desk->name}}" class="form-control" placeholder="Ex: rb-livraison">
          </div>
          <div class="mb-3">
            <label class="form-label">Ecotrack link: </label>
            <input type="text" name="ecotrack_link" value="{{$desk->ecotrack_link}}" class="form-control" placeholder="Ex: rblivraison.ecotrack.dz">
          </div>
          <div class="mb-3">
            <label class="form-label">Ecotrack token: </label>
            <input type="text" name="ecotrack_token" value="{{$desk->ecotrack_token}}" class="form-control" placeholder="Ex: 5et45ssdg135rd1gv23df">
          </div>
          <div class="mb-3 form-check">
              <input type="checkbox" name="default_stock" class="form-check-input" id="default_stock" {{$desk->default_stock?"checked":""}}>
              <label class="form-check-label" for="default_stock">Default from stock</label>
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
@if($user->Has_Permission('desks_delete'))
@foreach($desks as $desk)
<div class="modal fade" id="deleteDesk{{$desk->id}}" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="{{route('desks_delete', $desk->id)}}" method="POST">
        @csrf
        @method('delete')
        <div class="modal-header">
            <h5 class="modal-title">Delete desk {{$desk->name}} ?</h5>
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