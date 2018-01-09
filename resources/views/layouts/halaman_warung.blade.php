@extends('layouts.app_pelanggan')
@section('content')
<?php
$setting_aplikasi = \App\SettingAplikasi::select('tipe_aplikasi')->first();
?>
<style type="text/css">
.list-produk {

    padding-left: 4px;
    padding-right: 4px;

}
.card .card-image{

    height: auto; /*this makes sure to maintain the aspect ratio*/
    margin-top: 0px;
}
.card-pricing {
    margin-bottom: 0px;
}
.tombolBeli {
    padding: 10px 0px;
    margin:0px;
}
.card-pricing .card-content {
    padding: 5px !important;
}
.card .footer {
    margin-top: 0px;
    font-family: Helvetica,Arial,sans-serif;
    font-weight: 400;
    line-height:1.2em;
    text-decoration: none;
    font-size:15px;
}
.buttonColor{
    @if($setting_aplikasi->tipe_aplikasi == "1") /*tipe-aplikasi == 1, aplikasi topos*/
    background-color: #2ac326;
    @else
    background-color: #01573e;
    @endif
}
@font-face {
  font-family: "San Francisco";
  font-weight: 200;
  src: url("//applesocial.s3.amazonaws.com/assets/styles/fonts/sanfrancisco/sanfranciscodisplay-thin-webfont.woff2");
}

.flexFont {
    @if(Agent::isMobile())
    height:3em;
    @else
    height:3em;
    @endif
    padding:1%;
    margin: 5px;

}
.btnWarung {
    padding:1%;
    margin: 0px;
}

