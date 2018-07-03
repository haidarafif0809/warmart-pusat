@extends('layouts.app')

@section('content')

<!-- LOGI USER WARUNG -->
@if(Auth::user()->tipe_user == 4 AND (App\UserWarung::select('foto_ktp')->where('tipe_user',4)->orderBy('id', 'asc')->limit(1)->first()->foto_ktp == null || \App\Warung::select('kabupaten')->where('id',Auth::user()->id_warung)->first()->kabupaten == "" ))
<router-view name="ubahProfilUserWarung"></router-view>
<div class="alert alert-info">
	<div class="alert-icon">
		<i class="material-icons">info_outline</i>
	</div>  

	<b>Info : Mohon lengkapi <router-link :to="{name: 'ubahProfilUserWarung'}" class="btn-primary btn-simple">profil toko</router-link> dan <router-link :to="{name: 'indexProfilWarung'}" class="btn-primary btn-simple">alamat toko</router-link> anda, karena diperlukan untuk estimasi ongkos kirim Pengiriman Barang kepada Pelanggan Anda.</b>
</div>
<router-view></router-view>
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


@elseif(Auth::user()->tipe_user == 4 AND App\UserWarung::select('foto_ktp')->where('tipe_user',4)->orderBy('id', 'asc')->limit(1)->first()->foto_ktp != null )
{{-- dashboard untuk warung --}}

<router-view name="dashboardIndex"></router-view>
<router-view></router-view>

@endif
@endsection
