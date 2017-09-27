<div class="form-group{{ $errors->has('nama_kategori_harga') ? ' has-error' : '' }}">
	{!! Form::label('nama_kategori_harga', 'Nama Kategori Harga', ['class'=>'col-md-2 control-label']) !!}
	<div class="col-md-4">
		{!! Form::text('nama_kategori_harga', null, ['class'=>'form-control','placeholder'=>'Nama Kategori Harga','required','autocomplete'=>'off', 'id'=>'nama_kategori_harga']) !!}
		{!! $errors->first('nama_kategori_harga', '<p class="help-block" id="nama_kategori_harga_error">:message</p>') !!}
	</div> 
	{!! Form::button('<i class="material-icons">send</i> Submit', ['class'=>'btn btn-primary', 'id'=>'tombol_simpan', 'type'=>'submit']) !!}  
</div>