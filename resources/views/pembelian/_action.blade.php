{!! Form::model($model, ['url' => $form_url, 'method' => 'delete', 'class' => 'form-inline js-confirm', 'data-confirm' => $confirm_message]) !!} 

<button type="button" class="btn btn-sm btn-default btn-detail-{{$model->id}}" id="btnDetail" data-toggle="modal" data-id="{{$model->id}}" data-faktur="{{$model->no_faktur}}">Detail</button>   

|<a href="{{ $edit_url }}" class="btn btn-sm btn-success" id="btnEdit-{{$model->id}}">Ubah</a> | 


{!! Form::submit('Hapus',['class'=>'btn btn-sm btn-danger js-confirm', 'id' => 'btnHapus-'.$model->id]) !!} 
{!! Form::close() !!} 

<!-- MODAL PILIH PRODUK --> 
<div class="modal " id="myModal-{{$model->id}}" role="dialog" data-backdrop=""> 
  <div class="modal-dialog"> 
   
    <!-- Modal content --> 
    <div class="modal-content"> 
      <div class="modal-header"> 
        <button type="button" class="close" data-dismiss="modal" id="closeModalX" data-id="{{$model->id}}">&times;</button> 
        
        <h4 class="modal-title">Detail Pembelian</h4> 
      </div> 
      <div class="modal-body">  
        <div class="responsive"> 
         <table class="table table-bordered" id="table-detail-{{$model->id}}">  
          <thead> 
            <tr> 
              <th>No. Faktur</th> 
              <th>Produk</th> 
              <th>Jumlah</th> 
              <th>Harga</th> 
              <th>Potongan</th> 
              <th>Pajak</th> 
              <th>Subtotal</th> 
            </tr> 
          </thead> 
        </table> 
      </div>   
    </div>   
  </div> 
</div> 

</div> 
</div> 
<!-- / MODAL PILIH PRODUK --> 