<div class="form-group{{ $errors->has('kode_kas') ? ' has-error' : '' }}">
	{!! Form::label('kode_kas', 'Kode Kas', ['class'=>'col-md-2 control-label ']) !!}
	<div class="col-md-4">
		{!! Form::text('kode_kas', null, ['class'=>'form-control','placeholder'=>'Kode Kas','required','autocomplete'=>'off', 'id'=>'kode_kas']) !!}
		{!! $errors->first('kode_kas', '<p class="help-block" id="kode_kas_error">:message</p>') !!}
	</div>
</div>

<div class="form-group{{ $errors->has('nama_kas') ? ' has-error' : '' }}">
	{!! Form::label('nama_kas', 'Nama Kas', ['class'=>'col-md-2 control-label ']) !!}
	<div class="col-md-4">
		{!! Form::text('nama_kas', null, ['class'=>'form-control','placeholder'=>'Nama Kas','required','autocomplete'=>'off', 'id'=>'nama_kas']) !!}
		{!! $errors->first('nama_kas', '<p class="help-block" id="nama_kas_error">:message</p>') !!}
	</div>
</div>

<div class="form-group{{ $errors->has('status_kas') ? ' has-error' : '' }}">
	{!! Form::label('status_kas', 'Status Kas', ['class'=>'col-md-2 control-label']) !!}
	<div class="col-md-4">
		{!! Form::select('status_kas', [
		'1'=>'Aktif',
		'0'=>'Tidak Aktif	'],null, ['class'=>'form-control js-selectize-reguler', 'placeholder' => '--SILAKAN PILIH--','id'=>'pilih_status_kas', 'required' => '']) !!}
		{!! $errors->first('status_kas', '<p class="help-block">:message</p>') !!}
	</div>
</div>

<div class="form-group{{ $errors->has('default_kas') ? ' has-error' : '' }}">
	{!! Form::label('default_kas', 'Default Kas', ['class'=>'col-md-2 control-label']) !!}
	<div class="col-md-4">
		{!! Form::select('default_kas', [
		'1'=>'Iya',
		'0'=>'Tidak	'],null, ['class'=>'form-control js-selectize-reguler', 'placeholder' => '--SILAKAN PILIH--','id'=>'pilih_default_kas', 'required' => '']) !!}
		{!! $errors->first('default_kas', '<p class="help-block">:message</p>') !!}
		<p style="color: red; font-style: italic;">*Note : Hanya 1 Kas yang dijadikan default.</p>
	</div>
</div>

<div class="form-group">
	<div class="col-md-4 col-md-offset-2">
	{!! Form::button('<i class="material-icons">send</i> Submit', ['class'=>'btn btn-primary', 'id'=>'btnSimpanBank', 'type'=>'submit']) !!} 
	</div>
</div>