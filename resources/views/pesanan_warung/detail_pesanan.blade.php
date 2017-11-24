@extends('layouts.app')
@section('content') 
<div class="col-md-12"> 


  <div class="card" >

    <ul class="breadcrumb" >

      <li><a href="{{ url('/home') }}">Home</a></li>
      <li><a href="{{ url('/pesanan-warung') }}">Pesanan</a></li>
      <li class="active">Detail Pesenan</li>

    </ul>  

    <div class="card-content" style="padding-bottom: 10px;padding-top: 0px">

      <div class="row">

        <div class="col-md-2">Order #{{ $pesanan->id }}

          <!-- MODAL PILIH PRODUK -->
          <div class="modal " id="data_pemesa" role="dialog" data-backdrop="">
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

          <button class="btn btn-round btn-info btn-xs" data-toggle="modal" data-target="#myModal" rel="tooltip" data-placement="top" title="Selesaikan Pesanan" > <i class="material-icons">done</i></button> 

          <button id="batalkan-konfirmasi-pesanan-warung" id-pesanan="{{$pesanan->id}}" class="btn btn-round btn-danger btn-xs"  rel="tooltip" data-placement="top" title="Batalkan Konfirmasi" ><i class="material-icons">cancel</i></button>

          @elseif($pesanan->konfirmasi_pesanan == 2) 

          <button id="batalkan-pesanan-warung" id-pesanan="{{$pesanan->id}}" class="btn btn-round btn-danger btn-xs"  rel="tooltip" data-placement="top" title="Batalkan Pesanan" ><i class="material-icons">cancel</i></button>

          @elseif($pesanan->konfirmasi_pesanan == 3) 

          <button id="konfirmasi-pesanan-warung" id-pesanan="{{$pesanan->id}}" class="btn btn-round btn-info btn-xs"  rel="tooltip" data-placement="top" title="Lanjutkan Pesanan"><i class="material-icons">done</i></button>
          @endif

          <button type="button" class="btn btn-sm btn-primary" id="btnDetail" data-toggle="modal" data-target="#data_pemesa">Pemesan</button> 

        </div>

      </div>  

    </div> 

  </div>  

  <div class="card"> 

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

                <div class="btn-group">

                  @if($pesanan->konfirmasi_pesanan == 0) 
                  @if($detail_pesanans->jumlah_produk == 0)
                  <a disabled="true" class="btn btn-round btn-xs"> <i class="material-icons">remove</i></a>
                  @else
                  <a  href="{{ url('kurang-produk-pesanan-warung/'.$detail_pesanans->id.'') }}" class="btn btn-round btn-xs"> <i class="material-icons">remove</i></a>
                  @endif
                  <a class="btn btn-round btn-xs" >{{  $detail_pesanans->jumlah_produk }} </a>
                  <a href="{{ url('tambah-produk-pesanan-warung/'.$detail_pesanans->id.'') }}" class="btn btn-round btn-xs"> <i class="material-icons">add</i></a>
                  @else
                  <a class="btn btn-xs" >{{  $detail_pesanans->jumlah_produk }} </a>
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
    </div>  
  </div>
</div>
</div>  

<!-- MODAL PILIH PRODUK -->
<div class="modal " id="myModal" role="dialog" data-backdrop="">

  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">

      <div class="modal-header">

        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Pilih Kas</h4>

      </div>

      <div class="modal-body">   
        {!! Form::open(['url' => route('pesanan-warung.selesai_konfirmasi'),'method' => 'post','id' => 'form-selesaikan-pesanan', 'class'=>'form-horizontal']) !!}

        {!! Form::hidden('id_pesanan', $pesanan->id, ['Produk','required', 'id'=>'id_pesanan']) !!}
        <div class="form-group{{ $errors->has('id_kas') ? ' has-error' : '' }}">

          {!! Form::label('id_kas', 'Kas', ['class'=>'col-md-2 control-label']) !!}
          <div class="col-md-6">
            {!! Form::select('id_kas', $kas, null, ['class'=>'form-control','id'=>'kas']) !!}
            {!! $errors->first('id_kas', '<p class="help-block">:message</p>') !!}
          </div>
          {!! Form::button('Simpan', ['class'=>'btn btn-info','type'=>'submit','id'=>'btnSimpan']) !!}

        </div>

        {!! Form::close() !!} 
      </div> 

    </div>

  </div> 

</div>

@endsection

@section('scripts')  
<script type="text/javascript">
  $(document).on('click', '#selesai_pesanan', function () { 

    var kas = '<select id="ppn_swal" name="ppn_swal" class="swal2-input js-selectize-reguler">'

    + '<?php echo $data_kas ?>'+


    '</select></div>'; 

    swal({ 
      title:'Pilih Kas',  
      html:
      '<div class="row">'+  
      '<div class="col-sm-12">'+kas+''+  
      '</div>', 
      animation: false,
      showCloseButton: true,
      showCancelButton: true,
      focusConfirm: true,
      confirmButtonText:'<i class="fa fa-thumbs-o-up"></i> Submit',
      confirmButtonAriaLabel: 'Thumbs up, great!',
      cancelButtonText:'<i class="fa fa-thumbs-o-down"> Batal',
      closeOnConfirm: true,
      cancelButtonAriaLabel: 'Thumbs down',
      inputAttributes: {
        'name': 'qty_produk',
      },
      inputValidator : function (value) {
        return new Promise(function (resolve, reject) {
          if (value) {
            resolve();
          } else {

            reject('Kas Harus Di isi!');

          }
        });
      }
    }).then(function (selesaikan_pesanan) {

      $("#id_pesanan").val(id_tbs); 
      $("#kas").val(result[0]);  
      $("#form-selesaikan-pesanan").submit();   
    })   
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
</script>
@endsection