.smaller {
    font-size: 0.7em;
    background-color:red;
    width: 10em;
}
.page-header.header-small {
    height: 35vh;
    min-height: 35vh;
}
.ecommerce-page .page-header .container {
    @if(Agent::isMobile())
    padding-top: 7vh;
    @else
    padding-top: 10vh;
    @endif
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

</style>

@if (Agent::isMobile()) <!--JIKA DAKSES VIA HP/TAB-->

@if($setting_aplikasi->tipe_aplikasi == 0)
<div class="page-header header-filter header-small" data-parallax="true"" style="{!! $foto_latar_belakang !!}">
    @else
    <div class="page-header header-small" data-parallax="true"" style="background-color: #2ac326">
        @endif
        <a href="{{ url('/home') }}"><img  class="img img-raised" src="{!! $logo_warmart !!}" style="width: 10%"/></a>

        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="brand">
                       @if($setting_aplikasi->tipe_aplikasi == 0)
                       <h3 class="title">PASAR MUSLIM INDONESIA</h3>
                       @else
                       <h3 class="title">TOKO ONLINE DAN POS</h3>
                       @endif
                   </div>
               </div>
           </div>
       </div>
   </div>

   <div class="main main-raised" >

    <div class="container">
        <h3 class="title text-center">{!! $nama_kategori !!}</h3>
        {!! $list_warung !!}
        <div class="card card-raised card-form-horizontal">
            <div class="card-content">
                {!! Form::open(['url' => route('halaman_warung.pencarian'),'method' => 'get', 'class'=>'form-horizontal']) !!}
                <div class="row">
                    <div class="col-sm-10">
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="material-icons">search</i>
                            </span>
                            <input type="text" name="search" id="cari_produk" value="" placeholder="Cari Produk.." class="form-control" />
                            <input type="hidden" name="id_warung" id="cari_produk" value="{{$id}}" class="form-control" />
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <button type="submit" class="btn btn-block buttonColor"  >Cari</button>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>

        <div class="row">
          <div class="col-md-12">
            <!--Menampilkan Warung Secara Acak-->
            <h4 class="title" style="color:#01573e; margin-bottom: 1px; margin-top:0px"> Produk</h4>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingTwo">
                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        <h4 class="title">
                            KATEGORI
                            <i class="material-icons">keyboard_arrow_down</i>
                        </h4>
                    </a>
                </div>
                <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                    <div class="panel-body">

                        <ul class="nav" class="buttonColor">
                            <li><a style="color:white" href="{{route('halaman-warung.halaman_warung',$id)}}"><i class="material-icons">format_align_justify</i> SEMUA KATEGORI</a></li>
                        </ul>

                        <ul class="nav buttonColor">
                            {!! $kategori_produk !!}
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12"><br>
            <div class="row ">
                <!-- Menampilkan Produk -->
                {!! $daftar_produk !!}
                <div class="col-md-12 col-sm-12 col-xs-12">
                    {{$produk_pagination}}
                </div>

            </div>
        </div>
    </div>
</div>

</div> <!-- end-main-raised -->
@else <!--JIKA DIAKSES VIA KOMPUTER-->

@if($setting_aplikasi->tipe_aplikasi == 0)
<div class="page-header header-filter header-small" data-parallax="true"" style="{!! $foto_latar_belakang !!}">
    @else
    <div class="page-header header-small" data-parallax="true"" style="background-color: #2ac326">
        @endif
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="brand">
                        @if($setting_aplikasi->tipe_aplikasi == 0)
                        <h1 class="title">PASAR MUSLIM INDONESIA</h1>
                        <h3 class="title"> Segala Kemudahan Untuk Umat Muslim Berbelanja.</h3>
                        @else
                        <h1 class="title">TOKO ONLINE DAN POS</h1>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="main main-raised" >

        <div class="container">
            <h3 class="title text-center">{!! $nama_kategori !!}</h3>
            {!! $list_warung !!}
            <div class="card card-raised card-form-horizontal">
                <div class="card-content">
                    {!! Form::open(['url' => route('halaman_warung.pencarian'),'method' => 'get', 'class'=>'form-horizontal']) !!}
                    <div class="row">
                        <div class="col-sm-10">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="material-icons">search</i>
                                </span>
                                <input type="text" name="search" id="cari_produk" value="" placeholder="Cari Produk.." class="form-control" />
                                <input type="hidden" name="id_warung" id="cari_produk" value="{{$id}}" class="form-control" />

                            </div>
                        </div>
                        <div class="col-sm-2">
                            <button type="submit" class="btn btn-block buttonColor">Cari</button>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>

            <div class="row">

                <!--Menampilkan NAMA Warung -->
                <div class="col-md-12">
                    <h5 class="title" style="color:#01573e; margin-bottom: 1px; margin-top:0px"> Produk </h5>
                </div>

                <div class="col-md-3">
                    <ul class="nav nav-tabs buttonColor" data-tabs="tabs">
                        <li><a href="{{route('halaman-warung.halaman_warung',$id)}}"><i class="material-icons">format_align_justify</i> Semua Kategori</a></li>
                    </ul>
                </div>


                <div class="col-md-9">
                    <ul class="nav nav-tabs buttonColor" data-tabs="tabs">
                        {!! $kategori_produk !!}
                    </ul>
                </div>

                <div class="col-md-12"><br>
                    <div class="row">
                        <!-- Menampilkan Produk -->
                        <span id="span-produk">{!! $daftar_produk !!}</span>
                        <div class="col-md-12">
                            {{$produk_pagination}}
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div> <!-- end-main-raised -->
    @endif

    @endsection
    @section('scripts')
    <script type="text/javascript">
        flexFont = function () {
            @if(Agent::isMobile())
            var divs = document.getElementsByClassName("flexFont");
            for(var i = 0; i < divs.length; i++) {
                var relFontsize = divs[i].offsetWidth*0.1;
                divs[i].style.fontSize = relFontsize+'px';
            }
            @else
            var divs = document.getElementsByClassName("flexFont");
            for(var i = 0; i < divs.length; i++) {
                var relFontsize = divs[i].offsetWidth*0.06;
                divs[i].style.fontSize = relFontsize+'px';
            }

            @endif
            @if(Agent::isMobile())
            var divs = document.getElementsByClassName("flexFontWarung");
            for(var i = 0; i < divs.length; i++) {
                var relFontsize = divs[i].offsetWidth*0.14;
                divs[i].style.fontSize = relFontsize+'px';
            }
            @else
            var divs = document.getElementsByClassName("flexFontWarung");
            for(var i = 0; i < divs.length; i++) {
                var relFontsize = divs[i].offsetWidth*0.16;
                divs[i].style.fontSize = relFontsize+'px';
            }

            @endif
        };

        window.onload = function(event) {
            flexFont();
        };
        window.onresize = function(event) {
            flexFont();
        };
    </script>
    @endsection
