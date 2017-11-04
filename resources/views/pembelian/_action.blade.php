{!! Form::model($model, ['url' => $form_url, 'method' => 'delete', 'class' => 'form-inline js-confirm', 'data-confirm' => $confirm_message]) !!}

<button type="button" class="btn btn-sm btn-default" id="btnDetail-{{$model->id}}" data-toggle="modal" data-target="#myModal{{$model->id}}">Detail</button>  

|<a href="{{ $edit_url }}" class="btn btn-sm btn-success" id="btnEdit-{{$model->id}}">Ubah</a> |


{!! Form::submit('Hapus',['class'=>'btn btn-sm btn-danger js-confirm', 'id' => 'btnHapus-'.$model->id]) !!}
{!! Form::close() !!}

<!-- MODAL PILIH PRODUK -->
  <!---div class="modal " id="myModal{{$model->id}}" role="dialog" data-backdrop="">
    <div class="modal-dialog">
    
      <!-- Modal content
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Detail Item Keluar</h4>
        </div>
        <div class="modal-body"> 
        <div class="responsive">
           <table class="table table-bordered"> 
            <thead>
                <tr>
                  <th>No Faktur</th>
                  <th>Produk</th>
                  <th>Jumlah Produk</th>
                </tr>
            </thead>
            <tbody> 
                  @foreach($data_detail_item_keluar as $data_detail_item_keluars)
                <tr>
                  <td>{{  $data_detail_item_keluars->no_faktur }}</td>
                  <td>{{  $data_detail_item_keluars->produk->nama_barang }}</td>
                  <td>{{  $data_detail_item_keluars->jumlah_produk }}</td>  
                </tr>
                  @endforeach
            </tbody>
          </table>
        </div>  
        </div>  
        </div>
      </div>
      
    </div>
  </div>
<!-- / MODAL PILIH PRODUK -->
