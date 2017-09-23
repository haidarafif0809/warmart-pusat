@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<ul class="breadcrumb">
				<li><a href="{{ url('/home') }} ">Home</a></li>
				<li><a href="{{ url('/admin/master_user') }}">User</a></li>
				<li class="active">Tambah User</li>
			</ul>
			<div class="panel panel-default">
				<div class="panel-heading">
					<h2 class="panel-title">Tambah User</h2>
				</div>

				<div class="card-content">

					{!! Form::open(['url' => route('user.store'),'method' => 'post', 'class'=>'form-horizontal']) !!}
					@include('user._form')
					{!! Form::close() !!}
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
