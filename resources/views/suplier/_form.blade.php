<div class="form-group{{ $errors->has('nama_suplier') ? ' has-error' : '' }}">
	{!! Form::label('nama_suplier', 'Nama Suplier', ['class'=>'col-md-2 control-label']) !!}
	<div class="col-md-4">
		{!! Form::text('nama_suplier', null, ['class'=>'form-control','required','autocomplete'=>'off', 'placeholder' => 'Nama Suplier', 'id' => 'nama_suplier']) !!}
		{!! $errors->first('nama_suplier', '<p class="help-block" id="nama_suplier_error">:message</p>') !!}
	</div>
</div>

<div class="form-group{{ $errors->has('alamat') ? ' has-error' : '' }}">
	{!! Form::label('alamat', 'Alamat', ['class'=>'col-md-2 control-label']) !!}
	<div class="col-md-4">
		{!! Form::text('alamat', null, ['class'=>'form-control','required','autocomplete'=>'off', 'placeholder' => 'Alamat', 'id' => 'alamat']) !!}
		{!! $errors->first('alamat', '<p class="help-block">:message</p>') !!}
	</div>
</div>


<div class="form-group{{ $errors->has('no_telp') ? ' has-error' : '' }}">
	{!! Form::label('no_telp', 'No Telp', ['class'=>'col-md-2 control-label']) !!}
	<div class="col-md-4">
		{!! Form::text('no_telp', null, ['class'=>'form-control','required','autocomplete'=>'off', 'placeholder' => 'No Telp', 'id' => 'no_telp']) !!}
		{!! $errors->first('no_telp', '<p class="help-block">:message</p>') !!}
	</div>
</div>

<div class="form-group">
	<div class="col-md-4 col-md-offset-2">
		{!! Form::button('<i class="material-icons">send</i>Submit', ['class'=>'btn btn-primary','type'=>'submit', 'id'=>'btnSubmit']) !!}
	</div>
</div>
