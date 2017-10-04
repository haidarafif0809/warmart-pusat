<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
	{!! Form::label('name', 'Nama', ['class'=>'col-md-2 control-label']) !!}
	<div class="col-md-4">
		{!! Form::text('name', null, ['class'=>'form-control','placeholder'=>'Nama','required','autocomplete'=>'off']) !!}
		{!! $errors->first('name', '<p class="help-block">:message</p>') !!}
	</div>
</div>

<div class="form-group{{ $errors->has('display_name') ? ' has-error' : '' }}">
	{!! Form::label('display_name', 'Display Nama', ['class'=>'col-md-2 control-label']) !!}
	<div class="col-md-4">
		{!! Form::text('display_name', null, ['class'=>'form-control','placeholder' => 'Display Nama','required','autocomplete'=>'off']) !!}
		{!! $errors->first('display_name', '<p class="help-block">:message</p>') !!}
	</div>
</div>

<div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
	{!! Form::label('description', 'Deskripsi', ['class'=>'col-md-2 control-label']) !!}
	<div class="col-md-4">
		{!! Form::text('description', null, ['class'=>'form-control','placeholder' => 'Deskripsi','autocomplete'=>'off']) !!}
		{!! $errors->first('description', '<p class="help-block">:message</p>') !!}
	</div>
</div>

<div class="form-group">
	<div class="col-md-4 col-md-offset-2">
			{!! Form::button('<i class="material-icons">send</i> Submit', ['class'=>'btn btn-primary', 'id'=>'btnSimpanWarung', 'type'=>'submit']) !!}
	</div>
</div>