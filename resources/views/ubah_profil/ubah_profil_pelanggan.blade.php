@extends('layouts.app_pelanggan')

@section('content')
<link href="{{ asset('css/selectize.bootstrap3.css') }}" rel="stylesheet">

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
.page-header.header-small {
    @if(Agent::isMobile())
    height: 30vh;
    min-height: 30vh;
    @else
    height: 35vh;
    min-height: 35vh;
    @endif
}
.ecommerce-page .page-header .container {
    padding-top: 10vh;
}
h4 {
    @if(Agent::isMobile())
    font-size: 1.2em;
    line-height: 1.4em;
    margin: 20px 0 10px;
    @endif
}
.panel .panel-heading {
    background-color: transparent;
    border-bottom: 2px solid #ddd;
    padding: 5px 0px 5px 0px;
}
.buttonColor{
  background-color: #2ac326  
}
</style>

@if($setting_aplikasi->tipe_aplikasi == 0)
<div class="page-header header-filter header-small" data-parallax="true"" style="background-image: url('image/background2.jpg');">
    @else
    <div class="page-header header-small backgroundColor" data-parallax="true">
        @endif

        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="brand">

                        @if (Agent::isMobile()) <!--JIKA DAKSES VIA HP/TAB--> 
                        @else

                        @if($setting_aplikasi->tipe_aplikasi == 0)
                        <h1 class="title">PASAR MUSLIM INDONESIA</h1>
                        <h3 class="title"> Segala Kemudahan Untuk Umat Muslim Berbelanja.</h3>
                        @else
                        <h1 class="title">TOKO ONLINE DAN POS</h1>
                        @endif

                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="main main-raised" >
        <div class="container">
            <ul class="breadcrumb">
                <li><a href="{{ url('/daftar-produk') }}" style="color: purple">Home</a></li>
                <li class="active">Ubah Profil</li>
            </ul>

            <div class="card-content">

                <div class="toolbar">
                    {!! Form::model($user, ['url' => route('user.proses_ubah_profil_pelanggan'), 'method' => 'put', 'files'=>'true','class'=>'form-horizontal ']) !!}
                    <div class="marginFrom form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                        {!! Form::label('name', 'Nama', ['class'=>'col-md-2 control-label']) !!}
                        <div class="col-md-10">
                            {!! Form::text('name', null, ['class'=>'form-control','required','autocomplete'=>'off', 'placeholder' => 'Nama Pelanggan', 'id' => 'nama_pelanggan']) !!}
                            {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>

                    <div class="marginFrom form-group{{ $errors->has('no_telp') ? ' has-error' : '' }}">
                        {!! Form::label('no_telp', 'No. Telpon', ['class'=>'col-md-2 control-label']) !!}
                        <div class="col-md-10">
                            {!! Form::number('no_telp', null, ['class'=>'form-control','required','autocomplete'=>'off', 'placeholder' => 'No. Telpon', 'id' => 'telpon_pelanggan']) !!}
                            {!! $errors->first('no_telp', '<p class="help-block" id="no_telp_error">:message</p>') !!}
                        </div>
                    </div>

                    <div class="marginFrom form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        {!! Form::label('email', 'Email', ['class'=>'col-md-2 control-label']) !!}
                        <div class="col-md-10">
                            {!! Form::email('email', null, ['class'=>'form-control','autocomplete'=>'off', 'placeholder' => 'Email Pelanggan', 'id' => 'email_pelanggan']) !!}
                            {!! $errors->first('email', '<p class="help-block" id="email_error">:message</p>') !!}
                        </div>
                    </div>

                    <div class="marginFrom form-group{{ $errors->has('alamat') ? ' has-error' : '' }}">
                        {!! Form::label('alamat', 'Alamat', ['class'=>'col-md-2 control-label']) !!}
                        <div class="col-md-10">
                            {!! Form::text('alamat', null, ['class'=>'form-control','required','autocomplete'=>'off', 'placeholder' => 'Alamat Pelanggan', 'id' => 'alamat_pelanggan']) !!}
                            {!! $errors->first('alamat', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>

                    <div class="marginFrom form-group{{ $errors->has('tgl_lahir') ? ' has-error' : '' }}">
                        {!! Form::label('tgl_lahir', 'Tanggal Lahir', ['class'=>'col-md-2 control-label']) !!}
                        <div class="col-md-10">
                            {!! Form::text('tgl_lahir',null, ['class'=>'form-control datepicker', 'id'=>'datepicker','placeholder'=>'Tanggal Lahir','readonly']) !!}
                            {!! $errors->first('tgl_lahir', '<p class="help-block">:message</p>') !!}

                        </div>
                    </div>

                    @if($setting_aplikasi->tipe_aplikasi == 0)
                    <div class="marginFrom form-group{{ $errors->has('komunitas') ? ' has-error' : '' }}">
                        {!! Form::label('komunitas', 'Komunitas', ['class'=>'col-md-2 control-label']) !!}
                        <div class="col-md-10">
                            @if (isset($komunitas_pelanggan))
                            {!! Form::select('komunitas', [''=>'']+App\Komunitas::where('tipe_user','2')->pluck('name','id')->all(),$komunitas_pelanggan->komunitas_id, ['class'=>'', 'placeholder' => '--PILIH KOMUNITAS--','id'=>'pilih_komunitas']) !!}
                            {!! $errors->first('komunitas', '<p class="help-block">:message</p>') !!}
                            @else
                            {!! Form::select('komunitas',
                                [''=>'']+App\Komunitas::where('tipe_user','2')->pluck('name','id')->all(),null
                                , ['class'=>'', 'placeholder' => '--PILIH KOMUNITAS--','id'=>'pilih_komunitas']) !!}
                                {!! $errors->first('komunitas', '<p class="help-block">:message</p>') !!}
                                @endif
                            </div>
                        </div>
                        @endif
                        <div class="marginFrom form-group{{ $errors->has('provinsi') ? ' has-error' : '' }}">
                            {!! Form::label('provinsi', 'Provinsi', ['class'=>'col-md-2 control-label']) !!}
                            <div class="col-md-10">
                                {!! Form::select('provinsi', $provinsi,null, ['placeholder' => 'Cari Provinsi ...','id'=>'provinsi']) !!}
                                {!! $errors->first('provinsi', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>

                        <div class="marginFrom form-group{{ $errors->has('kabupaten') ? ' has-error' : '' }}">
                            {!! Form::label('kabupaten', 'Kabupaten', ['class'=>'col-md-2 control-label']) !!}
                            <div class="col-md-10" id="form-kabupaten">
                               {!! Form::select('kabupaten', [''=>''],null, ['required'=> 'true','placeholder' => 'Cari Kabupaten ...','id'=>'kabupaten']) !!}                              
                               {!! $errors->first('kabupaten', '<p class="help-block">:message</p>') !!}
                           </div>
                       </div>

                       <div class="marginFrom form-group{{ $errors->has('kecamatan') ? ' has-error' : '' }}">
                        {!! Form::label('kecamatan', 'Kecamatan', ['class'=>'col-md-2 control-label']) !!}
                        <div class="col-md-10" id="form-kecamatan">
                            {!! Form::select('kecamatan', [''=>''],null, ['required'=> 'true','placeholder' => 'Cari Kecamatan ...','id'=>'kecamatan']) !!}                              
                            {!! $errors->first('kecamatan', '<p class="help-block">:message</p>') !!}                           
                        </div>
                    </div>

                    <div class="marginFrom form-group{{ $errors->has('kelurahan') ? ' has-error' : '' }}">
                        {!! Form::label('kelurahan', 'Kelurahan', ['class'=>'col-md-2 control-label']) !!}
                        <div class="col-md-10">
                            {!! Form::select('kelurahan', [''=>''],null, ['required'=> 'true','placeholder' => 'Cari Kelurahan ...','id'=>'kelurahan']) !!}                              
                            {!! $errors->first('kelurahan', '<p class="help-block">:message</p>') !!}                             
                        </div>
                    </div>

                    {!! Form::text('id', $user->id, ['class'=>'marginFrom form-control','autocomplete'=>'off','style'=>'display :none']) !!}
                    <div class=" marginFrom col-md-2"></div>
                    <div class=" marginFrom col-md-4">
                        {!! Form::button('<i class="material-icons">save</i>Simpan Profil', ['class'=>' marginFrom btn btn-rose ', 'type'=>'submit', 'id' => 'btnSimpanProfil']) !!}                      
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
    @endsection

    @section('scripts')

    <script type="text/javascript">
        $(document).ready(function(){
            var lokasi_pelanggan = "{{ $lokasi_pelanggan->count() }}";    
            if (lokasi_pelanggan > 0) {            
             var provinsi_pelanggan =  "{{$provinsi_pelanggan}}"; 
             var kabupaten_pelanggan =  "{{$kabupaten_pelanggan}}"; 
             var kecamatan_pelanggan =  "{{$kecamatan_pelanggan}}"; 
             var kelurahan_pelanggan =  "{{$kelurahan_pelanggan}}"; 
         }

         $('.datepicker').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true
        });

         $('#pilih_komunitas').selectize({
          sortField: 'text'
      });
         $('#provinsi').selectize({
           loadingClass: 'selectizeLoading',
           valueField: 'id',
           labelField: 'name',
           searchField: 'name',
           create: false,
           onChange: function (id) 
           {
            if (!id.length) return;
            selectKota.clearOptions(); 
            selectKecamatan.clearOptions(); 
            selectKelurahan.clearOptions(); 
            selectKota.settings.placeholder = "Tunggu Sebentar ...";
            selectKota.updatePlaceholder();
            selectKota.load(function (callback) { timeOutS = setTimeout(setKotaOptions, 500, callback, id); });
        }
    });
         $('#kabupaten').selectize({
            loadingClass: 'selectizeLoading',
            valueField: 'id',
            labelField: 'name',
            searchField: 'name',
            create: false,
            onChange: function (id) 
            {
                if (!id.length) return;
                selectKecamatan.clearOptions();
                selectKelurahan.clearOptions(); 
                selectKecamatan.settings.placeholder = "Tunggu Sebentar ...";
                selectKecamatan.updatePlaceholder();
                selectKecamatan.load(function (callback) { timeOutS = setTimeout(setKecamatanOptions, 500, callback, id); });
            }
        });
         $('#kecamatan').selectize({
            loadingClass: 'selectizeLoading',
            valueField: 'id',
            labelField: 'name',
            searchField: 'name',
            create: false,
            onChange: function (id) 
            {
                if (!id.length) return;
                selectKelurahan.clearOptions(); 
                selectKelurahan.settings.placeholder = "Tunggu Sebentar ...";
                selectKelurahan.updatePlaceholder();
                selectKelurahan.load(function (callback) { timeOutS = setTimeout(setKelurahanOptions, 500, callback, id); });
            }
        });
         $('#kelurahan').selectize({
            loadingClass: 'selectizeLoading',
            valueField: 'id',
            labelField: 'name',
            searchField: 'name',
            create: false
        });

         var selectProvinsi = $('#provinsi').data('selectize');

         var selectKota = $('#kabupaten').data('selectize');
         selectKota.disable();

         var selectKecamatan = $('#kecamatan').data('selectize');
         selectKecamatan.disable();

         var selectKelurahan = $('#kelurahan').data('selectize');
         selectKelurahan.disable();

         var setKecamatanOptions = function (callback, id)
         {
            clearTimeout(timeOutS);
            var id_kabupaten = $('#kabupaten').val();
            var kecamatan = [];

            $.getJSON('{{ Url("cek-wilayah-pelanggan") }}',{'_token': $('meta[name=csrf-token]').attr('content'),id_kabupaten:id_kabupaten,type:"kecamatan"}, function(resp){ 

                var kecamatan = [];
                $.each(resp, function (i, item) { 

                    kecamatan.push({
                        id: resp[i].id,
                        name: resp[i].name
                    });

                });
                callback(kecamatan);
                selectKecamatan.settings.placeholder = "Cari Kecamatan ...";
                selectKecamatan.updatePlaceholder();
                selectKecamatan.enable();
                if (lokasi_pelanggan > 0) {
                    selectKecamatan.setValue(kecamatan_pelanggan);
                }else{
                 selectKecamatan.focus(); 
               }
               console.log(kecamatan);
           });
        };

        var setKotaOptions = function (callback, id)
        {
            clearTimeout(timeOutS);
            var id_provinsi = $('#provinsi').val();
            var kabupaten = [];

            $.getJSON('{{ Url("cek-wilayah-pelanggan") }}',{'_token': $('meta[name=csrf-token]').attr('content'),id_provinsi:id_provinsi,type:"kabupaten"}, function(resp){                 

                $.each(resp, function (i, item) { 

                    kabupaten.push({
                        id: resp[i].id,
                        name: resp[i].name
                    });

                });
                callback(kabupaten);
                selectKota.settings.placeholder = "Cari Kabupaten atau Kota ...";
                selectKota.updatePlaceholder();
                selectKota.enable();
                if (lokasi_pelanggan > 0) {
                 selectKota.setValue(kabupaten_pelanggan);
             }else{
                 selectKota.focus(); 
             }
             console.log(kabupaten);
         });
        };

        var setKelurahanOptions = function (callback, id)
        {
            clearTimeout(timeOutS);
            var id_kecamatan = $('#kecamatan').val();
            var kelurahan = [];

            $.getJSON('{{ Url("cek-wilayah-pelanggan") }}',{'_token': $('meta[name=csrf-token]').attr('content'),id_kecamatan:id_kecamatan,type:"kelurahan"}, function(resp){ 

                $.each(resp, function (i, item) { 

                    kelurahan.push({
                        id: resp[i].id,
                        name: resp[i].name
                    });

                });
                callback(kelurahan);
                selectKelurahan.settings.placeholder = "Cari Kelurahan ...";
                selectKelurahan.updatePlaceholder();
                selectKelurahan.enable();
                if (lokasi_pelanggan > 0) {
                   selectKelurahan.setValue(kelurahan_pelanggan);
               }else{
                   selectKelurahan.focus(); 
               }
               console.log(kelurahan);
           });
        };

        if (lokasi_pelanggan > 0) {        
            selectProvinsi.setValue(provinsi_pelanggan);
        }

    });

$(document).on('click', '#btnSimpanProfil', function(){
    swal("Berhasil!", "Profil Berhasil Diubah", "success");
});
</script>


@endsection
