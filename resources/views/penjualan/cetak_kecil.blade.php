<!DOCTYPE doctype html>
<html lang="en">
<head>
	<title>
		War-Mart.id
	</title>
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
	<p>Pelanggan : {{$penjualan->pelanggan}}</p>
	<p>-------------------------------------------------------------</p>
	<p>No. Transaksi : #{{$penjualan->id}}</p>

	<p>Kasir : {{$penjualan->kasir}}</p>
	<p>-------------------------------------------------------------</p>	

	<table>
		<tbody>
			@foreach ($detail_penjualan as $detail_penjualans)			
			<tr><td width:"50%"> {{title_case($detail_penjualans->produk->nama_barang)}} </td><td style="padding:3px"> {{number_format($detail_penjualans->harga_produk, 2, ',', '.')}}</td><td style="padding:3px">{{number_format($detail_penjualans->jumlah_produk, 2, ',', '.')}}</td><td style="padding:3px">{{number_format($detail_penjualans->subtotal, 2, ',', '.')}}</td></tr>
			@endforeach

		</tbody>
	</table>

	<p>-------------------------------------------------------------</p>
	<p>Subtotal : {{number_format($subtotal, 2, ',', '.')}}</p>
	<p>Diskon : {{number_format($penjualan->potongan, 2, ',', '.')}}</p>
	<p>Total : {{number_format($penjualan->total, 2, ',', '.')}}</p>
	<p>Tunai : {{number_format($penjualan->tunai, 2, ',', '.')}}</p>
	<p>Kembalian : {{number_format($penjualan->kembalian, 2, ',', '.')}}</p>
	<p>======================================</p>
	<p>{{$penjualan->waktu_jual}}</p>

	<p>Terima Kasih </p>
	<p>No. Telp : {{$penjualan->no_telp_warung}}</p>

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
