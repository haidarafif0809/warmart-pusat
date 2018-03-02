<!DOCTYPE doctype html>
<html lang="en">
<head>
	@if($setting_aplikasi->tipe_aplikasi == 0)
    <link rel="apple-touch-icon"  href="img/favicon.png" />
    <link rel="icon" type="image/png" href="img/favicon.png" />
    @else
    <link rel="apple-touch-icon"  href="img/icon_topos.png?v=1" />
    <link rel="icon" type="image/png" href="img/icon_topos.png?v=1" />
    @endif
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
			<div class="col-md-12">
				<h4>
					<b>
						<center> 
							LAPORAN HUTANG BEREDAR 

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

		</div>

		<div class="row">
			<div class="col-md-12">
				<table class="table table-bordered">
						<th>Waktu</th>
						<th>No Transaksi</th>
						<th>Supplier</th>
						<th style="text-align:right">Nilai Transaksi</th>
						<th style="text-align:right">Dibayar</th>
						<th style="text-align:right">Nilai Hutang</th>
						<th style="text-align:right">Jatuh Tempo</th>
						<th style="text-align:right">Umur Hutang</th>
						<th style="text-align:right">Petugas</th>
				</thead>
				<tbody>
					@foreach ($data_supplier_hutang as $data_supplier_hutangs)
					<tr>
						<td>{{ $data_supplier_hutangs['Waktu'] }}</td>
						<td>{{ $data_supplier_hutangs['no_faktur'] }}</td>
						<td>{{ $data_supplier_hutangs['nama_suplier'] }}</td>
						<td align="right">{{ pemisahTitik($data_supplier_hutangs['total'])  }}</td>
						<td align="right">{{ pemisahTitik($data_supplier_hutangs['pembayaran']) }}</td>
						<td align="right">{{ pemisahTitik($data_supplier_hutangs['sisa_hutang']) }}</td>
						<td align="right">{{ $data_supplier_hutangs['tanggal_jt_tempo'] }}</td>
						<td align="right">{{ pemisahTitik($data_supplier_hutangs['usia_hutang']) }} Hari</td>
						<td align="right">{{ $data_supplier_hutangs['name'] }}</td>
					</tr>
					@endforeach
					<tr style="color: red">
						<td class="table1">Total Nilai</td>
						<td class="table1"></td>
						<td class="table1"></td>
						<td class="table1" align='right'>{{ pemisahTitik($nilai_transaksi) }}</td>
						<td class="table1" align='right'>{{ pemisahTitik($pembayaran) }}</td>
						<td class="table1" align='right'>{{ pemisahTitik($sisa_hutang) }}</td>
						<td class="table1" align='right'></td>
						<td class="table1" align='right'></td>
						<td class="table1" align='right'></td>						
					</tr>
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
