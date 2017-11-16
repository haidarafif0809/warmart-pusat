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

<div class="main main-raised" style="background-color: #E5E5E5"> 
  <div class="container">
    <div class="card-content"> 
      <h3 class="title text-center">Selesaikan Pemesanan</h3>
      <div class="row">
        <div class="card" id="card-ubah-profil"><br>
          <ul class="breadcrumb">
            <li><a href="{{ url('/daftar-produk') }}">Home</a></li>
            <li><a href="{{ url('/keranjang-belanja') }}">Keranjang Belanjaan</a></li>
            <li class="active">Selesaikan Pemesanan</li>
          </ul>
        </div> 
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
        <div class="row"> 
          <div class="card"><br>
            <div class="col-md-7"> 
              <div class="card"   data-background-color="rose">
                <div class="card-header card-header-text">
                  <h6 class="card-title" style="color: black; padding-left: 10px"> Alamat Pengiriman</h6> <hr>
                </div>

                {!! Form::model($user, ['url' => route('selesaikan-pemesanan.proses'), 'method' => 'put', 'files'=>'true','class'=>'form-horizontal']) !!}
                <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                  {!! Form::label('name', 'Nama', ['class'=>'col-md-2 control-label']) !!}
                  <div class="col-md-6">
                    {!! Form::text('name', null, ['class'=>'form-control','required','autocomplete'=>'off', 'placeholder' => 'Nama', 'id' => 'nama_pelanggan']) !!}
                    {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
                  </div>
                </div>

                <div class="form-group{{ $errors->has('no_telp') ? ' has-error' : '' }}">
                  {!! Form::label('no_telp', 'No. Telpon', ['class'=>'col-md-2 control-label']) !!}
                  <div class="col-md-6">
                    {!! Form::text('no_telp', null, ['class'=>'form-control','required','autocomplete'=>'off', 'placeholder' => 'No. Telpon', 'id' => 'nama_pelanggan']) !!}
                    {!! $errors->first('no_telp', '<p class="help-block">:message</p>') !!}
                  </div>
                </div>

                <div class="form-group{{ $errors->has('alamat') ? ' has-error' : '' }}">
                  {!! Form::label('alamat', 'Alamat', ['class'=>'col-md-2 control-label']) !!}
                  <div class="col-md-6">
                    {!! Form::textarea('alamat', null, ['class'=>'form-control','required','autocomplete'=>'off', 'placeholder' => 'Alamat', 'id' => 'nama_pelanggan']) !!}
                    {!! $errors->first('alamat', '<p class="help-block">:message</p>') !!}
                  </div>
                </div>

                {!! Form::hidden('jumlah_produk',$jumlah_produk->total_produk , ['class'=>'form-control']) !!}
                {!! Form::hidden('subtotal', $subtotal, ['class'=>'form-control']) !!} 
              </div>
            </div> 

            <div class="col-md-5">

              <div class="card"  data-background-color="rose">
                <div class="card-header card-header-text">
                  <h6 class="card-title" style="color: black; padding-left: 10px">Rincian Pesanan</h6> <hr>
                </div>
                <div class="card-content table-responsive"> 
                  <table class="table">
                    <thead> 
                      <td><b>PRODUK</b></td>
                      <td><b>JUMLAH</b></td>
                      <td><b>HARGA</b></td> 
                      <td><b>HAPUS</b></td>
                    </thead> 
                  </table>
                  <div style="height: 123px; overflow-y: scroll;">
                    <table class="table"> 
                      <tbody>     

                        @foreach($keranjang_belanjaan as $keranjang_belanjaans)
                        <tr style="margin-top:0px;margin-bottom: 0px;">
                          <td><a href="{{ url('detail-produk/'.$keranjang_belanjaans->id_produk.'') }}">{{ $keranjang_belanjaans->produk->nama_barang }}</a></td>
                          <td>{{ $keranjang_belanjaans->jumlah_produk }}</td>
                          <td>{{ number_format($keranjang_belanjaans->produk->harga_jual,0,',','.') }}</td>
                          <td><a href="{{ url('/keranjang-belanja/hapus-produk-keranjang-belanja/'.$keranjang_belanjaans->id_keranjang_belanja.'') }}" type="button"> 
                            <i class="material-icons">close</i>
                          </a></td>
                        </tr>  
                        @endforeach

                      </tbody>
                    </table>
                  </div>
                </div>
                <div class="card-content table-responsive"> 
                  <table>
                    <tbody>      
                      <tr><td width="50%">Jumlah Produk </td> <td>: &nbsp;&nbsp;&nbsp;</td> <td>{{ $jumlah_produk->total_produk }}</td></tr>
                      <tr><td width="50%">Subtotal </td> <td>: &nbsp;&nbsp;&nbsp;</td> <td>Rp. {{ number_format($subtotal,0,',','.') }}</td></tr>
                    </tbody>
                  </table><hr>
                  <table>
                    <tbody>     
                      <tr><td width="40%"><h5><b>Total :</b></h5></td> <td> &nbsp;&nbsp;&nbsp;</td> <td><h5><b>RP {{ number_format($subtotal,0,',','.') }}</b></h5></td></tr>
                    </tbody>
                  </table>
                </div>
              </div> 
              {!! Form::button('Selesai Pesanan <i class="material-icons">keyboard_arrow_right</i> ', ['class'=>'btn btn-round pull-right', 'type'=>'submit','style'=>'background-color: #01573e']) !!}
              {!! Form::close() !!}
            </div>
          </div>
        </div> 
        @endif
      </div> 
    </div>
  </div>
</div>  
</div> <!-- end-main-raised -->
@endsection

@section('scripts')   
@endsection 