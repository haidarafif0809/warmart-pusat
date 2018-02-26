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

</style>
<body>

	<p><b>{{$penjualan->nama_warung}}</b></p>
	<p><b>{{$penjualan->alamat_warung}}</b></p>
	<p>======================================</p>
	<p>Pelanggan : {{$nama_pelanggan}}</p>
	<p>-------------------------------------------------------------</p>
	<p>No. Transaksi : #{{$penjualan->id}}</p>

	<p>Kasir : {{$penjualan->kasir}}</p>
	<p>-------------------------------------------------------------</p>	

	<table>
		<tbody>

			@foreach ($detail_penjualan as $detail_penjualans)			
			<tr><td style="padding: 3px;"> {{title_case($detail_penjualans->produk->nama_barang)}} </td><td style="padding:3px" align="right"> {{number_format($detail_penjualans->harga_produk, 0, ',', '.')}}</td><td  align="right" style="padding:3px">{{$detail_penjualans->jumlah_produk}}</td><td  align="right" style="padding:3px">{{number_format($detail_penjualans->harga_produk * $detail_penjualans->jumlah_produk, 0, ',', '.')}}</td></tr>
			@endforeach
			<tr><td style="padding: 3px;"> Subtotal </td><td style="padding:3px" align="right">:</td><td  align="right" style="padding:3px"></td><td  align="right" style="padding:3px">{{number_format($subtotal, 0, ',', '.')}}</td></tr>
			<tr><td style="padding: 3px;"> Diskon </td><td style="padding:3px" align="right">:</td><td  align="right" style="padding:3px"></td><td  align="right" style="padding:3px">{{number_format($potongan, 0, ',', '.')}}</td></tr>
			<tr><td style="padding: 3px;"> Total </td><td style="padding:3px" align="right">:</td><td  align="right" style="padding:3px"></td><td  align="right" style="padding:3px">{{number_format($penjualan->total, 0, ',', '.')}}</td></tr>
			<tr><td style="padding: 3px;"> Tunai </td><td style="padding:3px" align="right">:</td><td  align="right" style="padding:3px"></td><td  align="right" style="padding:3px">{{number_format($penjualan->tunai, 0, ',', '.')}}</td></tr>
			<tr><td style="padding: 3px;"> Kembalian </td><td style="padding:3px" align="right">:</td><td  align="right" style="padding:3px"></td><td  align="right" style="padding:3px">{{number_format($penjualan->kembalian, 0, ',', '.')}}</td></tr>
		</tbody>
	</table>
	
	<p>======================================</p>
	<p>{{$penjualan->waktu_jual}}</p>

	<p>Terima Kasih </p>
	<p>No. Telp : {{$penjualan->no_telp_warung}}</p>	
	<p><i>Support by andaglos.id</i></p><br><br>

</body>
<!--   Core JS Files   -->
<script src="{{ asset('js/app.js?v=1.51')}}" type="text/javascript"></script>

<script>
	$(document).ready(function(){
		window.print();
		window.close();
	});
</script>
@yield('scripts')
</html>
