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
	return number_format($angka, 2, ',', '.');
}
?>
<body>

	<div class="container">

		<div class="row">
			<div class="col-md-12">
				<h4>
					<b>
						<center> 
							LAPORAN KARTU STOK 

							<table style="text-align: center">
								<tbody>
									<tr><td><b> {{ strtoupper($data_warung->name) }} </b></td></tr>
									<tr><td width="100%"> {{ $data_warung->alamat }} </td></tr>
									<tr><td> {{ $data_warung->no_telpon }} </td></tr>
								</tbody>
							</table>
						</center>
					</b>
				</h4>
				<hr style="margin: 0px 0px">
			</div>
			<div class="col-md-9">
				<table>
					<tbody>
						<tr><td>Kode Produk</td> <td width="5%">:</td> <td> {{ $produk->kode_barang }} </td></tr>
						<tr><td>Nama Produk</td> <td width="5%">:</td> <td> {{ strtoupper($produk->nama_barang) }}</td></tr>
					</tbody>
				</table>
			</div>

			<div class="col-md-3">
				<table>
					<tbody>
						<tr style="text-align: right"><td>PERIODE</td> <td width="5%">:</td> <td> {{ $dari_tanggal }} s/d {{ $sampai_tanggal }}</td></tr>
					</tbody>
				</table>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">

				<table class="table table-bordered">
					<th class="table1" style="width: 10%">No Faktur</th>
					<th class="table1" style="width: 10%">Jenis Transaksi</th>
					<th class="table1" style="width: 10%; text-align:right">Harga</th>
					<th class="table1" style="width: 10%; text-align:center">Waktu</th>
					<th class="table1" style="width: 10%; text-align:right">Jumlah Masuk</th>
					<th class="table1" style="width: 10%; text-align:right">Jumlah Keluar</th>
					<th class="table1" style="width: 10%; text-align:right">Saldo</th>
				</thead>
				<tbody>
					<tr style="color: red">
						<td class="table1"></td>
						<td class="table1">SALDO AWAL</td>
						<td class="table1"></td>
						<td class="table1"></td>
						<td class="table1"></td>
						<td class="table1"></td>
						<td class="table1" align='right'>{{ pemisahTitik($total_saldo_awal) }}</td>
					</tr>

					@foreach ($data_kartu_stok as $kartu_stok)
					<tr>
						<td class="table1">{{ $kartu_stok['data_kartu_stoks']->no_faktur }}</td>
						<td class="table1">
							@if($kartu_stok['data_kartu_stoks']->jenis_transaksi == 'item_masuk')
							Item Masuk
							@elseif($kartu_stok['data_kartu_stoks']->jenis_transaksi == 'item_keluar')
							Item Keluar
							@elseif($kartu_stok['data_kartu_stoks']->jenis_transaksi == 'PenjualanPos')
							Penjualan POS - {{$kartu_stok['pelanggan']}}
							@elseif($kartu_stok['data_kartu_stoks']->jenis_transaksi == 'pembelian')
							Pembelian - {{$kartu_stok['suplier']}}
							@elseif($kartu_stok['data_kartu_stoks']->jenis_transaksi == 'pembelian')
							Pembelian - {{$kartu_stok['suplier']}}
							@elseif($kartu_stok['data_kartu_stoks']->jenis_transaksi == 'penjualan')
							Penjualan Online - {{$kartu_stok['data_kartu_stoks']->pelanggan_online}}
							@endif
						</td>
						<td class="table1" align='right'>{{ pemisahTitik($kartu_stok['data_kartu_stoks']->harga_unit) }}</td>
						<td class="table1" align="center">{{ date_format($kartu_stok['data_kartu_stoks']->created_at, "d M Y") }}</td>
						<td class="table1" align='right'>{{ pemisahTitik($kartu_stok['data_kartu_stoks']->jumlah_masuk) }}</td>
						<td class="table1" align='right'>{{ pemisahTitik($kartu_stok['data_kartu_stoks']->jumlah_keluar) }}</td>
						<td class="table1" align='right'>{{ pemisahTitik($kartu_stok['saldo_awal']) }}</td>
					</tr>
					@endforeach
				</tbody>
			</table>

		</div>

		<div class="col-sm-9"></div>
		<div class="col-sm-3" style="text-align: right; font-weight: bold">
			<font>Petugas <br><br><br> {{$petugas}}</font>
		</div> <!--/ col-sm-6-->

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
