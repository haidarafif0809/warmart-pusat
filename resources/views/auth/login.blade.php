@extends('layouts.app_login')

@section('content')

@include('layouts._flash_login')

<!-- PILIH TIPE APLIKASI -->
<?php
$session_id    = session()->getId();
$setting_aplikasi = \App\SettingAplikasi::select('tipe_aplikasi')->first();
?>

@if ($errors->has('no_telp'))
<div class="alert alert-danger alert-with-icon">
    <i class="material-icons" data-notify="icon">
        error_outline
    </i>
    <button aria-label="Close" class="close" data-dismiss="alert" type="button">
        x
    </button>
    <span data-notify="message">
        <b>
            Failed:
        </b>
        {{ $errors->first('no_telp') }}
    </span>
</div>
@endif
<form action="{{ url('/login') }}" method="POST">
    {{ csrf_field() }}
    <div class="card card-login ">
        <div class="card-header text-center" data-background-color="blue">
            <h4 class="card-title">
                Login
            </h4>
        </div>
        <div class="card-content">
            <div class="input-group ">
                <span class="input-group-addon">
                    <i class="material-icons">
                        phone
                    </i>
                </span>
                <div class="form-group label-floating ">
                    <input autocomplete="off" class="form-control" name="no_telp" placeholder="Nomor Telpon" type="number" value="{{ old('no_telp') }}">
                </input>
            </div>
        </div>
        <div class="input-group">
            <span class="input-group-addon">
                <i class="material-icons">
                    lock_outline
                </i>
            </span>
            <div class="form-group label-floating ">
                <input class="form-control" name="password" placeholder="Password" type="password">
                <input  class="form-control" name="session_id" type="hidden" id="session_id">
                <input  class="form-control" name="status_login" type="hidden" id="status_login" value="0">
                @if ($errors->has('password'))
                <span class="label label-danger">
                    <strong>
                        {{ $errors->first('password') }}
                    </strong>
                </span>
                @endif
            </input>
        </div>
    </div>
</div>
<div class="footer text-center">
    <center>
        <a href="{{ url('/lupa-password/') }}" style="padding-right: 40%;font-size: 90%">
            Lupa Password
        </a>
    </center>
    <button class="btn btn-rose btn-simple btn-wd btn-lg" id="login" type="submit">
        Login
    </button>
</div>
<center>
    <p>
        Belum Daftar?
        @if($setting_aplikasi->tipe_aplikasi == 0)
        <a class="swal-pendaftaran" href="#"> Daftar Sekarang </a>
        @else
        <a href="{{ url('/register-customer') }}"> Daftar Sekarang </a>
        @endif
    </p>
</center>
</div>
</form>
@endsection 

@section('scripts')
<script type="text/javascript">
    $('.swal-pendaftaran').click(function(){
        swal({
            title: 'Daftar Sebagai?',
            html:
            '<li class="" style="list-style-type:none"><a href="{{ url('/register-customer') }}"  class="btn btn-info"><i class="material-icons">person_add</i> Pelanggan</a></li><li class="" style="list-style-type:none"><a href="{{ url('/register') }}"  class="btn btn-success"><i class="material-icons">people</i> Komunitas</a></li><li class=""  style="list-style-type:none"><a href="{{ url('/register-warung') }}"  class="btn btn-warning"><i class="material-icons">store</i> Warung</a></li> ',
            showConfirmButton :  false,
        });
    });
</script>
@endsection
