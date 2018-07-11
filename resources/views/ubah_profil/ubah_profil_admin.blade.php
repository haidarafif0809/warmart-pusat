@extends('layouts.app')
@section('content')
<div class="row">
  <div class="col-md-12">
   <ul class="breadcrumb">
    <li><a href="{{ url('/home') }}">Home</a></li>
    <li class="active">Ubah Profil</li>
  </ul>


  <div class="card">
    <div class="card-header card-header-icon" data-background-color="purple">
     <i class="material-icons">account_circle</i>
   </div>
   <div class="card-content">
     <h4 class="card-title"> Ubah Profil </h4>
     <div class="toolbar">

      {!! Form::model($user, ['url' => route('user.proses_ubah_profil_admin'), 'method' => 'put', 'files'=>'true','class'=>'form-horizontal']) !!}
      <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
        {!! Form::label('name', 'Nama', ['class'=>'col-md-2 control-label']) !!}
        <div class="col-md-4">
          {!! Form::text('name', null, ['class'=>'form-control','placeholder' => 'Nama','required','autocomplete'=>'off']) !!}
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
        {!! Form::label('email', 'Email', ['class'=>'col-md-2 control-label']) !!}
        <div class="col-md-4">
          {!! Form::text('email', null, ['class'=>'form-control','placeholder' => 'Email','required','autocomplete'=>'off']) !!}
          {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
        </div>
      </div>

      <div class="form-group{{ $errors->has('alamat') ? ' has-error' : '' }}">
        {!! Form::label('alamat', 'Alamat', ['class'=>'col-md-2 control-label']) !!}
        <div class="col-md-4">
          {!! Form::text('alamat', null, ['class'=>'form-control','placeholder' => 'Alamat','required','autocomplete'=>'off']) !!}
          {!! $errors->first('alamat', '<p class="help-block">:message</p>') !!}
        </div>
      </div> 

      {!! Form::hidden('id', $user->id, ['class'=>'form-control','autocomplete'=>'off']) !!}
      
      <div class="form-group">
        <div class="col-md-4 col-md-offset-2">
          {!! Form::button('<i class="material-icons">send</i> Submit', ['class'=>'btn btn-primary', 'id'=>'btnSimpanWarung', 'type'=>'submit']) !!}
        </div>
      </div>

      {!! Form::close() !!}

    </div>

  </div>
</div>
</div>
</div>


@endsection