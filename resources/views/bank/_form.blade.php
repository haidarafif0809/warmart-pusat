<div class="form-group{{ $errors->has('nama_bank') ? ' has-error' : '' }}">
	{!! Form::label('nama_bank', 'Nama Bank', ['class'=>'col-md-2 control-label']) !!}
	<div class="col-md-4">
		{!! Form::text('nama_bank', null, ['class'=>'form-control','placeholder'=>'Nama Bank','required','autocomplete'=>'off', 'id'=>'nama_bank']) !!}
		{!! $errors->first('nama_bank', '<p class="help-block" id="nama_bank_error">:message</p>') !!}
	</div>
</div>

<div class="form-group{{ $errors->has('atas_nama') ? ' has-error' : '' }}">
	{!! Form::label('atas_nama', 'Atas Nama', ['class'=>'col-md-2 control-label']) !!}
	<div class="col-md-4">
		{!! Form::text('atas_nama', null, ['class'=>'form-control','placeholder'=>'Atas Nama','required','autocomplete'=>'off', 'id'=>'atas_nama']) !!}
		{!! $errors->first('atas_nama', '<p class="help-block" id="atas_nama_error">:message</p>') !!}
	</div>
</div>

<div class="form-group{{ $errors->has('no_rek') ? ' has-error' : '' }}">
	{!! Form::label('no_rek', 'No. Rekening', ['class'=>'col-md-2 control-label']) !!}
	<div class="col-md-4">
		{!! Form::text('no_rek', null, ['class'=>'form-control','placeholder'=>'No. Rekening','required','autocomplete'=>'off', 'id'=>'no_rek']) !!}
		{!! $errors->first('no_rek', '<p class="help-block" id="no_rek_error">:message</p>') !!}
	</div>
</div>

<div class="form-group">
	<div class="col-md-4 col-md-offset-2">
	{!! Form::button('<i class="material-icons">send</i> Submit', ['class'=>'btn btn-primary', 'id'=>'btnSimpanBank', 'type'=>'submit']) !!} 
	</div>
</div>