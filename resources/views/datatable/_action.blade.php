{!! Form::model($model, ['url' => $form_url, 'method' => 'delete', 'class' => 'form-inline js-confirm', 'data-confirm' => $confirm_message]) !!}

	@if(isset($permission_otoritas) && $permission_otoritas == TRUE)
		<a href="{{ $permission_url }}" class="btn btn-sm btn-warning">Permission</a>
	@endif 

	@if(isset($permission_ubah) && $permission_ubah == TRUE)
		<a href="{{ $edit_url }}" class="btn btn-sm btn-success">Ubah</a> |
	@endif

	@if(isset($permission_hapus) && $permission_hapus == TRUE)
		{!! Form::submit('Hapus',['class'=>'btn btn-sm btn-danger js-confirm']) !!}
{!! Form::close() !!}
	@endif

