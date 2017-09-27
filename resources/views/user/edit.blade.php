@extends('layouts.app')

@section('content')

		<div class="row">
			<div class="col-md-12">
				<ul class="breadcrumb">
					<li><a href="{{ url('/home') }} ">Home</a></li>
					<li><a href="{{ url('/user') }}">User</a></li>
					<li class="active">Edit User</li>
				</ul>

		 <div class="card">
			   	   <div class="card-header card-header-icon" data-background-color="purple">
                       <i class="material-icons">account_circle</i>
                                </div>
                      <div class="card-content">
                         <h4 class="card-title"> User </h4>
                      
						{!! Form::model($user, ['url' => route('user.update', $user->id), 'method' => 'put', 'files'=>'true','class'=>'form-horizontal']) !!}
							@include('user._form')
						{!! Form::close() !!}
					</div>
				</div>
			</div>
		</div>
@endsection
	