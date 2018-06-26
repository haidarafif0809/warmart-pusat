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

?>
<body>

	<div class="container">
		<div class="row"><!--row1-->
			<div class="col-sm-2"></div><!--penutup colsm2-->
			<div class="col-sm-8">
				<center> <h4> <b> {{strtoupper($retur_penjualan->name)}} </b> </h4> 
					<p> {{$retur_penjualan->alamat}}<br>
						No.Telp : {{$retur_penjualan->no_telpon}} 
					</p> 
				</center>

			</div><!--penutup colsm5-->
		</div><!--penutup row1-->


		<div class="row">
			<div class="col-sm-9">				
				<table>
					<tbody>
						<tr><td width="25%">No Transaksi</td> <td> :</td> <td>{{$retur_penjualan->no_faktur_retur}} </tr>
					</tbody>
				</table>
			</div>

			<div class="col-sm-3">
				<table>
					<tbody>
						<tr><td width="25%"> Waktu</td> <td> :&nbsp;&nbsp;</td> <td>{{tanggal($retur_penjualan->created_at)}} </td></tr> 
						<tr><td width="25%"> Pelanggan</td> <td> :&nbsp;&nbsp;</td> <td>{{$pelanggan}}</td></tr> 

					</tbody>
				</table>

			</div> <!--end col-sm-2-->
		</div> <!--end row-->  
		<br>
		<table class="table table-bordered">
			<thead>
				<th>Produk</th>
				<th class="text-right">Jumlah Retur</th>
				<th class="text-center">Satuan</th>
				<th class="text-right">Harga</th>
				<th class="text-right">Potongan</th>
				<th class="text-right">Subtotal</th>
			</thead>
			<tbody>
				@foreach ($detail_retur as $detail_returs)	
				<tr>
					<td>{{ title_case($detail_returs->nama_barang) }}</td>
					<td class="text-right">{{ pemisahTitik($detail_returs->jumlah_retur) }}</td>
					<td class="text-center">{{ strtoupper($detail_returs->nama_satuan) }}</td>
					<td class="text-right">{{ pemisahTitik($detail_returs->harga_produk) }}</td>
					<td class="text-right">{{ pemisahTitik($detail_returs->potongan) }}</td>
					<td class="text-right">{{ pemisahTitik($detail_returs->subtotal) }}</td>
				</tr>
				@endforeach
			</tbody>
		</table>

		<div class="row">
			<div class="col-sm-9">
				<i><b>Terbilang :</b>{{ title_case($terbilang) }}</i> <br>
			</div>

			<div class="col-sm-3">
				<table>
					<tbody>
						<tr><td  width="50%">Subtotal</td> <td> :&nbsp;</td> <td class="text-right"> {{ pemisahTitik($subtotal) }}  </td></tr>
						<tr><td  width="50%">Diskon Faktur</td> <td> :&nbsp;</td> <td class="text-right"> {{ pemisahTitik($retur_penjualan->potongan) }}  </td></tr>
						<tr><td  width="50%">Total Akhir</td> <td> :&nbsp;</td> <td class="text-right"> {{ pemisahTitik($retur_penjualan->total) }}  </td></tr>
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
						<font class="satu">( .......... )</font>
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
