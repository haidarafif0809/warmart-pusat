 {!! Form::model($user, ['url' => route('selesaikan-pemesanan.proses'), 'method' => 'put', 'files'=>'true','class'=>'form-horizontal','id'=>'formSelesaikanPesanan','style'=>'margin-left:10px;margin-right:10px;']) !!}
 <div style="margin-bottom: 1px; margin-top: 1px;" class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
  {!! Form::label('name', 'Nama', ['class'=>'col-md-2 control-label', 'style'=> 'margin-bottom:1px; margin-top:1px;']) !!}
  <div class="col-md-6">
    {!! Form::text('name', null, ['class'=>'form-control','required','autocomplete'=>'off', 'placeholder' => 'Nama', 'id' => 'nama_pelanggan']) !!}
    {!! $errors->first('name', '<p class="help-block" id="name_error">:message</p>') !!}
  </div>
</div>

<div style="margin-bottom: 1px; margin-top: 1px;" class="form-group{{ $errors->has('no_telp') ? ' has-error' : '' }}">
  {!! Form::label('no_telp', 'No. Telpon', ['class'=>'col-md-2 control-label', 'style'=> 'margin-bottom:1px; margin-top:1px;']) !!}
  <div class="col-md-6">
    {!! Form::text('no_telp', null, ['class'=>'form-control','required','autocomplete'=>'off', 'placeholder' => 'No. Telpon', 'id' => 'no_telp']) !!}
    {!! $errors->first('no_telp', '<p class="help-block" id="no_telp_error">:message</p>') !!}
  </div>
</div>

@if (Auth::check() == false)
<div style="margin-bottom: 1px; margin-top: 1px;" class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
  {!! Form::label('password', 'Password', ['class'=>'col-md-2 control-label', 'style'=> 'margin-bottom:1px; margin-top:1px;']) !!}
  <div class="col-md-6">
    <input class="form-control" name="password" placeholder="Password" type="password" required="" id="password">
    {!! $errors->first('password', '<p class="help-block" id="password_error">:message</p>') !!}
  </div>
</div>
@endif

<div style="margin-bottom: 1px; margin-top: 1px;" class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
  {!! Form::label('email', 'Email', ['class'=>'col-md-2 control-label', 'style'=> 'margin-bottom:1px; margin-top:1px;']) !!}
  <div class="col-md-6">
    {!! Form::text('email', null, ['class'=>'form-control','required','autocomplete'=>'off', 'placeholder' => 'Email', 'id' => 'email']) !!}
    {!! $errors->first('email', '<br><b class="help-block" id="email_error">:message</b>') !!}
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
    {!! $errors->first('alamat', '<p class="help-block" id="alamat_error">:message</p>') !!}
    <button  style="margin-bottom: 1px; margin-top: 1px;"  class="btn buttonColor" type="button" id="ubah_alamat">
      Ubah Alamat
    </button>
  </div>
</div>

<div style="margin-bottom: 1px; margin-top: 1px;" class="form-group{{ $errors->has('kurir') ? ' has-error' : '' }}">
  {!! Form::label('kurir', 'Kurir', ['class'=>'col-md-2 control-label', 'style'=> 'margin-bottom:1px; margin-top:1px;']) !!}
  <div class="col-md-6 hurufBesar">
    {!! Form::select('kurir', $kurir, null,['required'=> 'true','placeholder' => 'Cari Kurir','id'=>'kurir']) !!}
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
    {!! Form::select('metode_pembayaran', [''=>''],null, ['required'=> 'true','placeholder' => 'Pilih Metode Pembayaran','id'=>'metode_pembayaran']) !!}
  </div>
</div>

<div style="margin-bottom: 1px; margin-top: 1px; display: none" class="form-group{{ $errors->has('bank') ? ' has-error' : '' }}" id="bankForm">
  {!! Form::label('bank', 'Bank Transfer', ['class'=>'col-md-2 control-label', 'style'=> 'margin-bottom:1px; margin-top:1px;']) !!}
  <div class="col-md-6 hurufBesar">
    {!! Form::select('bank', $bank, null,['required'=> 'true','placeholder' => 'Cari Bank','id'=>'bank']) !!}
  </div>
</div>

<div class="col-md-2"></div>
<p id="note_pembayaran" style="color: red; font-style: italic;"></p>

<span style="display: none">
  {!! Form::text('jumlah_produk',$jumlah_produk->total_produk , ['class'=>'form-control']) !!}
  {!! Form::text('kota_pengirim',null, ['class'=>'form-control','id'=>'kota_pengirim']) !!}
  {!! Form::text('ongkos_kirim', 0, ['class'=>'form-control','id'=>'ongkos_kirim','placeholder'=>'ongkos kirim']) !!}
  {!! Form::text('subtotal', $subtotal, ['class'=>'form-control','id'=>'subtotal','placeholder'=>'subtotal']) !!}

  {{-- Data Pelanggan --}}
  {!! Form::text('provinsi_pelanggan',$data_pelanggan['provinsi_pelanggan'], ['class'=>'form-control','id'=>'provinsi_pelanggan']) !!}
  {!! Form::text('kabupaten_pelanggan',$data_pelanggan['kabupaten_pelanggan'], ['class'=>'form-control','id'=>'kabupaten_pelanggan']) !!}
</span>