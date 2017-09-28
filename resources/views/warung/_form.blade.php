<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
	{!! Form::label('name', 'Nama Warung', ['class'=>'col-md-2 control-label']) !!}
	<div class="col-md-4">
		{!! Form::text('name', null, ['class'=>'form-control','placeholder' => 'Nama Warung','required','autocomplete'=>'off']) !!}
		{!! $errors->first('name', '<p class="help-block">:message</p>') !!}
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
		@if (isset($warung) && $warung->wilayah)
		{!! Form::select('kelurahan', 
		[''=>'']+App\Kelurahan::pluck('nama','id')->all(),$warung->wilayah
		, ['class'=>'form-control js-selectize-reguler', 'placeholder' => 'Silahkan Pilih','id'=>'pilih_kelurahan']) !!}
		{!! $errors->first('kelurahan', '<p class="help-block">:message</p>') !!}
		@else
		{!! Form::select('kelurahan', 
		[''=>'']+App\Kelurahan::pluck('nama','id')->all(),null
		, ['class'=>'form-control js-selectize-reguler', 'placeholder' => 'Silahkan Pilih','id'=>'pilih_kelurahan']) !!}
		{!! $errors->first('kelurahan', '<p class="help-block">:message</p>') !!}
		@endif
	</div>
</div>
 
<div class="form-group{{ $errors->has('url_api') ? ' has-error' : '' }}">
	{!! Form::label('url_api', 'URL API', ['class'=>'col-md-2 control-label']) !!}
	<div class="col-md-4">
		{!! Form::text('url_api', null, ['class'=>'form-control','placeholder' => 'URL API','required','autocomplete'=>'off']) !!}
		{!! $errors->first('url_api', '<p class="help-block">:message</p>') !!}
	</div>
</div>


<div class="form-group">
	<div class="col-md-4 col-md-offset-2">
			{!! Form::button('<i class="material-icons">send</i> Submit', ['class'=>'btn btn-primary', 'id'=>'btnSimpanStokingCenter', 'type'=>'submit']) !!}
	</div>
</div>
