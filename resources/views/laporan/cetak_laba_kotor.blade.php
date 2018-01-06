<!DOCTYPE doctype html>
<html lang="en">
<head>
	<link href="{{ asset('img/favicon.png') }}" rel="apple-touch-icon" sizes="76x76"/>
	<link href="{{ asset('img/favicon.png') }}" rel="icon" type="image/png"/>
	<title>
		War-Mart.id
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
			<div class="col-md-12"><h4><b><center> LAPORAN LABA KOTOR </center></b></h4><hr style="margin: 0px 0px"></div>
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
				<h5><b> PENJUALAN POS </b></h5>
				<table class="table table-bordered">
					<thead>
						<th class="table1" style="width:10%">No. Transaksi</th>
						<th class="table1" style="width: 10%">Waktu</th>
						<th class="table1" style="width: 10%">Pelanggan</th>
						<th class="table1" style="width: 10%; text-align: right">Penjualan</th>
						<th class="table1" style="width: 10%; text-align: right">Hpp</th>
						<th class="table1" style="width: 10%; text-align: right">Laba Kotor</th>
						<th class="table1" style="width: 10%; text-align: right">Diskon Faktur</th>
						<th class="table1" style="width: 10%; text-align: right">Laba Jual</th>
					</thead>
					<tbody>
						@foreach ($data_laba_kotor as $data_laba_kotors)
						<tr>
							<td class="table1">{{ $data_laba_kotors['laba_kotor']->id }}</td>
							<td class="table1">{{ date_format($data_laba_kotors['laba_kotor']->created_at, "d M Y") }}</td>
							<td class="table1">{{ $data_laba_kotors['laba_kotor']->name }}</td>
							<td class="table1" align='right'>{{ pemisahTitik($data_laba_kotors['total']) }}</td>
							<td class="table1" align='right'>{{ pemisahTitik($data_laba_kotors['hpp']) }}</td>
							<td class="table1" align='right'>{{ pemisahTitik($data_laba_kotors['total_laba_kotor']) }}</td>
							<td class="table1" align='right'>{{ pemisahTitik($data_laba_kotors['laba_kotor']->potongan) }}</td>
							<td class="table1" align='right'>{{ pemisahTitik($data_laba_kotors['laba_jual']) }}</td>
						</tr>
						@endforeach
						<tr style="color: red">
							<td class="table1">TOTAL</td>
							<td class="table1"></td>
							<td class="table1"></td>
							<td class="table1" align='right'>{{ pemisahTitik($subtotal_penjualan) }}</td>
							<td class="table1" align='right'>{{ pemisahTitik($subtotal_hpp) }}</td>
							<td class="table1" align='right'>{{ pemisahTitik($subtotal_laba_kotor) }}</td>
							<td class="table1" align='right'>{{ pemisahTitik($subtotal_potongan) }}</td>
							<td class="table1" align='right'>{{ pemisahTitik($subtotal_laba_jual) }}</td>
						</tr>
					</tbody>
				</table>


				<h5><b> PENJUALAN PESANAN </b></h5>
				<table class="table table-bordered">
					<thead>
						<th class="table1" style="width:10%">No. Transaksi</th>
						<th class="table1" style="width: 10%">Waktu</th>
						<th class="table1" style="width: 10%">Pelanggan</th>
						<th class="table1" style="width: 10%; text-align: right">Penjualan</th>
						<th class="table1" style="width: 10%; text-align: right">Hpp</th>
						<th class="table1" style="width: 10%; text-align: right">Laba Kotor</th>
						<th class="table1" style="width: 10%; text-align: right">Diskon Faktur</th>
						<th class="table1" style="width: 10%; text-align: right">Laba Jual</th>
					</thead>
					<tbody>
						@foreach ($data_laba_kotor_pesanan as $data_laba_kotor_pesanans)
						<tr>
							<td class="table1">{{ $data_laba_kotor_pesanans['laba_kotor_pesanan']->id }}</td>
							<td class="table1">{{ date_format($data_laba_kotor_pesanans['laba_kotor_pesanan']->created_at, "d M Y") }}</td>
							<td class="table1">{{ $data_laba_kotor_pesanans['laba_kotor_pesanan']->name }}</td>
							<td class="table1" align='right'>{{ pemisahTitik($data_laba_kotor_pesanans['total']) }}</td>
							<td class="table1" align='right'>{{ pemisahTitik($data_laba_kotor_pesanans['hpp']) }}</td>
							<td class="table1" align='right'>{{ pemisahTitik($data_laba_kotor_pesanans['total_laba_kotor_pesanan']) }}</td>
							<td class="table1" align='right'>0</td>
							<td class="table1" align='right'>{{ pemisahTitik($data_laba_kotor_pesanans['laba_jual']) }}</td>
						</tr>
						@endforeach
						<tr style="color: red">
							<td class="table1">TOTAL</td>
							<td class="table1"></td>
							<td class="table1"></td>
							<td class="table1" align='right'>{{ pemisahTitik($subtotal_penjualan_pesanan) }}</td>
							<td class="table1" align='right'>{{ pemisahTitik($subtotal_hpp_pesanan) }}</td>
							<td class="table1" align='right'>{{ pemisahTitik($subtotal_laba_kotor_pesanan) }}</td>
							<td class="table1" align='right'>{{ 0 }}</td>
							<td class="table1" align='right'>{{ pemisahTitik($subtotal_laba_jual_pesanan) }}</td>
						</tr>
					</tbody>
				</table>
			</div>

			<div class="col-md-9"></div>
			<div class="col-md-3">
				<table>
					<tbody style="font-weight: bold">
						<tr><td width="75%"><font class="satu">Total Penjualan</font></td> <td> :&nbsp;</td> <td><font class="satu"> {{ pemisahTitik($total_akhir['total_penjualan']) }} </font></tr>
						<tr><td width="75%"><font class="satu">Total Hpp</font></td> <td> :&nbsp;</td> <td><font class="satu"> {{ pemisahTitik($total_akhir['total_hpp']) }} </font></tr>
						<tr><td width="75%"><font class="satu">Total Laba Kotor</font></td> <td> :&nbsp;</td> <td><font class="satu"> {{ pemisahTitik($total_akhir['total_laba_kotor']) }} </font></tr>
						<tr><td width="75%"><font class="satu">Total Diskon Faktur</font></td> <td> :&nbsp;</td> <td><font class="satu"> {{ pemisahTitik($total_akhir['total_potongan']) }} </font></tr>
						<tr><td width="75%"><font class="satu">Total Laba Jual</font></td> <td> :&nbsp;</td> <td><font class="satu"> {{ pemisahTitik($total_akhir['total_laba_jual']) }} </font></tr>
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
