<div class="form-group{{ $errors->has('dari_kas') ? ' has-error' : '' }}"> 
  {!! Form::label('dari_kas', 'Dari Kas', ['class'=>'col-md-2 control-label']) !!} 
  <div class="col-md-4"> 
    {!! Form::select('dari_kas', $kas,null, ['class'=>'js-selectize-reguler', 'id'=>'dari_kas','placeholder' => 'Dari Kas']) !!} 
    {!! $errors->first('dari_kas', '<p class="help-block">:message</p>') !!} 
  </div> 
</div> 
<div class="form-group{{ $errors->has('ke_kas') ? ' has-error' : '' }}"> 
  {!! Form::label('ke_kas', 'Ke Kas', ['class'=>'col-md-2 control-label']) !!} 
  <div class="col-md-4"> 
    {!! Form::select('ke_kas', $kas,null, ['class'=>'js-selectize-reguler', 'placeholder' => 'Ke Kas']) !!} 
    {!! $errors->first('ke_kas', '<p class="help-block">:message</p>') !!} 
  </div> 
</div> 
 
<div class="form-group{{ $errors->has('jumlah') ? ' has-error' : '' }}"> 
  {!! Form::label('jumlah', 'Jumlah', ['class'=>'col-md-2 control-label']) !!} 
  <div class="col-md-4"> 
    {!! Form::text('jumlah', null, ['class'=>'form-control','id'=>'jumlah','required','autocomplete'=>'off', 'placeholder' => 'Jumlah ']) !!} 
    {!! $errors->first('jumlah', '<p class="help-block">:message</p>') !!} 
  </div> 
</div> 
<div class="form-group{{ $errors->has('keterangan') ? ' has-error' : '' }}"> 
  {!! Form::label('keterangan', 'Keterangan', ['class'=>'col-md-2 control-label']) !!} 
  <div class="col-md-4"> 
    {!! Form::text('keterangan', null, ['class'=>'form-control','autocomplete'=>'off', 'placeholder' => 'Keterangan ']) !!} 
    {!! $errors->first('keterangan', '<p class="help-block">:message</p>') !!} 
  </div> 
</div> 
 
{!! Form::hidden('sisa_kas', null, ['class'=>'form-control','required','autocomplete'=>'off','id'=>'sisa_kas', 'placeholder' => 'Jumlah ']) !!} 
 
{!! Form::hidden('no_faktur', null, null) !!} 
 
 
<div class="form-group"> 
  <div class="col-md-4 col-md-offset-2"> 
    {!! Form::button(' <i class="material-icons">save</i>Simpan', ['class'=>'btn btn-primary','type'=>'submit', 'id'=>'submit_kas']) !!} 
 
  </div> 
</div> 