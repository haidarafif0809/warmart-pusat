@extends('layouts.app')
@section('content')
<div class="col-md-12">

  {!! Form::open(['url' => route('pesanan-warung.selesai_konfirmasi'),'method' => 'post','id' => 'form-selesaikan-pesanan', 'class'=>'form-horizontal']) !!}

  {!! Form::hidden('id_pesanan', $pesanan->id, ['Produk','required', 'id'=>'id_pesanan']) !!}
  {!! Form::hidden('id_kas', null, ['Produk','required', 'id'=>'id_kas']) !!}

  {!! Form::close() !!}


  {!! Form::open(['url' => route('pesanan-warung.edit_jumlah_pesanan'),'method' => 'post','id' => 'form-edit-jumlah-pesanan', 'class'=>'form-horizontal']) !!}

  {!! Form::hidden('jumlah_produk', null, ['id'=>'jumlah_produk']) !!}
  {!! Form::hidden('id', null, ['id'=>'id']) !!}

  {!! Form::close() !!}



  <ul class="breadcrumb" style="margin-bottom: 1px; margin-top: 1px;">
    <li><a href="{{ url('/home') }}">Home</a></li>
    <li><a href="{{ url('/pesanan-warung') }}">Pesanan</a></li>
    <li class="active">Detail Pesenan</li>

  </ul>

  @if ($agent->isMobile()) <!--JIKA DAKSES VIA HP/TAB-->

  <div class="card" style="margin-bottom: 5px; margin-top: 1px;">
    <div class="card-content">
     <b class="card-title" style="margin-top: 1px; margin-bottom: 1px;">Order #{{ $pesanan->id }}</b>

     <hr style="margin-top: 1x; margin-bottom: 1px;">
     <b class="card-title" style="margin-top: 1px; margin-bottom: 1px;">Waktu Pesan</b>
     <p style="margin-top: 1px; margin-bottom: 1px;">{{ $pesanan->created_at }}</p>
     <hr style="margin-top: 1x; margin-bottom: 1px;">

     <b class="card-title" style="margin-top: 1px; margin-bottom: 1px;">Alamat Pengiriman</b>
     <p style="margin-top: 1px; margin-bottom: 1px;"> {{ $pesanan->nama_pemesan }}</p>
     <p style="margin-top: 1px; margin-bottom: 1px;">{{ $pesanan->no_telp_pemesan }}</p>
     <p style="margin-top: 1px; margin-bottom: 1px;">{{ $pesanan->alamat_pemesan }}</p>

     <hr style="margin-top: 1x; margin-bottom: 1px;">
     <div class="row">
      <div class="col-md-12">
        <div class="row">

         <div class="col-xs-6">
           <p> <b class="card-title" style="margin-top: 1px; margin-bottom: 1px;">Status Pesanan</b></p>
           <p style="margin-top: 1px; margin-bottom: 1px;">{!! $status_pesanan !!}</p>
         </div>

         <div class="col-xs-6">
          <b class="card-title" style="margin-top: 1px; margin-bottom: 1px;">Konfirmasi/Batal</b>

          @if($pesanan->konfirmasi_pesanan == 0)
          <div class="btn-group">
            <button id="konfirmasi-pesanan-warung" id-pesanan="{{$pesanan->id}}"  class="btn btn-info btn-xs">Lanjut</button>

            <button id="batalkan-pesanan-warung" id-pesanan="{{$pesanan->id}}" class="btn btn-danger btn-xs">Batal</button>
          </div>
          @elseif($pesanan->konfirmasi_pesanan == 1)
          <div class="btn-group">
            <button class="btn btn-info btn-xs" data-id="{{ $pesanan->id }}" id="selesaikan_pesanan">Selesai</button>

            <button id="batalkan-konfirmasi-pesanan-warung" id-pesanan="{{$pesanan->id}}"  class="btn btn-danger btn-xs">Batal</button>
          </div>

          @elseif($pesanan->konfirmasi_pesanan == 2)

          <button id="batalkan-pesanan-warung" id-pesanan="{{$pesanan->id}}" class="btn btn-danger btn-xs">Batal</button>

          @elseif($pesanan->konfirmasi_pesanan == 3)

          <button id="konfirmasi-pesanan-warung" id-pesanan="{{$pesanan->id}}"  class="btn btn-info btn-xs">Lanjut</button>
          @endif


        </div>
      </div>
    </div>

  </div>



  <hr style="margin-top: 1x; margin-bottom: 1px;">
  <b class="card-title" style="margin-top: 1px; margin-bottom: 1px;">Info Pembayaran</b>

  <div class="row">
    <div class="col-sm-6 col-xs-6">Total </div>

    <div class="col-sm-6 col-xs-6"><p align="right" class="text-danger"><b>Rp. {{ number_format($subtotal,0,',','.') }}</b></p></div>
  </div>


  <hr style="margin-top: 1x; margin-bottom: 1px;">
