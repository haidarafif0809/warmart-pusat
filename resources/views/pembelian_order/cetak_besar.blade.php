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
					<h4> <b> {{$data_order->name}} </b> </h4> 
					<p> 
						{{$data_order->alamat}}<br>
						No.Telp : {{$data_order->no_telpon}} 
					</p> 
				</center>
			</div>
		</div>


		<div class="row">
			<div class="col-sm-8">				
				<table>
					<tbody>
						<tr><td width="25%"><font class="satu">No Transaksi</font></td> <td> :</td> <td><font class="satu">{{$data_order->no_faktur_order}}</font> </tr>
						<tr><td  width="25%"><font class="satu">Supplier</font></td> <td> :</td> <td><font class="satu"> {{$data_order->nama_suplier}} </font></td></tr>
						<tr><td  width="25%"><font class="satu">Alamat</font></td> <td> :</td> <td><font class="satu"> {{$data_order->alamat}} </font></td></tr>
					</tbody>
				</table>
			</div>

			<div class="col-sm-4">
				<table>
					<tbody>
						<tr><td width="25%"><font class="satu"> Waktu</td> <td> :&nbsp;&nbsp;</td> <td>{{$data_order->created_at}}</font> </td></tr> 
						<tr><td width="25%"><font class="satu"> Status Order</td> <td> :&nbsp;&nbsp;</td> <td>{{$status_order}}</font></td></tr> 

					</tbody>
				</table>

			</div>
		</div><br>

		<table class="table table-bordered">
			<thead>
				<th class="table1" style="width: 35%"> Nama Produk  </th>
				<th class="table1" style="width: 5%"> <center> Satuan </center> </th>
				<th class="table1" style="width: 5%"> <center> Qty </center> </th>
				<th class="table1" style="width: 15%"> <center> Harga </center> </th>
				<th class="table1" style="width: 5%"> <center> Disc. </center> </th>
				<th class="table1" style="width: 5%"> <center> Tax. </center> </th>
				<th class="table1" style="width: 12%"> <center> Subtotal </center> </th>

			</thead>
			<tbody>

				@foreach ($detail_orders as $detail_order)	
				<tr>
					<td class='table1'>{{title_case($detail_order->nama_barang)}} </td>
					<td class='table1' align='right'>{{$detail_order->nama_satuan}} </td>
					<td class='table1' align='right'>{{number_format($detail_order->jumlah_produk, 0, ',', '.')}} </td>
					<td class='table1' align='right'>{{number_format($detail_order->harga_produk, 0, ',', '.')}}</td>
					<td class='table1' align='right'>{{number_format($detail_order->potongan, 0, ',', '.')}}</td>
					<td class='table1' align='right'>{{number_format($detail_order->tax, 0, ',', '.')}}</td>
					<td class='table1' align='right'>{{number_format($detail_order->subtotal, 0, ',', '.')}}</td>
				</tr>
				@endforeach
				<tr style="color: red">
					<td class='table1'>TOTAL AKHIR</td>
					<td> </td>
					<td> </td>
					<td> </td>
					<td> </td>
					<td> </td>
					<td class='table1' align='right'>{{number_format($subtotal, 0, ',', '.')}}</td>
				</tr>

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
