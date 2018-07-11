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
{!! Form::open(['url' => url('/register'),'method' => 'post', 'class'=>'form-horizontal']) !!}
{{ csrf_field() }}
<div class="card card-login ">
    <div class="card-header text-center" data-background-color="blue">
        <h4 class="card-title">
            Registrasi Pelanggan
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
                <div class="form-group label-floating">
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
                    {!! Form::email('email', null, ['class'=>'form-control','required','autocomplete'=>'off','placeholder'=>'Email']) !!}
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
                    <div class="form-group label-floating ">
                        {!! Form::text('alamat', null, ['class'=>'form-control','required','autocomplete'=>'off','placeholder'=>'Alamat']) !!}
                        {!! $errors->first('alamat', '
                            <p class="label label-danger">
                                :message
                            </p>
                            ') !!}
                        </div>
                    </div>

                    @if($setting_aplikasi == 0) <!--JIKA TIPE APLIKASI == 0, maka tampil komunitas / Warmart-->
                    <div class="input-group ">
                        <span class="input-group-addon">
                            <i class="material-icons">
                                people
                            </i>
                        </span>
                        <div class="form-group label-floating ">
                            {!! Form::select('komunitas', [''=>'']+App\Komunitas::where('tipe_user',2)->pluck('name','id')->all(),null, ['class'=>'js-selectize-reguler', 'placeholder' => 'Pilih Komunitas (tidak wajib)','id' => 'komunitas']) !!}
                            {!! $errors->first('alamat', '
                                <p class="label label-danger">
                                    :message
                                </p>
                                ') !!}
                            </div>
                        </div>
                        @else <!--JIKA TIPE APLIKASI != 0, maka tidak tampil komunitas / Topos-->
                        <div class="input-group" style="display: none">
                            <span class="input-group-addon">
                                <i class="material-icons">
                                    people
                                </i>
                            </span>
                            <div class="form-group label-floating ">
                                {!! Form::select('komunitas', [''=>'']+App\Komunitas::where('tipe_user',2)->pluck('name','id')->all(),null, ['class'=>'js-selectize-reguler', 'placeholder' => 'Pilih Komunitas (tidak wajib)','id' => 'komunitas']) !!}
                                {!! $errors->first('alamat', '
                                    <p class="label label-danger">
                                        :message
                                    </p>
                                    ') !!}
                                </div>
                            </div>
                            @endif

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
                                <input data_toogle="0" id="ceklis_syarat" name="ceklis_syarat" type="checkbox" value="0">
                            </input>
                        </label>
                        <b>
                         @if(\App\SettingAplikasi::select('tipe_aplikasi')->first()->tipe_aplikasi == 0)
                            * Anda memahami & menyetujui
                                                <a href="{{ url('/syarat-ketentuan') }}" target="_blank">
                                                    <u>
                                                        Syarat & Ketentuan
                                                    </u>
                                                </a>
                            @else
                            * Anda memahami & menyetujui
                                                <a href="{{ url('/syarat-ketentuan-topos') }}" target="_blank">
                                                    <u>
                                                        Syarat & Ketentuan
                                                    </u>
                                                </a>
                            @endif
                        </b>
                    </div>
                </div>
                {!! Form::hidden('id_register', 1, ['class'=>'form-control','autocomplete'=>'off']) !!}

                @if(isset($komunitas_id))
                {!! Form::hidden('komunitas_id', $komunitas_id, ['class'=>'form-control','autocomplete'=>'off']) !!}
                @endif
            </div>
            <div class="footer text-center">
                <button class="btn btn-rose btn-simple btn-wd btn-lg" disabled="" id="tombol_regist" type="submit">
                    Registrasi Pelanggan
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
        {!! Form::close() !!}
        @endsection
        @section('scripts')
        <script type="text/javascript">
            $('select').selectize({
             sortField: 'text'
         });
     </script>
     @endsection
