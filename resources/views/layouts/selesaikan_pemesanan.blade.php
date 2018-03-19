<?php
$settingFooter = \App\SettingFooter::select()->first();
$default_bank = \App\SettingTransferBank::select('id')->where('default_bank', 1)->first()->id;
$default_kurir = \App\SettingJasaPengiriman::select('jasa_pengiriman')->where('default_jasa_pengiriman', 1)->first()->jasa_pengiriman;
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
  .hurufBesar{
    text-transform: uppercase;
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
            <br>
            <h3 class="title"> PASAR MUSLIM INDONESIA </h3>
            <h4 class="title"> Segala Kemudahan Untuk Umat Muslim Berbelanja. </h4>
            @else
            <br>
            <h3 class="title"> <?=$settingFooter->judul_warung;?> </h3>
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
                    @if (Auth::check() == false) 
                    {!! Form::textarea('alamat', null, ['class'=>'form-control field','required','autocomplete'=>'off', 'placeholder' => 'Alamat Lengkap', 'id' => 'alamat', 'rows'=>'3']) !!}
                    @else
                    {!! Form::textarea('alamat', $user->alamat, ['class'=>'form-control field','required','autocomplete'=>'off', 'placeholder' => 'Alamat Lengkap', 'id' => 'alamat', 'rows'=>'3']) !!}
                    @endif
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

              @if (Auth::check() == false) 

              <div class="card" style="margin-bottom: 5px; margin-top: 5px;">
                <div class="card-header" style="margin-bottom: 1px; margin-top: 1px;">
                  <h4 class="card-title" style="color: black; padding-left: 10px ;" >Alamat Pengiriman
                  </h4>
                </div>
                <ul class="nav nav-pills nav-pills-rose" style="padding-left: 10px ;">
                  <li>
                    <a href="#login" data-toggle="tab">Login</a>
                  </li>
                  <li class="active">
                    <a href="#beliTanpaDaftar" data-toggle="tab">Beli Tanpa Daftar</a>
                  </li>
                </ul>
                <div class="tab-content">
                  <div class="tab-pane" id="login">
                    @include('layouts._form_login_selesaikan_pesanan')
                  </div>
                  <div class="tab-pane active" id="beliTanpaDaftar">
                    @include('layouts._form_selesaikan_pesanan')
                  </div>
                </div>
              </div>

              @else
              <div class="card" style="margin-bottom: 5px; margin-top: 5px;"> 
                <div class="card-header" style="margin-bottom: 1px; margin-top: 1px;"> 
                  <h6 class="card-title" style="color: black; padding-left: 10px ;" > Alamat Pengiriman</h6> <hr> 
                </div> 
                @include('layouts._form_selesaikan_pesanan')
              </div>
              @endif
            </div>

            <div class="col-md-5">
              @if ($agent->isMobile()) <!--JIKA DAKSES VIA HP/TAB-->
              @foreach($keranjang_belanjaan as $keranjang_belanjaans)
              <div class="card" style="margin-bottom: 1px; margin-top: 1px;">
                <div class="row">
                  <div class="col-md-12">

                    <div class="row">
                      <div class="col-xs-4" style="padding-right:0px">
                        <div class="img-container" style="margin:10px;">
                         @if($keranjang_belanjaans->produk->foto != NULL)
                         <img src="foto_produk/{{$keranjang_belanjaans->produk->foto}}">
                         @else
                         <img src="image/foto_default.png">
                         @endif
                       </div>
                     </div>

                     <div class="col-xs-8" style="padding-left:0px; padding-right:0px; padding-top:23px">
                      <p><b><a href="detail-produk/{{$keranjang_belanjaans->id_produk}}" style="font-size: 12px;">{{$keranjang_belanjaans->NamaProdukMobile}}</a></b><br>
                        <b style="color:red">{{$keranjang_belanjaans->jumlah_produk }} x {{number_format($keranjang_belanjaans->produk->harga_jual,0,',','.') }} : {{number_format($keranjang_belanjaans->produk->harga_jual * $keranjang_belanjaans->jumlah_produk,0,',','.') }}</b></p>
                        <p style="font-size: 10px; margin-top:10px">{{$keranjang_belanjaans->produk->warung->name}}</p>
                      </div>

                    </div>
                  </div>
                </div>
              </div>
              @endforeach
              {{$pagination}}
              <div class="card" style="margin-bottom: 1px; margin-top: 1px;">

                <table class="table">
                  <tbody>
                    <tr style="margin-top: 1px; margin-bottom:1px;">
                     <td style="margin-top: 1px; margin-bottom:1px;">Total Produk</td>
                     <td class="text-right" style="margin-top: 1px; margin-bottom:1px;" id="total_produk">{{ $jumlah_produk->total_produk }}</td>
                   </tr>
                   <tr style="margin-top: 1px; margin-bottom:1px;">
                     <td style="margin-top: 1px; margin-bottom:1px;">Subtotal</td>
                     <td class="text-right" style="margin-top: 1px; margin-bottom:1px;" id="subtotal-keranjang" data-subtotal="{{$subtotal}}">Rp. {{ number_format($subtotal,0,',','.') }}</td>
                   </tr>
                   <tr style="margin-top: 1px; margin-bottom:1px;">
                     <td style="margin-top: 1px; margin-bottom:1px;">Biaya Kirim</td>
                     <td class="text-right" style="margin-top: 1px; margin-bottom:1px;" id="biaya_kirim" data-biayaKirim="0">0</td>
                   </tr>
                   <tr style="margin-top: 1px; margin-bottom:1px;">
                     <td style="margin-top: 1px; margin-bottom:1px;">Total Belanja</td>
                     <td class="text-right" style="margin-top: 1px; margin-bottom:1px;" id="total_belanja" data-total="{{$subtotal}}">Rp. {{ number_format($subtotal,0,',','.') }}</td>
                   </tr>
                 </tbody>
               </table>

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

              <div class="card-content table-responsive" style="padding-top: 1px; padding-bottom: 1px;">
                <table class="table table-hover">
                  <thead class="text-success">

                    <th>Produk</th>
                    <th>Jumlah</th>
                    <th>Harga</th>
                    <th>Subtotal</th>
                  </thead>
                  <tbody>
                    @foreach($keranjang_belanjaan as $keranjang_belanjaans)
                    <tr id="card-produk-{{ $keranjang_belanjaans->id_keranjang_belanja }}">
                      <td><a class="btn-simple" href="{{ url('detail-produk/'.$keranjang_belanjaans->id_produk.'') }}">{{ $keranjang_belanjaans->NamaProduk }}</a></td>
                      <td class="text-right" id="jumlah-produk-{{ $keranjang_belanjaans->id_keranjang_belanja }}">{{ $keranjang_belanjaans->jumlah_produk }}</td>
                      <td class="text-right">{{ number_format($keranjang_belanjaans->produk->harga_jual,0,',','.') }}</td>
                      <td class="text-right" id="subtotal-produk-{{ $keranjang_belanjaans->id_keranjang_belanja }}" data-subtotal="{{$keranjang_belanjaans->produk->harga_jual * $keranjang_belanjaans->jumlah_produk}}">{{ number_format($keranjang_belanjaans->produk->harga_jual * $keranjang_belanjaans->jumlah_produk,0,',','.') }}</td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
                <div class="text-right">{{$pagination}}</div>
              </div>

              <hr style="margin-top: 1px;">
              <div class="card-content" style="margin-top: 1px;">

                <div class="row">
                  <div class="col-md-4"><b>Total Produk</b></div>
                  <div class="col-md-3"><b>:</b></div>
                  <div class="col-md-5"><b class="text-right" id="total_produk">{{ $jumlah_produk->total_produk }}</b></div>
                </div>

                <div class="row">
                  <div class="col-md-4"><b>Subtotal</b></div>
                  <div class="col-md-3"><b>:</b></div>
                  <div class="col-md-5"><b class="text-right" id="subtotal-keranjang" data-subtotal="{{$subtotal}}">Rp. {{ number_format($subtotal,0,',','.') }} </b></div>
                </div>

                <div class="row">
                  <div class="col-md-4"><b>Biaya Kirim</b></div>
                  <div class="col-md-3"><b>:</b></div>
                  <div class="col-md-5"><b class="text-right" id="biaya_kirim" data-biayaKirim="0">0</b></div>
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
     loadingClass: 'selectizeLoading',
     valueField: 'province_id',
     labelField: 'province',
     searchField: 'province',
     onChange: function (province_id) 
     {
      if (!province_id.length) return;
      selectKota.clearOptions(); 
      selectKota.settings.placeholder = "Tunggu Sebentar ...";
      selectKota.updatePlaceholder();
      selectKota.load(function (callback) {
       timeOutS = setTimeout(
        setKotaOptions, 500, callback, province_id
        ); 
     });
    }
  });

    var $selectKota = $('#kota').selectize({
     loadingClass: 'selectizeLoading',
     valueField: 'city_id',
     labelField: 'city_name',
     searchField: 'city_name',
     create: false
   });

    var $selectKurir = $('#kurir').selectize({
      sortField: 'text'
    });

    var $selectBank = $('#bank').selectize({
      sortField: 'text',
      valueField: 'id'
    });

    var $selectLayananKurir = $('#layanan_kurir').selectize({
     loadingClass: 'selectizeLoading',
     valueField: 'id',
     labelField: 'service',
     searchField: 'service',
     create: false              

   });

    $('#metode_pembayaran').selectize({
     valueField: 'id',
     labelField: 'name',
     searchField: 'name',
     create: false,
   });

    var selectKota = $('#kota').data('selectize');
    var selectMetodePembayaran = $('#metode_pembayaran').data('selectize');
    var selectLayananKurir = $('#layanan_kurir').data('selectize');
    Number.prototype.format = function(n, x, s, c) {
      var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\D' : '$') + ')',
      num = this.toFixed(Math.max(0, ~~n));

      return (c ? num.replace('.', c) : num).replace(new RegExp(re, 'g'), '$&' + (s || ','));
    };


    $.getJSON('{{ Url("provinsi-destinasi-pengiriman") }}',{'_token': $('meta[name=csrf-token]').attr('content')}, function(resp){ 

      $select[0].selectize.addOption(resp.rajaongkir.results);   
      var kabupaten = "{{$kabupaten}}";
      var kota_pengirim = $("#kota_pengirim").val();
      var provinsi_pelanggan = $("#provinsi_pelanggan").val();
      var kabupaten_pelanggan = $("#kabupaten_pelanggan").val();
      var auth = "{{Auth::check()}}";


      if (kota_pengirim == '') {
        $.each(resp.rajaongkir.results, function (i, item) { 

          if (kabupaten == resp.rajaongkir.results[i].type+" "+resp.rajaongkir.results[i].city_name) {
            var city_id = resp.rajaongkir.results[i].city_id;
            var provinsi = resp.rajaongkir.results[i].province_id;
            $("#kota_pengirim").val(city_id);
            if (auth == true) {
              document.getElementById('provinsi').selectize.setValue(provinsi);
            }
          };

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
        selectMetodePembayaran.clearOptions();
        if (kurir == 'cod' || kurir == 'ojek') {     
          pengirimanBiasa(kurir); 
        }else{
          hitungOngkir(kurir,kota_pengirim,kota_tujuan,berat_barang);
        }
      }

    });

    function pengirimanBiasa(kurir){    
      var subtotal = parseInt($("#total_belanja").attr("data-total"));                
      $("#subtotal").val(subtotal);
      var subtotal = subtotal.format(0, 3, '.', ',');
      $("#ongkos_kirim").val("0");
      $("#biaya_kirim").text("0");
      $("#biaya_kirim").attr("data-biayaKirim",0);
      $("#ongkir").text("");
      $("#waktu_pengiriman").text("");  
      selectLayananKurir.setValue("");
      selectLayananKurir.disable();
      selectLayananKurir.clearOptions(); 
      $("#total_belanja").text("Rp. "+subtotal);

      if (kurir == 'cod') {
        var options = [{"id":"Bayar di Tempat","name":"Bayar di Tempat"}];  
        selectMetodePembayaran.addOption(options);
        selectMetodePembayaran.setValue("Bayar di Tempat");

      }else{
        var options = [{"id":"Bayar di Tempat","name":"Bayar di Tempat"},{"id":"TRANSFER","name":"TRANSFER"}];  
        selectMetodePembayaran.addOption(options);
        selectMetodePembayaran.focus();
      }
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
    swal.close();
  } 

  function updateOngkir(ongkir,waktu_pengiriman,layanan_kurir,total_belanja){
    $("#subtotal").val(total_belanja);
    $("#ongkos_kirim").val(ongkir);
    $("#biaya_kirim").text("Rp. "+ongkir);
    $("#biaya_kirim").attr("data-biayaKirim",ongkir);
    $("#biaya_kirim").removeClass('spinner');
    $("#ongkir").text("Rp. "+ongkir);
    $("#ongkir").removeClass('spinner');
    $("#waktu_pengiriman").text(waktu_pengiriman);   
    selectLayananKurir.setValue(layanan_kurir);
    selectLayananKurir.enable(); 
    $("#total_belanja").removeClass('spinner'); 
    $("#total_belanja").text("Rp. "+total_belanja);
    var options = [{"id":"TRANSFER","name":"TRANSFER"}];  
    selectMetodePembayaran.addOption(options);
    selectMetodePembayaran.setValue("TRANSFER");

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
    $("#subtotal").val(total_belanja);
    $("#ongkos_kirim").val(ongkir);
    $("#biaya_kirim").text("Rp. "+ongkir);
    $("#biaya_kirim").attr("data-biayaKirim",ongkir);
    $("#ongkir").text("Rp. "+ongkir);
    $("#waktu_pengiriman").text(waktu_pengiriman); 
    $("#total_belanja").text("Rp. "+total_belanja);

  }

});

  var setKotaOptions = function (callback, id_provinsi)
  {
    clearTimeout(timeOutS);

    $.getJSON('{{ Url("kota-destinasi-pengiriman") }}',{'_token': $('meta[name=csrf-token]').attr('content'),id_provinsi:id_provinsi}, function(resp){
      var respon = $.parseJSON('[' + resp.data + ']');
      var rajaongkir = respon[0]['rajaongkir']['results'];

      callback(rajaongkir);
      var kabupaten_pelanggan = $("#kabupaten_pelanggan").val(); 
      $.each(rajaongkir, function (i, item) { 

        if (kabupaten_pelanggan == rajaongkir[i].type+" "+rajaongkir[i].city_name) {
          var kota = rajaongkir[i].city_id;
          var alamat = $("#alamatPelanggan").val();
          var alamat_pengiriman = alamat +", Kota/Kab. "+rajaongkir[i].city_name+",  "+rajaongkir[i].province;

          selectKota.setValue(kota); 
          $("#alamatPelanggan").val(alamat_pengiriman);
          $(".alamat_pengiriman").hide();
          $("#formAlamat").show();

          document.getElementById('kurir').selectize.setValue(resp.kurir);

        }else{
          selectKota.settings.placeholder = "Cari Kabupaten atau Kota ...";
          selectKota.updatePlaceholder();  
          selectKota.focus();  
        };
      }); 
    });
  };


  selectMetodePembayaran.on('change', function(){
    var metode_pembayaran = selectMetodePembayaran.getValue();

    if (metode_pembayaran == "TRANSFER") {
      $("#note_pembayaran").text("");
      $("#bankForm").show();
      console.log("<?= $default_bank; ?>");
      document.getElementById('bank').selectize.setValue("<?= $default_bank; ?>");
    }else{
     $("#note_pembayaran").text("Anda dapat melakukan pembayaran saat Anda menerima pesanan.");
     $("#bankForm").hide();
   }
 }); 

  $(document).on('click', '.alamat_pengiriman', function () {  
    $("#myModal").show();
    $select[0].selectize.focus();
  }); 
  $(document).on('click', '#ubah_alamat', function () {  
    $("#myModal").show();
    $select[0].selectize.focus();
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
      document.getElementById('kurir').selectize.setValue("<?= $default_kurir; ?>");
    }

  });

  $(document).on('click', '#SelesaikanPesanan', function () {
    $("#formSelesaikanPesanan").submit(function(){
      return false;
    });
    var setting_aplikasi = "{{$setting_aplikasi->tipe_aplikasi}}";
    if (setting_aplikasi == 0) {
      var pesan = "Berhasil Menyelesaikan Pesanan."+"<p>Terima Kasih Telah Berbelanja Di Warmart , Silahkan Tunggu Konfirmasi Dari Warung</p>";
    }else{
      var pesan = "Berhasil Menyelesaikan Pesanan."+"<p>Terima Kasih Telah Berbelanja Di Tempat Kami , Silahkan Tunggu Konfirmasi Dari Kami</p>";
    }
    var nama_pelanggan = $("#nama_pelanggan").val();
    var no_telp = $("#no_telp").val();
    var email = $("#email").val();
    var alamatPelanggan = $("#alamatPelanggan").val();
    var kurir = $("#kurir").val();
    var metode_pembayaran = $("#metode_pembayaran").val();
    var ongkos_kirim = $("#ongkos_kirim").val();
    var provinsi = $("#provinsi").val();
    var kota = $("#kota").val();

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

    }else if(email == ""){

      swal({
        text: 'Email harus di isi!'
      }).then((result) => {
        if (result.value) {
          $("#email").focus();
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

  }else if(provinsi == "" || kota == ""){   

   swal({
    text: 'Mohon Lengkapi Alamat Pengiriman!'
  }).then((result) => {
    if (result.value) {
     $("#myModal").show();
     document.getElementById('provinsi').selectize.focus(); 
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
      var sisa_jumlah_produk = "| 0";
      $("#jumlah-keranjang").text(sisa_jumlah_produk);
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
    var jumlah_produk = $("#jumlah-produk-"+id).text();
    var subtotal_produk = $("#subtotal-produk-"+id).attr("data-subtotal");
    var total_produk = $("#total_produk").text();
    var subtotal = $("#subtotal-keranjang").attr("data-subtotal");
    var biaya_kirim = $("#biaya_kirim").attr("data-biayaKirim");

    var totalProduk = parseInt(total_produk) - parseInt(jumlah_produk);
    var subtotalAkhir = parseInt(subtotal) - parseInt(subtotal_produk);
    var total_belanja = parseInt(subtotalAkhir) + parseInt(biaya_kirim);


    swal({
      html: "Anda Yakin Ingin Menghapus Produk <b>"+nama+"</b> Dari Keranjang Belanja ?",
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya',
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.value) {
        $("#card-produk-"+id).remove();
        $("#total_produk").text(totalProduk);
        $("#subtotal-keranjang").attr("data-subtotal",subtotalAkhir);
        $("#subtotal-keranjang").text("Rp. "+subtotalAkhir.format(0, 3, '.', ','));
        $("#total_belanja").attr("data-total",total_belanja);
        $("#total_belanja").text("Rp. "+total_belanja.format(0, 3, '.', ','));
        alertBerhasilHapus(nama);
        hapusProduk(id);

      }
    })

  });

  function alertBerhasilHapus(nama){
   swal({
    html :  "Produk <b>"+nama+"</b> Berhasil Dihapus Dari Keranjang Belanjaan",
    showConfirmButton :  false,
    type: "success",
    timer: 1000
  });
 }
 function hapusProduk(id){
  var totalProduk = "{{$cek_belanjaan}}";
  var totalProduk = parseInt(totalProduk) - 1; 
  var sisa_jumlah_produk = "| "+totalProduk;
  $("#jumlah-keranjang").text(sisa_jumlah_produk);
  $.get('{{ Url("keranjang-belanja/hapus-produk-keranjang-belanja") }}',{'_token': $('meta[name=csrf-token]').attr('content'),id:id}, function(resp){
  });
}

});


</script>

@endsection
