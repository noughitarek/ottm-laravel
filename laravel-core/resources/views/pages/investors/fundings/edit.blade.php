@extends('layouts.main')
@section('subtitle', "Create an investors")
@section('content')
@php
$user = Auth::user();
@endphp
<form action="{{route('accountingfundings_edit', [$investor->id, $funding->id])}}" method="POST">
@csrf
@method('put')
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
                        <input type="text" class="form-control" id="name" name="name" placeholder="Ex: Tarek" value="{{ old('name')??$funding->name }}" required>
                        @error('name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Amount <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="total_amount" name="total_amount" placeholder="Ex: 50000" value="{{ old('total_amount')??$funding->total_amount }}" required>
                        @error('total_amount')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Type <span class="text-danger">*</span></label>
                        <select class="form-control" id="type" name="type">
                            <option value>Select the type</option>
                            <option value="tests" {{$funding->type=="tests"?'selected':''}}>Tests investement</option>
                            <option value="products" {{$funding->type=="products"?'selected':''}}>Products investement</option>
                        </select>
                        @error('type')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3 {{$funding->type=="products"?'':'d-none'}}" id="ProductsSelect">
                        <label class="form-label">Products <span class="text-danger">*</span></label>
                        <select class="form-control" id="products" name="products">
                            <option value>Select the type</option>
                            @foreach($products as $product)
                            <option value="{{$product->id}}">{{$product->name}}</option>
                            @endforeach
                        </select>
                        @error('type')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Investor pourcentage <span class="text-danger">*</span></label>
                        <input type="number" min="1" max="100" class="form-control" id="investor_pourcentage" name="investor_pourcentage" placeholder="Ex: 50" value="{{ old('investor_pourcentage')??$funding->investor_pourcentage }}" required>
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
@section('script')
<script>
    document.getElementById('type').addEventListener('change', function(){
        if(this.value == 'products'){
            document.getElementById('ProductsSelect').classList.remove('d-none')            
        }
        else{
            document.getElementById('ProductsSelect').classList.add('d-none')     
        }
    })
</script>
@endsection