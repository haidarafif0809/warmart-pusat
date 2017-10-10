<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
	{!! Form::label('name', 'Nama Warung', ['class'=>'col-md-2 control-label']) !!}
	<div class="col-md-4">
		{!! Form::text('name', null, ['class'=>'form-control','placeholder' => 'Nama Warung','required','autocomplete'=>'off', 'id' => 'nama_warung']) !!}
		{!! $errors->first('name', '<p class="help-block" id="nama_warung_error">:message</p>') !!}
	</div>
</div>

<div class="form-group{{ $errors->has('no_telpon') ? ' has-error' : '' }}">
	{!! Form::label('no_telpon', 'No. Telpon', ['class'=>'col-md-2 control-label']) !!}
	<div class="col-md-4">
		{!! Form::number('no_telpon', null, ['class'=>'form-control','required','autocomplete'=>'off', 'placeholder' => 'No. Telpon', 'id' => 'no_telpon']) !!}
		{!! $errors->first('no_telpon', '<p class="help-block" id="no_telp_error">:message</p>') !!}
	</div>
</div>

@if (isset($warung) && $warung->email)
<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}" style="display: none">
	{!! Form::label('email', 'Email', ['class'=>'col-md-2 control-label']) !!}
	<div class="col-md-4">
		{!! Form::text('email', null, ['class'=>'form-control','required','autocomplete'=>'off', 'placeholder' =>'Email']) !!}
		{!! $errors->first('email', '<p class="help-block">:message</p>') !!}
	</div>
</div> 
@else
<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
	{!! Form::label('email', 'Email', ['class'=>'col-md-2 control-label']) !!}
	<div class="col-md-4">
		{!! Form::email('email', null, ['class'=>'form-control','required','autocomplete'=>'off', 'placeholder' =>'Email']) !!}
		{!! $errors->first('email', '<p class="help-block">:message</p>') !!}
	</div>
</div> 
@endif



<div class="form-group{{ $errors->has('nama_bank') ? ' has-error' : '' }}">
	{!! Form::label('nama_bank', 'Nama Bank', ['class'=>'col-md-2 control-label ']) !!}
	<div class="col-md-4">
	@if (isset($warung) && $warung->bank_warung->nama_bank)
		{!! Form::text('nama_bank', $warung->bank_warung->nama_bank, ['class'=>'form-control','placeholder'=>'Nama Bank','required','autocomplete'=>'off', 'id'=>'nama_bank']) !!}
		{!! $errors->first('nama_bank', '<p class="help-block" id="nama_bank_error">:message</p>') !!}
	@else
		{!! Form::text('nama_bank', null, ['class'=>'form-control','placeholder'=>'Nama Bank','required','autocomplete'=>'off', 'id'=>'nama_bank']) !!}
		{!! $errors->first('nama_bank', '<p class="help-block" id="nama_bank_error">:message</p>') !!}
	@endif
	</div>
</div>

<div class="form-group{{ $errors->has('atas_nama') ? ' has-error' : '' }}">
	{!! Form::label('atas_nama', 'Atas Nama', ['class'=>'col-md-2 control-label ']) !!}
	<div class="col-md-4">
	@if (isset($warung) && $warung->bank_warung->atas_nama)
		{!! Form::text('atas_nama', $warung->bank_warung->atas_nama, ['class'=>'form-control','placeholder'=>'Atas Nama','required','autocomplete'=>'off', 'id'=>'atas_nama']) !!}
		{!! $errors->first('atas_nama', '<p class="help-block" id="atas_nama_error">:message</p>') !!}
	@else
		{!! Form::text('atas_nama', null, ['class'=>'form-control','placeholder'=>'Atas Nama','required','autocomplete'=>'off', 'id'=>'atas_nama']) !!}
		{!! $errors->first('atas_nama', '<p class="help-block" id="atas_nama_error">:message</p>') !!}
	@endif
	</div>
</div>

<div class="form-group{{ $errors->has('no_rek') ? ' has-error' : '' }}">
	{!! Form::label('no_rek', 'No. Rekening', ['class'=>'col-md-2 control-label ']) !!}
	<div class="col-md-4">
	@if (isset($warung) && $warung->bank_warung->no_rek)
		{!! Form::text('no_rek', $warung->bank_warung->no_rek, ['class'=>'form-control','placeholder'=>'No. Rekening','required','autocomplete'=>'off', 'id'=>'no_rek']) !!}
		{!! $errors->first('no_rek', '<p class="help-block" id="no_rek_error">:message</p>') !!}
	@else
		{!! Form::text('no_rek', null, ['class'=>'form-control','placeholder'=>'No. Rekening','required','autocomplete'=>'off', 'id'=>'no_rek']) !!}
		{!! $errors->first('no_rek', '<p class="help-block" id="no_rek_error">:message</p>') !!}
	@endif
	</div>
</div>


<div class="form-group{{ $errors->has('alamat') ? ' has-error' : '' }}">
	{!! Form::label('alamat', 'Alamat', ['class'=>'col-md-2 control-label']) !!}
	<div class="col-md-4">
		{!! Form::text('alamat', null, ['class'=>'form-control','placeholder' => 'Alamat','required','autocomplete'=>'off', 'id' => 'alamat_warung']) !!}
		{!! $errors->first('alamat', '<p class="help-block" id="alamat_warung_error">:message</p>') !!}
	</div>
</div>

<div class="form-group{{ $errors->has('kelurahan') ? ' has-error' : '' }}">
	{!! Form::label('kelurahan', 'Kelurahan', ['class'=>'col-md-2 control-label']) !!}
	<div class="col-md-4">
		@if (isset($warung) && $warung->wilayah)
		{!! Form::select('kelurahan', 
		[''=>'']+App\Kelurahan::pluck('nama','id')->all(),$warung->wilayah
		, ['class'=>'form-control js-selectize-reguler', 'placeholder' => '--SILAKAN PILIH--','id'=>'pilih_kelurahan']) !!}
		{!! $errors->first('kelurahan', '<p class="help-block">:message</p>') !!}
		@else
		{!! Form::select('kelurahan', 
		[''=>'']+App\Kelurahan::pluck('nama','id')->all(),null
		, ['class'=>'form-control js-selectize-reguler', 'placeholder' => '--SILAKAN PILIH--','id'=>'pilih_kelurahan']) !!}
		{!! $errors->first('kelurahan', '<p class="help-block">:message</p>') !!}
		@endif
	</div>
</div>


<div class="form-group">
	<div class="col-md-4 col-md-offset-2">
			{!! Form::button('<i class="material-icons">send</i> Submit', ['class'=>'btn btn-primary', 'id'=>'btnSimpanStokingCenter', 'type'=>'submit']) !!}
	</div>
</div>
