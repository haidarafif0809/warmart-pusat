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

<router-view name="dashboardIndex"></router-view>
<router-view></router-view>

@endif
@endsection