</div>
</div>

@foreach($detail_pesanan as $detail_pesanans)

<div class="card" style="margin-bottom: 1px; margin-top: 1px;">
  <div class="row">
    <div class="col-md-12">

      <div class="row">
        <div class="col-xs-4">
          <div class="img-container" style="margin-bottom:10px;margin-top: 10px; margin-left: 10px; margin-right: 10px;">
            @if($detail_pesanans->produk->foto != NULL)
            <img src="../foto_produk/{{$detail_pesanans->produk->foto}}">
            @else
            <img src="../image/foto_default.png">
            @endif
          </div>

        </div>

        <div class="col-xs-8">
          <p style="margin-bottom:1px;margin-top: 1px;"><b>{{$detail_pesanans->NamaBarang}}</b></p>
          <p style="margin-bottom:1px;margin-top: 1px;"><b>Rp. {{ number_format($detail_pesanans->harga_produk,0,',','.') }}</b></p>
          <button id="edit-jumlah-pesanan" data-nama="{{$detail_pesanans->NamaBarang}}" data-id="{{$detail_pesanans->id}}" class="btn btn-info btn-xs"><b>{{$detail_pesanans->jumlah_produk}} {{$detail_pesanans->produk->satuan->nama_satuan}}</b></button>
          <div class="btn-group" align="right">


            @if($pesanan->konfirmasi_pesanan == 0)

            @if($detail_pesanans->jumlah_produk == 0)
            <a disabled="true" class="btn btn-xs"><b>-</b></a>
            @else
            <a href="{{ url('kurang-produk-pesanan-warung/'.$detail_pesanans->id.'') }}" class="btn btn-xs"><b>-</b></a>
            @endif

            <a href="{{ url('tambah-produk-pesanan-warung/'.$detail_pesanans->id.'') }}" class="btn btn-xs"><b>+</b></a>

            @endif

          </div>
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          @if($detail_pesanans->jumlah_produk == 0)
          <b style="margin-bottom:1px;margin-top: 1px;" class="text-danger">Dibatalkan</b>
          @endif

        </div>

      </div>

    </div>
  </div>

</div>
@endforeach

{!! $pagination !!}

@else

