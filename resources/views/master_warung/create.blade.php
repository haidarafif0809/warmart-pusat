@extends('layouts.app')

@section('content')
	<div class="row">
		<div class="col-md-12">
			<ul class="breadcrumb">
				<li><a href="{{ url('/home') }} ">Home</a></li>
				<li><a href="{{ url('/warung') }}">Warung</a></li>
				<li class="active">Tambah Warung</li>
			</ul>
			<div class="panel panel-default">
				<div class="panel-heading">
					<h2 class="panel-title">Tambah Warung</h2>
				</div>

				<div class="panel-body">
					{!! Form::open(['url' => route('warung.store'),'method' => 'POST', 'class'=>'form-horizontal']) !!}
					@include('master_warung._form')
					{!! Form::close() !!}
				</div>
			</div>
		</div>
	</div>
@endsection
