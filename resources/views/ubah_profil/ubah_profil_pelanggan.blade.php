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
            <li class="active">Ubah Profil Pelanggan</li>
        </ul>
    </div>

    <div class="card" id="card-ubah-profil-mobile" >
        <div class="card-header card-header-icon" data-background-color="rose">
            <i class="material-icons">settings</i>
        </div>

        <div class="card-content">
            <h4 class="card-title"> Ubah Profil </h4>
            <div class="toolbar">
                {!! Form::model($user, ['url' => route('user.proses_ubah_profil_pelanggan'), 'method' => 'put', 'files'=>'true','class'=>'form-horizontal']) !!}
                <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                    {!! Form::label('name', 'Nama Pelanggan', ['class'=>'col-md-2 control-label']) !!}
                    <div class="col-md-10">
                        {!! Form::text('name', null, ['class'=>'form-control','required','autocomplete'=>'off', 'placeholder' => 'Nama Pelanggan', 'id' => 'nama_pelanggan']) !!}
                        {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>

                <div class="form-group{{ $errors->has('no_telp') ? ' has-error' : '' }}">
                    {!! Form::label('no_telp', 'No. Telpon', ['class'=>'col-md-2 control-label']) !!}
                    <div class="col-md-10">
                        {!! Form::number('no_telp', null, ['class'=>'form-control','required','autocomplete'=>'off', 'placeholder' => 'No. Telpon', 'id' => 'telpon_pelanggan']) !!}
                        {!! $errors->first('no_telp', '<p class="help-block" id="no_telp_error">:message</p>') !!}
                    </div>
                </div>

                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                    {!! Form::label('email', 'Email Pelanggan', ['class'=>'col-md-2 control-label']) !!}
                    <div class="col-md-10">
                        {!! Form::email('email', null, ['class'=>'form-control','autocomplete'=>'off', 'placeholder' => 'Email Pelanggan', 'id' => 'email_pelanggan']) !!}
                        {!! $errors->first('email', '<p class="help-block" id="email_error">:message</p>') !!}
                    </div>
                </div> 

                <div class="form-group{{ $errors->has('alamat') ? ' has-error' : '' }}">
                    {!! Form::label('alamat', 'Alamat Pelanggan', ['class'=>'col-md-2 control-label']) !!}
                    <div class="col-md-10">
                        {!! Form::text('alamat', null, ['class'=>'form-control','required','autocomplete'=>'off', 'placeholder' => 'Alamat Pelanggan', 'id' => 'alamat_pelanggan']) !!}
                        {!! $errors->first('alamat', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>

                <div class="form-group{{ $errors->has('tgl_lahir') ? ' has-error' : '' }}">
                    {!! Form::label('tgl_lahir', 'Tanggal Lahir', ['class'=>'col-md-2 control-label']) !!}
                    <div class="col-md-10">
                        {!! Form::text('tgl_lahir',null, ['class'=>'form-control datepicker', 'id'=>'datepicker','placeholder'=>'Tanggal Lahir','readonly']) !!}
                        {!! $errors->first('tgl_lahir', '<p class="help-block">:message</p>') !!}

                    </div>
                </div>

                <div class="form-group{{ $errors->has('komunitas') ? ' has-error' : '' }}">
                    {!! Form::label('komunitas', 'Komunitas', ['class'=>'col-md-2 control-label']) !!}
                    <div class="col-md-10">
                        @if (isset($komunitas_pelanggan))
                        {!! Form::select('komunitas', [''=>'']+App\Komunitas::where('tipe_user','2')->pluck('name','id')->all(),$komunitas_pelanggan->komunitas_id, ['class'=>'form-control js-selectize-reguler', 'placeholder' => '--PILIH KOMUNITAS--','id'=>'pilih_komunitas']) !!}
                        {!! $errors->first('komunitas', '<p class="help-block">:message</p>') !!}
                        @else
                        {!! Form::select('komunitas', 
                        [''=>'']+App\Komunitas::where('tipe_user','2')->pluck('name','id')->all(),null
                        , ['class'=>'form-control js-selectize-reguler', 'placeholder' => '--PILIH KOMUNITAS--','id'=>'pilih_komunitas']) !!}
                        {!! $errors->first('komunitas', '<p class="help-block">:message</p>') !!}
                        @endif
                    </div>
                </div>
                {!! Form::hidden('id', $user->id, ['class'=>'form-control','autocomplete'=>'off']) !!}
                <div class="col-md-2"></div>
                <div class="col-md-4">
                    {!! Form::button('<i class="material-icons">save</i>Simpan Profil', ['class'=>'btn btn-rose ', 'type'=>'submit', 'id' => 'btnSimpanProfil']) !!}
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
                <li class="active">Ubah Profil Pelanggan</li>
            </ul>
        </div>

        <div class="card" id="card-ubah-profil" >
            <div class="card-header card-header-icon" data-background-color="rose">
                <i class="material-icons">settings</i>
            </div>

            <div class="card-content">
                <h4 class="card-title"> Ubah Profil </h4>
                <div class="toolbar">
                    {!! Form::model($user, ['url' => route('user.proses_ubah_profil_pelanggan'), 'method' => 'put', 'files'=>'true','class'=>'form-horizontal']) !!}
                    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                        {!! Form::label('name', 'Nama Pelanggan', ['class'=>'col-md-2 control-label']) !!}
                        <div class="col-md-10">
                            {!! Form::text('name', null, ['class'=>'form-control','required','autocomplete'=>'off', 'placeholder' => 'Nama Pelanggan', 'id' => 'nama_pelanggan']) !!}
                            {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('no_telp') ? ' has-error' : '' }}">
                        {!! Form::label('no_telp', 'No. Telpon', ['class'=>'col-md-2 control-label']) !!}
                        <div class="col-md-10">
                            {!! Form::number('no_telp', null, ['class'=>'form-control','required','autocomplete'=>'off', 'placeholder' => 'No. Telpon', 'id' => 'telpon_pelanggan']) !!}
                            {!! $errors->first('no_telp', '<p class="help-block" id="no_telp_error">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        {!! Form::label('email', 'Email Pelanggan', ['class'=>'col-md-2 control-label']) !!}
                        <div class="col-md-10">
                            {!! Form::email('email', null, ['class'=>'form-control','autocomplete'=>'off', 'placeholder' => 'Email Pelanggan', 'id' => 'email_pelanggan']) !!}
                            {!! $errors->first('email', '<p class="help-block" id="email_error">:message</p>') !!}
                        </div>
                    </div> 

                    <div class="form-group{{ $errors->has('alamat') ? ' has-error' : '' }}">
                        {!! Form::label('alamat', 'Alamat Pelanggan', ['class'=>'col-md-2 control-label']) !!}
                        <div class="col-md-10">
                            {!! Form::text('alamat', null, ['class'=>'form-control','required','autocomplete'=>'off', 'placeholder' => 'Alamat Pelanggan', 'id' => 'alamat_pelanggan']) !!}
                            {!! $errors->first('alamat', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('tgl_lahir') ? ' has-error' : '' }}">
                        {!! Form::label('tgl_lahir', 'Tanggal Lahir', ['class'=>'col-md-2 control-label']) !!}
                        <div class="col-md-10">
                            {!! Form::text('tgl_lahir',null, ['class'=>'form-control datepicker', 'id'=>'datepicker','placeholder'=>'Tanggal Lahir','readonly']) !!}
                            {!! $errors->first('tgl_lahir', '<p class="help-block">:message</p>') !!}

                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('komunitas') ? ' has-error' : '' }}">
                        {!! Form::label('komunitas', 'Komunitas', ['class'=>'col-md-2 control-label']) !!}
                        <div class="col-md-10">
                            @if (isset($komunitas_pelanggan))
                            {!! Form::select('komunitas', [''=>'']+App\Komunitas::where('tipe_user','2')->pluck('name','id')->all(),$komunitas_pelanggan->komunitas_id, ['class'=>'form-control js-selectize-reguler', 'placeholder' => '--PILIH KOMUNITAS--','id'=>'pilih_komunitas']) !!}
                            {!! $errors->first('komunitas', '<p class="help-block">:message</p>') !!}
                            @else
                            {!! Form::select('komunitas', 
                            [''=>'']+App\Komunitas::where('tipe_user','2')->pluck('name','id')->all(),null
                            , ['class'=>'form-control js-selectize-reguler', 'placeholder' => '--PILIH KOMUNITAS--','id'=>'pilih_komunitas']) !!}
                            {!! $errors->first('komunitas', '<p class="help-block">:message</p>') !!}
                            @endif
                        </div>
                    </div>
                    {!! Form::hidden('id', $user->id, ['class'=>'form-control','autocomplete'=>'off']) !!}
                    <div class="col-md-2"></div>
                    <div class="col-md-4">
                        {!! Form::button('<i class="material-icons">save</i>Simpan Profil', ['class'=>'btn btn-rose ', 'type'=>'submit', 'id' => 'btnSimpanProfil']) !!}
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
    $("#nama_pelanggan").focus();
});

   $('.datepicker').datepicker({
    format: 'dd-mm.yyyy', 
    autoclose: true,
});
   $(document).on('click', '#btnSimpanProfil', function(){
    swal("Berhasil!", "Profil Berhasil Diubah", "success");
});
</script>
@endsection

