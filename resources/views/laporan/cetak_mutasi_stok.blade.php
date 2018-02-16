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
			<div class="col-md-12"><h4><b><center> LAPORAN MUTASI STOK PRODUK</center></b></h4><hr style="margin: 0px 0px"></div>
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
					<th class="table1" style="width: 10%">Kode Produk</th>
					<th class="table1" style="width: 25%">Nama Produk</th>
					<th class="table1" style="width: 5%">Satuan</th>
					<th class="table1" style="width: 5%; text-align:right">Awal</th>
					<th class="table1" style="width: 10%; text-align:right">Nilai Awal</th>
					<th class="table1" style="width: 5%; text-align:right">Masuk</th>
					<th class="table1" style="width: 10%; text-align:right">Nilai Masuk</th>
					<th class="table1" style="width: 5%; text-align:right">Keluar</th>
					<th class="table1" style="width: 10%; text-align:right">Nilai Keluar</th>
					<th class="table1" style="width: 5%; text-align:right">Akhir</th>
					<th class="table1" style="width: 10%; text-align:right">Nilai Akhir</th>
				</thead>
				<tbody>
					@foreach ($data_mutasi_stok as $data_mutasi_stoks)
					<tr>
						<td class="table1">{{ $data_mutasi_stoks['daftar_produks']->kode_barang }}</td>
						<td class="table1">{{ $data_mutasi_stoks['daftar_produks']->nama_barang }}</td>
						<td class="table1">{{ $data_mutasi_stoks['daftar_produks']->nama_satuan }}</td>
						<td class="table1" align="right">{{ pemisahTitik($data_mutasi_stoks['stok_awal']) }}</td>
						<td class="table1" align="right">{{ pemisahTitik($data_mutasi_stoks['total_awal']) }}</td>
						<td class="table1" align="right">{{ pemisahTitik($data_mutasi_stoks['stok_masuk']) }}</td>
						<td class="table1" align="right">{{ pemisahTitik($data_mutasi_stoks['total_masuk']) }}</td>
						<td class="table1" align="right">{{ pemisahTitik($data_mutasi_stoks['stok_keluar']) }}</td>
						<td class="table1" align="right">{{ pemisahTitik($data_mutasi_stoks['total_keluar']) }}</td>
						<td class="table1" align="right">{{ pemisahTitik($data_mutasi_stoks['stok_akhir']) }}</td>
						<td class="table1" align="right">{{ pemisahTitik($data_mutasi_stoks['total_akhir']) }}</td>
					</tr>
					@endforeach<tr style="color: red">
					<td>TOTAL</td>
					<td></td>
					<td></td>
					<td align="right">{{ pemisahTitik($subtotal_mutasi_stok['total_stok_awal']) }}</td>
					<td align="right">{{ pemisahTitik($subtotal_mutasi_stok['total_nilai_awal']) }}</td>
					<td align="right">{{ pemisahTitik($subtotal_mutasi_stok['total_stok_masuk']) }}</td>
					<td align="right">{{ pemisahTitik($subtotal_mutasi_stok['total_nilai_masuk']) }}</td>
					<td align="right">{{ pemisahTitik($subtotal_mutasi_stok['total_stok_keluar']) }}</td>
					<td align="right">{{ pemisahTitik($subtotal_mutasi_stok['total_nilai_keluar']) }}</td>
					<td align="right">{{ pemisahTitik($subtotal_mutasi_stok['total_stok_akhir']) }}</td>
					<td align="right">{{ pemisahTitik($subtotal_mutasi_stok['total_nilai_akhir']) }}</td>
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
	$(document).ready(function(){
		window.print();
	});
</script>
@yield('scripts')
</html>
