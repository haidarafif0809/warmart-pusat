<div class="form-group{{ $errors->has('kode_barcode') ? ' has-error' : '' }}">
	{!! Form::label('kode_barcode', 'Barcode', ['class'=>'col-md-2 control-label']) !!}
	<div class="col-md-4">
		{!! Form::text('kode_barcode', null, ['class'=>'form-control','placeholder' => 'Barcode(Jika Ada)','autocomplete'=>'off', 'id' => 'kode_barcode']) !!}

		{!! $errors->first('kode_barcode', '<p class="help-block" id="kode_barcode_error" >:message</p>') !!}
	</div>
</div>

<div class="form-group{{ $errors->has('kode_barang') ? ' has-error' : '' }}">
	{!! Form::label('kode_barang', 'Kode Produk', ['class'=>'col-md-2 control-label']) !!}
	<div class="col-md-4">
		{!! Form::text('kode_barang', null, ['class'=>'form-control','required','autocomplete'=>'off', 'placeholder' => 'Kode Produk', 'id' => 'kode_barang']) !!}
		{!! $errors->first('kode_barang', '<p class="help-block" id="kode_produk_error">:message</p>') !!}
	</div>
</div>

<div class="form-group{{ $errors->has('nama_barang') ? ' has-error' : '' }}">
	{!! Form::label('nama_barang', 'Nama Produk', ['class'=>'col-md-2 control-label']) !!}
	<div class="col-md-4">
		{!! Form::text('nama_barang', null, ['class'=>'form-control','required','autocomplete'=>'off', 'id' =>'nama_barang', 'placeholder' => 'Nama Produk']) !!}
		{!! $errors->first('nama_barang', '<p class="help-block" id="nama_produk_error">:message</p>') !!}
	</div>
</div> 

<div class="form-group{{ $errors->has('kategori_barang_id') ? ' has-error' : '' }}">
	{!! Form::label('kategori_barang_id', 'Kategori Produk', ['class'=>'col-md-2 control-label']) !!}
	<div class="col-md-4">
		{!! Form::select('kategori_barang_id', 
		[''=>'']+App\KategoriBarang::pluck('nama_kategori_barang','id')->all(),
		null, ['class'=>'js-selectize-reguler', 'required','placeholder' => 'Pilih Kategori Produk','id'=>'kategori_barang', 'style'=> 'width: 215px;']) !!}
		{!! $errors->first('kategori_barang_id', '<p class="help-block" id="kategori_error">:message</p>') !!}
	</div>
</div>


<div class="form-group{{ $errors->has('satuan_id') ? ' has-error' : '' }}">
	{!! Form::label('satuan_id', 'Satuan', ['class'=>'col-md-2 control-label']) !!}
	<div class="col-md-4">
		{!! Form::select('satuan_id', 
		['1'=>'PCS']+App\Satuan::pluck('nama_satuan','id')->all(),
		null, ['class'=>'js-selectize-reguler','required', 'id'=>'satuan', 'style'=> 'width: 215px;']) !!}
		{!! $errors->first('satuan_id', '<p class="help-block" id="satuan_error">:message</p>') !!}
	</div>
</div>



<div class="form-group{{ $errors->has('harga_beli') ? ' has-error' : '' }}">
	{!! Form::label('harga_beli', 'Harga Beli', ['class'=>'col-md-2 control-label']) !!}
	<div class="col-md-4">
		{!! Form::text('harga_beli', null, ['class'=>'form-control','required','autocomplete'=>'off', 'id' =>'harga_beli', 'placeholder' => 'Harga Beli']) !!}
		{!! $errors->first('harga_beli', '<p class="help-block" id="harga_beli_error">:message</p>') !!}
	</div>
</div> 

<div class="form-group{{ $errors->has('harga_jual') ? ' has-error' : '' }}">
	{!! Form::label('harga_jual', 'Harga Jual', ['class'=>'col-md-2 control-label']) !!}
	<div class="col-md-4">
		{!! Form::text('harga_jual', null, ['class'=>'form-control','required','autocomplete'=>'off', 'id' =>'harga_jual', 'placeholder' => 'Harga Jual']) !!}
		{!! $errors->first('harga_jual', '<p class="help-block" id="harga_jual_error">:message</p>') !!}
	</div>
</div> 


<div class="form-group{{ $errors->has('hitung_stok') ? ' has-error' : '' }}">
	{!! Form::label('hitung_stok', 'Hitung Stok', ['class'=>'col-md-2 control-label']) !!}
		<div class="col-md-1">
			<div class="togglebutton">
				<label>
					@if (isset($barang) && $barang->hitung_stok == 1)
			    	<input type="checkbox" name="hitung_stok" id="hitung_stok" value="1" checked="">						
					@else
			    	<input type="checkbox" name="hitung_stok" id="hitung_stok" value="1">
			    	@endif
				</label>
			</div>
		</div>
</div>

<div class="form-group{{ $errors->has('status_aktif') ? ' has-error' : '' }}">
	{!! Form::label('status_aktif', 'Bisa Dijual', ['class'=>'col-md-2 control-label']) !!}
		<div class="col-md-1">
			<div class="togglebutton">
				<label>
					@if (isset($barang) && $barang->status_aktif == 1)
			    	<input type="checkbox" name="status_aktif" id="status_aktif" value="1" checked="">
			    	@else
			    	<input type="checkbox" name="status_aktif" id="status_aktif" value="1">
			    	@endif
				</label>
			</div>
		</div>
</div>


<div class="form-group{{ $errors->has('foto') ? ' has-error' : '' }}">
	{!! Form::label('foto', 'Foto', ['class'=>'col-md-2 control-label']) !!}
	<div class="col-md-4">
		<div class="fileinput fileinput-new text-center" data-provides="fileinput">
		    <div class="fileinput-new thumbnail">

				@if (isset($barang) && $barang->foto)
					<p>
						{!! Html::image(asset('foto_produk/'.$barang->foto), null, ['class' => 'img-rounded img-responsive']) !!}
					</p>
				@else
				<img src="../../assets/img/image_placeholder.jpg" alt="Foto Akan Tampil Disini">
				@endif
		        
		    </div>
		    <div class="fileinput-preview fileinput-exists thumbnail"></div>
		    <div>
		        <span class="btn btn-rose btn-round btn-file">
		            <span class="fileinput-new">Ambil Foto</span>
		            <span class="fileinput-exists">Ubah</span>
		            {!! Form::file('foto',null,['id'=>'foto']) !!}
					{!! $errors->first('foto', '<p class="help-block">:message</p>') !!}
		        </span>
		        <a href="#pablo" class="btn btn-danger btn-round fileinput-exists" data-dismiss="fileinput"><i class="fa fa-times"></i> Batal</a>
		    </div>
		</div>
	</div>
</div> 


<div class="form-group">
	<div class="col-md-4 col-md-offset-2">
			{!! Form::button('<i class="material-icons">send</i> Submit', ['class'=>'btn btn-primary', 'id'=>'btnSimpan', 'type'=>'submit', 'dusk'=>'btn-submit']) !!}


	</div>
</div>
