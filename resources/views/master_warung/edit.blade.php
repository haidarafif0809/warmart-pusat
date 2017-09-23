@extends('layouts.app')

@section('content')
	
		<div class="row">
			<div class="col-md-12">
				<ul class="breadcrumb">
					<li><a href="{{ url('/home') }} ">Home</a></li>
					<li><a href="{{ url('/master_barang') }}">Warung</a></li>
					<li class="active">Edit Warung</li>
				</ul>

				<div class="panel panel-default">
					<div class="panel-heading">
						<h2 class="panel-title">Edit Warung</h2>
					</div>

					<div class="panel-body">
						{!! Form::model($warung, ['url' => route('warung.update', $warung->id), 'method' => 'put', 'files'=>'true','class'=>'form-horizontal']) !!}
						@include('master_warung._form')
						{!! Form::close() !!}
					</div>
				</div>
			</div>
		</div>
	
@endsection