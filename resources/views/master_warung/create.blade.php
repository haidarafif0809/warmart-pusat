@extends('layouts.app')

@section('content')
	<div class="row">
		<div class="col-md-12">
			<ul class="breadcrumb">
				<li><a href="{{ url('/home') }} ">Home</a></li>
				<li><a href="{{ url('/warung') }}">Warung</a></li>
				<li class="active">Tambah Warung</li>
			</ul>
			  <div class="card">
			   	   <div class="card-header card-header-icon" data-background-color="purple">
                       <i class="material-icons">home</i>
                   </div>
                      <div class="card-content">
                         <h4 class="card-title"> Warung </h4>
                      
					{!! Form::open(['url' => route('warung.store'),'method' => 'post', 'class'=>'form-horizontal']) !!}
						@include('master_warung._form')
					{!! Form::close() !!}
				</div>
			</div>
		</div>
	</div>
@endsection
