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
        @if($user->Has_Permission("remarketing_create"))
        <a href="{{route('remarketing_create')}}" class="btn btn-primary" > Create a message </a>
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
            <th class="d-xl-table-cell">Message</th>
            <th class="d-xl-table-cell">Algorithme</th>
            <th class="d-xl-table-cell">Photos/video</th>
            <th class="d-xl-table-cell">Message</th>
            <th class="d-xl-table-cell">Action</th>
          </tr>
        </thead>
        <tbody>
        @foreach($remarketings as $remarketing)
        <tr>
          <td class="single-line">
            <i class="align-middle me-2 fas fa-fw fa-hashtag"></i>{{$remarketing->id}}<br>
            <i class="align-middle me-2 fas fa-fw fa-ruler-vertical"></i>{{$remarketing->name}}<br>
            <i class="align-middle me-2 fas fa-fw fa-calendar"></i>{{$remarketing->created_at}}<br>
            @foreach($remarketing->Pages() as $page)
            <i class="align-middle me-2 fas fa-fw fa-user-cog"></i>{{$page->name}}<br>
            @endforeach
          </td>
          <td class="single-line">
            <i class="align-middle me-2 fas fa-fw fa-robot"></i>Send it after <b>{{$remarketing->Send_After()}}</b><br>
            <i class="align-middle me-2 fas fa-fw"></i>if last message from <b>{{($remarketing->last_message_from=="any"?"Any":($remarketing->last_message_from=="page"?"Page":"User"))}}</b> <br>
            <i class="align-middle me-2 fas fa-fw"></i>and if <b>{{($remarketing->make_order?"the customer didn't make an order":"any")}}</b> <br>
            <i class="align-middle me-2 fas fa-fw"></i>since <b>{{($remarketing->since=='last_from_user'?"the last message from costumer":($remarketing->since=='last_from_page'?"the last message from page":"the first message of the conversation"))}}</b> <br>
          </td>
          <td class="single-line">
            @foreach(explode(',',$remarketing->photos) as $photo)
            @if($photo != "")
              <a href="{{$photo}}" target="_blank"><i class="align-middle me-2 fas fa-fw fa-file-image"></i>Open</a><br>
            @endif
            @endforeach
            @foreach(explode(',',$remarketing->video) as $video)
            @if($photo != "")
              <a href="{{$video}}" target="_blank"><i class="align-middle me-2 fas fa-fw fa-file-video"></i>Open</a><br>
            @endif
            @endforeach
          </td>
          <td class="single-line">
            {{$remarketing->message}}
          </td>
          <td>
            @if($user->Has_Permission('remarketing_edit'))
            <a href="{{route('remarketing_edit', $remarketing->id)}}" class="btn btn-warning" >
              Edit
            </a>
            @endif
            @if($user->Has_Permission('remarketing_delete'))
            <button data-bs-toggle="modal" data-bs-target="#deleteRemarketing{{$remarketing->id}}" class="btn btn-danger" >
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

@if($user->Has_Permission('remarketing_delete'))
@foreach($remarketings as $remarketing)
<div class="modal fade" id="deleteRemarketing{{$remarketing->id}}" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="{{route('remarketing_delete', $remarketing->id)}}" method="POST">
        @csrf
        @method('delete')
        <div class="modal-header">
            <h5 class="modal-title">Delete message {{$remarketing->name}} ?</h5>
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