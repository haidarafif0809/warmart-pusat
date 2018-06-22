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

<div class="form-group{{ $errors->has('status_kas') ? ' has-error' : '' }}">
    {!! Form::label('status_kas', 'Tampil Ditransaksi', ['class'=>'col-md-2 control-label']) !!}
        <div class="col-md-1">
            <div class="togglebutton">
                <label>
                    @if (isset($kas) && $kas->status_kas == 1)
                    <input type="checkbox" name="status_kas" id="status_kas" value="1" checked="">                        
                    @else
                    <input type="checkbox" name="status_kas" id="status_kas" value="1">
                    @endif
                </label>
            </div>
        </div>
</div>

<div class="form-group{{ $errors->has('default_kas') ? ' has-error' : '' }}">
    {!! Form::label('default_kas', 'Kas Utama', ['class'=>'col-md-2 control-label']) !!}
        <div class="col-md-1">
            <div class="togglebutton">
                <label>
                    @if (isset($kas) && $kas->default_kas == 1)
                    <input type="checkbox" name="default_kas" id="default_kas" value="1" checked="" data_toogle = "0">
                    @else
                    <input type="checkbox" name="default_kas" id="default_kas" value="1" data_toogle = "1">
                    @endif
                </label>
            </div>
        </div>
</div>


<div class="form-group">
	<div class="col-md-4 col-md-offset-2">
	<p style="color: red; font-style: italic;">*Note : Hanya 1 Kas yang dijadikan default.</p>
	{!! Form::button('<i class="material-icons">send</i> Submit', ['class'=>'btn btn-primary', 'id'=>'btnSimpanKas', 'type'=>'submit']) !!} 
	</div>
</div>