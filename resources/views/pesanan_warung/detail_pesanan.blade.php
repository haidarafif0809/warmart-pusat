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
        <div class="col-md-2">Order #{{ $pesanan->id }}</div>
        <div class="col-md-3">Di pesan pada {{ $pesanan->created_at }}</div>

        <div class="col-md-3">Total : RP {{ number_format($subtotal,0,',','.') }}</div>  
        <div class="col-md-2">Status : {!! $status_pesanan !!}</div>
        <div class="col-md-2">Terima : 

          @if($pesanan->konfirmasi_pesanan == 0)
          <a href="{{ url('konfirmasi-pesanan-warung/'.$pesanan->id.'') }}" class="btn btn-round btn-info btn-xs"  rel="tooltip" data-placement="top" title="Lanjutkan Pesanan" > <i class="material-icons">done</i></a> 
          <a href="{{ url('batalkan-pesanan-warung/'.$pesanan->id.'') }}" class="btn btn-round btn-danger btn-xs"  rel="tooltip" data-placement="top" title="Batalkan Pesanan" > <i class="material-icons">cancel</i></a>
          @elseif($pesanan->konfirmasi_pesanan == 1)
          <a href="{{ url('selesai-konfirmasi-pesanan-warung/'.$pesanan->id.'') }}" class="btn btn-round btn-info btn-xs"  rel="tooltip" data-placement="top" title="Selesaikan Pesanan" > <i class="material-icons">done</i></a> 
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
@endsection

@section('scripts')  
@endsection
