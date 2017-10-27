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
@endif
 <center> <img src="{{{ asset('image/andaglos_logo.png') }}}" class="img-responsive" width="500" height="160"> </center>
 <center> <img src="{{{ asset('image/home.png') }}}" class="img-responsive"> </center>

@endsection
