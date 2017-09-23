@extends('layouts.app')

@section('content')

		<div class="row">
			<div class="col-md-12">
				<ul class="breadcrumb">
					<li><a href="{{ url('/home') }} ">Home</a></li>
					<li><a href="{{ url('/master_users') }}">User</a></li>
					<li class="active">Edit User</li>
				</ul>

				<div class="panel panel-default">
					<div class="panel-heading">
						<h2 class="panel-title">Edit User</h2>
					</div>

					<div class="panel-body">
						{!! Form::model($user, ['url' => route('user.update', $user->id), 'method' => 'put', 'files'=>'true','class'=>'form-horizontal']) !!}
						@include('user._form')
						{!! Form::close() !!}
					</div>
				</div>
			</div>
		</div>
	
@endsection
	