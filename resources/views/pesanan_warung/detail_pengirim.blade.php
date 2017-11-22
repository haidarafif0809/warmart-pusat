<a href="{{ url('detail-pesanan-warung/'.$detail_pengirim->id .'') }}" type="button" class="btn btn-sm btn-default">Detail Pesanan</a>
<button type="button" class="btn btn-sm btn-default" id="btnDetail-{{$detail_pengirim->id}}" data-toggle="modal" data-target="#myModal{{$detail_pengirim->id}}">Data Pengirim</button>
<!-- MODAL PILIH PRODUK -->
<div class="modal " id="myModal{{$detail_pengirim->id}}" role="dialog" data-backdrop="">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Data Pengirim</h4>
      </div>

      <div class="modal-body"> 
        <div class="responsive"> 

          <h4 style="padding-left: 10px;">Nama : {{ $detail_pengirim->nama_pemesan }}</h4>
          <p style="padding-left: 10px;">Alamat : {{ $detail_pengirim->alamat_pemesan }}</p>
          <p style="padding-left: 10px;">No Telp : {{ $detail_pengirim->no_telp_pemesan }}</p>
        </div>  
      </div>  
    </div>
  </div> 
</div>
