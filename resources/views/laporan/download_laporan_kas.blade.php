<html>
<?php
function tanggal($tanggal)
{
	return date_format($tanggal, "d-m-Y H:i:s");
}
?>

<center>
	<p style="font-weight: bold">
		@if($jenis_laporan == 0)
		LAPORAN KAS DETAIL PERPERIODE
		@else
		LAPORAN KAS REKAP PERPERIODE
		@endif
		<br>
	</p>

	<p style="font-weight: bold">
		{{ strtoupper($data_warung->name) }}
	</p>
</center>

@if($jenis_laporan == 0){{-- DOWNLOAD DETAIL --}}

<p style="font-weight: bold">KAS MASUK : Rp. {{$subtotal_kas_masuk}}</p>
<table class="table table-bordered">
	<thead>
		<tr>		
			<th>No. Transaksi</th>
			<th>Jenis Transaksi</th>
			<th>Ke Kas</th>
			<th>Total</th>
			<th>Waktu</th>
		</tr><br>
	</thead>
	<tbody>
		@foreach ($data_laporan_kas as $data_laporan_kass)
		<tr>
			<td>{{ $data_laporan_kass['data_laporan']->no_faktur }}</td>
			<td>{{ $data_laporan_kass['jenis_transaksi'] }}</td>
			<td>{{ $data_laporan_kass['data_laporan']->nama_kas }}</td>
			<td>{{ $data_laporan_kass['data_laporan']->jumlah_masuk }}</td>
			<td>{{ tanggal($data_laporan_kass['data_laporan']->created_at) }}</td>
		</tr>
		@endforeach
	</tbody>
</table>

<p style="font-weight: bold">KAS KELUAR : Rp. {{$subtotal_kas_keluar}}</p>
<table class="table table-bordered">
	<thead>
		<tr>		
			<th>No. Transaksi</th>
			<th>Jenis Transaksi</th>
			<th>Ke Kas</th>
			<th>Total</th>
			<th>Waktu</th>
		</tr><br>
	</thead>
	<tbody>
		@foreach ($data_laporan_kas_keluar as $data_laporan_kas_keluars)
		<tr>
			<td>{{ $data_laporan_kas_keluars['data_laporan']->no_faktur }}</td>
			<td>{{ $data_laporan_kas_keluars['jenis_transaksi'] }}</td>
			<td>{{ $data_laporan_kas_keluars['data_laporan']->nama_kas }}</td>
			<td>{{ $data_laporan_kas_keluars['data_laporan']->jumlah_keluar }}</td>
			<td>{{ tanggal($data_laporan_kas_keluars['data_laporan']->created_at) }}</td>
		</tr>
		@endforeach
	</tbody>
</table>

<p style="font-weight: bold">KAS MUTASI (MASUK) : Rp. {{$subtotal_kas_mutasi_masuk}}</p>
<table class="table table-bordered">
	<thead>
		<tr>		
			<th>No. Transaksi</th>
			<th>Jenis Transaksi</th>
			<th>Ke Kas</th>
			<th>Total</th>
			<th>Waktu</th>
		</tr><br>
	</thead>
	<tbody>
		@foreach ($data_laporan_kas_mutasi_masuk as $data_laporan_kas_mutasi_masuks)
		<tr>
			<td>{{ $data_laporan_kas_mutasi_masuks['data_laporan']->no_faktur }}</td>
			<td>{{ $data_laporan_kas_mutasi_masuks['jenis_transaksi'] }}</td>
			<td>{{ $data_laporan_kas_mutasi_masuks['data_laporan']->nama_kas }}</td>
			<td>{{ $data_laporan_kas_mutasi_masuks['data_laporan']->jumlah_masuk }}</td>
			<td>{{ tanggal($data_laporan_kas_mutasi_masuks['data_laporan']->created_at) }}</td>
		</tr>
		@endforeach
	</tbody>
</table>

<p style="font-weight: bold">KAS MUTASI (KELUAR) : Rp. {{$subtotal_kas_mutasi_keluar}}</p>
<table class="table table-bordered">
	<thead>
		<tr>		
			<th>No. Transaksi</th>
			<th>Jenis Transaksi</th>
			<th>Ke Kas</th>
			<th>Total</th>
			<th>Waktu</th>
		</tr><br>
	</thead>
	<tbody>
		@foreach ($data_laporan_kas_mutasi_keluar as $data_laporan_kas_mutasi_keluars)
		<tr>
			<td>{{ $data_laporan_kas_mutasi_keluars['data_laporan']->no_faktur }}</td>
			<td>{{ $data_laporan_kas_mutasi_keluars['jenis_transaksi'] }}</td>
			<td>{{ $data_laporan_kas_mutasi_keluars['data_laporan']->nama_kas }}</td>
			<td>{{ $data_laporan_kas_mutasi_keluars['data_laporan']->jumlah_keluar }}</td>
			<td>{{ tanggal($data_laporan_kas_mutasi_keluars['data_laporan']->created_at) }}</td>
		</tr>
		@endforeach
	</tbody>
