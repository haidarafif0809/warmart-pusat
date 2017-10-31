@extends('layouts.app_pelanggan')
@section('content') 

<div class="card-content">
    <h4 class="card-title">Keranjang Belanjaan</h4>
    <div class="row">
        @if($cek_belanjaan == 0)
        <div class="col-md-12">
            <center>
                <h3>Keranjang Belanjaan Anda Kosong,Silahkan Berbelanja.</h3>
                <a  href="{{ url('/daftar-produk') }}" type="button" class="btn btn-info btn-round">Lanjut Belanja<i class="material-icons">keyboard_arrow_right</i></a>
            </center> 
        </div>
        @else
        <div class="col-md-8"> 
            <table class="table table-shopping">
                <thead >
                    <tr class="card" style="width: 725px;">
                        <th class="text-center"></th>
                        <th style="padding-left: 20%"><b>Produk</b></th>   
                        <th style="padding-left: 125%"><b>Harga Produk</b></th> 
                        <th style="padding-left: 135%"><b>Kuantitas</b></th> 
                    </tr>
                </thead>
                <tbody>                         
                    @foreach($keranjang_belanjaan as $keranjang_belanjaans)
                    <tr class="card" style="margin-bottom: 2px;margin-top: 2px;width: 725px;">
                        <td>
                            <div class="img-container"> 
                                <img src="{{ asset('image/foto_default.png')}}" alt="...">
                            </div>
                        </td>
                        <td class="td-name">
                            <a href="#jacket">Spring Jacket</a>
                            <br />
                            <small><i class="material-icons">store</i>  Ghaza </small>
                        </td>  
                        <td class="td-number">
                            <b>Rp.10.000.000</b>
                        </td> 
                        <td class="td-number">
                            <div class="btn-group">
                                <button class="btn btn-round btn-info btn-xs"> <i class="material-icons">remove</i> </button>
                                <a class="btn btn-round btn-info btn-xs"> 1 </a>
                                <button class="btn btn-round btn-info btn-xs"> <i class="material-icons">add</i> </button>
                            </div>
                        </td>   
                        <td class="td-actions">
                            <button type="button" rel="tooltip" data-placement="left" title="Remove item" class="btn btn-simple">
                                <i class="material-icons">close</i>
                            </button>
                        </td>
                    </tr> 
                    @endforeach
                </tbody>
            </table> 
        </div>
        <div class="col-md-4">

            <div class="card">
                <div class="card-header card-header-text" data-background-color="blue">
                    <h6 class="card-title">Rincian Pesanan</h6> 
                </div>
                <div class="card-content table-responsive"> 
                    <table>
                      <tbody>      
                          <tr><td width="50%">Jumlah Produk </td> <td>: &nbsp;&nbsp;&nbsp;</td> <td>10</td></tr>
                          <tr><td width="50%">Subtotal </td> <td>: &nbsp;&nbsp;&nbsp;</td> <td>Rp. 10.000.000.000</td></tr>
                      </tbody>
                  </table><hr>
                  <table>
                      <tbody>     
                          <tr><td width="40%"><h5><b>Total :</b></h5></td> <td> &nbsp;&nbsp;&nbsp;</td> <td><h5><b>RP 10.000.000.000</b></h5></td></tr>
                      </tbody>
                  </table>
              </div>
          </div>
          <button type="button" class="btn btn-info btn-round">Lanjut Ke Pembayaran <i class="material-icons">keyboard_arrow_right</i></button>
      </div>
  </div>
  @endif
</div>
</div>
@endsection

@section('scripts') 
@endsection 