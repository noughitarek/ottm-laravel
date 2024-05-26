@extends('layouts.main')
@section('subtitle', "OTTM")
@section('content')
@php
$user = Auth::user();
@endphp
<form action="{{route('tracking_edit')}}" method="POST" enctype="multipart/form-data">
@csrf
<div class="col-12 col-lg-12 col-xxl-12 d-flex">
  <div class="card flex-fill">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="card-title mb-0">OTTM</h5>
        @if($user->Has_Permission('tracking_edit'))
            <button type="submit" class="btn btn-primary">Save changes</button>
        @endif
    </div>
  </div>
</div>
<div class="col-12 col-lg-12 col-xxl-12 d-flex">
  <div class="card flex-fill">
	<div class="card-header">
	  <h5 class="card-title mb-0">Messages template</h5>
	</div>
	<div class="card-body mb-2">
	  <div class="row mb-2 d-flex align-items-center">
	    <div class="col-md-12">
			<ul>
				<li>@{{phone}}: delivery men phone number (if exists)</li>
			</ul>
		</div>

	    <div class="col-md-12">
		  <div class="mb-3">
		    <label for="validating">Validating (vers_hub)</label>
			<textarea class="form-control" name="settings-messages_template-validating" id="settings-messages_template-validating">{{config('settings.messages_template.validating')}}</textarea>
		  </div>
		<div class="mb-3">
			<label for="shipping">Shipping (en_hub)</label>
			<textarea class="form-control" name="settings-messages_template-shipping" id="settings-messages_template-shipping">{{config('settings.messages_template.shipping')}}</textarea>
		</div>
		<div class="mb-3">
			<label for="wilaya">Wilaya (vers_wilaya)</label>
			<textarea class="form-control" name="settings-messages_template-wilaya" id="settings-messages_template-wilaya">{{config('settings.messages_template.wilaya')}}</textarea>
		</div>
		<div class="mb-3">
			<label for="delivery">Delivery (en_livraison)</label>
			<textarea class="form-control" name="settings-messages_template-delivery" id="settings-messages_template-delivery">{{config('settings.messages_template.delivery')}}</textarea>
		</div>
		<div class="mb-3">
			<label for="delivered">Delivered (livre_non_encaisse)</label>
			<textarea class="form-control" name="settings-messages_template-delivered" id="settings-messages_template-delivered">{{config('settings.messages_template.delivered')}}</textarea>
		</div>
		<div class="mb-3">
			<label for="ready">Ready (encaisse_non_paye)</label>
			<textarea class="form-control" name="settings-messages_template-ready" id="settings-messages_template-ready">{{config('settings.messages_template.ready')}}</textarea>
		</div>
		<div class="mb-3">
			<label for="recovering">Recovering (paye_et_archive)</label>
			<textarea class="form-control" name="settings-messages_template-recovering" id="settings-messages_template-recovering">{{config('settings.messages_template.recovering')}}</textarea>
		</div>
		<div class="mb-3">
			<label for="back">Back (suspendu|retour)</label>
			<textarea class="form-control" name="settings-messages_template-back" id="settings-messages_template-back">{{config('settings.messages_template.back')}}</textarea>
		</div>
		<div class="mb-3">
			<label for="back_Ready">Back Ready (retour_recu|retour_archive)</label>
			<textarea class="form-control" name="settings-messages_template-back_Ready" id="settings-messages_template-back_Ready">{{config('settings.messages_template.back_Ready')}}</textarea>
		</div>
	  </div>
	</div>
    @if($user->Has_Permission('tracking_edit'))
	    <button type="submit" class="btn btn-primary">Save changes</button>
	@endif
	</div>
</div>
</form>
@endsection