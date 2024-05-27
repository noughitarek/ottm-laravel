@extends('layouts.main')
@section('subtitle', "Pages")
@section('content')
@php
$user = Auth::user();
@endphp
<div class="col-12 col-lg-12 col-xxl-12 d-flex">
  <div class="card flex-fill">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="card-title mb-0">Pages</h5>
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
            <th class="d-xl-table-cell">Total messages</th>
            <th class="d-xl-table-cell">Orders</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
            @foreach($facebook_pages as $facebook_page)
            <tr>
                <td class="d-xl-table-cell">
                    <a href="{{route('conversations_page', $facebook_page->facebook_page_id)}}"><i class="align-middle me-2 fas fa-fw fa-hashtag"></i> {{$facebook_page->facebook_page_id}}</a><br>
                    <i class="align-middle me-2 fas fa-fw fa-user"></i> {{$facebook_page->name}}<br>
                </td>
                <td>
                {{$facebook_page->Messages_Count()}} message on 
                {{$facebook_page->Conversations_Count()}} conversation
                </td>
                <td>
                {{$facebook_page->Orders()}}
                </td>
                <td>
                </td>
            </tr>
            @endforeach
        </tbody>
      </table>
    </div>
    {{ $facebook_pages->links('components.pagination') }}
  </div>
</div>
@endsection