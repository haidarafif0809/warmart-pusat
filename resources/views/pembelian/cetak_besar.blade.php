<!DOCTYPE doctype html>
<html lang="en">
<head>
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
		<div class="row"><!--row1-->
			<div class="col-sm-2"></div><!--penutup colsm2-->
			<div class="col-sm-8">
				<center> <h4> <b> {{$pembelian->nama_warung}} </b> </h4> 
					<p> {{$pembelian->alamat_warung}}<br>
						No.Telp : {{$pembelian->no_telp_warung}} </p> </center>

					</div><!--penutup colsm5-->
				</div><!--penutup row1-->


				<div class="row">
					<div class="col-sm-8">				
						<table>
							<tbody>
								<tr><td width="25%"><font class="satu">No Transaksi</font></td> <td> :</td> <td><font class="satu">{{$pembelian->no_faktur}}</font> </tr>
								<tr><td  width="25%"><font class="satu">Supplier</font></td> <td> :</td> <td><font class="satu"> {{$nama_suplier}} </font></td></tr>
								<tr><td  width="25%"><font class="satu">Alamat</font></td> <td> :</td> <td><font class="satu"> {{$alamat_suplier}} </font></td></tr>
							</tbody>
						</table>
					</div>

					<div class="col-sm-4">
						<table>
							<tbody>
								<tr><td width="25%"><font class="satu"> Waktu</td> <td> :&nbsp;&nbsp;</td> <td>{{$pembelian->waktu_beli}}</font> </td></tr> 
								<tr><td width="25%"><font class="satu"> Kasir</td> <td> :&nbsp;&nbsp;</td> <td>{{$pembelian->kasir}}</font></td></tr> 
								<tr><td width="25%"><font class="satu"> Status </td> <td> :&nbsp;&nbsp;</td> <td>{{$pembelian->status_pembelian}}</font></td></tr> 

							</tbody>
						</table>

					</div> <!--end col-sm-2-->
				</div> <!--end row-->  
				<br>
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

						@foreach ($detail_pembelian as $detail_pembelians)	
						<tr>
							<td class='table1'>{{title_case($detail_pembelians->produk->nama_barang)}} </td>
							<td class='table1' align='right'>{{$detail_pembelians->produk->satuan->nama_satuan}} </td>
							<td class='table1' align='right'>{{number_format($detail_pembelians->jumlah_produk, 2, ',', '.')}} </td>
							<td class='table1' align='right'>{{number_format($detail_pembelians->harga_produk, 2, ',', '.')}}</td>
							<td class='table1' align='right'>{{number_format($detail_pembelians->potongan, 2, ',', '.')}}</td>
							<td class='table1' align='right'>{{number_format($detail_pembelians->tax, 2, ',', '.')}}</td>
							<td class='table1' align='right'>{{number_format($detail_pembelians->subtotal, 2, ',', '.')}}</td>
						</tr>
						@endforeach

					</tbody>

				</table>
				<br>

				<div class="row"><!--row1-->
					<div class="col-sm-8">
						<i><b><font class="satu">Terbilang :</font></b>{{$terbilang}}</i> <br>
					</div><!--penutup colsm2-->

					<div class="col-sm-2">
						<table>
							<tbody>

								<tr><td  width="40%"><font class="satu">Kas</font></td> <td> :&nbsp;</td> <td class="text-right"><font class="satu">{{$pembelian->nama_kas}}</font> </td></tr>  
								<tr><td  width="40%"><font class="satu">Bayar</font></td> <td> :&nbsp;</td> <td class="text-right"><font class="satu"> {{number_format($pembelian->tunai, 2, ',', '.')}}</font> </td></tr>
								<tr><td  width="40%"><font class="satu">Kembalian</font></td> <td> :&nbsp;</td> <td class="text-right"><font class="satu">{{number_format($pembelian->kembalian, 2, ',', '.')}}</font> </td></tr> 

							</tbody>
						</table>

					</div>

					<div class="col-sm-2">

						<table>
							<tbody>
								<tr><td width="50%"><font class="satu">Subtotal</font></td> <td> :&nbsp;</td> <td class="text-right"><font class="satu"> {{number_format($subtotal, 2, ',', '.')}} </font></tr>
								<tr><td width="50%"><font class="satu">Diskon</font></td> <td> :&nbsp;</td> <td class="text-right"><font class="satu"> {{number_format($pembelian->potongan, 2, ',', '.')}}</font> </tr>
								<tr><td  width="50%"><font class="satu">Total Akhir</font></td> <td> :&nbsp;</td> <td class="text-right"><font class="satu"> {{number_format($pembelian->total, 2, ',', '.')}}</font>  </td></tr>
							</tbody>
						</table>


					</div><!--penutup colsm5-->
				</div><!--penutup row1-->
				<br>
				<div class="row">
					<div class="col-sm-9">

						<font class="satu"><b>Supplier<br><br><br> <font class="satu">{{$nama_suplier}}</font> </b></font>

					</div> <!--/ col-sm-6-->

					<div class="col-sm-3">

						<font class="satu"><b>Petugas <br><br><br> <font class="satu">{{$pembelian->kasir}}</font></b></font>

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
