<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
	{!! Form::label('name', 'Nama Stoking Center', ['class'=>'col-md-2 control-label']) !!}
	<div class="col-md-4">
		{!! Form::text('name', null, ['class'=>'form-control','placeholder' => 'Nama Stoking Center','required','autocomplete'=>'off']) !!}
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
		@if (isset($stokingcenter) && $stokingcenter->wilayah)
		{!! Form::select('kelurahan', 
		[''=>'']+App\Kelurahan::pluck('nama','id')->all(),$stokingcenter->wilayah
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


<div class="form-group{{ $errors->has('kategoriharga') ? ' has-error' : '' }}">
	{!! Form::label('kategoriharga', 'KategoriHarga', ['class'=>'col-md-2 control-label']) !!}
	<div class="col-md-4">
		@if (isset($stokingcenter) && $stokingcenter->kategori_harga)
		{!! Form::select('kategoriharga', 
		[''=>'']+App\KategoriHarga::pluck('nama_kategori_harga','id')->all(),$stokingcenter->kategori_harga
		, ['class'=>'form-control js-selectize-reguler', 'placeholder' => 'Silahkan Pilih','id'=>'pilih_kategoriharga']) !!}
		{!! $errors->first('kategoriharga', '<p class="help-block">:message</p>') !!}
		@else
		{!! Form::select('kategoriharga', 
		[''=>'']+App\KategoriHarga::pluck('nama_kategori_harga','id')->all(),null
		, ['class'=>'form-control js-selectize-reguler', 'placeholder' => 'Silahkan Pilih','id'=>'pilih_kategoriharga']) !!}
		{!! $errors->first('kategoriharga', '<p class="help-block">:message</p>') !!}
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
