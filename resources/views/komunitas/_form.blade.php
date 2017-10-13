<div class="form-group{{ $errors->has('no_telp') ? ' has-error' : '' }}">
	{!! Form::label('no_telp', 'No Telp', ['class'=>'col-md-2 control-label']) !!}
	<div class="col-md-4">
		{!! Form::number('no_telp', null, ['class'=>'form-control','placeholder'=>'No Telp','required','autocomplete'=>'off']) !!}
		{!! $errors->first('no_telp', '<p class="help-block">:message</p>') !!}
	</div>
</div>

<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
	{!! Form::label('name', 'Nama Komunitas', ['class'=>'col-md-2 control-label']) !!}
	<div class="col-md-4">
		{!! Form::text('name', null, ['class'=>'form-control','placeholder' => 'Nama Komunitas','required','autocomplete'=>'off']) !!}
		{!! $errors->first('name', '<p class="help-block">:message</p>') !!}
	</div>
</div>


<div class="form-group{{ $errors->has('name_penggiat') ? ' has-error' : '' }}">
	{!! Form::label('name_penggiat', 'Nama Penggiat', ['class'=>'col-md-2 control-label']) !!}
	<div class="col-md-4">
		@if (isset($komunitas) && $komunitas->komunitas_penggiat)
		{!! Form::text('name_penggiat', $komunitas->komunitas_penggiat->nama_penggiat, ['class'=>'form-control','placeholder' => 'Nama Penggiat','required','autocomplete'=>'off']) !!}
		@else
		{!! Form::text('name_penggiat', null, ['class'=>'form-control','placeholder' => 'Nama Penggiat','autocomplete'=>'off']) !!}
		@endif
		{!! $errors->first('name_penggiat', '<p class="help-block">:message</p>') !!}
	</div>
</div>


<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
	{!! Form::label('email', 'Email', ['class'=>'col-md-2 control-label']) !!}
	<div class="col-md-4">
		{!! Form::email('email', null, ['class'=>'form-control','placeholder' => 'Email','required','autocomplete'=>'off']) !!}
		{!! $errors->first('email', '<p class="help-block">:message</p>') !!}
	</div>
</div>

<div class="form-group{{ $errors->has('id_warung') ? ' has-error' : '' }}">
	{!! Form::label('id_warung', 'Warung', ['class'=>'col-md-2 control-label']) !!}
	<div class="col-md-4">
		@if (isset($komunitas) && $komunitas->id_warung)
		{!! Form::select('id_warung', 
		[''=>'']+App\Warung::pluck('name','id')->all(),$komunitas->id_warung
		, ['class'=>'form-control js-selectize-reguler', 'placeholder' => 'Silahkan Pilih','id'=>'pilih_id_warung']) !!}
		{!! $errors->first('id_warung', '<p class="help-block">:message</p>') !!}
		@else
		{!! Form::select('id_warung', 
		[''=>'']+App\Warung::pluck('name','id')->all(),null
		, ['class'=>'form-control js-selectize-reguler', 'placeholder' => 'Silahkan Pilih','id'=>'pilih_id_warung']) !!}
		{!! $errors->first('id_warung', '<p class="help-block">:message</p>') !!}
		@endif
	</div>
</div>

<div class="form-group{{ $errors->has('alamat') ? ' has-error' : '' }}">
	{!! Form::label('alamat', 'Alamat Komunitas', ['class'=>'col-md-2 control-label']) !!}
	<div class="col-md-4">
		{!! Form::text('alamat', null, ['class'=>'form-control','placeholder' => 'Alamat','required','autocomplete'=>'off']) !!}
		{!! $errors->first('alamat', '<p class="help-block">:message</p>') !!}
	</div>
</div>


