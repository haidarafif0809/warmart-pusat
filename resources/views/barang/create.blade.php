@extends('layouts.app')

@section('content')
	<div class="row">
		<div class="col-md-12">
			<ul class="breadcrumb">
				<li><a href="{{ url('/home') }} ">Home</a></li>
				<li><a href="{{ url('/barang') }}">Barang</a></li>
				<li class="active">Tambah Barang</li>
			</ul>
			  <div class="card">
			   	   <div class="card-header card-header-icon" data-background-color="purple">
                       <i class="material-icons">dns</i>
                   </div>
                      <div class="card-content">
                         <h4 class="card-title"> Barang </h4>
                      
					{!! Form::open(['url' => route('barang.store'),'method' => 'post', 'class'=>'form-horizontal']) !!}
						@include('barang._form')
					{!! Form::close() !!}
				</div>
			</div>
		</div>
	</div>
@endsection
