<?php
$settingFooter = \App\SettingFooter::select()->
first();
?>
@extends('layouts.app_pelanggan')
@section('content')
<style type="text/css">
.page-header.header-small {
  height: 35vh;
  min-height: 35vh;
}
.ecommerce-page .page-header .container {
  padding-top: 10vh;
}
h4 {
  @if(Agent::isMobile())
  font-size: 1.2em;
  line-height: 1.4em;
  margin: 20px 0 10px;
  @endif
}
.panel .panel-heading {
  background-color: transparent;
  border-bottom: 2px solid #ddd;
  padding: 5px 0px 5px 0px;
}
.buttonColor{
  background-color: #2ac326  
}
.validationProvinsi{
  display: none;
  color: red;
}
.validationKota{
  display: none;
  color: red;
}
.validationAlamat{
  display: none;
  color: red;
}
#formAlamat{
  display: none;
}
.modal {
  overflow-y:auto;
}
</style>

<?php
$setting_aplikasi = \App\SettingAplikasi::select('tipe_aplikasi')->first();
?>

@if($setting_aplikasi->tipe_aplikasi == 0)
<div class="page-header header-filter header-small" data-parallax="true" style="background-image: url('image/background2.jpg');">
  @else
  <div class="page-header header-small buttonColor" data-parallax="true">
    @endif

    @if (Agent::isMobile())
    <div class="container">
      <div class="row">
        <div class="col-md-8 col-md-offset-2">
          <div class="brand">

            @if($setting_aplikasi->tipe_aplikasi == 0)
            <h3 class="title"> PASAR MUSLIM INDONESIA </h1>
              <h4 class="title"> Segala Kemudahan Untuk Umat Muslim Berbelanja. </h4>
              @else
              <h3 class="title"> <?=$settingFooter->judul_warung;?> </h1>
                @endif

              </div>
            </div>
          </div>
        </div>
        @else
        <div class="container">
          <div class="row">
            <div class="col-md-8 col-md-offset-2">
              <div class="brand">

                @if($setting_aplikasi->tipe_aplikasi == 0)
                <h1 class="title"> PASAR MUSLIM INDONESIA </h3>
                  <h4 class="title"> Segala Kemudahan Untuk Umat Muslim Berbelanja. </h4>
                  @else
                  <h1 class="title"><?=$settingFooter->judul_warung;?></h3>
                    @endif

                  </div>
                </div>
              </div>
            </div>
            @endif
          </div>

          <div class="main main-raised">

            <div class="container">


              <!-- Classic Modal -->
              <div class="modal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close closeModal" data-dismiss="modal" aria-hidden="true">
                        <i class="material-icons">clear</i>
                      </button>
                      <h4 class="modal-title">Alamat Pengiriman</h4>
                    </div>
                    <div class="modal-body">
                      <p>
                       <div class="form-group">
                        <font class="validationProvinsi">Provinsi Harus di Isi</font>
                        {!! Form::select('provinsi', [''=>''],null, ['required'=> 'true','placeholder' => 'Cari Provinsi','id'=>'provinsi']) !!} 
                      </div>

                      <div class="form-group">
                        <font class="validationKota">Kota Harus di Isi</font>
                        {!! Form::select('kota', [''=>''],null, ['required'=> 'true','placeholder' => 'Cari Kota/Kab.','id'=>'kota']) !!}
                      </div>

                      <div class="form-group">
                        <font class="validationAlamat">Alamat Harus di Isi</font>
                        {!! Form::textarea('alamat', null, ['class'=>'form-control field','required','autocomplete'=>'off', 'placeholder' => 'Alamat Lengkap', 'id' => 'alamat', 'rows'=>'3']) !!}
                      </div>

                    </p>
                  </div>
                  <div class="modal-footer">
                    {!! Form::button('Simpan', ['id'=>'selesaiAlamatPengiriman','class'=>'btn buttonColor', 'type'=>'submit']) !!}
                    <button type="button" class="btn btn-danger btn-simple closeModal" data-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>
            <!--  End Modal -->

            <ul class="breadcrumb" style="margin-top: 10px; margin-bottom: 10px;">
              <li><a href="{{ url('/daftar-produk') }}"><b>Home</b></a></li>
              <li><a href="{{ url('/keranjang-belanja') }}"><b>Keranjang Belanja</b></a></li>
              <li class="active"><b>Pesanan</b></li>
            </ul>
            <div class="card-content">
              <h3 class="title text-center">Selesaikan Pemesanan</h3>
              <div class="row">
                @if($cek_belanjaan == 0)
                <div class="card">
                  <div class="col-md-12">
                    <center>
                      <h3>Keranjang Belanjaan Anda Kosong,Silahkan Berbelanja.</h3>
                      <a  href="{{ url('/daftar-produk') }}" type="button" class="btn btn-block buttonColor">Lanjut Belanja<i class="material-icons">keyboard_arrow_right</i></a>
                    </center>
                  </div>
                </div>
                @else
                <div class="col-md-7">
                  <div class="card" style="margin-bottom: 5px; margin-top: 5px;">
                    <div class="card-header" style="margin-bottom: 1px; margin-top: 1px;">
                      <h6 class="card-title" style="color: black; padding-left: 10px ;" > Alamat Pengiriman</h6> <hr>
                    </div>

                    {!! Form::model($user, ['url' => route('selesaikan-pemesanan.proses'), 'method' => 'put', 'files'=>'true','class'=>'form-horizontal','id'=>'formSelesaikanPesanan']) !!}
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

                    <div class="col-md-2 alamat_pengiriman"></div>
                    <button class="btn alamat_pengiriman" type="button">Masukan Alamat Pengiriman</button>

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
                        {!! Form::select('kurir', ['jne'=>'JNE','pos'=>'POS','tiki'=>'TIKI','cod'=>'Bayar di Tempat(COD)'],null, ['required'=> 'true','placeholder' => 'Cari Kurir','id'=>'kurir']) !!}
                      </div>
                    </div>

                    <div style="margin-bottom: 1px; margin-top: 1px;" class="form-group{{ $errors->has('layanan_kurir') ? ' has-error' : '' }}">
                      {!! Form::label('layanan_kurir', 'Layanan Kurir', ['class'=>'col-md-2 control-label', 'style'=> 'margin-bottom:1px; margin-top:1px;']) !!}
                      <div class="col-md-6">
                        {!! Form::select('layanan_kurir', [''=>''],null, ['required'=> 'true','placeholder' => 'Cari Layanan','id'=>'layanan_kurir']) !!}
                      </div>
                      <div class="col-md-4">
                        <font style="font-size:25px;" id="ongkir"></font>
                      </div>
                    </div>

                    <div class="col-md-2"></div>
                    <p id="waktu_pengiriman" style="color: red; font-style: italic;"></p>

                    <div style="margin-bottom: 1px; margin-top: 1px;" class="form-group{{ $errors->has('metode_pembayaran') ? ' has-error' : '' }}">
                      {!! Form::label('metode_pembayaran', 'Pembayaran', ['class'=>'col-md-2 control-label', 'style'=> 'margin-bottom:1px; margin-top:1px;']) !!}
                      <div class="col-md-6">
                        {!! Form::text('metode_pembayaran', null, ['class'=>'form-control','required','autocomplete'=>'off', 'placeholder' => 'Metode Pembayaran', 'id' => 'metode_pembayaran','readonly']) !!}
                      </div>
                    </div>

                    <div class="col-md-2"></div>
                    <p id="note_pembayaran" style="color: red; font-style: italic;"></p>

                    <span style="display: none">
                      {!! Form::text('jumlah_produk',$jumlah_produk->total_produk , ['class'=>'form-control']) !!}
                      {!! Form::text('kota_pengirim',null, ['class'=>'form-control','id'=>'kota_pengirim']) !!}
                      {!! Form::text('subtotal', $subtotal, ['class'=>'form-control','id'=>'subtotal']) !!}
                      {!! Form::text('ongkos_kirim', 0, ['class'=>'form-control','id'=>'ongkos_kirim']) !!}
                    </span>

                  </div>

                </div>

                <div class="col-md-5">
                  @if ($agent->isMobile()) <!--JIKA DAKSES VIA HP/TAB-->

                  <div class="card" style="margin-top: 1px; margin-bottom: 1px; margin-left: 1px;">

                    @foreach($keranjang_belanjaan as $keranjang_belanjaans)
                    <div class="row" style="margin-bottom: 1px;">
                      <div class="col-md-12">

                        <div class="row">
                          <div class="col-xs-4">
                            <div class="img-container" style="margin-bottom:5px;margin-top: 5px; margin-left: 5px; margin-right: 5px;">
                              @if($keranjang_belanjaans->produk->foto != NULL)
                              <img src="foto_produk/{{$keranjang_belanjaans->produk->foto}}">
                              @else
                              <img src="image/foto_default.png">
                              @endif
                            </div>

                          </div>

                          <div class="col-xs-8">
                            <p style="margin-bottom:1px;margin-top: 1px;"><a href="#"><b>{{$keranjang_belanjaans->NamaProdukMobile}}</b></a></p>
                            <div class="responsive">
                              <table>
                                <tbody>
                                  <tr style="font-weight: bold">
                                    <td width="25%"> {{number_format($keranjang_belanjaans->produk->harga_jual,0,',','.') }} </td>
                                    <td>&nbsp; x </td>
                                    <td> {{$keranjang_belanjaans->jumlah_produk }} </td>
                                    <td> {{number_format($keranjang_belanjaans->produk->harga_jual * $keranjang_belanjaans->jumlah_produk,0,',','.') }} </td>
                                  </tr>
                                </tbody>
                              </table>
                            </div>
                            <p style="margin-bottom:1px;margin-top: 1px;"><small>{{$keranjang_belanjaans->produk->warung->name}}</small></p>

                          </div>

                        </div>

                      </div>
                    </div><hr style="margin-bottom: 1px; margin-top: 1px;">

                    @endforeach

                  </div>

                  {{$pagination}}
                  <div class="card" style="margin-bottom: 1px; margin-top: 1px;">
                    <div class="row">
                      <div class="col-xs-8">
                        <p style="margin-left: 5px; margin-top: 5px; margin-bottom: 5px;"><b> Total Produk</b></p>
                      </div>
                      <div class="col-xs-6">
                        <p style="margin-left: 5px; margin-top: 5px; margin-bottom: 5px;"><b> {{ $jumlah_produk->total_produk }}</b></p>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-xs-4">
                        <p style="margin-left: 5px; margin-top: 5px; margin-bottom: 5px;"><b>Total</b></p>
                      </div>
                      <div class="col-xs-2">

                      </div>
                      <div class="col-xs-6">
                        <p style="margin-left: 5px; margin-top: 5px; margin-bottom: 5px;"><b>Rp. {{ number_format($subtotal,0,',','.') }}</b></p>
                      </div>
                    </div>
                  </div>

                  @if($setting_aplikasi->tipe_aplikasi == 0)
                  <center>{!! Form::button('Selesai Pesanan <i class="material-icons">keyboard_arrow_right</i> ', ['id'=>'SelesaikanPesanan','class'=>'btn btn-round', 'type'=>'submit', 'style'=>'background-color: #01573e']) !!}</center>
                  @else
                  <center>{!! Form::button('Selesai Pesanan <i class="material-icons">keyboard_arrow_right</i> ', ['id'=>'SelesaikanPesanan','class'=>'btn btn-round buttonColor', 'type'=>'submit']) !!}</center>
                  @endif          

                  @else

                  <div class="card" style="margin-bottom: 5px; margin-top: 5px;">
                    <div class="card-header" style="padding-bottom: 1px;">
                      <h6 class="card-title" style="color: black; padding-left: 10px; margin-bottom: 1px;">Rincian Pesanan</h6> <hr>
                    </div>

                    <div class="card-content" style="padding-top: 1px; padding-bottom: 1px;">
                      <div class="row">
                        <div class="col-md-4"><h5><b>Produk</b></h5> </div>
                        <div class="col-md-2"><h5><b>Jumlah</b></h5> </div>
                        <div class="col-md-3"><h5><b>Harga</b></h5> </div>
                        <div class="col-md-3"><h5><b>Subtotal</b></h5> </div>
                      </div>

                      @foreach($keranjang_belanjaan as $keranjang_belanjaans)
                      <div class="row">

                        <div class="col-md-4">
                          <li><b> <a href="{{ url('detail-produk/'.$keranjang_belanjaans->id_produk.'') }}">{{ $keranjang_belanjaans->NamaProduk }}</a></b></li>
                        </div>

                        <div class="col-md-2">
                          <b align="right">{{ $keranjang_belanjaans->jumlah_produk }}</b>
                        </div>

                        <div class="col-md-3">
                          <b align="right">
                            {{ number_format($keranjang_belanjaans->produk->harga_jual,0,',','.') }}</b>
                          </div>

                          <div class="col-md-3">

                            <div class="row">

                              <div class="col-md-8">
                                <b>{{ number_format($keranjang_belanjaans->produk->harga_jual * $keranjang_belanjaans->jumlah_produk,0,',','.') }}</b>
                              </div>

                              <div class="col-md-4">
                                <a href="#" id="btnHapusProduk" data-nama="{{title_case($keranjang_belanjaans->produk->nama_barang)}}" data-id="{{$keranjang_belanjaans->id_keranjang_belanja}}" type="button"><i class="material-icons">close</i></a>
                              </div>

                            </div>

                          </div>

                        </div>
                        @endforeach
                        {{$pagination}}

                      </div>
                      <hr style="margin-top: 1px;">
                      <div class="card-content" style="margin-top: 1px;">

                        <div class="row">
                          <div class="col-md-4"><b>Total Produk</b></div>
                          <div class="col-md-3"><b>:</b></div>
                          <div class="col-md-5"><b class="text-right">{{ $jumlah_produk->total_produk }}</b></div>
                        </div>

                        <div class="row">
                          <div class="col-md-4"><b>Subtotal</b></div>
                          <div class="col-md-3"><b>:</b></div>
                          <div class="col-md-5"><b class="text-right">Rp. {{ number_format($subtotal,0,',','.') }} </b></div>
                        </div>

                        <div class="row">
                          <div class="col-md-4"><b>Biaya Kirim</b></div>
                          <div class="col-md-3"><b>:</b></div>
                          <div class="col-md-5"><b class="text-right" id="biaya_kirim">0</b></div>
                        </div>

                        <div class="row">
                          <div class="col-md-4"><h5><b>Total Belanja</b></h5></div>
                          <div class="col-md-3"><h5><b>:</h5></div>
                            <div class="col-md-5"><h5 class="text-danger"><b class="text-right" id="total_belanja" data-total="{{$subtotal}}">Rp. {{ number_format($subtotal,0,',','.') }}</b></h5></div>
                          </div>
                        </div>

                      </div>

                      @if($setting_aplikasi->tipe_aplikasi == 0)
                      {!! Form::button('Selesai Pesanan <i class="material-icons">keyboard_arrow_right</i> ', ['id'=>'SelesaikanPesanan','class'=>'btn btn-round pull-right', 'type'=>'button', 'style'=>'background-color: #01573e']) !!}
                      @else
                      {!! Form::button('Selesai Pesanan <i class="material-icons">keyboard_arrow_right</i> ', ['id'=>'SelesaikanPesanan','class'=>'btn btn-round pull-right buttonColor', 'type'=>'button']) !!}
                      @endif 

                      @endif

                      {!! Form::close() !!}
                    </div>
                    @endif
                  </div>
                </div>
              </div>
            </div>
          </div> <!-- end-main-raised -->
          @endsection


          @section('scripts')
          <script type="text/javascript">
            $(document).ready(function(){

              var $select = $('#provinsi').selectize({
                valueField: 'province_id',
                labelField: 'province',
                searchField: 'province'
              });

              var $selectKota = $('#kota').selectize({
                valueField: 'city_id',
                labelField: 'city_name',
                searchField: 'city_name',
                create: false
              });

              var $selectKurir = $('#kurir').selectize({
                sortField: 'text'
              });

              var $selectLayananKurir = $('#layanan_kurir').selectize({
               valueField: 'id',
               labelField: 'service',
               searchField: 'service',
               create: false              

             });

              Number.prototype.format = function(n, x, s, c) {
                var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\D' : '$') + ')',
                num = this.toFixed(Math.max(0, ~~n));

                return (c ? num.replace('.', c) : num).replace(new RegExp(re, 'g'), '$&' + (s || ','));
              };


              $.getJSON('{{ Url("provinsi-destinasi-pengiriman") }}',{'_token': $('meta[name=csrf-token]').attr('content')}, function(resp){ 
                $select[0].selectize.addOption(resp.rajaongkir.results);   
                var kabupaten = "{{$kabupaten}}";
                var kota_pengirim = $("#kota_pengirim").val();

                if (kota_pengirim == '') {

                 $.each(resp.rajaongkir.results, function (i, item) { 

                  if (kabupaten == resp.rajaongkir.results[i].type+" "+resp.rajaongkir.results[i].city_name) {
                    var city_id = resp.rajaongkir.results[i].city_id;
                    $("#kota_pengirim").val(city_id);

                  }

                }); 

               }          
             });

              $selectKurir.on('change', function(){

                var kurir = $(this).val();
                var kota_pengirim = $("#kota_pengirim").val();
                var kota_tujuan = $("#kota").val();
                var provinsi = $("#provinsi").val();
                var alamat = $("#alamat").val();
                var berat_barang = "{{$berat_barang}}";

                if (kota_tujuan == '') {
                  alertAlamatBelumLengkap();
                }else if (provinsi == '') {
                  alertAlamatBelumLengkap();
                }else if (alamat == '') {
                  alertAlamatBelumLengkap();
                }
                else{
                  if (kurir != 'cod') {                        
                    hitungOngkir(kurir,kota_pengirim,kota_tujuan,berat_barang);
                  }else{
                    pengirimanCod();
                  }
                }

              });

              function pengirimanCod(){    
                var subtotal = parseInt($("#total_belanja").attr("data-total"));                
                $("#subtotal").val(subtotal);
                var subtotal = subtotal.format(0, 3, '.', ',');
                $("#ongkos_kirim").val("0");
                $("#biaya_kirim").text("0");
                $("#ongkir").text("");
                $("#waktu_pengiriman").text(""); 
                document.getElementById('layanan_kurir').selectize.setValue("");   
                $selectLayananKurir[0].selectize.clearOptions(); 
                $("#total_belanja").text("Rp. "+subtotal);
                $("#metode_pembayaran").val("Bayar di Tempat");
                $("#note_pembayaran").text("Anda dapat melakukan pembayaran saat Anda menerima pesanan.");
              }

              function alertAlamatBelumLengkap(){
               swal({
                text: 'Lengkapi alamat terlebih dahulu untuk memilih jasa pengiriman!'
              });
               document.getElementById('kurir').selectize.setValue('');
             }

             function hitungOngkir(kurir,kota_pengirim,kota_tujuan,berat_barang){

              $("#total_belanja").addClass('spinner');
              $("#total_belanja").text('');
              $("#ongkir").addClass('spinner');
              $("#ongkir").text('');
              $("#biaya_kirim").addClass('spinner');
              $("#biaya_kirim").text('');
              $("#waktu_pengiriman").text('');  

              $.getJSON('{{ Url("hitung-ongkir") }}',{'_token': $('meta[name=csrf-token]').attr('content'),kurir:kurir,kota_pengirim:kota_pengirim,kota_tujuan:kota_tujuan,berat_barang:berat_barang}, function(resp){ 
                $selectLayananKurir[0].selectize.clearOptions();
                var subtotal = $("#total_belanja").attr("data-total");
                var options_layanan = [];
                var status_layanan = 0; 

                $.each(resp.rajaongkir.results[0].costs, function (i, item) { 
                  status_layanan += 1;
                  options_layanan.push({
                    id: resp.rajaongkir.results[0].costs[i].service+"|"+resp.rajaongkir.results[0].costs[i].cost[0].value+"|"+resp.rajaongkir.results[0].costs[i].cost[0].etd+"|"+resp.rajaongkir.results[0].costs[i].description,
                    service: resp.rajaongkir.results[0].costs[i].service+" | "+resp.rajaongkir.results[0].costs[i].description
                  });

                });
                $selectLayananKurir[0].selectize.addOption(options_layanan);    

                if (status_layanan == 0) {
                  swal({
                    text: 'Kurir yang anda pilih tidak tersedia, silakan pilih yang lain!'
                  }).then((result) => {
                    if (result.value) {
                      $selectKurir[0].selectize.focus();
                    }
                  });  

                  var ongkir = 0;
                  var waktu_pengiriman = "";
                  var layanan_kurir = "";
                  var total_belanja = parseInt(subtotal);             
                  updateOngkir(ongkir,waktu_pengiriman,layanan_kurir,total_belanja);

                }else{

                  if (resp.rajaongkir.results[0].code == "pos") {

                    var waktu_pengiriman = "Waktu Pengiriman " +resp.rajaongkir.results[0].costs[0].cost[0].etd;
                  }else{

                    var waktu_pengiriman = "Waktu Pengiriman " +resp.rajaongkir.results[0].costs[0].cost[0].etd+ " HARI.";
                  }

                  var resp_ongkir = resp.rajaongkir.results[0].costs[0].cost[0].value;
                  var total_belanja = parseInt(subtotal) + parseInt(resp_ongkir);
                  var layanan_kurir = resp.rajaongkir.results[0].costs[0].service+"|"+resp.rajaongkir.results[0].costs[0].cost[0].value+"|"+resp.rajaongkir.results[0].costs[0].cost[0].etd+"|"+resp.rajaongkir.results[0].costs[0].description;

                  var ongkir = resp_ongkir.format(0, 3, '.', ',');
                  var total_belanja = total_belanja.format(0, 3, '.', ',');
                  updateOngkir(ongkir,waktu_pengiriman,layanan_kurir,total_belanja);

                }


              });
            } 

            function updateOngkir(ongkir,waktu_pengiriman,layanan_kurir,total_belanja){
              $("#subtotal").val(ongkir);
              $("#ongkos_kirim").val(total_belanja);
              $("#biaya_kirim").text("Rp. "+ongkir);
              $("#biaya_kirim").removeClass('spinner');
              $("#ongkir").text("Rp. "+ongkir);
              $("#ongkir").removeClass('spinner');
              $("#waktu_pengiriman").text(waktu_pengiriman); 
              document.getElementById('layanan_kurir').selectize.setValue(layanan_kurir);    
              $("#total_belanja").removeClass('spinner'); 
              $("#total_belanja").text("Rp. "+total_belanja);
              $("#metode_pembayaran").val("TRANSFER");
            }

            $selectLayananKurir.on('change', function(){

              var layanan = $(this).val();
              var kurir = $("#kurir").val();
              var service = layanan.split("|");         
              var ongkir = parseInt(service[1]);

              if (layanan != "") {

               if (kurir == "pos") {
                var waktu_pengiriman = "Waktu Pengiriman " +service[2];
              }else{
                var waktu_pengiriman = "Waktu Pengiriman " +service[2]+ " HARI.";
              }

              var subtotal = $("#total_belanja").attr("data-total");
              var total_belanja = parseInt(subtotal) + parseInt(ongkir);
              var ongkir = ongkir.format(0, 3, '.', ',');
              var total_belanja = total_belanja.format(0, 3, '.', ',');
              $("#subtotal").val(ongkir);
              $("#ongkos_kirim").val(total_belanja);
              $("#biaya_kirim").text("Rp. "+ongkir);
              $("#ongkir").text("Rp. "+ongkir);
              $("#waktu_pengiriman").text(waktu_pengiriman); 
              $("#total_belanja").text("Rp. "+total_belanja);

            }

          });

            $(document).on('change', '#provinsi', function () {     

              var id_provinsi = $(this).val();

              if (id_provinsi != '') {

                $selectKota[0].selectize.clearOptions();               
                $selectKota[0].selectize.focus();
                $.getJSON('{{ Url("kota-destinasi-pengiriman") }}',{'_token': $('meta[name=csrf-token]').attr('content'),id_provinsi:id_provinsi}, function(resp){
                 $selectKota[0].selectize.addOption(resp.rajaongkir.results);    
                 $selectKota[0].selectize.focus();
               });

              }

            });

            $(document).on('click', '.alamat_pengiriman', function () {  
              $("#myModal").show();
            }); 
            $(document).on('click', '#ubah_alamat', function () {  
              $("#myModal").show();
            });     
            $(document).on('change', '#kota', function () {   
              $("#alamat").focus();
            }); 

            $(document).on('click', '.closeModal', function () {  
              $("#myModal").hide();
            });  

            $(document).on('click', '#pembayaran', function () {  
              $("#modalPembayaran").show();
            });  

            $(document).on('click', '#selesaiAlamatPengiriman', function () {

              var provinsi = $("#provinsi").text();
              var kota = $("#kota").text();
              var alamat = $("#alamat").val();

              var alamat_pengiriman = alamat +" Kota/Kab. "+kota+",  "+provinsi;

              if ($("#provinsi").val() == '') {
                $(".validationProvinsi").show();
                $select[0].selectize.focus();
              }else if ($("#kota").val() == '') {
                $(".validationKota").show();
                $selectKota[0].selectize.focus();
              }else if (alamat == '') {
                $(".validationAlamat").show();
                $('#alamat').focus();
              }else{
                $("#myModal").hide();
                $(".alamat_pengiriman").hide();
                $("#formAlamat").show();
                $("#alamatPelanggan").val(alamat_pengiriman);
                document.getElementById('kurir').selectize.setValue('jne')
              }

            });

            $(document).on('click', '#SelesaikanPesanan', function () {
              $("#formSelesaikanPesanan").submit(function(){
                return false;
              });
              var setting_aplikasi = "{{$setting_aplikasi->tipe_aplikasi}}";
              if (setting_aplikasi == 0) {
                var pesan = "Berhasil Menyelesaikan Pesanan."+"<p>Terima Kasih Telah Berbelanja Di Warmart , Silahkan Tunggu Konfirmasi Dari Warung<p>";
              }else{
                var pesan = "Berhasil Menyelesaikan Pesanan."+"<p>Terima Kasih Telah Berbelanja Di Tempat Kami , Silahkan Tunggu Konfirmasi Dari Kami<p>";
              }
              var nama_pelanggan = $("#nama_pelanggan").val();
              var no_telp = $("#no_telp").val();
              var alamatPelanggan = $("#alamatPelanggan").val();
              var kurir = $("#kurir").val();
              var metode_pembayaran = $("#metode_pembayaran").val();


              if (nama_pelanggan == '') {

                swal({
                  text: 'Nama harus di isi!'
                }).then((result) => {
                  if (result.value) {
                    $("#nama_pelanggan").focus();
                  }
                }); 
                

              }else if(no_telp == ""){

                swal({
                  text: 'No. Telp harus di isi!'
                }).then((result) => {
                  if (result.value) {
                    $("#no_telp").focus();
                  }
                });  

              }else if(kurir == ""){   

               swal({
                text: 'Kurir harus di isi!'
              }).then((result) => {
                if (result.value) {
                 document.getElementById('kurir').selectize.focus(); 
               }
             });

            }else{


              swal({
                html: "Anda Yakin Ingin Menyelesaikan Pesanan Ini ?",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya',
                cancelButtonText: 'Batal'
              }).then((result) => {
                if (result.value) {

                  document.getElementById("formSelesaikanPesanan").submit();

                  swal({
                    html : pesan,
                    showConfirmButton :  false,
                    type: "success",
                    timer: 10000,
                    onOpen: () => {
                      swal.showLoading()
                    }
                  });

                }
              });

            }

          });

            function alertValidationSelesaiPesanan(){
              swal({
                text: 'Kurir yang anda pilih tidak tersedia, silakan pilih yang lain!'
              }).then((result) => {
                if (result.value) {
                  $selectKurir[0].selectize.focus();
                }
              }); 
            }

            $(document).on('click', '#btnHapusProduk', function () {
              var id = $(this).attr("data-id");
              var nama = $(this).attr("data-nama");

              swal({
                html: "Anda Yakin Ingin Menghapus Produk <b>"+nama+"</b> Dari Keranjang Belanja ?",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya',
                cancelButtonText: 'Batal'
              }).then((result) => {
                if (result.value) {
                  var url_hapus_produk_keranjang_belanja = window.location.origin + (window.location.pathname).replace("keranjang-belanja", "keranjang-belanja/hapus-produk-keranjang-belanja/"+id);
                  window.location.href=url_hapus_produk_keranjang_belanja;
                  swal({
                    html :  "Produk <b>"+nama+"</b> Berhasil Dihapus Dari Keranjang Belanjaan",
                    showConfirmButton :  false,
                    type: "success",
                    timer: 10000,
                    onOpen: () => {
                      swal.showLoading()
                    }
                  });

                }
              })

            });

          });
        </script>

        @endsection
