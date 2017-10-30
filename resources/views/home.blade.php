@extends('layouts.app')

@section('content')

<!-- LOGI USER WARUNG -->
@if(Auth::user()->tipe_user == 4 AND Auth::user()->konfirmasi_admin == 0)
<div class="alert alert-info">
   <div class="alert-icon">
    <i class="material-icons">info_outline</i>
</div>
<b>Info : Pendaftaran anda sebagai warung sedang menunggu verifikasi dari admin.</b>
</div>
@elseif(Auth::user()->tipe_user == 2 AND Auth::user()->konfirmasi_admin == 0)
<div class="alert alert-info">
   <div class="alert-icon">
    <i class="material-icons">info_outline</i>
</div>
<b>Info : Pendaftaran anda sebagai komunitas sedang menunggu verifikasi dari admin.</b>
</div>
@elseif(Auth::user()->tipe_user == 1)
<div class="row">   
 <div class="col-lg-4 col-md-6 col-sm-6">
    <div class="card card-stats">
        <div class="card-header" data-background-color="rose">
            <i class="material-icons">person_add</i>
        </div>
        <div class="card-content">
            <p class="category">Jumlah Customer</p>
            <h3 class="card-title">{{ $jumlah_customer }}</h3>
        </div> 
    </div>
</div>
<div class="col-lg-4 col-md-6 col-sm-6">
    <div class="card card-stats">
        <div class="card-header" data-background-color="rose">
            <i class="material-icons">store</i>
        </div>
        <div class="card-content">
            <p class="category">Jumlah Warung</p>
            <h3 class="card-title">{{ $jumlah_warung }}</h3>
        </div> 
    </div>
</div>
<div class="col-lg-4 col-md-6 col-sm-6">
    <div class="card card-stats">
        <div class="card-header" data-background-color="rose">
            <i class="material-icons">people</i>
        </div>
        <div class="card-content">
            <p class="category">Jumlah Komunitas</p>
            <h3 class="card-title">{{ $jumlah_komunitas }}</h3>
        </div> 
    </div>
</div>

<div class="col-lg-3 col-md-6 col-sm-6">
    <div class="card card-stats"> 
        <div class="card-content">
            <p class="category">Warung Tervalidasi</p>
            <h3 class="card-title">{{ $jumlah_warung_tervalidasi }}</h3>
        </div> 
    </div>
</div>
<div class="col-lg-3 col-md-6 col-sm-6">
    <div class="card card-stats"> 
        <div class="card-content">
            <p class="category">Komunitas Tervalidasi</p>
            <h3 class="card-title">{{ $jumlah_komunitas_tervalidasi }}</h3>
        </div> 
    </div>
</div>
<div class="col-lg-3 col-md-6 col-sm-6">
    <div class="card card-stats"> 
        <div class="card-content">
            <p class="category">Jumlah Produk</p>
            <h3 class="card-title">{{ $produk }}</h3>
        </div> 
    </div>
</div>
<div class="col-lg-3 col-md-6 col-sm-6">
    <div class="card card-stats"> 
        <div class="card-content">
            <p class="category">Jumlah Error Log</p>
            <h3 class="card-title">{{ $error_log }}</h3>
        </div> 
    </div>
</div>
</div>
@elseif(Auth::user()->tipe_user == 4 AND Auth::user()->konfirmasi_admin == 1)
<div class="row">    
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-stats"> 
            <div class="card-content">
                <p class="category">Posisi Kas Keselurahan Saat Ini</p>
                <h3 class="card-title">{{ $transaksi_kas->jumlah_kas }}</h3>
            </div> 
        </div>
    </div>  
    <div class="col-lg-2 col-md-6 col-sm-6">
        <div class="card card-stats"> 
            <div class="card-content">
                <p class="category">Total Kas Masuk</p>
                <h3 class="card-title">{{ $jumlah_kas_masuk->total_kas_masuk }}</h3>
            </div> 
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-6">
        <div class="card card-stats"> 
            <div class="card-content">
                <p class="category">Total Kas Keluar</p>
                <h3 class="card-title">{{ $jumlah_kas_keluar->total_kas_keluar }}</h3>
            </div> 
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-6">
        <div class="card card-stats"> 
            <div class="card-content">
                <p class="category">Jumlah Produk</p>
                <h3 class="card-title">{{ $produk_warung }}</h3>
            </div> 
        </div>
    </div> 
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-stats"> 
            <div class="card-content">
                <p class="category">Nilai Persedian</p>
                <h3 class="card-title">{{ $total_persedian }}</h3>
            </div> 
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-6">
        <div class="card card-stats"> 
            <div class="card-content">
                <p class="category">Jumlah Item Masuk</p>
                <h3 class="card-title">{{ $stok_masuk->jumlah_item_masuk }}</h3>
            </div> 
        </div>
    </div>
    <div class="col-lg-2 col-md-6 col-sm-6">
        <div class="card card-stats"> 
            <div class="card-content">
                <p class="category">Jumlah Item Keluar</p>
                <h3 class="card-title">{{ $stok_keluar->jumlah_item_keluar }}</h3>
            </div> 
        </div>
    </div>
</div>
@endif

<center> <img src="{{{ asset('image/warmart_logo.png') }}}" class="img-responsive" width="500" height="160"> </center>
<center> <img src="{{{ asset('image/home.png') }}}" class="img-responsive"> </center>

@endsection
