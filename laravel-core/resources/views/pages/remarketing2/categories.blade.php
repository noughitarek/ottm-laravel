@extends('layouts.main')
@section('subtitle', "RTM")
@section('content')
@php
$user = Auth::user();
@endphp
<div class="col-12 col-lg-12 col-xxl-12 d-flex">
  <div class="card flex-fill">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="card-title mb-0">RTM</h5>
        <div>
        @if($user->Has_Permission("remarketing_create"))
        <a href="{{route('remarketing_interval_create')}}" class="btn btn-primary" > Create a message </a>
        @endif
        </div>
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
            @foreach($categories as $category)
            <tr>
                <td class="d-xl-table-cell">
                    <a href="{{route('remarketing_interval_category', $category->id)}}" >
                    <i class="align-middle me-2 fas fa-fw fa-hashtag"></i> {{$category->id}}<br>
                    <i class="align-middle me-2 fas fa-fw fa-box"></i> {{$category->name}}<br>
                    <i class="align-middle me-2 fas fa-fw fa-barcode"></i> {{$category->slug}}<br>
                    </a>
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
@endsection