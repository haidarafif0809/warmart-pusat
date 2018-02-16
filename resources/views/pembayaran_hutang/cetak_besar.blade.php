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
		<div class="row"><!--row1-->
			<div class="col-sm-2"></div><!--penutup colsm2-->
			<div class="col-sm-8">
				<center> <h4> <b> {{$pembayaran_hutang->nama_warung}} </b> </h4> 
					<p> {{$pembayaran_hutang->alamat_warung}}<br>
						No.Telp : {{$pembayaran_hutang->no_telp_warung}} </p> </center>

					</div><!--penutup colsm5-->
				</div><!--penutup row1-->


				<div class="row">
					<div class="col-sm-8">				
						<table>
							<tbody>
								<tr><td width="25%"><font class="satu">No Transaksi</font></td> <td> :</td> <td><font class="satu">{{$pembayaran_hutang->no_faktur}}</font> </tr>
								<tr><td width="25%"><font class="satu">Keterangan</font></td> <td> :</td> <td><font class="satu">{{$pembayaran_hutang->keterangan}}</font> </tr>
							</tbody>
						</table>
					</div>

					<div class="col-sm-4">
						<table>
							<tbody>
								<tr><td width="25%"><font class="satu"> Waktu</td> <td> :&nbsp;&nbsp;</td> <td>{{$pembayaran_hutang->waktu_beli}}</font> </td></tr> 
								<tr><td width="25%"><font class="satu"> Kasir</td> <td> :&nbsp;&nbsp;</td> <td>{{$pembayaran_hutang->name}}</font></td></tr> 

							</tbody>
						</table>

					</div> <!--end col-sm-2-->
				</div> <!--end row-->  
				<br>
				<table class="table table-bordered">
					<thead>
						<th>Faktur Beli</th>
						<th> Supplier </th>
						<th >Jatuh Tempo</th>
						<th  style="text-align:right;">Hutang</th>
						<th  style="text-align:right;">Potongan</th>
						<th  style="text-align:right;">Subtotal</th>
						<th  style="text-align:right;">Pembayaran</th>
						<th style="text-align:right;">Sisa Hutang</th>
					</thead>
					<tbody>
						@foreach ($detail_pembayaran_hutang as $detail_pembayaran_hutangs)	
						<tr>
							<td>{{ $detail_pembayaran_hutangs->no_faktur_pembelian }}</td>
							<td>{{ $detail_pembayaran_hutangs->nama_suplier }}</td>
							<td >{{ $detail_pembayaran_hutangs->jatuh_tempo }}</td>
							<td align="right">{{ number_format($detail_pembayaran_hutangs->hutang, 2, ',', '.') }}</td>
							<td align="right">{{ number_format($detail_pembayaran_hutangs->potongan, 2, ',', '.') }}</td>
							<td align="right">{{ number_format($detail_pembayaran_hutangs->total, 2, ',', '.') }}</td>
							<td align="right">{{ number_format($detail_pembayaran_hutangs->jumlah_bayar, 2, ',', '.') }}</td>
							<td align="right">{{ number_format($detail_pembayaran_hutangs->sisa_hutang, 2, ',', '.') }}</td>
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

								<tr><td  width="40%"><font class="satu">Kas</font></td> <td> :&nbsp;</td> <td class="text-right"><font class="satu">{{$pembayaran_hutang->nama_kas}}</font> </td></tr>

							</tbody>
						</table>

					</div>

					<div class="col-sm-2">

						<table>
							<tbody>
								<tr><td  width="50%"><font class="satu">Total Akhir</font></td> <td> :&nbsp;</td> <td class="text-right"><font class="satu"> {{number_format($pembayaran_hutang->total, 2, ',', '.')}}</font>  </td></tr>
							</tbody>
						</table>


					</div><!--penutup colsm5-->
				</div><!--penutup row1-->
				<br>
				<div class="row">
					<div class="col-sm-9">
					</div> <!--/ col-sm-6-->

					<div class="col-sm-3">

						<font class="satu"><b>Petugas <br><br><br> <font class="satu">{{$pembayaran_hutang->kasir}}</font></b></font>

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
