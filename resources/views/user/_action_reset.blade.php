@if(isset($reset_password_user) && $reset_password_user == TRUE)	
	{!! Form::model($model, ['url' => $reset_url, 'method' => 'get','class'=>'form-inline js-confirm','data-confirm' =>$confirm_message]) !!} 
		{!! Form::submit('Reset Password',['class'=>'btn btn-sm btn-info js-confirm', 'id'=> $id_reset]) !!}
	{!! Form::close() !!} 
@endif