<div class="card" style="margin-top: 1px; margin-bottom: 1px;">
  <div class="card-content" style="padding-bottom: 10px;padding-top: 1px">

    <div class="row">

      <div class="col-md-2">Order #{{ $pesanan->id }}

        <!-- MODAL PILIH PRODUK -->
        <div class="modal " id="data_pemesan" role="dialog" data-backdrop="">
          <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Data Pemesan</h4>
              </div>

              <div class="modal-body">
                <div class="responsive">

                  <h4 style="padding-left: 10px;">Nama : {{ $pesanan->nama_pemesan }}</h4>
                  <p style="padding-left: 10px;">Alamat : {{ $pesanan->alamat_pemesan }}</p>
                  <p style="padding-left: 10px;">No Telp : {{ $pesanan->no_telp_pemesan }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>


      <div class="col-md-3">Di pesan pada {{ $pesanan->created_at }}</div>

      <div class="col-md-3">Total : Rp. {{ number_format($subtotal,0,',','.') }}</div>

      <div class="col-md-2">Status : {!! $status_pesanan !!}</div>

      <div class="col-md-2">Terima :

        @if($pesanan->konfirmasi_pesanan == 0)

        <button id="konfirmasi-pesanan-warung" id-pesanan="{{$pesanan->id}}" class="btn btn-round btn-info btn-xs"  rel="tooltip" data-placement="top" title="Lanjutkan Pesanan"><i class="material-icons">done</i></button>

        <button id="batalkan-pesanan-warung" id-pesanan="{{$pesanan->id}}" class="btn btn-round btn-danger btn-xs"  rel="tooltip" data-placement="top" title="Batalkan Pesanan" ><i class="material-icons">cancel</i></button>

        @elseif($pesanan->konfirmasi_pesanan == 1)

        <button class="btn btn-round btn-info btn-xs" data-id="{{ $pesanan->id }}" id="selesaikan_pesanan" rel="tooltip" data-placement="top" title="Selesaikan Pesanan" > <i class="material-icons">done</i></button>

        <button id="batalkan-konfirmasi-pesanan-warung" id-pesanan="{{$pesanan->id}}" class="btn btn-round btn-danger btn-xs"  rel="tooltip" data-placement="top" title="Batalkan Konfirmasi" ><i class="material-icons">cancel</i></button>

        @elseif($pesanan->konfirmasi_pesanan == 2)

        <button id="batalkan-pesanan-warung" id-pesanan="{{$pesanan->id}}" class="btn btn-round btn-danger btn-xs"  rel="tooltip" data-placement="top" title="Batalkan Pesanan" ><i class="material-icons">cancel</i></button>

        @elseif($pesanan->konfirmasi_pesanan == 3)

        <button id="konfirmasi-pesanan-warung" id-pesanan="{{$pesanan->id}}" class="btn btn-round btn-info btn-xs"  rel="tooltip" data-placement="top" title="Lanjutkan Pesanan"><i class="material-icons">done</i></button>
        @endif

        <button type="button" class="btn btn-sm btn-primary" id="btnDetail" data-toggle="modal" data-target="#data_pemesan">Pemesan</button>


      </div>

    </div>

  </div>

</div>

<div class="card" style="margin-top: 1px; margin-bottom: 1px;">

  <div class="card-content">

    <div class="responsive">

     <table class="table table-hover table-responsive">

      <thead>
        <tr>
          <th><b>Produk</b></th>
          <th><b>Harga</b> </th>
          <th><center><b>Jumlah</b></center></th>
          <th><b>Status</b> </th>
        </tr>
      </thead>

      <tbody>

        @foreach($detail_pesanan as $detail_pesanans)
        <tr>

          <td>{{  $detail_pesanans->NamaBarang }}</td>
          <td>Rp. {{ number_format($detail_pesanans->harga_produk,0,',','.') }}</td>

          <td>
            <center>



              {{ $detail_pesanans->jumlah_produk }}
              <div class="btn-group">

                @if($pesanan->konfirmasi_pesanan == 0)
                @if($detail_pesanans->jumlah_produk == 0)
                <a disabled="true" class="btn btn-round btn-xs"> <i class="material-icons">remove</i></a>
                @else
                <a  href="{{ url('kurang-produk-pesanan-warung/'.$detail_pesanans->id.'') }}" class="btn btn-round btn-xs"> <i class="material-icons">remove</i></a>
                @endif
                <a href="{{ url('tambah-produk-pesanan-warung/'.$detail_pesanans->id.'') }}" class="btn btn-round btn-xs"> <i class="material-icons">add</i></a>

                @endif

              </div>

            </center>
          </td>

          @if($detail_pesanans->jumlah_produk == 0)
          <td style="color: red"> <b>Di Batalkan</b></td>
          @else
          {!! $status_pesanan !!}
          @endif

        </tr>

        @endforeach
      </tbody>
    </table>


    {!! $pagination !!}
  </div>
</div>
</div>


@endif

</div>

@endsection

@section('scripts')
<script type="text/javascript">

  $(document).on('click', '#selesaikan_pesanan', function () {

    var id = $(this).attr("data-id");
    var url_create_kas = window.location.origin + (window.location.pathname).replace("detail-pesanan-warung/"+id, "dashboard#/kas");

    $.getJSON("{{ Url('kas/cek-kas-warung')}}", function(data){

      if (data.cek_kas == 0) {

        var kas_warung = '<input type="hidden" id="kas" value="0">Anda Belum Punya Kas, Silahkan Buat Kas <a target="blank" href="'+url_create_kas+'">Disini</a>';

      }else{

       var kas_warung = '<select id="kas" name="kas" class="swal2-input js-selectize-reguler">';

       $.each(data.kas, function (i, item) {

        if (data.kas[i].status_kas == 1) {

          if (data.kas[i].default_kas == 1) {

            kas_warung += '<option value="'+data.kas[i].id+'" selected>'+data.kas[i].nama_kas+'</option>';
          }else{

            kas_warung += '<option value="'+data.kas[i].id+'">'+data.kas[i].nama_kas+'</option>';
          }

        }


      });

       kas_warung += '</select></div>';

     }

     swal({
      title: "Pilih Kas",
      html: kas_warung,
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Simpan',
      cancelButtonText: 'Batal',
      confirmButtonClass: 'btn btn-success',
      cancelButtonClass: 'btn btn-danger',
      buttonsStyling: false,
      preConfirm: function () {
        return new Promise(function (resolve) {
          resolve([
            $('#kas').val()
            ])
        })
      }
    }).then(function (result) {

      if (result[0] == '' || result[0] == 0) {

        swal('Oops...', result[0], 'error');
        return false;
      }else{

        $("#id_kas").val(result[0]);
        $("#form-selesaikan-pesanan").submit();

        swal({
          html :  "Pesanan order #<b>"+id+" Berhasil Di Selesaikan</b>",
          showConfirmButton :  false,
          type: "success",
          timer: 10000,
          onOpen: () => {
            swal.showLoading()
          }
        });
      }

    });

  });
  });

  $(document).on('click', '#batalkan-konfirmasi-pesanan-warung', function () {

    swal({
      text: "Anda Yakin Ingin Membatalkan Konfirmasi Pesanan Ini??",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya!',
      cancelButtonText: 'Tidak',
      confirmButtonClass: 'btn btn-success',
      cancelButtonClass: 'btn btn-danger',
      buttonsStyling: false
    }).then(function () {

      var url_batalKan_konfirmasi_pesanan = window.location.origin + (window.location.pathname).replace("detail-pesanan-warung", "batalkan-konfirmasi-pesanan-warung");
      window.location.href=url_batalKan_konfirmasi_pesanan;
    })
  });

  $(document).on('click', '#konfirmasi-pesanan-warung', function () {

    swal({
      text: "Anda Yakin Ingin Melanjutkan Pesanan Ini??",
      type: 'question',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya!',
      cancelButtonText: 'Tidak',
      confirmButtonClass: 'btn btn-success',
      cancelButtonClass: 'btn btn-danger',
      buttonsStyling: false
    }).then(function () {

      var url_konfirmasi_pesanan = window.location.origin + (window.location.pathname).replace("detail-pesanan-warung", "konfirmasi-pesanan-warung");
      window.location.href=url_konfirmasi_pesanan;
    })
  });

  $(document).on('click', '#batalkan-pesanan-warung', function () {

    swal({
      text: "Anda Yakin Ingin Membatalkan Pesanan Ini??",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya!',
      cancelButtonText: 'Tidak',
      confirmButtonClass: 'btn btn-success',
      cancelButtonClass: 'btn btn-danger',
      buttonsStyling: false
    }).then(function () {

      var url_batalKan_pesanan = window.location.origin + (window.location.pathname).replace("detail-pesanan-warung", "batalkan-pesanan-warung");
      window.location.href=url_batalKan_pesanan;
    })
  });

  $(document).on('click', '#edit-jumlah-pesanan', function () {

    var nama_produk = $(this).attr("data-nama");
    var id = $(this).attr("data-id");

    swal({
      title: nama_produk,
      input: 'number',
      inputPlaceholder : 'Jumlah Produk',
      html:'Masukkan Jumlah Produk Yang Di Inginkan',
      showCloseButton: true,
      showCancelButton: true,
      focusConfirm: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Simpan',
      cancelButtonText: 'Batal',
      confirmButtonClass: 'btn btn-success',
      cancelButtonClass: 'btn btn-danger',
      buttonsStyling: false,
      inputAttributes: {
        'name': 'edit_qty_produk',
      },
      inputValidator : function (value) {
        return new Promise(function (resolve, reject) {
          if (value) {
            resolve();
          }
          else {
            reject('Jumlah Produk Harus Di Isi!');
          }
        })
      }
    }).then(function (jumlah_produk) {



      if (jumlah_produk != "0") {

       swal({
        text :  "Jumlah Produk <b>"+nama_produk+"</b> Berhasil Di Ubah",
        showConfirmButton :  false,
        type: "success",
        timer: 10000,
        onOpen: () => {
          swal.showLoading()
        }
      });

       $("#jumlah_produk").val(jumlah_produk);
       $("#id").val(id);
       $("#form-edit-jumlah-pesanan").submit();
     }
     else {
      swal('Oops...', 'Jumlah Tidak Boleh 0 !', 'error');
      return false;
    }


  });

  });
</script>
@endsection
