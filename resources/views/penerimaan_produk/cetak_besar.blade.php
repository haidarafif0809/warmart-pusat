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
	th,td{
		padding: 1px;
	}
	.table1, .th, .td {
		border: 1px solid black;
		font-size: 15px;
		font: verdana;
	}
</style>

<body>

	<div class="container">

		<div class="row">
			<div class="col-sm-2"></div>
			<div class="col-sm-8">
				<center>
					<h4> <b> {{$data_penerimaan->name}} </b> </h4> 
					<p> 
						{{$data_penerimaan->alamat}}<br>
						No.Telp : {{$data_penerimaan->no_telpon}} 
					</p> 
				</center>
			</div>
		</div>


		<div class="row">
			<div class="col-sm-8">				
				<table>
					<tbody>
						<tr><td width="15%"><font class="satu">No Transaksi</font></td> <td> :</td> <td><font class="satu">{{$data_penerimaan->no_faktur_penerimaan}}</font> </tr>
						<tr><td  width="15%"><font class="satu">Supplier</font></td> <td> :</td> <td><font class="satu"> {{$data_penerimaan->nama_suplier}} </font></td></tr>
						<tr><td  width="15%"><font class="satu">Alamat</font></td> <td> :</td> <td><font class="satu"> {{$data_penerimaan->alamat_supplier}} </font></td></tr>
					</tbody>
				</table>
			</div>

			<div class="col-sm-4">
				<table>
					<tbody>
						<tr><td width="15%"><font class="satu"> Waktu</td> <td> :&nbsp;&nbsp;</td> <td>{{$data_penerimaan->created_at}}</font> </td></tr> 
						<tr><td width="15%"><font class="satu"> Status </td> <td> :&nbsp;&nbsp;</td> <td>{{$status_penerimaan}}</font></td></tr> 

					</tbody>
				</table>

			</div>
		</div><br>

		<table class="table table-bordered">
			<thead>
				<th class="table1" style="width: 35%"> Nama Produk  </th>
				<th class="table1" style="width: 5%; text-align: right;"> Jumlah Order  </th>
				<th class="table1" style="width: 5%; text-align: right;"> Jumlah Fisik  </th>
				<th class="table1" style="width: 5%; text-align: right;"> Selisih Fisik  </th>
				<th class="table1" style="width: 5%"> <center> Satuan </center> </th>

			</thead>
			<tbody>

				@foreach ($detail_orders as $detail_order)	
				<tr>
					<td class='table1'>{{title_case($detail_order->nama_barang)}} </td>
					<td class='table1' align='right'>
						{{number_format($detail_order->jumlah_produk, 0, ',', '.')}}
					</td>
					<td class='table1' align='right'>
						{{number_format($detail_order->jumlah_fisik, 0, ',', '.')}}
					</td>
					<td class='table1' align='right'>
						{{number_format($detail_order->selisih_fisik, 0, ',', '.')}}
					</td>
					<td class='table1' align='right'>{{$detail_order->nama_satuan}} </td>
				</tr>
				@endforeach
			</tbody>

		</table>

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
