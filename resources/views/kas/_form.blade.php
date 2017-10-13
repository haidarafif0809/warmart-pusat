<div class="form-group{{ $errors->has('kode_kas') ? ' has-error' : '' }}">
	{!! Form::label('kode_kas', 'Kode Kas', ['class'=>'col-md-2 control-label ']) !!}
	<div class="col-md-4">
		{!! Form::text('kode_kas', null, ['class'=>'form-control','placeholder'=>'Kode Kas','required','autocomplete'=>'off', 'id'=>'kode_kas']) !!}
		{!! $errors->first('kode_kas', '<p class="help-block" id="kode_kas_error">:message</p>') !!}
	</div>
</div>

<div class="form-group{{ $errors->has('nama_kas') ? ' has-error' : '' }}">
	{!! Form::label('nama_kas', 'Nama Kas', ['class'=>'col-md-2 control-label ']) !!}
	<div class="col-md-4">
		{!! Form::text('nama_kas', null, ['class'=>'form-control','placeholder'=>'Nama Kas','required','autocomplete'=>'off', 'id'=>'nama_kas']) !!}
		{!! $errors->first('nama_kas', '<p class="help-block" id="nama_kas_error">:message</p>') !!}
	</div>
</div>

@if (isset($kas->status_kas))
	
	<div class="form-group{{ $errors->has('status_kas') ? ' has-error' : '' }}">
		{!! Form::label('status_kas', 'Status Kas', ['class'=>'col-md-2 control-label']) !!}
		@if($kas->status_kas == 1)
			<div class="col-md-1">
				<div class="radio form-group">
					<label>
						<input type="radio" name="status_kas" id="status_kas_radioOn" value="1" checked="true">
						Aktif
					</label>
				</div>
			</div>
			<div class="col-md-1">
				<div class="radio form-group">
					<label>
						<input type="radio" name="status_kas" id="status_kas_radio" value="0">
						Tidak Aktif
					</label>
				</div>
			</div>
		@else
			<div class="col-md-1">
				<div class="radio form-group">
					<label>
						<input type="radio" name="status_kas" id="status_kas_radioOn" value="1">
						Aktif
					</label>
				</div>
			</div>
			<div class="col-md-1">
				<div class="radio form-group">
					<label>
						<input type="radio" name="status_kas" id="status_kas_radio" value="0" checked="true">
						Tidak Aktif
					</label>
				</div>
			</div>
		@endif
	</div>

@else

	<div class="form-group{{ $errors->has('status_kas') ? ' has-error' : '' }}">
		{!! Form::label('status_kas', 'Status Kas', ['class'=>'col-md-2 control-label']) !!}
		<div class="col-md-1">
			<div class="radio form-group">
				<label>
					<input type="radio" name="status_kas" id="status_kas_radioOn" value="1">
					Aktif
				</label>
			</div>
		</div>
		<div class="col-md-1">
			<div class="radio form-group">
				<label>
					<input type="radio" name="status_kas" id="status_kas_radio" value="0">
					Tidak Aktif
				</label>
			</div>
		</div>
	</div>

@endif

@if (isset($kas->default_kas))
	
	<div class="form-group{{ $errors->has('default_kas') ? ' has-error' : '' }}">
		{!! Form::label('default_kas', 'Default Kas', ['class'=>'col-md-2 control-label']) !!}
		@if($kas->default_kas == 1)
			<div class="col-md-1">
				<div class="radio form-group">
					<label>
						<input type="radio" name="default_kas" id="default_kas_radioOn" value="1" checked="true">
						Iya
					</label>
				</div>
			</div>
			<div class="col-md-1">
				<div class="radio form-group">
					<label>
						<input type="radio" name="default_kas" id="default_kas_radio" value="0">
						Tidak 
					</label>
				</div>
			</div>
		@else
			<div class="col-md-1">
				<div class="radio form-group">
					<label>
						<input type="radio" name="default_kas" id="default_kas_radioOn" value="1">
						Iya
					</label>
				</div>
			</div>
			<div class="col-md-1">
				<div class="radio form-group">
					<label>
						<input type="radio" name="default_kas" id="default_kas_radio" value="0" checked="true">
						Tidak 
					</label>
				</div>
			</div>
		@endif
	</div>

@else

	<div class="form-group{{ $errors->has('default_kas') ? ' has-error' : '' }}">
		{!! Form::label('default_kas', 'Default Kas', ['class'=>'col-md-2 control-label']) !!}
		<div class="col-md-1">
			<div class="radio form-group">
				<label>
					<input type="radio" name="default_kas" id="default_kas_radioOn" value="1">
					Iya
				</label>
			</div>
		</div>
		<div class="col-md-1">
			<div class="radio form-group">
				<label>
					<input type="radio" name="default_kas" id="default_kas_radio" value="0">
					Tidak 
				</label>
			</div>
		</div>	
	</div>
	
@endif


<div class="form-group">
	<div class="col-md-4 col-md-offset-2">
	<p style="color: red; font-style: italic;">*Note : Hanya 1 Kas yang dijadikan default.</p>
	{!! Form::button('<i class="material-icons">send</i> Submit', ['class'=>'btn btn-primary', 'id'=>'btnSimpanKas', 'type'=>'submit']) !!} 
	</div>
</div>