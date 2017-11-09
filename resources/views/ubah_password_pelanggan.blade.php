@extends('layouts.app_pelanggan')

@section('content')
<style type="text/css">
    #card-ubah-profil{ 
        background: #fafafa;; 
        position: relative; 
        z-index: 3; 

        margin: -60px 30px 60px; 
        border-radius: 6px; 
        box-shadow: 0 16px 24px 2px rgba(0, 0, 0, 0.14), 0 6px 30px 5px rgba(0, 0, 0, 0.12), 0 8px 10px -5px rgba(0, 0, 0, 0.2); 
    } 
    #card-ubah-profil-mobile{ 
        background: #fafafa;; 
        position: relative; 
        z-index: 3; 

        margin: -30px 0px 60px;
        border-radius: 6px; 
        box-shadow: 0 16px 24px 2px rgba(0, 0, 0, 0.14), 0 6px 30px 5px rgba(0, 0, 0, 0.12), 0 8px 10px -5px rgba(0, 0, 0, 0.2); 
    } 
</style>
@if (Agent::isMobile()) <!--JIKA DAKSES VIA HP/TAB-->
<div class="page-header header-filter header-small" data-parallax="true"" style="background-image: url('image/background2.jpg');">
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="brand">
                    <h3 class="title">PASAR MUSLIM INDONESIA</h3>
                    <h6 class="title"> Segala Kemudahan Untuk Umat Muslim Berbelanja.</h6>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="card" id="card-ubah-profil-mobile"><br>
        <ul class="breadcrumb">
            <li><a href="{{ url('/daftar-produk') }}" style="color: purple">Home</a></li>
            <li class="active">Ubah Password Pelanggan</li>
        </ul>
    </div>

    <div class="card" id="card-ubah-profil-mobile" >
        <div class="card-header card-header-icon" data-background-color="rose">
            <i class="material-icons">lock_outline</i>
        </div>

        <div class="card-content">
            <h4 class="card-title"> Ubah Password </h4>
            <div class="toolbar">
                {!! Form::model($user, ['url' => route('user.proses_ubah_password_pelanggan', $user->id), 'method' => 'put', 'files'=>'true','class'=>'form-horizontal']) !!}
                <div class="col-md-1"></div>

                <div class="input-group col-md-10">
                    <span class="input-group-addon">
                     <i class="material-icons">lock_outline</i>
                 </span>
                 <div class="form-group label-floating {{ $errors->has('password') ? ' has-error' : '' }}">
                  <label class="control-label">Password Baru</label>
                  <input type="password" id="password" class="form-control" name="password" autocomplete="off">

                  <span class="help-block">
                      <strong>{{ $errors->first('password_baru') }}</strong>
                  </span>
              </div>
          </div> 

          <div class="col-md-1"></div>

          <div class="input-group col-md-10">
           <span class="input-group-addon">
            <i class="material-icons">lock_outline</i>
        </span>
        <div class="form-group label-floating">
            <label class="control-label">Konfirmasi Password Baru</label>
            <input type="password" id="password-confirm" class="form-control" name="password_confirmation" autocomplete="off">

            <span class="help-block">
              <strong>{{ $errors->first('konfirmasi_password') }}</strong>
          </span>

      </div>
  </div>
  <br>
  <div class="col-md-1"></div>
  <div class="col-md-4">
      {!! Form::button('<i class="material-icons">save</i> Ubah Password', ['class'=>'btn btn-rose', 'type'=>'submit', 'id' => 'btnSimpanPassword']) !!}
  </div>
  {!! Form::close() !!}
</div>
</div>
</div>
</div>
@else <!--JIKA DIAKSES VIA KOMPUTER-->
<div class="page-header header-filter header-small" data-parallax="true"" style="background-image: url('image/background2.jpg');">
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="brand">
                    <h1 class="title">PASAR MUSLIM INDONESIA</h1>
                    <h3 class="title"> Segala Kemudahan Untuk Umat Muslim Berbelanja.</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="main main-raised" >
    <div class="container">
        <div class="card" id="card-ubah-profil"><br>
            <ul class="breadcrumb">
                <li><a href="{{ url('/daftar-produk') }}" style="color: purple">Home</a></li>
                <li class="active">Ubah Password Pelanggan</li>
            </ul>
        </div>
        <br><br><br>
        <div class="card" id="card-ubah-profil" >
            <div class="card-header card-header-icon" data-background-color="rose">
                <i class="material-icons">lock_outline</i>
            </div>

            <div class="card-content">
                <h4 class="card-title"> Ubah Password </h4>
                <div class="toolbar">
                    {!! Form::model($user, ['url' => route('user.proses_ubah_password_pelanggan', $user->id), 'method' => 'put', 'files'=>'true','class'=>'form-horizontal']) !!}
                    <div class="col-md-1"></div>

                    <div class="input-group col-md-10">
                        <span class="input-group-addon">
                         <i class="material-icons">lock_outline</i>
                     </span>
                     <div class="form-group label-floating {{ $errors->has('password') ? ' has-error' : '' }}">
                      <label class="control-label">Password Baru</label>
                      <input type="password" id="password" class="form-control" name="password" autocomplete="off">

                      <span class="help-block">
                          <strong>{{ $errors->first('password_baru') }}</strong>
                      </span>
                  </div>
              </div> 

              <div class="col-md-1"></div>

              <div class="input-group col-md-10">
               <span class="input-group-addon">
                <i class="material-icons">lock_outline</i>
            </span>
            <div class="form-group label-floating">
                <label class="control-label">Konfirmasi Password Baru</label>
                <input type="password" id="password-confirm" class="form-control" name="password_confirmation" autocomplete="off">

                <span class="help-block">
                  <strong>{{ $errors->first('konfirmasi_password') }}</strong>
              </span>

          </div>
      </div>
      <br>
      <div class="col-md-1"></div>
      <div class="col-md-4">
          {!! Form::button('<i class="material-icons">save</i> Ubah Password', ['class'=>'btn btn-rose', 'type'=>'submit', 'id' => 'btnSimpanPassword']) !!}
      </div>
      {!! Form::close() !!}
  </div>
</div>
</div>
</div>
</div> <!-- end-main-raised -->
@endif
@endsection
{{-- div card ubah profil --}}

@section('scripts')
<script type="text/javascript">
 $(document).ready(function(){
    $("#password").focus();
});

 $(document).on('click', '#btnSimpanPassword', function(){
    swal("Berhasil!", "Password Berhasil Diubah", "success"); 
});
</script>
@endsection

