@extends('layouts.main')
@section('subtitle', "ARM")
@section('content')
@php
$user = Auth::user();
@endphp
<div class="col-12 col-lg-12 col-xxl-12 d-flex">
  <div class="card flex-fill">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="card-title mb-0">ARM</h5>
        <div>
        @if($user->Has_Permission("responder_create"))
        <a href="{{route('responder_create')}}" class="btn btn-primary" > Create an auto-response </a>
        @endif
        </div>
    </div>
  </div>
</div>
<div class="col-12 col-lg-12 col-xxl-12 d-flex">
  <div class="card flex-fill">
    <div class="table-responsive">
      <table class="table table-hover my-0" id="datatables-orders">
        <thead>
          <tr>
            <th class="d-xl-table-cell">Message</th>
            <th class="d-xl-table-cell">Photos/video</th>
            <th class="d-xl-table-cell">Rates</th>
            <th class="d-xl-table-cell">Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach($responders as $responder)
            <tr>
                <td class="single-line">
                    <i class="align-middle me-2 fas fa-fw fa-hashtag"></i>{{$responder->id}}<br>
                    <i class="align-middle me-2 fas fa-fw fa-ruler-vertical"></i>{{$responder->name}}<br>
                    <i class="align-middle me-2 fas fa-fw fa-calendar"></i>{{$responder->created_at}}<br>
                    @foreach($responder->Pages() as $page)
                    <i class="align-middle me-2 fas fa-fw fa-user-cog"></i>{{$page->name}}<br>
                    @endforeach
                    <i class="align-middle me-2 fas fa-fw fa-toggle-{{$responder->is_active?'on':'off'}}"></i>{{$responder->is_active?'Active':'Inactive'}}<br>
                </td>
                <td class="single-line">
                    @foreach($responder->Template() as $template)
                    <i class="align-middle me-2 fas fa-fw fa-file"></i> {{$template->Template()->name}}<br>
                    @endforeach
                </td>
                <td class="single-line">
                    Total: <span class="text-danger">{{$responder->Total()}} (100%)</span><br>
                    Responses: <span class="text-primary">{{$responder->ResponseRate()[1]}} ({{$responder->ResponseRate()[0]}}%)</span> <br>
                    Orders: <span class="text-success">{{$responder->OrderRate()[1]}} ({{$responder->OrderRate()[0]}}%)</span>
                </td>
                <td>
                    @if($user->Has_Permission('responder_edit'))
                    <a href="{{route('responder_history', $responder->id)}}" class="btn btn-secondary" > History </a>
                    @if(!$responder->is_active)
                      <button data-bs-toggle="modal" data-bs-target="#activateResponder{{$responder->id}}"  class="btn btn-success">Activate</button>
                    @else
                      <button data-bs-toggle="modal" data-bs-target="#deactivateResponder{{$responder->id}}"  class="btn btn-primary">Deactivate</button>
                    @endif
                    <a href="{{route('responder_edit', $responder->id)}}" class="btn btn-warning" >
                    Edit
                    </a>
                    @endif
                    @if($user->Has_Permission('responder_delete'))
                    <button type="button" data-bs-toggle="modal" data-bs-target="#deleteResponder{{$responder->id}}" class="btn btn-danger" >
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
@if($user->Has_Permission('responder_delete'))
@foreach($responders as $responder)
<div class="modal fade" id="deleteResponder{{$responder->id}}" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="{{route('responder_delete', $responder->id)}}" method="POST">
        @csrf
        @method('delete')
        <div class="modal-header">
            <h5 class="modal-title">Delete message {{$responder->name}} ?</h5>
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
@if(!$responder->is_active)
<div class="modal fade" id="activateResponder{{$responder->id}}" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form method="POST" action="{{route('responder_activate', $responder->id)}}">
          @csrf
          @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title">Activate message {{$responder->name}} ?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-success">Activate</button>
            </div>
      </form>
    </div>
  </div>
</div>
@else
<div class="modal fade" id="deactivateResponder{{$responder->id}}" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form method="POST" action="{{route('responder_deactivate', $responder->id)}}">
          @csrf
          @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title">Deactivate message {{$responder->name}} ?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Deactivate</button>
            </div>
      </form>
    </div>
  </div>
</div>
@endif
@endforeach
@endif
@endsection