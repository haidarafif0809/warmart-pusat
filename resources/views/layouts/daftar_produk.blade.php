@extends('layouts.app_pelanggan')
@section('content') 

@if (Agent::isMobile()) <!--JIKA DAKSES VIA HP/TAB-->

<div class="page-header header-filter header-small" data-parallax="true"" style="background-image: url('image/background2.jpg');">

    <div class="container"> 
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="brand">
                    <h1 class="title">PASAR MUSLIM INDONESIA</h1>
                    <h4 class="title"> Segala Kemudahan Untuk Umat Muslim Berbelanja.</h4>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="main main-raised" style="background-color: #E5E5E5">

    <div class="container">
        <h3 class="title text-center">{!! $nama_kategori !!}</h3>

        <div class="card card-raised card-form-horizontal">
            <div class="card-content">
                <form method="" action="">
                    <div class="row">
                        <div class="col-sm-10">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="material-icons">search</i>
                                </span>
                                <input type="email" id="cari_produk" value="" placeholder="Cari Produk.." class="form-control" />
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <button type="button" class="btn btn-block" style="background-color: #f44336">Cari</button>
                        </div>
                    </div>
                </form>
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

                            <ul class="nav" style="background-color: #f44336">                                        
                                <li><a style="color:white" href="{{route('daftar_produk.index')}}"><i class="material-icons">format_align_justify</i> SEMUA KATEGORI</a></li>
                            </ul>

                            <ul class="nav" style="background-color: #f44336">
                                {!! $kategori_produk !!}                        
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12"><br>
                <div class="row">
                    <!-- Menampilkan Produk -->
                    {!! $daftar_produk !!}
                    <div class="col-md-12">
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
                    <h4 class="title"> Segala Kemudahan Untuk Umat Muslim Berbelanja.</h4>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="main main-raised" style="background-color: #E5E5E5">

    <div class="container">
        <h3 class="title text-center">{!! $nama_kategori !!}</h3>

        <div class="card card-raised card-form-horizontal">
            <div class="card-content">
                <form method="" action="">
                    <div class="row">
                        <div class="col-sm-10">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="material-icons">search</i>
                                </span>
                                <input type="email" id="cari_produk" value="" placeholder="Cari Produk.." class="form-control" />
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <button type="button" class="btn btn-block" style="background-color: #f44336">Cari</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3"> 
                <ul class="nav nav-tabs" data-tabs="tabs" style="background-color: #f44336">                                        
                    <li><a href="{{route('daftar_produk.index')}}"><i class="material-icons">format_align_justify</i> Semua Kategori</a></li>
                </ul>
            </div>
            <div class="col-md-9">                        
                <ul class="nav nav-tabs" data-tabs="tabs" style="background-color: #f44336">
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
@endsection 