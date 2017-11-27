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
{{-- dashboard untuk admin --}}

<router-view name="dashboardIndex"></router-view>

<router-view>

</router-view>


@elseif(Auth::user()->tipe_user == 4 AND Auth::user()->konfirmasi_admin == 1)
{{-- dashboard untuk warung --}}
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
@endsection
@section('scripts')
@if(Auth::user()->tipe_user == 1)
<script type="text/javascript" src="{{ asset('js/app.js?v=1.18')}}"></script>
@endif
@endsection

