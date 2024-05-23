@extends('layouts.main')
@section('subtitle', "Wilayas")
@section('content')
@php
$user = Auth::user();
@endphp
<form action="{{route('deliverymens_edit', $wilaya->id)}}" method="POST">
  @csrf
  <div class="col-12 col-lg-12 col-xxl-12 d-flex">
    <div class="card flex-fill">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Wilaya Of {{$wilaya->name}}</h5>
        <button class="btn btn-primary">Save</button>
      </div>
    </div>
  </div>
  <div class="col-12 col-lg-12 col-xxl-12 d-flex">
    <div class="card flex-fill">
      <div class="table-responsive">
        <table class="table table-hover my-0" id="datatables-orders">
          <thead>
            <tr>
              <th class="d-xl-table-cell">Wilaya</th>
                @foreach($desks as $desk)
                <th class="d-xl-table-cell">{{$desk->name}}</th>
                @endforeach
            </tr>
          </thead>
          <tbody>
            <tr>
                <td class="d-xl-table-cell">
                    <i class="align-middle me-2 fas fa-fw fa-hashtag"></i> 0<br>
                    <i class="align-middle me-2 fas fa-fw fa-box"></i> Fill all<br>
                </td>
                @foreach($desks as $desk)
                <td class="d-xl-table-cell">
                    <input type="text" class="form-control all_desks" id="desk{{$desk->id}}" placeholder="{{$desk->name}}">
                </td>
                @endforeach
            </tr>
          </tbody>
        </table>
      </div>     
    </div>        
  </div>
  <div class="col-12 col-lg-12 col-xxl-12 d-flex">
    <div class="card flex-fill">
      <div class="table-responsive">
        <table class="table table-hover my-0" id="datatables-orders">
          <thead>
            <tr>
              <th class="d-xl-table-cell">Wilaya</th>
                @foreach($desks as $desk)
                <th class="d-xl-table-cell">{{$desk->name}}</th>
                @endforeach
            </tr>
          </thead>
          <tbody>
            @foreach($wilaya->Communes() as $commune)
            <tr>
                <td class="d-xl-table-cell">
                    <i class="align-middle me-2 fas fa-fw fa-hashtag"></i> {{$commune->id}}<br>
                    <i class="align-middle me-2 fas fa-fw fa-box"></i> {{$commune->name}}<br>
                </td>
                @foreach($desks as $desk)
                <td class="d-xl-table-cell">
                    <input type="text" class="form-control communes-desk{{$desk->id}}"  value="{{$desk->CommunePhone($commune)}}" name="desk[{{$desk->id}}][{{$commune->id}}]" id="desk{{$desk->id}}-{{$commune->id}}" placeholder="{{$desk->name}}">
                </td>
                @endforeach
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</form>

@endsection
@section('script')
<script>
document.addEventListener("DOMContentLoaded", function() {
    const all_desks = document.querySelectorAll('.all_desks')
    all_desks.forEach((desk)=>{
        desk.addEventListener('input', function(){
            const communes = document.querySelectorAll('.communes-'+desk.id)
            communes.forEach((commune)=>{
                commune.value = desk.value
            })
        })
        
    })
})
</script>
@endsection