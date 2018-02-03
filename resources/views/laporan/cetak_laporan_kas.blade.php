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
function tanggal($tanggal)
{
	return date_format($tanggal, "d-m-Y H:i:s");
}
?>
<body>

	<div class="container">

		<div class="row">
			<div class="col-md-12"><h4><b><center>
				@if($jenis_laporan == 0)
				LAPORAN KAS DETAIL PERPERIODE
				@else
				LAPORAN KAS REKAP PERPERIODE
				@endif
			</center></b></h4><hr style="margin: 0px 0px"></div>
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
						<tr style="text-align: right">
							<td  width="20%">Periode</td> <td> &nbsp;:&nbsp; </td> 
							<td> {{ $dari_tanggal }} s/d {{ $sampai_tanggal }}</td>
						</tr>
						<tr style="text-align: right">
							<td  width="20%">Petugas</td> <td> &nbsp;:&nbsp; </td> 
							<td> {{ $petugas }}</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>

		<hr style="margin: 0px 0px">

		<div class="row">
			<div class="col-md-12">	
				@if($jenis_laporan == 0) <!-- LAPORAN DETAIL -->

				<h5><b> KAS MASUK : <span style="color:red">Rp. {{pemisahTitik($subtotal_kas_masuk)}}</span> </b></h5>
				<table class="table table-bordered">
					<thead>
						<th class="table1">No. Transaksi</th>
						<th class="table1">Jenis Transaksi</th>
						<th class="table1">Ke Kas</th>
						<th class="table1" style="text-align:right">Total</th>
						<th class="table1">Waktu</th>
					</thead>
					<tbody>
						@foreach ($data_laporan_kas as $data_laporan_kass)
						<tr>
							<td class="table1">{{ $data_laporan_kass['data_laporan']->no_faktur }}</td>
							<td class="table1">{{ $data_laporan_kass['jenis_transaksi'] }}</td>
							<td class="table1">{{ $data_laporan_kass['data_laporan']->nama_kas }}</td>
							<td class="table1" align="right">{{ pemisahTitik($data_laporan_kass['data_laporan']->jumlah_masuk) }}</td>
							<td class="table1">{{ tanggal($data_laporan_kass['data_laporan']->created_at) }}</td>
						</tr>
						@endforeach
					</tbody>
				</table>
				<hr style="margin-top: 30px; margin-bottom: 15px; border-top: 5px solid #eeeeee;">				
				<h5><b> KAS KELUAR : <span style="color:red">Rp. {{pemisahTitik($subtotal_kas_keluar)}}</span> </b></h5>
				<table class="table table-bordered">
					<thead>
						<th class="table1">No. Transaksi</th>
						<th class="table1">Jenis Transaksi</th>
						<th class="table1">Ke Kas</th>
						<th class="table1" style="text-align:right">Total</th>
						<th class="table1">Waktu</th>
					</thead>
					<tbody>
						@foreach ($data_laporan_kas_keluar as $data_laporan_kas_keluars)
						<tr>
							<td class="table1">{{ $data_laporan_kas_keluars['data_laporan']->no_faktur }}</td>
							<td class="table1">{{ $data_laporan_kas_keluars['jenis_transaksi'] }}</td>
							<td class="table1">{{ $data_laporan_kas_keluars['data_laporan']->nama_kas }}</td>
							<td class="table1" align="right">{{ pemisahTitik($data_laporan_kas_keluars['data_laporan']->jumlah_keluar) }}</td>
							<td class="table1">{{ tanggal($data_laporan_kas_keluars['data_laporan']->created_at) }}</td>
						</tr>
						@endforeach
					</tbody>
				</table>
				<hr style="margin-top: 30px; margin-bottom: 15px; border-top: 5px solid #eeeeee;">				
				<h5><b> KAS MUTASI (MASUK) : <span style="color:red">Rp. {{pemisahTitik($subtotal_kas_mutasi_masuk)}}</span> </b></h5>
				<table class="table table-bordered">
					<thead>
						<th class="table1">No. Transaksi</th>
						<th class="table1">Jenis Transaksi</th>
						<th class="table1">Ke Kas</th>
						<th class="table1" style="text-align:right">Total</th>
						<th class="table1">Waktu</th>
					</thead>
					<tbody>
						@foreach ($data_laporan_kas_mutasi_masuk as $data_laporan_kas_mutasi_masuks)
						<tr>
							<td class="table1">{{ $data_laporan_kas_mutasi_masuks['data_laporan']->no_faktur }}</td>
							<td class="table1">{{ $data_laporan_kas_mutasi_masuks['jenis_transaksi'] }}</td>
							<td class="table1">{{ $data_laporan_kas_mutasi_masuks['data_laporan']->nama_kas }}</td>
							<td class="table1" align="right">{{ pemisahTitik($data_laporan_kas_mutasi_masuks['data_laporan']->jumlah_masuk) }}</td>
							<td class="table1">{{ tanggal($data_laporan_kas_mutasi_masuks['data_laporan']->created_at) }}</td>
						</tr>
						@endforeach
					</tbody>
				</table>
				<hr style="margin-top: 30px; margin-bottom: 15px; border-top: 5px solid #eeeeee;">				
				<h5><b> KAS MUTASI (KELUAR) : <span style="color:red">Rp. {{pemisahTitik($subtotal_kas_mutasi_keluar)}}</span> </b></h5>
				<table class="table table-bordered">
					<thead>
						<th class="table1">No. Transaksi</th>
						<th class="table1">Jenis Transaksi</th>
						<th class="table1">Ke Kas</th>
						<th class="table1" style="text-align:right">Total</th>
						<th class="table1">Waktu</th>
					</thead>
					<tbody>
						@foreach ($data_laporan_kas_mutasi_keluar as $data_laporan_kas_mutasi_keluars)
						<tr>
							<td class="table1">{{ $data_laporan_kas_mutasi_keluars['data_laporan']->no_faktur }}</td>
							<td class="table1">{{ $data_laporan_kas_mutasi_keluars['jenis_transaksi'] }}</td>
							<td class="table1">{{ $data_laporan_kas_mutasi_keluars['data_laporan']->nama_kas }}</td>
							<td class="table1" align="right">{{ pemisahTitik($data_laporan_kas_mutasi_keluars['data_laporan']->jumlah_keluar) }}</td>
							<td class="table1">{{ tanggal($data_laporan_kas_mutasi_keluars['data_laporan']->created_at) }}</td>
						</tr>
						@endforeach
					</tbody>
				</table>
				<hr style="margin-top: 30px; margin-bottom: 15px; border-top: 5px solid #eeeeee;">

				@else <!-- LAPORAN REKAP -->

				<h5><b> KAS MASUK : <span style="color:red">Rp. {{pemisahTitik($subtotal_kas_masuk)}}</span> </b></h5>
				<table class="table table-bordered">
					<thead>
						<th class="table1">Waktu</th>
						<th class="table1">Ke Kas</th>
						<th class="table1" style="text-align:right">Total</th>
					</thead>
					<tbody>
						@foreach ($data_laporan_kas as $data_laporan_kass)
						<tr>
							<td class="table1">{{ tanggal($data_laporan_kass['data_laporan']->created_at) }}</td>
							<td class="table1">{{ $data_laporan_kass['data_laporan']->nama_kas }}</td>
							<td class="table1" align="right">{{ pemisahTitik($data_laporan_kass['data_laporan']->jumlah_masuk) }}</td>
						</tr>
						@endforeach
					</tbody>
				</table>
				<hr style="margin-top: 30px; margin-bottom: 15px; border-top: 5px solid #eeeeee;">				
				<h5><b> KAS KELUAR : <span style="color:red">Rp. {{pemisahTitik($subtotal_kas_keluar)}}</span> </b></h5>
				<table class="table table-bordered">
					<thead>
						<th class="table1">Waktu</th>
						<th class="table1">Ke Kas</th>
						<th class="table1" style="text-align:right">Total</th>
					</thead>
					<tbody>
						@foreach ($data_laporan_kas_keluar as $data_laporan_kas_keluars)
						<tr>
							<td class="table1">{{ tanggal($data_laporan_kas_keluars['data_laporan']->created_at) }}</td>
							<td class="table1">{{ $data_laporan_kas_keluars['data_laporan']->nama_kas }}</td>
							<td class="table1" align="right">{{ pemisahTitik($data_laporan_kas_keluars['data_laporan']->jumlah_keluar) }}</td>
						</tr>
						@endforeach
					</tbody>
				</table>
				<hr style="margin-top: 30px; margin-bottom: 15px; border-top: 5px solid #eeeeee;">				
				<h5><b> KAS MUTASI (MASUK) : <span style="color:red">Rp. {{pemisahTitik($subtotal_kas_mutasi_masuk)}}</span> </b></h5>
				<table class="table table-bordered">
					<thead>
						<th class="table1">Waktu</th>
						<th class="table1">Ke Kas</th>
						<th class="table1" style="text-align:right">Total</th>
					</thead>
					<tbody>
						@foreach ($data_laporan_kas_mutasi_masuk as $data_laporan_kas_mutasi_masuks)
						<tr>
							<td class="table1">{{ tanggal($data_laporan_kas_mutasi_masuks['data_laporan']->created_at) }}</td>
							<td class="table1">{{ $data_laporan_kas_mutasi_masuks['data_laporan']->nama_kas }}</td>
							<td class="table1" align="right">{{ pemisahTitik($data_laporan_kas_mutasi_masuks['data_laporan']->jumlah_masuk) }}</td>
						</tr>
						@endforeach
					</tbody>
				</table>
				<hr style="margin-top: 30px; margin-bottom: 15px; border-top: 5px solid #eeeeee;">				
				<h5><b> KAS MUTASI (KELUAR) : <span style="color:red">Rp. {{pemisahTitik($subtotal_kas_mutasi_keluar)}}</span> </b></h5>
				<table class="table table-bordered">
					<thead>
						<th class="table1">Waktu</th>
						<th class="table1">Ke Kas</th>
						<th class="table1" style="text-align:right">Total</th>
					</thead>
					<tbody>
						@foreach ($data_laporan_kas_mutasi_keluar as $data_laporan_kas_mutasi_keluars)
						<tr>
							<td class="table1">{{ tanggal($data_laporan_kas_mutasi_keluars['data_laporan']->created_at) }}</td>
							<td class="table1">{{ $data_laporan_kas_mutasi_keluars['data_laporan']->nama_kas }}</td>
							<td class="table1" align="right">{{ pemisahTitik($data_laporan_kas_mutasi_keluars['data_laporan']->jumlah_keluar) }}</td>
						</tr>
						@endforeach
					</tbody>
				</table>
				<hr style="margin-top: 30px; margin-bottom: 15px; border-top: 5px solid #eeeeee;">

				@endif
			</div>
			<div class="col-sm-10">
				<h4><b>TOTAL KAS</b></h4>
				<table>
					<tbody>
						<tr>
							<td width="50%">Kas Awal</td>
							<td> :&nbsp;</td>
							<td class="text-right"> Rp. {{pemisahTitik($total_kas['total_awal'])}} </td>
						</tr>
						<tr>
							<td width="50%">Perubahan Kas</td>
							<td> :&nbsp;</td>
							<td class="text-right"> Rp. {{pemisahTitik($total_kas['perubahan_kas'])}} </td>
						</tr>
						<tr>
							<td width="50%">Kas Akhir</td>
							<td> :&nbsp;</td>
							<td class="text-right"> Rp. {{pemisahTitik($total_kas['total_akhir'])}} </td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="col-sm-2">
				<font class="satu">
					<b>Petugas <br><br><br><br>
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
