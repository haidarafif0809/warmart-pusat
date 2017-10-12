@extends('layouts.app')

@section('content')
	<div class="row">
		<div class="col-md-12">
			<ul class="breadcrumb">
				<li><a href="{{ url('/home') }} ">Home</a></li>
				<li><a href="{{ url('/barang') }}">Produk</a></li>
				<li class="active">Tambah Produk</li>
			</ul>
			  <div class="card">
			   	   <div class="card-header card-header-icon" data-background-color="purple">
                       <i class="material-icons">dns</i>
                   </div>
                      <div class="card-content">
                         <h4 class="card-title"> Produk </h4>
                      
					{!! Form::open(['url' => route('barang.store'),'method' => 'post', 'files'=>'true','class'=>'form-horizontal']) !!}
						@include('barang._form')
					{!! Form::close() !!}
				</div>
			</div>
		</div>
	</div>
@endsection
