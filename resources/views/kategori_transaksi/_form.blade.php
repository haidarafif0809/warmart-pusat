<div class="form-group{{ $errors->has('nama_kategori_transaksi') ? ' has-error' : '' }}">
	{!! Form::label('nama_kategori_transaksi', 'Nama Kategori Transaksi', ['class'=>'col-md-2 control-label']) !!}
	<div class="col-md-4">
		{!! Form::text('nama_kategori_transaksi', null, ['class'=>'form-control','required','autocomplete'=>'off', 'placeholder' => 'Nama Kategori Transaksi', 'id' => 'nama_kategori_transaksi']) !!}
		{!! $errors->first('nama_kategori_transaksi', '<p class="help-block">:message</p>') !!}
	</div>
</div>

<div class="form-group">
	<div class="col-md-4 col-md-offset-2">
		{!! Form::button('<i class="material-icons">send</i>Submit', ['class'=>'btn btn-primary','type'=>'submit', 'id'=>'btnSubmitTransaksi']) !!}
	</div>
</div>
