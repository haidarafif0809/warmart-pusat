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

         <table> 

          <tr><td>Pemesan</td> <td> :&nbsp;</td> <td>{{$pesanan->pelanggan->name}}</td></tr>
          <tr><td>No. Telp</td> <td> :&nbsp;</td> <td>{{$pesanan->pelanggan->no_telp}}</td></tr>
          <tr><td>Alamat</td> <td> :&nbsp;</td> <td>{{$pesanan->pelanggan->alamat}}</td></tr>

        </table>
      </div>


      <div class="col-md-3">Di pesan pada {{ $pesanan->created_at }}</div>

      <div class="col-md-3">Total : RP {{ number_format($subtotal,0,',','.') }}</div>  
      <div class="col-md-2">Status : {!! $status_pesanan !!}</div>
      <div class="col-md-2">Terima : 

        @if($pesanan->konfirmasi_pesanan == 0)
        <a href="{{ url('konfirmasi-pesanan-warung/'.$pesanan->id.'') }}" class="btn btn-round btn-info btn-xs"  rel="tooltip" data-placement="top" title="Lanjutkan Pesanan" > <i class="material-icons">done</i></a> 
        <a href="{{ url('batalkan-pesanan-warung/'.$pesanan->id.'') }}" class="btn btn-round btn-danger btn-xs"  rel="tooltip" data-placement="top" title="Batalkan Pesanan" > <i class="material-icons">cancel</i></a> 
        @elseif($pesanan->konfirmasi_pesanan == 1)  
        <button class="btn btn-round btn-info btn-xs" data-toggle="modal" data-target="#myModal" rel="tooltip" data-placement="top" title="Selesaikan Pesanan" > <i class="material-icons">done</i></button> 
        <a href="{{ url('batalkan-konfirmasi-pesanan-warung/'.$pesanan->id.'') }}" class="btn btn-round btn-danger btn-xs"  rel="tooltip" data-placement="top" title="Batalkan Konfirmasi" > <i class="material-icons">cancel</i></a>  
        @elseif($pesanan->konfirmasi_pesanan == 2) 
        <a href="{{ url('batalkan-pesanan-warung/'.$pesanan->id.'') }}" class="btn btn-round btn-danger btn-xs"  rel="tooltip" data-placement="top" title="Batalkan Pesanan" > <i class="material-icons">cancel</i></a> 
        @elseif($pesanan->konfirmasi_pesanan == 3) 
        <a href="{{ url('konfirmasi-pesanan-warung/'.$pesanan->id.'') }}" class="btn btn-round btn-info btn-xs"  rel="tooltip" data-placement="top" title="Lanjutkan Pesanan" > <i class="material-icons">done</i></a>  
        @endif 
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
          <td>{{  $detail_pesanans->produk->nama_barang }}</td>
          <td>RP {{ number_format($detail_pesanans->harga_produk,0,',','.') }}</td> 

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
    } 
  });
</script>
@endsection
