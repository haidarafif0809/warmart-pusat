<!DOCTYPE doctype html>
<html lang="en">
<head>
	@if(\App\SettingAplikasi::select('tipe_aplikasi')->first()->tipe_aplikasi == 0)
	<link href="{{ asset('img/favicon.png') }}" rel="apple-touch-icon" sizes="76x76"/>
	<link href="{{ asset('img/favicon.png') }}" rel="icon" type="image/png"/>
	@else
	<link href="{{ asset('img/icon_topos.png') }}" rel="apple-touch-icon" sizes="76x76"/>
	<link href="{{ asset('img/icon_topos.png') }}" rel="icon" type="image/png"/>
	@endif
	<title>
		@if($setting_aplikasi->tipe_aplikasi == 0)
		War-Mart.id
		@else
		{{$judul_warung = \App\SettingFooter::select()->first()->judul_warung}}
		@endif
	</title>
	<!-- Bootstrap core CSS     -->
	<link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet"/>
	<link href="{{ asset('css/selectize.bootstrap3.css') }}" rel="stylesheet">
</head>

<style type="text/css">
	p{
		margin-top: 1px; margin-bottom: 1px;
	}
	th,td{
		padding: 1px;
	}
	.table1, .th, .td {
		font-size: 15px;
		font: verdana;
	}
	.table>thead>tr>th, .table>tbody>tr>td {
		padding: 1px;
		line-height: 1.42857143;
		vertical-align: top;
		border: 3px solid #eeeeee;
	}
	.table-bordered {
		border: 3px solid #eeeeee;
	}
</style>
<?php
function pemisahTitik($angka)
{
	return number_format($angka, 0, ',', '.');
}
?>
<body>

	<div class="container">

		<div class="row">
			<div class="col-md-12"><h4><b><center> LAPORAN PENJUALAN PERIODE</center></b></h4><hr style="margin: 0px 0px"></div>
			<div class="col-md-9">
				<table>
					<tbody>
						<tr><td><b> {{ strtoupper($data_warung->name) }} </b></td></tr>
						<tr><td width="100%"> {{ $data_warung->alamat }} </td></tr>
						<tr><td> {{ $data_warung->no_telpon }} </td></tr>
					</tbody>
				</table>
			</div>

			<div class="col-md-3">
				<table>
					<tbody>
						<tr style="text-align: right"><td  width="20%">PERIODE</td> <td> &nbsp;:&nbsp; </td> <td> {{ $dari_tanggal }} s/d {{ $sampai_tanggal }}</td></tr>
					</tbody>
				</table>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				<table class="table table-bordered">
					<thead>
						<th>No Transaksi</th>
						<th style="width:1px;">Waktu</th>
						<th>Pelanggan</th>
						<th>Status</th>
						<th class="text-right">Total</th>
					</thead>
					<tbody>
						@foreach ($penjualans as $penjualan)
						<tr>
							<td class="table1">{{ $penjualan->id }}</td>
							<td class="table1">{{ $penjualan->waktu_jual }}</td>

							@if($penjualan['pelanggan_id'] == 0)
							<td class="table1">{{ "Umum" }}</td>
							@else
							<td class="table1">{{ $penjualan->pelanggan }}</td>
							@endif
							<td class="table1">{{ $penjualan->status_penjualan }}</td>
							<td class="table1" align="right">{{ pemisahTitik($penjualan->total) }}</td>
						</tr>
						@endforeach
						<tr style="color: red">
							<td class="table1">TOTAL</td>
							<td class="table1"></td>
							<td class="table1"></td>
							<td class="table1"></td>
							<td class="table1" align="right">{{ pemisahTitik($total_penjualan) }}</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>

	</div>

</body>
<!--   Core JS Files   -->
<script src="{{ asset('js/app.js?v=1.51')}}" type="text/javascript"></script>
<script>
	// $(document).ready(function(){
	// 	window.print();
	// });
</script>
@yield('scripts')
</html>
