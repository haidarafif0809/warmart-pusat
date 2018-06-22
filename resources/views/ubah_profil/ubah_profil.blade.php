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

      {!! Form::model($user, ['url' => route('user.proses_ubah_profil', $user->id), 'method' => 'put', 'files'=>'true','class'=>'form-horizontal']) !!}

      @if(Auth::user()->tipe_user == 1)
      <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
        {!! Form::label('name', 'Nama', ['class'=>'col-md-2 control-label']) !!}
        <div class="col-md-4">
          {!! Form::text('name', null, ['class'=>'form-control','required','autocomplete'=>'off', 'placeholder' => 'Nama']) !!}
          {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
        </div>
      </div>

      <div class="form-group{{ $errors->has('no_telp') ? ' has-error' : '' }}">
        {!! Form::label('no_telp', 'No Telp', ['class'=>'col-md-2 control-label']) !!}
        <div class="col-md-4">
          {!! Form::number('no_telp', null, ['class'=>'form-control','placeholder'=>'No Telp','required','autocomplete'=>'off']) !!}
          {!! $errors->first('no_telp', '<p class="help-block">:message</p>') !!}
        </div>
      </div>

      <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
        {!! Form::label('email', 'Email', ['class'=>'col-md-2 control-label']) !!}
        <div class="col-md-4">
          {!! Form::email('email', null, ['class'=>'form-control','required','autocomplete'=>'off', 'placeholder' =>'Email']) !!}
          {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
        </div>
      </div> 

      <div class="form-group{{ $errors->has('alamat') ? ' has-error' : '' }}">
        {!! Form::label('alamat', 'Alamat', ['class'=>'col-md-2 control-label']) !!}
        <div class="col-md-4">
          {!! Form::text('alamat', null, ['class'=>'form-control','required','autocomplete'=>'off', 'placeholder' => 'Alamat']) !!}
          {!! $errors->first('alamat', '<p class="help-block">:message</p>') !!}
        </div>
      </div>

      {!! Form::hidden('id_ubah_profil', 1, ['class'=>'form-control','autocomplete'=>'off']) !!}

      @elseif(Auth::user()->tipe_user == 2)
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


      <div class="form-group{{ $errors->has('nama_penggiat') ? ' has-error' : '' }}">
        {!! Form::label('nama_penggiat', 'Nama Penggiat', ['class'=>'col-md-2 control-label']) !!}
        <div class="col-md-4">
          @if (isset($komunitas) && $komunitas->komunitas_penggiat)
          {!! Form::text('nama_penggiat', $komunitas->komunitas_penggiat->nama_penggiat, ['class'=>'form-control','placeholder' => 'Nama Penggiat','autocomplete'=>'off']) !!}
          @else
          {!! Form::text('nama_penggiat', null, ['class'=>'form-control','placeholder' => 'Nama Penggiat','autocomplete'=>'off']) !!}
          @endif
          {!! $errors->first('nama_penggiat', '<p class="help-block">:message</p>') !!}
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
          {!! Form::text('alamat_penggiat', $komunitas->komunitas_penggiat->alamat_penggiat, ['class'=>'form-control','placeholder' => 'Alamat Penggiat','autocomplete'=>'off']) !!}
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
          {!! Form::text('nama_bank', null, ['class'=>'form-control','placeholder' => 'Nama Bank','autocomplete'=>'off']) !!}
          {!! $errors->first('nama_bank', '<p class="help-block">:message</p>') !!}
        </div>
      </div>


      <div class="form-group{{ $errors->has('no_rekening') ? ' has-error' : '' }}">
        {!! Form::label('no_rekening', 'No Rekening', ['class'=>'col-md-2 control-label']) !!}
        <div class="col-md-4">
          {!! Form::text('no_rekening', null, ['class'=>'form-control','placeholder' => 'No Rekening','autocomplete'=>'off']) !!}
          {!! $errors->first('no_rekening', '<p class="help-block">:message</p>') !!}
        </div>
      </div>


      <div class="form-group{{ $errors->has('an_rekening') ? ' has-error' : '' }}">
        {!! Form::label('an_rekening', 'A.N Rekening', ['class'=>'col-md-2 control-label']) !!}
        <div class="col-md-4">
          {!! Form::text('an_rekening', null, ['class'=>'form-control','placeholder' => 'A.N Rekening','autocomplete'=>'off']) !!}
          {!! $errors->first('an_rekening', '<p class="help-block">:message</p>') !!}
        </div>
      </div>

      {!! Form::hidden('id_ubah_profil', 2, ['class'=>'form-control','autocomplete'=>'off']) !!}



      @elseif(Auth::user()->tipe_user == 3)
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
          {!! Form::email('email', null, ['class'=>'form-control','autocomplete'=>'off', 'placeholder' => 'Email Customer', 'id' => 'email_customer']) !!}
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

          {!! Form::text('tgl_lahir', $tanggal, ['class'=>'form-control datepicker', 'id'=>'datepicker','placeholder'=>'Tanggal Lahir','readonly']) !!}
          {!! $errors->first('tgl_lahir', '<p class="help-block">:message</p>') !!}

          @else

          {!! Form::text('tgl_lahir', null, ['class'=>'form-control datepicker', 'id'=>'datepicker','placeholder'=>'Tanggal Lahir','readonly']) !!}
          {!! $errors->first('tgl_lahir', '<p class="help-block">:message</p>') !!}

          @endif    
        </div>
      </div>

      <div class="form-group{{ $errors->has('komunitas') ? ' has-error' : '' }}">
        {!! Form::label('komunitas', 'Komunitas', ['class'=>'col-md-2 control-label']) !!}
        <div class="col-md-4">
          @if (isset($komunitas_customer))
          {!! Form::select('komunitas', 
          [''=>'']+App\Komunitas::where('tipe_user','2')->pluck('name','id')->all(),$komunitas_customer->komunitas_id
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

      {!! Form::hidden('id_ubah_profil', 3, ['class'=>'form-control','autocomplete'=>'off']) !!}
      @elseif(Auth::user()->tipe_user == 4)
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
          {!! Form::email('email', null, ['class'=>'form-control','required','autocomplete'=>'off', 'placeholder' =>'Email']) !!}
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

      <div class="form-group{{ $errors->has('id_warung') ? ' has-error' : '' }}">
        {!! Form::label('id_warung', 'Warung', ['class'=>'col-md-2 control-label']) !!}
        <div class="col-md-4">
          @if (isset($user_warung) && $user_warung->id_warung)
          {!! Form::select('id_warung', 
          [''=>'']+App\Warung::pluck('name','id')->all(),$user_warung->id_warung
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


      {!! Form::hidden('id_ubah_profil', 4, ['class'=>'form-control','autocomplete'=>'off']) !!}

      @endif

      <div class="col-md-2"></div>
      <div class="col-md-4">
        {!! Form::button('Ubah Profil', ['class'=>'btn btn-primary', 'type'=>'submit']) !!}
      </div>

      {!! Form::close() !!}

    </div>

  </div>
</div>
</div>
</div>


@endsection