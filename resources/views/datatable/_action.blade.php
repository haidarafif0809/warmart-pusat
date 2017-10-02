{!! Form::model($model, ['url' => $form_url, 'method' => 'delete', 'class' => 'form-inline js-confirm', 'data-confirm' => $confirm_message]) !!}

<a href="{{ $edit_url }}" class="btn btn-sm btn-success">Ubah</a> |

{!! Form::submit('Hapus',['class'=>'btn btn-sm btn-danger js-confirm', 'id'=> 'btn-hapus']) !!}
{!! Form::close() !!}