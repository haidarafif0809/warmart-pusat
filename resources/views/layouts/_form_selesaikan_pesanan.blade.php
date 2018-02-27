 {!! Form::model($user, ['url' => route('selesaikan-pemesanan.proses'), 'method' => 'put', 'files'=>'true','class'=>'form-horizontal','id'=>'formSelesaikanPesanan','style'=>'margin-left:10px;margin-right:10px;']) !!}
 <div style="margin-bottom: 1px; margin-top: 1px;" class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
  {!! Form::label('name', 'Nama', ['class'=>'col-md-2 control-label', 'style'=> 'margin-bottom:1px; margin-top:1px;']) !!}
  <div class="col-md-6">
    {!! Form::text('name', null, ['class'=>'form-control','required','autocomplete'=>'off', 'placeholder' => 'Nama', 'id' => 'nama_pelanggan']) !!}
  </div>
</div>

<div style="margin-bottom: 1px; margin-top: 1px;" class="form-group{{ $errors->has('no_telp') ? ' has-error' : '' }}">
  {!! Form::label('no_telp', 'No. Telpon', ['class'=>'col-md-2 control-label', 'style'=> 'margin-bottom:1px; margin-top:1px;']) !!}
  <div class="col-md-6">
    {!! Form::text('no_telp', null, ['class'=>'form-control','required','autocomplete'=>'off', 'placeholder' => 'No. Telpon', 'id' => 'no_telp']) !!}
  </div>
</div>

<div style="margin-bottom: 1px; margin-top: 1px;" class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
  {!! Form::label('email', 'Email', ['class'=>'col-md-2 control-label', 'style'=> 'margin-bottom:1px; margin-top:1px;']) !!}
  <div class="col-md-6">
    {!! Form::text('email', null, ['class'=>'form-control','required','autocomplete'=>'off', 'placeholder' => 'Email', 'id' => 'email']) !!}
  </div>
</div>

<div class="col-md-2 alamat_pengiriman"></div>
@if (Agent::isMobile())
<center><button class="btn alamat_pengiriman" type="button">Masukan Alamat Pengiriman</button></center>
@else
<button class="btn alamat_pengiriman" type="button">Masukan Alamat Pengiriman</button>
@endif

<div id="formAlamat" style="margin-bottom: 1px; margin-top: 1px;" class="form-group{{ $errors->has('alamat') ? ' has-error' : '' }}">
  {!! Form::label('alamat', 'Alamat', ['class'=>'col-md-2 control-label', 'style'=> 'margin-bottom:1px; margin-top:1px;']) !!}
  <div class="col-md-6">
    {!! Form::textarea('alamat', null, ['class'=>'form-control','required','autocomplete'=>'off', 'placeholder' => 'Alamat', 'id' => 'alamatPelanggan', 'rows'=>'5','readonly']) !!}
    <button  style="margin-bottom: 1px; margin-top: 1px;"  class="btn btn-primary btn-simple " type="button" id="ubah_alamat">
      Ubah Alamat
    </button>
  </div>
</div>

<div style="margin-bottom: 1px; margin-top: 1px;" class="form-group{{ $errors->has('kurir') ? ' has-error' : '' }}">
  {!! Form::label('kurir', 'Kurir', ['class'=>'col-md-2 control-label', 'style'=> 'margin-bottom:1px; margin-top:1px;']) !!}
  <div class="col-md-6">
    {!! Form::select('kurir', ['jne'=>'JNE','pos'=>'POS','tiki'=>'TIKI','cod'=>'Bayar di Tempat(COD)','ojek'=>'Ojek'],null, ['required'=> 'true','placeholder' => 'Cari Kurir','id'=>'kurir']) !!}
  </div>
</div>

<div style="margin-bottom: 1px; margin-top: 1px;" class="form-group{{ $errors->has('layanan_kurir') ? ' has-error' : '' }}">
  {!! Form::label('layanan_kurir', 'Layanan Kurir', ['class'=>'col-md-2 control-label', 'style'=> 'margin-bottom:1px; margin-top:1px;']) !!}
  <div class="col-md-6">
    {!! Form::select('layanan_kurir', [''=>''],null, ['required'=> 'true','placeholder' => 'Cari Layanan','id'=>'layanan_kurir']) !!}
  </div>
  <div class="col-md-4">
   @if (Agent::isMobile())
   <font style="font-size:17px; margin-top:10px;margin-bottom: :10px;" id="ongkir"></font>
   @else
   <font style="font-size:25px;" id="ongkir"></font>
   @endif
 </div>
</div>

<div class="col-md-2"></div>
<p id="waktu_pengiriman" style="color: red; font-style: italic;"></p>

<div style="margin-bottom: 1px; margin-top: 1px;" class="form-group{{ $errors->has('metode_pembayaran') ? ' has-error' : '' }}">
  {!! Form::label('metode_pembayaran', 'Pembayaran', ['class'=>'col-md-2 control-label', 'style'=> 'margin-bottom:1px; margin-top:1px;']) !!}
  <div class="col-md-6">
    {!! Form::select('metode_pembayaran', ['Bayar di Tempat'=>'Bayar di Tempat','TRANSFER'=>'TRANSFER'],null, ['required'=> 'true','placeholder' => 'Pilih Metode Pembayaran','id'=>'metode_pembayaran']) !!}
  </div>
</div>

<div class="col-md-2"></div>
<p id="note_pembayaran" style="color: red; font-style: italic;"></p>

<span style="display: none">
  {!! Form::text('session_id',null , ['class'=>'form-control','id'=>'session_id_pelanggan']) !!}
  {!! Form::text('jumlah_produk',$jumlah_produk->total_produk , ['class'=>'form-control']) !!}
  {!! Form::text('kota_pengirim',null, ['class'=>'form-control','id'=>'kota_pengirim']) !!}
  {!! Form::text('ongkos_kirim', 0, ['class'=>'form-control','id'=>'ongkos_kirim','placeholder'=>'ongkos kirim']) !!}
  {!! Form::text('subtotal', $subtotal, ['class'=>'form-control','id'=>'subtotal','placeholder'=>'subtotal']) !!}

  {{-- Data Pelanggan --}}
  {!! Form::text('provinsi_pelanggan',$data_pelanggan['provinsi_pelanggan'], ['class'=>'form-control','id'=>'provinsi_pelanggan']) !!}
  {!! Form::text('kabupaten_pelanggan',$data_pelanggan['kabupaten_pelanggan'], ['class'=>'form-control','id'=>'kabupaten_pelanggan']) !!}
</span>