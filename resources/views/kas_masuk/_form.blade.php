@if(isset($kas_masuk))
<div class="form-group{{ $errors->has('kas') ? ' has-error' : '' }}">
	{!! Form::label('kas', 'Kas', ['class'=>'col-md-2 control-label']) !!}
	<div class="col-md-4">
		{!! Form::select('kas', $data_kas, null, ['placeholder' => ' Kas','id'=>'kas' ,'disabled'=>'']) !!}
		{!! $errors->first('kas', '<p class="help-block">:message</p>') !!}
	</div>
</div>
@else
<div class="form-group{{ $errors->has('kas') ? ' has-error' : '' }}">
	{!! Form::label('kas', 'Kas', ['class'=>'col-md-2 control-label']) !!}
	<div class="col-md-4">
		{!! Form::select('kas', $data_kas, null, ['placeholder' => ' Kas','id'=>'kas']) !!}
		{!! $errors->first('kas', '<p class="help-block">:message</p>') !!}
	</div>
</div>
@endif
<div class="form-group{{ $errors->has('kategori') ? ' has-error' : '' }}">
	{!! Form::label('kategori', 'Kategori Transaksi', ['class'=>'col-md-2 control-label']) !!}
	<div class="col-md-4">
		{!! Form::select('kategori', $data_kategori_transaksi, null, ['placeholder' => ' Kategori Transaksi','id'=>'kategori_transaksi']) !!}
		{!! $errors->first('kategori', '<p class="help-block">:message</p>') !!}
	</div>
</div>

<div class="form-group{{ $errors->has('jumlah') ? ' has-error' : '' }}">
	{!! Form::label('jumlah', 'Jumlah', ['class'=>'col-md-2 control-label']) !!}
	<div class="col-md-4">
		{!! Form::text('jumlah', null, ['class'=>'form-control','required','autocomplete'=>'off', 'placeholder' => 'Jumlah ']) !!}
		{!! $errors->first('jumlah', '<p class="help-block">:message</p>') !!}
	</div>
</div>

<div class="form-group{{ $errors->has('keterangan') ? ' has-error' : '' }}">
	{!! Form::label('keterangan', 'Keterangan', ['class'=>'col-md-2 control-label']) !!}
	<div class="col-md-4">
		{!! Form::text('keterangan', null, ['class'=>'form-control','autocomplete'=>'off', 'placeholder' => 'Keterangan ']) !!}
		{!! $errors->first('keterangan', '<p class="help-block">:message</p>') !!}
	</div>
</div>

{!! Form::hidden('no_faktur', null, null) !!}


<div class="form-group">
	<div class="col-md-4 col-md-offset-2">
		{!! Form::button('<i class="material-icons">save</i>Simpan', ['class'=>'btn btn-primary','type'=>'submit','id'=>'btnSimpan']) !!}

	</div>
</div>
