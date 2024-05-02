@extends('layouts.main')
@section('subtitle', "Group joiners")
@section('content')
@php
$user = Auth::user();
@endphp
<div class="col-12 col-lg-12 col-xxl-12 d-flex">
  <div class="card flex-fill">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="card-title mb-0">Group joiners</h5>
        @if($user->Has_Permission("group_joiner_create"))
        <div>
            <a href="{{route('group_joiner_create')}}" class="btn btn-primary" > Create a joiner </a>
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
            <th class="d-xl-table-cell">Categories</th>
            <th class="d-xl-table-cell">Keywords</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
            @foreach($group_joiners as $group_joiner)
            <tr>
                <td class="d-xl-table-cell">
                    <i class="align-middle me-2 fas fa-fw fa-hashtag"></i> {{$group_joiner->id}}<br>
                    <i class="align-middle me-2 fas fa-fw fa-box"></i> {{$group_joiner->name}}<br>
                    <i class="align-middle me-2 fas fa-fw fa-calendar"></i> {{$group_joiner->created_at}}<br>
                </td>
                <td class="d-xl-table-cell">
                  @foreach($group_joiner->Categories() as $category)
                    <i class="align-middle me-2 fas fa-fw fa-grip-horizontal"></i> {{$category->name}}<br>
                  @endforeach
                </td>
                <td class="d-xl-table-cell">
                  @foreach(explode(',', $group_joiner->keywords) as $keyword)
                    <i class="align-middle me-2 fas fa-fw fa-key"></i> {{$keyword}}<br>
                  @endforeach
                </td>
                <td>
                  @if($user->Has_Permission('group_joiner_history'))
                  <a href="{{route('group_joiner_history', $group_joiner->id)}}" class="btn btn-success" >
                    History
                  </a>
                  @endif
                  @if($user->Has_Permission('group_joiner_edit'))
                  <a href="{{route('group_joiner_edit', $group_joiner->id)}}" class="btn btn-warning" >
                    Edit
                  </a>
                  @endif
                  @if($user->Has_Permission('group_joiner_delete'))
                  <button data-bs-toggle="modal" data-bs-target="#deleteJoiner{{$group_joiner->id}}" class="btn btn-danger" >
                    Delete
                  </button>
                  @endif
                </td>
            </tr>
            @endforeach
        </tbody>
      </table>
    </div>
    {{ $group_joiners->links('components.pagination') }}
  </div>
</div>
@if($user->Has_Permission('group_joiner_delete'))
@foreach($group_joiners as $group_joiner)
<div class="modal fade" id="deleteJoiner{{$group_joiner->id}}" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="{{route('group_joiner_delete', $group_joiner->id)}}" method="POST">
        @csrf
        @method('delete')
        <div class="modal-header">
            <h5 class="modal-title">Delete joiner {{$group_joiner->name}} ?</h5>
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