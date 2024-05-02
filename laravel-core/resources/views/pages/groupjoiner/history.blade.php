@extends('layouts.main')
@section('subtitle', "Group joins")
@section('content')
@php
$user = Auth::user();
@endphp
<div class="col-12 col-lg-12 col-xxl-12 d-flex">
  <div class="card flex-fill">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="card-title mb-0">Group joins</h5>
    </div>
  </div>
</div>
<div class="col-12 col-lg-12 col-xxl-12 d-flex">
  <div class="card flex-fill">
    <div class="table-responsive">
      <table class="table table-hover my-0" id="datatables-orders">
        <thead>
          <tr>
            <th class="d-xl-table-cell">Joins</th>
            <th class="d-xl-table-cell">Groups</th>
            <th class="d-xl-table-cell">Keywords</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
            @foreach($joins as $join)
            <tr>
                <td class="d-xl-table-cell">
                    <i class="align-middle me-2 fas fa-fw fa-hashtag"></i> {{$join->id}}<br>
                    <i class="align-middle me-2 fas fa-fw fa-box"></i> {{$join->Joiner()->name}}<br>
                    <i class="align-middle me-2 fas fa-fw fa-calendar"></i> {{$join->created_at}}<br>
                </td>
                <td class="d-xl-table-cell">
                {{$join->Group()->name}}
                </td>
                <td class="d-xl-table-cell">
                  @foreach(explode(',', $join->Joiner()->keywords) as $keyword)
                    <i class="align-middle me-2 fas fa-fw fa-key"></i> {{$keyword}}<br>
                  @endforeach
                </td>
            </tr>
            @endforeach
        </tbody>
      </table>
    </div>
    {{ $joins->links('components.pagination') }}
  </div>
</div>
@endsection