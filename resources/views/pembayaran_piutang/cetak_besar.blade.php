<!DOCTYPE doctype html>
<html lang="en">
<head>

	@if($setting_aplikasi->tipe_aplikasi == 0)
	<link href="{{ asset('img/favicon.png') }}" rel="apple-touch-icon" sizes="76x76"/>
	<link href="{{ asset('img/favicon.png') }}" rel="icon" type="image/png"/>
	<title>
		War-Mart.id
	</title>
	@else
	<link href="{{ asset('img/icon_topos.png?v=1') }}" rel="apple-touch-icon" sizes="76x76"/>
	<link href="{{ asset('img/icon_topos.png?v=1') }}" rel="icon" type="image/png"/>
	<title>
		{{$judul_warung = \App\SettingFooter::select()->first()->judul_warung}}
	</title>
	@endif


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
	.text-center{
		text-align: center;
	}
	.text-right{
		text-align: right;
	}
</style>
<?php
function pemisahTitik($angka)
{
	return number_format($angka, 0, ',', '.');
}
function tanggal($tanggal)
{
	return date_format($tanggal, "d-m-Y");
}
function kekata($x) {
	$x = abs($x);
	$angka = array("", "satu", "dua", "tiga", "empat", "lima",
		"enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
	$temp = "";
	if ($x <12) {
		$temp = " ". $angka[$x];
	} else if ($x <20) {
		$temp = kekata($x - 10). " belas";
	} else if ($x <100) {
		$temp = kekata($x/10)." puluh". kekata($x % 10);
	} else if ($x <200) {
		$temp = " seratus" . kekata($x - 100);
	} else if ($x <1000) {
		$temp = kekata($x/100) . " ratus" . kekata($x % 100);
	} else if ($x <2000) {
		$temp = " seribu" . kekata($x - 1000);
	} else if ($x <1000000) {
		$temp = kekata($x/1000) . " ribu" . kekata($x % 1000);
	} else if ($x <1000000000) {
		$temp = kekata($x/1000000) . " juta" . kekata($x % 1000000);
	} else if ($x <1000000000000) {
		$temp = kekata($x/1000000000) . " milyar" . kekata(fmod($x,1000000000));
	} else if ($x <1000000000000000) {
		$temp = kekata($x/1000000000000) . " trilyun" . kekata(fmod($x,1000000000000));
	}     
	return $temp;
}

$tanggal = date_format(now(), "d-m-Y h:i:s");
?>
<body>

	<div class="container">

		<div class="row">
			<div class="col-md-12">
				<h4>
					<b>
						<center> 
							BUKTI PEMBAYARAN PIUTANG
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
						<tr><td>No. Faktur</td> <td width="5%">:</td> <td> {{$pembayaran_piutang->no_faktur}} </td></tr>
						<tr><td>Kas</td> <td width="5%">:</td> <td> {{$pembayaran_piutang->nama_kas}} </td></tr>
					</tbody>
				</table>
			</div>

			<div class="col-md-3">
				<table>
					<tbody>
						<tr style="text-align: right"><td>Petugas</td> <td width="5%">:</td> <td> {{$petugas}} </td></tr>
						<tr style="text-align: right"><td>Waktu</td> <td width="5%">:</td> <td> {{ $tanggal }} </td></tr>
					</tbody>
				</table>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">				
				<table class="table table-bordered">
					<thead>
						<tr>
							<th>Faktur Penjualan</th>
							<th> Pelanggan </th>
							<th class="text-center">Tanggal JT</th>
							<th class="text-right">Piutang</th>
							<th class="text-right">Potongan</th>
							<th class="text-right">Subtotal</th>
							<th class="text-right">Pembayaran</th>
							<th class="text-right">Sisa</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($detail_pembayaran_piutang as $detail_pembayaran_piutangs)	
						<tr>
							<td>{{ $detail_pembayaran_piutangs->no_faktur_penjualan }}</td>

							@if ($detail_pembayaran_piutangs->pelanggan_id == 0)
							<td>{{ "Umum" }}</td>
							@else
							<td>{{ $detail_pembayaran_piutangs->name }}</td>
							@endif
							
							<td align="center">{{ $detail_pembayaran_piutangs->jatuh_tempo }}</td>
							<td align="right">{{ pemisahTitik($detail_pembayaran_piutangs->piutang) }}</td>
							<td align="right">{{ pemisahTitik($detail_pembayaran_piutangs->potongan) }}</td>
							<td align="right">{{ pemisahTitik($detail_pembayaran_piutangs->piutang - $detail_pembayaran_piutangs->potongan) }}</td>
							<td align="right">{{ pemisahTitik($detail_pembayaran_piutangs->jumlah_bayar) }}</td>
							<td align="right">{{ pemisahTitik( ($detail_pembayaran_piutangs->piutang - $detail_pembayaran_piutangs->potongan) - $detail_pembayaran_piutangs->jumlah_bayar ) }}</td>
						</tr>
						@endforeach
					</tbody>

				</table>
			</div>
		</div>

		<div class="row">
			<div class="col-md-8">
				<table>
					<tbody>
						<tr style="text-align: right"><td>Keterangan</td> <td width="5%">:</td> <td> {{$pembayaran_piutang->keterangan}} </td></tr>
					</tbody>
				</table>
			</div>
			<div class="col-md-4">
				<table>
					<tbody>
						<tr style="text-align: right">
							<td>Total Keseluruhan</td>
							<td width="5%">:</td>
							<td> {{pemisahTitik($subtotal)}} </td>
						</tr>
						<tr style="text-align: right">
							<td>Terbilang</td>
							<td width="5%">:</td>
							<td> {{keKata($subtotal)}} </td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<hr style="margin-top: 30px; margin-bottom: 15px; border-top: 2px solid #eeeeee;">
		<div class="row">
			<div class="col-sm-10">
			</div>
			<div class="col-sm-2">
				<font class="satu">
					<b>Petugas <br><br><br>
						<font class="satu">{{$petugas}}</font>
					</b>
				</font>
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
