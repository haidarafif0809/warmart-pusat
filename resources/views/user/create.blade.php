@extends('layouts.app')

@section('content')

	<div class="row">
		<div class="col-md-12">
			<ul class="breadcrumb">
				<li><a href="{{ url('/home') }} ">Home</a></li>
				<li><a href="{{ url('/user') }}">User</a></li>
				<li class="active">Tambah User</li>
			</ul>
			  <div class="card">
			   	   <div class="card-header card-header-icon" data-background-color="purple">
                       <i class="material-icons">account_circle</i>
                   </div>
                      <div class="card-content">
                         <h4 class="card-title"> User </h4>
                      
					{!! Form::open(['url' => route('user.store'),'method' => 'post', 'class'=>'form-horizontal']) !!}
						@include('user._form')
					{!! Form::close() !!}
				</div>
			</div>
		</div>
	</div>

@endsection
