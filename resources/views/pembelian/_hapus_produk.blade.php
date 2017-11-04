{!! Form::model($model, ['url' => $form_url, 'method' => 'delete', 'class' => 'form-inline js-confirm form-hapus-tbs', 'data-confirm' => $confirm_message]) !!} 
	{!! Form::submit('Hapus',['class'=>'btn btn-sm btn-danger js-confirm btn-hapus-tbs']) !!}
{!! Form::close() !!}