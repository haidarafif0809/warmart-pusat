@extends('layouts.app_pelanggan')
@section('content')
<?php
$setting_aplikasi = \App\SettingAplikasi::select('tipe_aplikasi')->first();
$settingFooter = \App\SettingFooter::where('warung_id', \App\SettingPembedaAplikasi::where('app_address', url('/'))->first()->warung_id)->first();
$tema = \App\TemaWarna::where('default_tema', 1)->where('warung_id', \App\SettingPembedaAplikasi::where('app_address', url('/'))->first()->warung_id)->first();
?>
<style type="text/css">
    .list-produk {

        padding-left: 4px;
        padding-right: 4px;

    }
    .card .card-image{

        height: auto; /*this makes sure to maintain the aspect ratio*/
        margin-top: 0px;
        border-radius: 0px;
    }
    .card-pricing {
        margin-bottom: 0px;
    }
    .tombolBeli {
        padding: 6px 0px;
        margin:0px;
        border-radius: 0px;
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
        background-color: {{$tema->kode_tema}};
        @else
        background-color: #01573e;
        @endif
    }
    @font-face {
      font-family: "San Francisco";
      font-weight: 200;
      src: url("//applesocial.s3.amazonaws.com/assets/styles/fonts/sanfrancisco/sanfranciscodisplay-thin-webfont.woff2");
  }

  /*style untuk kategori*/ 
  .nav .open>a, .nav .open>a:hover, .nav .open>a:focus{
    background-color: {{$tema->kode_tema}};
}
.card .card-content {
    padding: 0px 30px;
}
.card-form-horizontal .card-content {
    padding-left: 15px;
    padding-right: 15px;
    padding-top: 10px;
    border-radius: 0px;
}
.nav-tabs {
    background: {{$tema->kode_tema}};
    border: 0;
    border-radius: 0px;
    padding: 9 15px;
}
/*style untuk kategori*/

.flexFont {
    @if(Agent::isMobile())
    height:3em;
    @else
    height:3em;
    @endif
    padding:3%;
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
    height: 0vh;
    @if(Agent::isMobile())
    min-height: 12vh;
    @else
    min-height: 18vh;
    @endif
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
.scrollable-menu {
    height: auto;
    max-height: 250px;
    overflow-x: hidden;
}
.nav-tabs > li > a{
    font-size: 14px;
}

.nav-pills > li > a {
    line-height: 1px;
    text-transform: uppercase;
    @if(Agent::isMobile())
    font-size: 7px;
    @else
    font-size: 10px;
    @endif
    font-weight: 400;
    min-width: 5px;
    text-align: center;
    color: #555555;
    transition: all .3s;
}
.class_coret {
    text-decoration: line-through;
    color:#a6a6a6;
}
</style>

@if (Agent::isMobile()) <!--JIKA DAKSES VIA HP/TAB-->

@if($setting_aplikasi->tipe_aplikasi == 0)
<div class="page-header header-filter header-small" data-parallax="true"" style="{!! $foto_latar_belakang !!}">
    @else
    <div class="page-header header-small" data-parallax="true"" style="background-color: #ffffff">
        @endif
        <a href="{{ url('/home') }}"><img  class="img img-raised" src="{!! $logo_warmart !!}" style="width: 10%"/></a>
    </div>

    <div class="main" >

        <div class="container">
            <h3 class="title text-center">{!! $nama_kategori !!}</h3>
            {!! $list_warung !!}
            <div class="row">
                <div class="col-sm-3">
                    <ul class="nav nav-tabs buttonColor card" data-tabs="tabs">
                        {!! $kategori_produk !!}
                    </ul>
                </div>
                <div class="col-sm-9">
                    <div class="card card-form-horizontal">
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
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>

            @if($cek_baner->count() > 0)
            <div class="row">
                <div class="col-md-12 col-md-offset-0">

                    <!-- Carousel Card -->
                    <div class="card card-carousel">
                        <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                            <div class="carousel slide" data-ride="carousel">


                                <!-- Wrapper for slides -->
                                <div class="carousel-inner">
                                 <div class="item active"><a href="{{ url('/detail-produk/'.$baner_promo_active->id_produk.'') }}">
                                    <img src="{{ url('/baner_setting_promo/'.$baner_promo_active->baner_promo.'') }}" alt="Awesome Image">
                                </a>
                            </div>
                            @foreach($baner_promo->get() as $baner_promos)
                            <div class="item"><a href="{{ url('/detail-produk/'.$baner_promos->id_produk.'') }}">
                                <img src="{{ url('/baner_setting_promo/'.$baner_promos->baner_promo.'') }}" alt="Awesome Image">
                            </a>
                        </div>
                        @endforeach
                    </div>

                    <!-- Controls -->
                    <a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">
                        <i class="material-icons">keyboard_arrow_left</i>
                    </a>
                    <a class="right carousel-control" href="#carousel-example-generic" data-slide="next">
                        <i class="material-icons">keyboard_arrow_right</i>
                    </a>
                </div>
            </div>
        </div>
        <!-- End Carousel Card -->
    </div>
</div>
@endif


<div class="row">
  <div class="col-md-12">
    <!--Menampilkan Warung Secara Acak-->
    <h4 class="title" style="color:#01573e; margin-bottom: 1px; margin-top:0px"> Produk</h4>
</div>
</div>

<div class="row">
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
    <div class="page-header header-small" data-parallax="true"" style="background-color: #ffffff">
        @endif
    </div>

    <div class="main" >
        <div class="container">
           <h3 class="title text-center">{!! $nama_kategori !!}</h3>
           {!! $list_warung !!}
       </div>

       <div class="row">
        <div class="col-sm-1"></div>
        <div class="col-sm-3">
            <ul class="nav nav-tabs buttonColor" data-tabs="tabs">
                {!! $kategori_produk !!}
            </ul>
        </div>
        <div class="col-sm-7">
            <div class="card card-form-horizontal">
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
                </div>
            </div>
            {!! Form::close() !!}
        </div>
        <div class="col-sm-1"></div>
    </div>

    @if($cek_baner->count() > 0)
    <div class="row">
        <div class="col-md-1 col-md-offset-0"></div>
        <div class="col-md-10 col-md-offset-0">

            <!-- Carousel Card -->
            <div class="card card-carousel">
                <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                    <div class="carousel slide" data-ride="carousel">

                        <!-- Indicators -->
                        <ol class="carousel-indicators">
                            <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
                            @foreach($baner_promo->get() as $baner_promos)
                            <li data-target="#carousel-example-generic" data-slide-to="{!!$baner_promos->id_setting_promo !!}"></li>
                            @endforeach
                        </ol>

                        <!-- Wrapper for slides -->
                        <div class="carousel-inner">
                         <div class="item active"><a href="{{ url('/detail-produk/'.$baner_promo_active->id_produk.'') }}">
                            <img src="{{ url('/baner_setting_promo/'.$baner_promo_active->baner_promo.'') }}" alt="Awesome Image">
                        </a>
                    </div>
                    @foreach($baner_promo->get() as $baner_promos)
                    <div class="item"><a href="{{ url('/detail-produk/'.$baner_promos->id_produk.'') }}">
                        <img src="{{ url('/baner_setting_promo/'.$baner_promos->baner_promo.'') }}" alt="Awesome Image">
                    </a>
                </div>
                @endforeach
            </div>

            <!-- Controls -->
            <a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">
                <i class="material-icons">keyboard_arrow_left</i>
            </a>
            <a class="right carousel-control" href="#carousel-example-generic" data-slide="next">
                <i class="material-icons">keyboard_arrow_right</i>
            </a>
        </div>
    </div>
</div>
<!-- End Carousel Card -->
</div>
<div class="col-md-1 col-md-offset-0"></div>
</div>
@endif

<div class="row">
    <!--Menampilkan NAMA Warung -->
    <div class="col-md-1"></div>
    <div class="col-md-10">
        <h5 class="title" style="color:#01573e; margin-bottom: 1px; margin-top:0px"> Produk </h5>
    </div>
    <div class="col-md-1"></div>
</div>
<div class="row">
    <div class="col-md-1"></div>
    <div class="col-md-10">
        <br>
        <div class="row">
            <div class="col-md-12">
                <!-- Menampilkan Produk -->
                <span id="span-produk">
                    {!! $daftar_produk !!}
                </span>
            </div>
            <div class="col-md-12">
                {{$produk_pagination}}
            </div>
        </div>
    </br>
</div>
<div class="col-md-1"></div>
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
            var relFontsize = divs[i].offsetWidth*0.08;
            divs[i].style.fontSize = relFontsize+'px';
        }

        @endif
        @if(Agent::isMobile())
        var divs = document.getElementsByClassName("flexFontWarung");
        for(var i = 0; i < divs.length; i++) {
            var relFontsize = divs[i].offsetWidth*0.15;
            divs[i].style.fontSize = relFontsize+'px';
        }
        @else
        var divs = document.getElementsByClassName("flexFontWarung");
        for(var i = 0; i < divs.length; i++) {
            var relFontsize = divs[i].offsetWidth*0.1;
            divs[i].style.fontSize = relFontsize+'px';
        }

        @endif
    };

    $(document).ready(function() {
        var data_strike = $("#id_promo").html();
        if (data_strike == "") {
            $("#coret").attr('class','kosong');
        }else{
           $("#coret").attr('class','class_coret'); 
       }  
   });

    window.onload = function(event) {
        flexFont();
    };
    window.onresize = function(event) {
        flexFont();
    };
</script>
@endsection
