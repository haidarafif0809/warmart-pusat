<div class="form-group{{ $errors->has('kas') ? ' has-error' : '' }}">
	{!! Form::label('kas', 'Kas', ['class'=>'col-md-2 control-label']) !!}
	<div class="col-md-4">
		{!! Form::select('kas', $data_kas, null, ['placeholder' => '--PILIH KAS--', 'id' => 'nama_kas']) !!}
		{!! $errors->first('kas', '<p class="help-block">:message</p>') !!}
	</div>
</div>
<div class="form-group{{ $errors->has('kategori') ? ' has-error' : '' }}">
	{!! Form::label('kategori', 'Kategori Transaksi', ['class'=>'col-md-2 control-label']) !!}
	<div class="col-md-4">
		{!! Form::select('kategori', $data_kategori_transaksi, null, [ 'placeholder' => '--PILIH KATEGORI TRANSAKSI--', 'id' => 'kategori_transaksi']) !!}
		{!! $errors->first('kategori', '<p class="help-block">:message</p>') !!}
	</div>
</div>
<div class="form-group{{ $errors->has('jumlah_kas') ? ' has-error' : '' }}" style="display: none">
	{!! Form::label('jumlah_kas', 'Total Kas', ['class'=>'col-md-2 control-label']) !!}
	<div class="col-md-4">
		{!! Form::text('jumlah_kas', null, ['class'=>'form-control','required','autocomplete'=>'off', 'placeholder' => 'Total Kas ', 'id' => 'total_kas', 'readonly']) !!}
		{!! $errors->first('jumlah_kas', '<p class="help-block">:message</p>') !!}
	</div>
</div>
<div class="form-group{{ $errors->has('jumlah') ? ' has-error' : '' }}">
	{!! Form::label('jumlah', 'Jumlah', ['class'=>'col-md-2 control-label']) !!}
	<div class="col-md-4">
		{!! Form::text('jumlah', null, ['class'=>'form-control','required','autocomplete'=>'off', 'placeholder' => 'Jumlah ', 'id' => 'jumlah_kas']) !!}
		{!! $errors->first('jumlah', '<p class="help-block">:message</p>') !!}
	</div>
</div>
<div class="form-group{{ $errors->has('keterangan') ? ' has-error' : '' }}">
	{!! Form::label('keterangan', 'Keterangan', ['class'=>'col-md-2 control-label']) !!}
	<div class="col-md-4">
		{!! Form::textarea('keterangan', null, ['class'=>'form-control','autocomplete'=>'off', 'placeholder' => 'Keterangan ', 'style' => 'height:75px', 'id' => 'keterangan']) !!}
		{!! $errors->first('keterangan', '<p class="help-block">:message</p>') !!}
	</div>
</div>

{!! Form::hidden('no_faktur', null, null) !!}


<div class="form-group">
	<div class="col-md-4 col-md-offset-2">
		{!! Form::button('<i class="material-icons">send</i>Submit', ['class'=>'btn btn-primary','type'=>'submit', 'id' => 'btnKasKeluar']) !!}

	</div>
</div>
