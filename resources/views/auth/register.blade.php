@extends('layouts.app_login')

@section('content')
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
        Mohon Maaf Nomor Telfon Yang Anda Input Sudah Terdaftar
    </span>
</div>
@endif
<form action="{{ url('/register') }}" method="POST">
    {{ csrf_field() }}
    <div class="card card-login ">
        <div class="card-header text-center" data-background-color="blue">
            <h4 class="card-title">
                Registrasi Komunitas
            </h4>
        </div>
        <div class="card-content">
            <div class="input-group ">
                <span class="input-group-addon">
                    <i class="material-icons">
                        person
                    </i>
                </span>
                <div class="form-group label-floating ">
                    {!! Form::text('name', null, ['class'=>'form-control','required','autocomplete'=>'off','placeholder'=>'Nama']) !!}
                {!! $errors->first('name', '
                    <p class="label label-danger">
                        :message
                    </p>
                    ') !!}
                </div>
            </div>
            <div class="input-group ">
                <span class="input-group-addon">
                    <i class="material-icons">
                        local_phone
                    </i>
                </span>
                <div class="form-group label-floating ">
                    {!! Form::text('no_telp', null, ['class'=>'form-control','required','autocomplete'=>'off','placeholder'=>'Nomor Telpon']) !!}
                </div>
            </div>
            <div class="input-group ">
                <span class="input-group-addon">
                    <i class="material-icons">
                        email
                    </i>
                </span>
                <div class="form-group label-floating ">
                    {!! Form::email('email', null, ['class'=>'form-control','autocomplete'=>'off','placeholder'=>'Email']) !!}
                {!! $errors->first('email', '
                    <p class="label label-danger">
                        :message
                    </p>
                    ') !!}
                </div>
            </div>
            <div class="input-group ">
                <span class="input-group-addon">
                    <i class="material-icons">
                        home
                    </i>
                </span>
                <div class="form-group label-floating">
                    {!! Form::text('alamat', null, ['class'=>'form-control','required','autocomplete'=>'off','placeholder'=>'Alamat']) !!}
                {!! $errors->first('alamat', '
                    <p class="label label-danger">
                        :message
                    </p>
                    ') !!}
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
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="material-icons">
                        lock_outline
                    </i>
                </span>
                <div class="form-group label-floating">
                    <input class="form-control" id="password-confirm" name="password_confirmation" placeholder="Konfirmasi Password" type="password">
                    </input>
                </div>
            </div>
            <div class="input-group">
                <div class="checkbox" style="padding:10;font-size:12px;">
                    <label>
                        <input data_toogle="0" id="ceklis_syarat_komunitas" name="ceklis_syarat_komunitas" type="checkbox" value="0">
                        </input>
                    </label>
                    <b>
                        * Anda memahami & menyetujui
                        <a href="{{ url('/syarat-ketentuan') }}" target="_blank">
                            <u>
                                Syarat & Ketentuan
                            </u>
                        </a>
                    </b>
                </div>
            </div>
            {!! Form::hidden('id_register', 2, ['class'=>'form-control','autocomplete'=>'off']) !!}
        </div>
        <div class="footer text-center">
            <button class="btn btn-rose btn-simple btn-wd btn-lg" disabled="" id="tombol_regist_komunitas" type="submit">
                Registrasi Komunitas
            </button>
        </div>
        <center>
            <p>
                Sudah Daftar?
                <a href="{{ url('/login') }}">
                    Masuk Sekarang
                </a>
            </p>
        </center>
    </div>
</form>
@endsection
