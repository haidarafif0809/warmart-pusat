<!DOCTYPE doctype html>
<html lang="en">
<head>
	<link href="{{ asset('img/favicon.png') }}" rel="apple-touch-icon" sizes="76x76"/>
	<link href="{{ asset('img/favicon.png') }}" rel="icon" type="image/png"/>
	<title>
		@if($setting_aplikasi->tipe_aplikasi == 0)
		War-Mart.id
		@else
		topos | Aplikasi POS & Toko Online
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
			<div class="col-md-12"><h4><b><center> LAPORAN PENJUALAN POS /PRODUK</center></b></h4><hr style="margin: 0px 0px"></div>
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
					<th class="table1" style="width: 10%">Kode Produk</th>
					<th class="table1" style="width: 25%">Nama Produk</th>
					<th class="table1" style="width: 10%" style="text-align:right">Jumlah</th>
					<th class="table1" style="width: 10%">Satuan</th>
					<th class="table1" style="width: 10%; text-align:right">Subtotal</th>
					<th class="table1" style="width: 10%; text-align:right">Diskon</th>
					<th class="table1" style="width: 10%; text-align:right">Pajak</th>
					<th class="table1" style="width: 10%; text-align:right">Total</th>
				</thead>
				<tbody>
					@foreach ($data_penjualan as $data_penjualans)
					<tr>
						<td class="table1">{{ $data_penjualans['laporan_penjualans']->kode_barang }}</td>
						<td class="table1">{{ $data_penjualans['laporan_penjualans']->nama_barang }}</td>
						<td class="table1" align="right">{{ pemisahTitik($data_penjualans['laporan_penjualans']->jumlah_produk) }}</td>
						<td class="table1">{{ $data_penjualans['laporan_penjualans']->nama_satuan }}</td>
						<td class="table1" align="right">{{ pemisahTitik($data_penjualans['laporan_penjualans']->subtotal) }}</td>
						<td class="table1" align="right">{{ pemisahTitik($data_penjualans['laporan_penjualans']->potongan) }}</td>
						<td class="table1" align="right">{{ pemisahTitik($data_penjualans['laporan_penjualans']->tax) }}</td>
						<td class="table1" align="right">{{ pemisahTitik($data_penjualans['sub_total']) }}</td>
					</tr>
					@endforeach
					<tr style="color: red">
						<td>TOTAL</td>
						<td></td>
						<td align="right">{{ pemisahTitik($total_penjualan->jumlah_produk) }}</td>
						<td></td>
						<td align="right">{{ pemisahTitik($total_penjualan->subtotal) }}</td>
						<td></td>
						<td></td>
						<td align="right">{{ pemisahTitik($total_penjualan->total) }}</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<div class="row">
			<div class="col-md-12"><h4><b><center> LAPORAN PENJUALAN ONLINE /PRODUK</center></b></h4><hr style="margin: 0px 0px"></div>
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
					<th class="table1" style="width: 10%">Kode Produk</th>
					<th class="table1" style="width: 25%">Nama Produk</th>
					<th class="table1" style="width: 10%" style="text-align:right">Harga</th>
					<th class="table1" style="width: 10%" style="text-align:right">Jumlah</th>
					<th class="table1" style="width: 10%; text-align:right">Subtotal</th>
					<th class="table1" style="width: 10%; text-align:right">Diskon</th>
					<th class="table1" style="width: 10%; text-align:right">Total</th>
				</thead>
				<tbody>
					@foreach ($data_penjualan_online as $data_penjualan_onlines)
					<tr>
						<td class="table1">{{ $data_penjualan_onlines['laporan_penjualan_online']->kode_barang }}</td>
						<td class="table1">{{ $data_penjualan_onlines['laporan_penjualan_online']->nama_barang }}</td>
						<td class="table1" align="right">{{ pemisahTitik($data_penjualan_onlines['laporan_penjualan_online']->harga) }}</td>
						<td class="table1" align="right">{{ pemisahTitik($data_penjualan_onlines['laporan_penjualan_online']->jumlah) }}</td>
						<td class="table1" align="right">{{ pemisahTitik($data_penjualan_onlines['laporan_penjualan_online']->total) }}</td>
						<td class="table1" align="right">{{ pemisahTitik($data_penjualan_onlines['laporan_penjualan_online']->potongan) }}</td>
						<td class="table1" align="right">{{ pemisahTitik($data_penjualan_onlines['laporan_penjualan_online']->subtotal) }}</td>
					</tr>
					@endforeach
					<tr style="color: red">
						<td>TOTAL</td>
						<td></td>
						<td></td>
						<td align="right">{{ pemisahTitik($total_penjualan_online->jumlah) }}</td>
						<td align="right">{{ pemisahTitik($total_penjualan_online->total) }}</td>
						<td></td>
						<td align="right">{{ pemisahTitik($total_penjualan_online->subtotal) }}</td>
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
