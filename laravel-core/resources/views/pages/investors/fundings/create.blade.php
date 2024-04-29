@extends('layouts.main')
@section('subtitle', "Create an investors")
@section('content')
@php
$user = Auth::user();
@endphp
<form action="{{route('accountingfundings_create', $investor->id)}}" method="POST">
@csrf
    <div class="col-12 col-lg-12 col-xxl-12 d-flex">
        <div class="card flex-fill">
            <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">New funding from {{$investor->name}}</h5>
                @if($user->Has_Permission("accounting_investors_create"))
                <div>
                    <a href="{{route('accountingfundings', $investor->id)}}" class="btn btn-secondary">Back</a>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-12 col-xxl-12 d-flex">
        <div class="col-12 col-lg-12 col-xxl-12 d-flex">
            <div class="card flex-fill">
                <div class="card-header">
                    <h5 class="card-title">General information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Ex: Tarek" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Amount <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="total_amount" name="total_amount" placeholder="Ex: 50000" value="{{ old('total_amount') }}" required>
                        @error('total_amount')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Type <span class="text-danger">*</span></label>
                        <select class="form-control" id="type" name="type">
                            <option value>Select the type</option>
                            <option value="tests">Tests investement</option>
                            <option value="products">Products investement</option>
                        </select>
                        @error('type')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Investor pourcentage <span class="text-danger">*</span></label>
                        <input type="number" min="1" max="100" class="form-control" id="investor_pourcentage" name="investor_pourcentage" placeholder="Ex: 50" value="{{ old('investor_pourcentage') }}" required>
                        @error('investor_pourcentage')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Investor <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" value="{{$investor->name}}" disabled>
                    </div>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection