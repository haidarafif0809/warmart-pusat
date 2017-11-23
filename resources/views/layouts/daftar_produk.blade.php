@extends('layouts.app_pelanggan')
@section('content') 

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
    @if(Agent::isMobile())
    height:3em;
    @else  
    height:3em;
    @endif
    padding:1%;
    margin: 0px;

}

.smaller {
    font-size: 0.7em;
    background-color:red;
    width: 10em;
}
</style>

@if (Agent::isMobile()) <!--JIKA DAKSES VIA HP/TAB-->

<div class="page-header header-small" data-parallax="true"" style="{!! $foto_latar_belakang !!}">
    <a href="{{ url('/home') }}"><img  class="img img-raised" src="{!! $logo_warmart !!}" style="width: 10%"/></a>

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

<div class="main main-raised" >

    <div class="container">
        <h3 class="title text-center">{!! $nama_kategori !!}</h3>

        <div class="card card-raised card-form-horizontal">
            <div class="card-content">
                {!! Form::open(['url' => route('daftar_produk.pencarian'),'method' => 'get', 'class'=>'form-horizontal']) !!}
                <div class="row">
                    <div class="col-sm-10">
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="material-icons">search</i>
                            </span>
                            <input type="text" name="search" id="cari_produk" value="" placeholder="Cari Produk Atau Warung.." class="form-control" />
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <button type="submit" class="btn btn-block" style="background-color: #01573e">Cari</button>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>

        <div class="row">   
            <div class="col-md-12">
                <!--Menampilkan Warung Secara Acak--> 
                <h4 class="title" style="color:#01573e; margin-bottom: 1px; margin-top:0px">Daftar Warung</h4> 
            </div>   
            <span class="span-warung">{!! $daftar_warung !!}</span> 
        </div>

        <div class="row">
            <div class="col-md-12">                 
                <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="headingTwo">
                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            <h4 class="title">
                                KATEGORI PRODUK
                                <i class="material-icons">keyboard_arrow_down</i>
                            </h4>
                        </a>
                    </div>
                    <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                        <div class="panel-body">

                            <ul class="nav" style="background-color: #01573e">                                        
                                <li><a style="color:white" href="{{route('daftar_produk.index')}}"><i class="material-icons">format_align_justify</i> SEMUA KATEGORI</a></li>
                            </ul>

                            <ul class="nav" style="background-color: #01573e">
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
<div class="page-header header-filter header-small" data-parallax="true"" style="{!! $foto_latar_belakang !!}">
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
        <h3 class="title text-center">{!! $nama_kategori !!}</h3>

        <div class="card card-raised card-form-horizontal">
            <div class="card-content">
                {!! Form::open(['url' => route('daftar_produk.pencarian'),'method' => 'get', 'class'=>'form-horizontal']) !!}
                <div class="row">
                    <div class="col-sm-10">
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="material-icons">search</i>
                            </span>
                            <input type="text" name="search" id="cari_produk" value="" placeholder="Cari Produk Atau Warung.." class="form-control" />
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <button type="submit" class="btn btn-block" style="background-color: #01573e">Cari</button>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>

        <div class="row">

            <!--Menampilkan Warung Secara Acak--> 
            <div class="col-md-12"> 
                <h5 class="title" style="color:#01573e; margin-bottom: 1px; margin-top:0px">Daftar Warung</h5>           
                <span id="span-warung">{!! $daftar_warung !!}</span>     
            </div>

            <div class="col-md-3"> 
                <h5 class="title" style="color:#01573e; margin-bottom: 1px; margin-top:25px">Daftar Produk</h5>  
                <ul class="nav nav-tabs" data-tabs="tabs" style="background-color: #01573e">                                        
                    <li><a href="{{route('daftar_produk.index')}}"><i class="material-icons">format_align_justify</i> Semua Kategori</a></li>
                </ul>
            </div>
            <div class="col-md-9">      
                <h5 class="title" style="color:#01573e; margin-bottom: 1px; margin-top:25px"><br></h5>                  
                <ul class="nav nav-tabs" data-tabs="tabs" style="background-color: #01573e">
                    {!! $kategori_produk !!}                        
                </ul>
            </div>

            <div class="col-md-12"><br>
                <div class="row">
                    <div class="col-md-12">
                        <!-- Menampilkan Produk -->
                        <span id="span-produk">{!! $daftar_produk !!}</span>
                    </div>
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
        var divs = document.getElementsByClassName("flexFont");
        for(var i = 0; i < divs.length; i++) {
            var relFontsize = divs[i].offsetWidth*0.05;
            divs[i].style.fontSize = relFontsize+'px';
        }
    };

    window.onload = function(event) {
        flexFont();
    };
    window.onresize = function(event) {
        flexFont();
    };
</script>
@endsection
