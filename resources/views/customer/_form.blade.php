<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
	{!! Form::label('name', 'Nama Customer', ['class'=>'col-md-2 control-label']) !!}
	<div class="col-md-4">
		{!! Form::text('name', null, ['class'=>'form-control','required','autocomplete'=>'off', 'placeholder' => 'Nama Customer', 'id' => 'nama_customer']) !!}
		{!! $errors->first('name', '<p class="help-block">:message</p>') !!}
	</div>
</div>


<div class="form-group{{ $errors->has('no_telp') ? ' has-error' : '' }}">
	{!! Form::label('no_telp', 'No. Telpon', ['class'=>'col-md-2 control-label']) !!}
	<div class="col-md-4">
		{!! Form::number('no_telp', null, ['class'=>'form-control','required','autocomplete'=>'off', 'placeholder' => 'No. Telpon', 'id' => 'telpon_customer']) !!}
		{!! $errors->first('no_telp', '<p class="help-block" id="no_telp_error">:message</p>') !!}
	</div>
</div>

<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
	{!! Form::label('email', 'Email Customer', ['class'=>'col-md-2 control-label']) !!}
	<div class="col-md-4">
		{!! Form::text('email', null, ['class'=>'form-control','autocomplete'=>'off', 'placeholder' => 'Email Customer', 'id' => 'email_customer']) !!}
		{!! $errors->first('email', '<p class="help-block" id="email_error">:message</p>') !!}
	</div>
</div> 

<div class="form-group{{ $errors->has('alamat') ? ' has-error' : '' }}">
	{!! Form::label('alamat', 'Alamat Customer', ['class'=>'col-md-2 control-label']) !!}
	<div class="col-md-4">
		{!! Form::text('alamat', null, ['class'=>'form-control','required','autocomplete'=>'off', 'placeholder' => 'Alamat Customer', 'id' => 'alamat_customer']) !!}
		{!! $errors->first('alamat', '<p class="help-block">:message</p>') !!}
	</div>
</div>

<div class="form-group{{ $errors->has('tgl_lahir') ? ' has-error' : '' }}">
	{!! Form::label('tgl_lahir', 'Tanggal Lahir', ['class'=>'col-md-2 control-label']) !!}
	<div class="col-md-4">
		@if (isset($customer) && $customer)

			{!! Form::text('tgl_lahir', $tanggal, ['class'=>'form-control datepicker', 'id'=>'datepicker1','placeholder'=>'Tanggal Lahir']) !!}
			{!! $errors->first('tgl_lahir', '<p class="help-block">:message</p>') !!}

		@else

			{!! Form::text('tgl_lahir', null, ['class'=>'form-control datepicker', 'id'=>'datepicker1','placeholder'=>'Tanggal Lahir']) !!}
			{!! $errors->first('tgl_lahir', '<p class="help-block">:message</p>') !!}

		@endif		
	</div>
</div>
 
<div class="form-group{{ $errors->has('komunitas') ? ' has-error' : '' }}">
	{!! Form::label('komunitas', 'Komunitas', ['class'=>'col-md-2 control-label']) !!}
	<div class="col-md-4">
		@if (isset($komunitas))
		{!! Form::select('komunitas', 
		[''=>'']+App\Komunitas::where('tipe_user','2')->pluck('name','id')->all(),$komunitas->komunitas_id
		, ['class'=>'form-control js-selectize-reguler', 'placeholder' => 'Silahkan Pilih','id'=>'pilih_komunitas']) !!}
		{!! $errors->first('komunitas', '<p class="help-block">:message</p>') !!}
		@else
		{!! Form::select('komunitas', 
		[''=>'']+App\Komunitas::where('tipe_user','2')->pluck('name','id')->all(),null
		, ['class'=>'form-control js-selectize-reguler', 'placeholder' => 'Silahkan Pilih','id'=>'pilih_komunitas']) !!}
		{!! $errors->first('komunitas', '<p class="help-block">:message</p>') !!}
		@endif
	</div>
</div>

<div class="form-group">
	<div class="col-md-4 col-md-offset-2">
		{!! Form::button('<i class="material-icons">send</i> Submit', ['class'=>'btn btn-primary', 'id'=>'btnSimpanCustomer', 'type'=>'submit']) !!}
	</div>
</div>