<div class="form-group{{ $errors->has('alamat_penggiat') ? ' has-error' : '' }}">
	{!! Form::label('alamat_penggiat', 'Alamat Penggiat', ['class'=>'col-md-2 control-label']) !!}
	<div class="col-md-4">
		@if (isset($komunitas) && $komunitas->komunitas_penggiat)
		{!! Form::text('alamat_penggiat', $komunitas->komunitas_penggiat->alamat_penggiat, ['class'=>'form-control','placeholder' => 'Alamat Penggiat','required','autocomplete'=>'off']) !!}
		{!! $errors->first('alamat_penggiat', '<p class="help-block">:message</p>') !!}
		@else
		{!! Form::text('alamat_penggiat', null, ['class'=>'form-control','placeholder' => 'Alamat Penggiat','autocomplete'=>'off']) !!}
		{!! $errors->first('alamat_penggiat', '<p class="help-block">:message</p>') !!}
		@endif


	</div>
</div>


<div class="form-group{{ $errors->has('kelurahan') ? ' has-error' : '' }}">
	{!! Form::label('kelurahan', 'Kelurahan', ['class'=>'col-md-2 control-label']) !!}
	<div class="col-md-4">
		@if (isset($komunitas) && $komunitas->wilayah)
		{!! Form::select('kelurahan', 
		[''=>'']+App\Kelurahan::pluck('nama','id')->all(),$komunitas->wilayah
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



<div class="form-group{{ $errors->has('nama_bank') ? ' has-error' : '' }}">
	{!! Form::label('nama_bank', 'Nama Bank', ['class'=>'col-md-2 control-label']) !!}
	<div class="col-md-4">
		@if (isset($komunitas) && $komunitas->bank_komunitas)
		{!! Form::text('nama_bank', $komunitas->bank_komunitas->nama_bank, ['class'=>'form-control','placeholder' => 'Nama Bank','required','autocomplete'=>'off']) !!}
		@else
		{!! Form::text('nama_bank', null, ['class'=>'form-control','placeholder' => 'Nama Bank','required','autocomplete'=>'off']) !!}
		@endif
		{!! $errors->first('nama_bank', '<p class="help-block">:message</p>') !!}
	</div>
</div>


<div class="form-group{{ $errors->has('no_rekening') ? ' has-error' : '' }}">
	{!! Form::label('no_rekening', 'No Rekening', ['class'=>'col-md-2 control-label']) !!}
	<div class="col-md-4">
		@if (isset($komunitas) && $komunitas->bank_komunitas)
		{!! Form::text('no_rekening', $komunitas->bank_komunitas->no_rek, ['class'=>'form-control','placeholder' => 'No Rekening','required','autocomplete'=>'off']) !!}
		@else
		{!! Form::text('no_rekening', null, ['class'=>'form-control','placeholder' => 'No Rekening','required','autocomplete'=>'off']) !!}		
		@endif

		
		{!! $errors->first('no_rekening', '<p class="help-block">:message</p>') !!}
	</div>
</div>


<div class="form-group{{ $errors->has('an_rekening') ? ' has-error' : '' }}">
	{!! Form::label('an_rekening', 'A.N Rekening', ['class'=>'col-md-2 control-label']) !!}
	<div class="col-md-4">
		@if (isset($komunitas) && $komunitas->bank_komunitas)
		{!! Form::text('an_rekening', $komunitas->bank_komunitas->atas_nama, ['class'=>'form-control','placeholder' => 'No Rekening','required','autocomplete'=>'off']) !!}
		@else
		{!! Form::text('an_rekening', null, ['class'=>'form-control','placeholder' => 'A.N Rekening','required','autocomplete'=>'off']) !!}		
		@endif

		{!! $errors->first('an_rekening', '<p class="help-block">:message</p>') !!}
	</div>
</div>



<div class="form-group">
	<div class="col-md-4 col-md-offset-2">
			{!! Form::button('<i class="material-icons">send</i> Submit', ['class'=>'btn btn-primary', 'id'=>'btnSimpanWarung', 'type'=>'submit']) !!}
	</div>
</div>
