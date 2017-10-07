@extends('layouts.app')

@section('content')
	<div class="row">
		<div class="col-md-12">
			<ul class="breadcrumb">
				<li><a href="{{ url('/home') }} ">Home</a></li>
				<li><a href="{{ url('/user_warung') }}">User Warung</a></li>
				<li class="active">Tambah Warung</li>
			</ul>
			  <div class="card">
			   	   <div class="card-header card-header-icon" data-background-color="purple">
                       <i class="material-icons">account_circle</i>
                       <i class="material-icons">store</i>
                   </div>
                      <div class="card-content">
                         <h4 class="card-title"> User Warung </h4>
                      
					{!! Form::open(['url' => route('user_warung.store'),'method' => 'post', 'class'=>'form-horizontal']) !!}
						@include('user_warung._form')
					{!! Form::close() !!}
				</div>
			</div>
		</div>
	</div>
@endsection