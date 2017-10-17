@extends('layouts.app')

@section('content')

	<div class="row">
		<div class="col-md-12">
			<ul class="breadcrumb">
				<li><a href="{{ url('/home') }} ">Home</a></li>
				<li><a href="{{ url('/kas_masuk') }}">Kas Masuk</a></li>
				<li class="active">Tambah Kas Masuk</li>
			</ul>
			  <div class="card">
			   	   <div class="card-header card-header-icon" data-background-color="purple">
                       <i class="material-icons">credit_card</i>
                   </div>
                      <div class="card-content">
                         <h4 class="card-title"> Kas Masuk </h4>
                      
					{!! Form::open(['url' => route('kas_masuk.store'),'method' => 'post', 'class'=>'form-horizontal']) !!}
						@include('kas_masuk._form')
					{!! Form::close() !!}
				</div>
			</div>
		</div>
	</div>

@endsection