</table>

@else {{-- DOWNLOAD REKAP --}}

<p style="font-weight: bold">KAS MASUK : Rp. {{$subtotal_kas_masuk}}</p>
<table class="table table-bordered">
	<thead>
		<tr>		
			<th>Waktu</th>
			<th>Ke Kas</th>
			<th>Total</th>
		</tr><br>
	</thead>
	<tbody>
		@foreach ($data_laporan_kas as $data_laporan_kass)
		<tr>
			<td>{{ tanggal($data_laporan_kass['data_laporan']->created_at) }}</td>
			<td>{{ $data_laporan_kass['data_laporan']->nama_kas }}</td>
			<td>{{ $data_laporan_kass['data_laporan']->jumlah_masuk }}</td>
		</tr>
		@endforeach
	</tbody>
</table>

<p style="font-weight: bold">KAS KELUAR : Rp. {{$subtotal_kas_keluar}}</p>
<table class="table table-bordered">
	<thead>
		<tr>		
			<th>Waktu</th>
			<th>Ke Kas</th>
			<th>Total</th>
		</tr><br>
	</thead>
	<tbody>
		@foreach ($data_laporan_kas_keluar as $data_laporan_kas_keluars)
		<tr>
			<td>{{ tanggal($data_laporan_kas_keluars['data_laporan']->created_at) }}</td>
			<td>{{ $data_laporan_kas_keluars['data_laporan']->nama_kas }}</td>
			<td>{{ $data_laporan_kas_keluars['data_laporan']->jumlah_keluar }}</td>
		</tr>
		@endforeach
	</tbody>
</table>

<p style="font-weight: bold">KAS MUTASI (MASUK) : Rp. {{$subtotal_kas_mutasi_masuk}}</p>
<table class="table table-bordered">
	<thead>
		<tr>		
			<th>Waktu</th>
			<th>Ke Kas</th>
			<th>Total</th>
		</tr><br>
	</thead>
	<tbody>
		@foreach ($data_laporan_kas_mutasi_masuk as $data_laporan_kas_mutasi_masuks)
		<tr>
			<td>{{ tanggal($data_laporan_kas_mutasi_masuks['data_laporan']->created_at) }}</td>
			<td>{{ $data_laporan_kas_mutasi_masuks['data_laporan']->nama_kas }}</td>
			<td>{{ $data_laporan_kas_mutasi_masuks['data_laporan']->jumlah_masuk }}</td>
		</tr>
		@endforeach
	</tbody>
</table>

<p style="font-weight: bold">KAS MUTASI (KELUAR) : Rp. {{$subtotal_kas_mutasi_keluar}}</p>
<table class="table table-bordered">
	<thead>
		<tr>		
			<th>Waktu</th>
			<th>Ke Kas</th>
			<th>Total</th>
		</tr><br>
	</thead>
	<tbody>
		@foreach ($data_laporan_kas_mutasi_keluar as $data_laporan_kas_mutasi_keluars)
		<tr>
			<td>{{ tanggal($data_laporan_kas_mutasi_keluars['data_laporan']->created_at) }}</td>
			<td>{{ $data_laporan_kas_mutasi_keluars['data_laporan']->nama_kas }}</td>
			<td>{{ $data_laporan_kas_mutasi_keluars['data_laporan']->jumlah_keluar }}</td>
		</tr>
		@endforeach
	</tbody>
</table>

@endif

<table class="table table-bordered">
	<tbody>
		<tr style="font-weight: bold">
			<td>Total Awal Kas</td>
			<td>{{ $total_kas['total_awal'] }}</td>
		</tr>
		<tr style="font-weight: bold">
			<td>Perubahan Kas</td>
			<td>{{ $total_kas['perubahan_kas'] }}</td>
		</tr>
		<tr style="font-weight: bold">
			<td>Total Akhir Kas</td>
			<td>{{ $total_kas['total_akhir'] }}</td>
		</tr>
	</tbody>
</table>

</html>
