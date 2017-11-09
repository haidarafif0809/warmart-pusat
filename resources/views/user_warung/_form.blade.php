<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
	{!! Form::label('name', 'Nama', ['class'=>'col-md-2 control-label']) !!}
	<div class="col-md-4">
		{!! Form::text('name', null, ['class'=>'form-control','placeholder' => 'Nama','required','autocomplete'=>'off']) !!}
		{!! $errors->first('name', '<p class="help-block">:message</p>') !!}
	</div>
</div>

<div class="form-group{{ $errors->has('no_telp') ? ' has-error' : '' }}">
	{!! Form::label('no_telp', 'No. Telpon', ['class'=>'col-md-2 control-label']) !!}
	<div class="col-md-4">
		{!! Form::number('no_telp', null, ['class'=>'form-control','required','autocomplete'=>'off', 'placeholder' => 'No. Telpon', 'id' => 'telpon_customer']) !!}
		{!! $errors->first('no_telp', '<p class="help-block" id="no_telp_error">:message</p>') !!}
	</div>
</div>

<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
	{!! Form::label('email', 'Email', ['class'=>'col-md-2 control-label']) !!}
	<div class="col-md-4">
		{!! Form::text('email', null, ['class'=>'form-control','placeholder' => 'Email','required','autocomplete'=>'off']) !!}
		{!! $errors->first('email', '<p class="help-block">:message</p>') !!}
	</div>
</div>

<div class="form-group{{ $errors->has('alamat') ? ' has-error' : '' }}">
	{!! Form::label('alamat', 'Alamat', ['class'=>'col-md-2 control-label']) !!}
	<div class="col-md-4">
		{!! Form::text('alamat', null, ['class'=>'form-control','placeholder' => 'Alamat','required','autocomplete'=>'off']) !!}
		{!! $errors->first('alamat', '<p class="help-block">:message</p>') !!}
	</div>
</div>


<div class="form-group{{ $errors->has('kelurahan') ? ' has-error' : '' }}">
	{!! Form::label('kelurahan', 'Kelurahan', ['class'=>'col-md-2 control-label']) !!}
	<div class="col-md-4">
		@if (isset($user_warung) && $user_warung->wilayah)
		{!! Form::select('kelurahan', 
		[''=>'']+App\Kelurahan::pluck('nama','id')->all(),$user_warung->wilayah
		, ['class'=>'form-control js-selectize-reguler', 'placeholder' => '--SILAKAN PILIH--','id'=>'pilih_kelurahan']) !!}
		{!! $errors->first('kelurahan', '<p class="help-block">:message</p>') !!}
		@else
		{!! Form::select('kelurahan', 
		[''=>'']+App\Kelurahan::pluck('nama','id')->all(),null
		, ['class'=>'form-control js-selectize-reguler', 'placeholder' => '--SILAKAN PILIH--','id'=>'pilih_kelurahan']) !!}
		{!! $errors->first('kelurahan', '<p class="help-block">:message</p>') !!}
		@endif
	</div>
</div>

<div class="form-group{{ $errors->has('id_warung') ? ' has-error' : '' }}">
	{!! Form::label('id_warung', 'Warung', ['class'=>'col-md-2 control-label']) !!}
	<div class="col-md-4">
		@if (isset($user_warung) && $user_warung->id_warung)
		{!! Form::select('id_warung', 
		[''=>'']+App\Warung::pluck('name','id')->all(),$user_warung->id_warung
		, ['class'=>'form-control js-selectize-reguler', 'placeholder' => 'Silahkan Pilih','id'=>'pilih_id_warung']) !!}
		{!! $errors->first('id_warung', '<p class="help-block">:message</p>') !!}
		@else
		{!! Form::select('id_warung', 
		[''=>'']+App\Warung::pluck('name','id')->all(),null
		, ['class'=>'form-control js-selectize-reguler', 'placeholder' => 'Silahkan Pilih','id'=>'pilih_id_warung']) !!}
		{!! $errors->first('id_warung', '<p class="help-block">:message</p>') !!}
		@endif
	</div>
</div>

<div class="form-group">
	<div class="col-md-4 col-md-offset-2">
		{!! Form::button('<i class="material-icons">send</i> Submit', ['class'=>'btn btn-primary', 'id'=>'btnSimpanStokingCenter', 'type'=>'submit']) !!}
	</div>
</div>
