@extends('layouts.main')
@section('subtitle', "Remarketing categories")
@section('content')
@php
$user = Auth::user();
@endphp
<div class="col-12 col-lg-12 col-xxl-12 d-flex">
  <div class="card flex-fill">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="card-title mb-0">Remarketing categories</h5>
        @if($user->Has_Permission('remarketing_categories_create'))
        <button data-bs-toggle="modal" data-bs-target="#createCategory" class="btn btn-primary" > Create a category </button>
        @endif
    </div>
  </div>
</div>
<div class="col-12 col-lg-12 col-xxl-12 d-flex">
  <div class="card flex-fill">
    <div class="table-responsive">
      <table class="table table-hover my-0">
        <thead>
          <tr>
            <th class="d-xl-table-cell">Category</th>
            <th class="d-xl-table-cell">Sub-categories</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
            @foreach($all_categories as $category)
            <tr>
                <td class="d-xl-table-cell">
                    <i class="align-middle me-2 fas fa-fw fa-hashtag"></i> {{$category->id}}<br>
                    <i class="align-middle me-2 fas fa-fw fa-box"></i> {{$category->name}}<br>
                    <i class="align-middle me-2 fas fa-fw fa-barcode"></i> {{$category->slug}}<br>
                </td>
                <td>
                  @foreach($category->Sub_Categories() as $sub_category)
                    <i class="align-middle me-2 fas fa-fw fa-file"></i>{{$sub_category->name}}<br>
                  @endforeach
                </td>
                <td>
                @if($category->slug != "undefined" && $category->slug != "sub-undefined")
                  @if($user->Has_Permission('remarketing_categories_edit'))
                    <button data-bs-toggle="modal" data-bs-target="#editCategory{{$category->id}}" class="btn btn-warning" >
                      Edit
                    </button>
                    @endif
                  @if($user->Has_Permission('remarketing_categories_delete'))
                  <button data-bs-toggle="modal" data-bs-target="#deleteCategory{{$category->id}}" class="btn btn-danger" >
                    Delete
                  </button>
                  @endif
                @endif
                </td>
            </tr>
            @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
@if($user->Has_Permission('remarketing_categories_create'))
<div class="modal fade" id="createCategory" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="{{route('remarketing_categories_create')}}" method="POST">
        @csrf
        <div class="modal-header">
            <h5 class="modal-title">Create a category</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body m-3">
          <div class="mb-3">
            <label class="form-label">Category's name: </label>
            <input type="text" name="name" class="form-control" placeholder="Ex: electric water pump" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Category's slug: </label>
            <input type="text" name="slug" class="form-control" placeholder="Ex: electric-water-pump" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Parent: </label>
            <select name="parent" class="form-control">
              <option value>No parent</option>
              @foreach($categories as $category)
              <option value="{{$category->id}}">{{$category->name}}</option>
              @endforeach
            </select>
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
@if($user->Has_Permission('remarketing_categories_edit'))
@foreach($all_categories as $category)
@if($category->slug != "undefined" && $category->slug != "sub-undefined")
<div class="modal fade" id="editCategory{{$category->id}}" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="{{route('remarketing_categories_edit', $category->id)}}" method="POST">
        @csrf
        @method('put')
        <div class="modal-header">
            <h5 class="modal-title">Edit category {{$category->id}}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body m-3">
          <div class="mb-3">
            <label class="form-label">Category's name: </label>
            <input type="text" name="name" value="{{$category->name}}" class="form-control" placeholder="Ex: electric water pump" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Category's slug: </label>
            <input type="text" name="slug" value="{{$category->slug}}" class="form-control" placeholder="Ex: electric-water-pump" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Parent: </label>
            <select name="parent" class="form-control">
              <option value>No parent</option>
              @foreach($categories as $sub_category)
              <option value="{{$sub_category->id}}" {{$category->parent==$sub_category->id?'selected':''}}>{{$sub_category->name}}</option>
              @endforeach
            </select>
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
@endif
@endforeach
@endif
@if($user->Has_Permission('remarketing_categories_delete'))
@if($category->slug != "undefined" && $category->slug != "sub-undefined")
@foreach($all_categories as $category)
<div class="modal fade" id="deleteCategory{{$category->id}}" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="{{route('remarketing_categories_delete', $category->id)}}" method="POST">
        @csrf
        @method('delete')
        <div class="modal-header">
            <h5 class="modal-title">Delete category {{$category->name}} ?</h5>
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
@endif
@endsection