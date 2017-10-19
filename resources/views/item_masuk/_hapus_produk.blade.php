<!-- MEMBUAT TOMBOL HAPUS TBS ITEM MASUK -->
{!! Form::model($model, ['url' => $form_url, 'method' => 'post', 'class' => 'form-inline js-confirm form-hapus-tbs', 'data-confirm' => $confirm_message]) !!} 
{!! Form::submit('Hapus',['class'=>'btn btn-sm btn-danger js-confirm btn-hapus-tbs']) !!}
{!! Form::close() !!}