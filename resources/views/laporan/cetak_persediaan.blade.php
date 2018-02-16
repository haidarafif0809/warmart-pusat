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
							LAPORAN PERSEDIAAN 

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
				   <th>Kode Produk</th>
				   <th>Nama Produk</th>
				   <th>Satuan</th>
				   <th class="text-right">Stok</th>
				   <th class="text-right">Hpp</th>
				   <th class="text-right">Nilai</th>
				</thead>
				<tbody>
					@foreach ($data_persediaan as $persediaans)
					<tr>
						<td class="table1">{{$persediaans['kode_produk']}}</td>
						<td class="table1">{{$persediaans['nama_produk']}}</td>
						<td class="table1">{{$persediaans['satuan']}}</td>
						<td class="table1" align='right'>{{$persediaans['stok']}}</td>
						<td class="table1" align='right'>{{$persediaans['hpp']}}</td>
						<td class="table1" align='right'>{{$persediaans['nilai']}}</td>
					</tr>
					@endforeach
					<tr style="color: red">
						<td class="table1">Total Nilai</td>
						<td class="table1"></td>
						<td class="table1"></td>
						<td class="table1"></td>
						<td class="table1"></td>
						<td class="table1" align='right'>{{ $total_nilai }}</td>
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
