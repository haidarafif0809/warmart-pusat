@extends('layouts.app_pelanggan')
@section('content')

<div class="page-header header-filter header-small" data-parallax="true" style="background-image: url('image/background2.jpg');">

  <div class="container">
    <div class="row">
      <div class="col-md-8 col-md-offset-2">
        <div class="brand">
          <h1 class="title">PASAR MUSLIM INDONESIA</h1>
          <h4 class="title"> Segala Kemudahan Untuk Umat Muslim Berbelanja.</h4>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="main main-raised">
  <div class="container">
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
              <a  href="{{ url('/daftar-produk') }}" type="button" class="btn btn-block" style="background-color: #01573e">Lanjut Belanja<i class="material-icons">keyboard_arrow_right</i></a>
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
                {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
              </div>
            </div>

            <div style="margin-bottom: 1px; margin-top: 1px;" class="form-group{{ $errors->has('no_telp') ? ' has-error' : '' }}">
              {!! Form::label('no_telp', 'No. Telpon', ['class'=>'col-md-2 control-label', 'style'=> 'margin-bottom:1px; margin-top:1px;']) !!}
              <div class="col-md-6">
                {!! Form::text('no_telp', null, ['class'=>'form-control','required','autocomplete'=>'off', 'placeholder' => 'No. Telpon', 'id' => 'nama_pelanggan']) !!}
                {!! $errors->first('no_telp', '<p class="help-block">:message</p>') !!}
              </div>
            </div>

            <div style="margin-bottom: 1px; margin-top: 1px;" class="form-group{{ $errors->has('alamat') ? ' has-error' : '' }}">
              {!! Form::label('alamat', 'Alamat', ['class'=>'col-md-2 control-label', 'style'=> 'margin-bottom:1px; margin-top:1px;']) !!}
              <div class="col-md-6">
                {!! Form::textarea('alamat', null, ['class'=>'form-control','required','autocomplete'=>'off', 'placeholder' => 'Alamat', 'id' => 'nama_pelanggan']) !!}
                {!! $errors->first('alamat', '<p class="help-block">:message</p>') !!}
              </div>
            </div>
            <span style="display: none">
              {!! Form::text('jumlah_produk',$jumlah_produk->total_produk , ['class'=>'form-control']) !!}
              {!! Form::text('subtotal', $subtotal, ['class'=>'form-control']) !!}
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
                <p style="margin-bottom:1px;margin-top: 1px;"><b>Rp. {{number_format($keranjang_belanjaans->produk->harga_jual,0,',','.') }} x {{$keranjang_belanjaans->jumlah_produk }} {{$keranjang_belanjaans->produk->satuan->nama_satuan}}</b></p>
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
          <div class="col-xs-6">
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

  <center>{!! Form::button('Selesai Pesanan <i class="material-icons">keyboard_arrow_right</i> ', ['id'=>'SelesaikanPesanan','class'=>'btn btn-round', 'type'=>'submit','style'=>'background-color: #01573e']) !!}</center>

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
            <b>{{ number_format($keranjang_belanjaans->produk->harga_jual,0,',','.') }}</b>
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
      <div class="col-md-4"><b>:</b></div>
      <div class="col-md-4"><b>{{ $jumlah_produk->total_produk }}</b></div>
    </div>

    <div class="row">
      <div class="col-md-4"><h5><b>Total </b></h5></div>
      <div class="col-md-4"><h5><b>:</h5></div>
        <div class="col-md-4"><h5 class="text-danger"><b>RP {{ number_format($subtotal,0,',','.') }}</b></h5></div>
      </div>
    </div>

  </div>

  {!! Form::button('Selesai Pesanan <i class="material-icons">keyboard_arrow_right</i> ', ['id'=>'SelesaikanPesanan','class'=>'btn btn-round pull-right', 'type'=>'submit','style'=>'background-color: #01573e']) !!}
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

  $(document).on('click', '#SelesaikanPesanan', function () {

    $("#formSelesaikanPesanan").submit(function(){
      return false;
    });

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
          html :  "Berhasil Menyelesaikan Pesanan."+"<p>Terima Kasih Telah Berbelanja Di Warmart , Silahkan Tunggu Konfirmasi Dari Warung<p>",
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

</script>
@endsection
