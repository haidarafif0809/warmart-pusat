@extends('layouts.app')
@section('content')

<!-- LOGI USER WARUNG -->
@if(Auth::user()->tipe_user == 4 AND Auth::user()->foto_ktp == "")
<div class="alert alert-info">
 <div class="alert-icon">
  <i class="material-icons">info_outline</i>
</div>
<b>Info : Silakan lengkapi profil untuk mengakses fitur warung.</b>
</div>
@endif

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

      {!! Form::model($user, ['url' => route('user.proses_ubah_profil_warung'), 'method' => 'put', 'files'=>'true','class'=>'form-horizontal']) !!}
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


      <div class="form-group{{ $errors->has('kelurahan') ? ' has-error' : '' }}">
        {!! Form::label('kelurahan', 'Kelurahan', ['class'=>'col-md-2 control-label']) !!}
        <div class="col-md-4">
          @if (isset($user_warung) && $user_warung->wilayah)
          {!! Form::select('kelurahan', 
          [''=>'']+App\Kelurahan::pluck('nama','id')->all(),$user_warung->wilayah
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

      {!! Form::hidden('id', $user->id, ['class'=>'form-control','autocomplete'=>'off']) !!}
      
      <div class="form-group{{ $errors->has('foto_ktp') ? ' has-error' : '' }}">
        {!! Form::label('foto_ktp', 'Foto KTP', ['class'=>'col-md-2 control-label']) !!}
        <div class="col-md-4">
          <div class="fileinput fileinput-new text-center" data-provides="fileinput">
            <div class="fileinput-new thumbnail">

              @if (isset($user_warung) && $user_warung->foto_ktp)
              <p>
                {!! Html::image(asset('foto_ktp_user/'.$user_warung->foto_ktp), null, ['class' => 'img-rounded img-responsive']) !!}
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
                {!! Form::file('foto_ktp',null,['id'=>'foto_ktp']) !!}
              </span>
              <a href="#pablo" class="btn btn-danger btn-round fileinput-exists" data-dismiss="fileinput"><i class="fa fa-times"></i> Batal</a>
            </div>
            {!! $errors->first('foto_ktp', '<p class="help-block">:message</p>') !!}
            <a style="color: red;">Size Foto (Ukuran Max : 3MB)</a>
          </div>
        </div>
      </div> 

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