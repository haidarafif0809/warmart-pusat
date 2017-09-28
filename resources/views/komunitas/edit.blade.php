@extends('layouts.app')

@section('content')

		<div class="row">
			<div class="col-md-12">
				<ul class="breadcrumb">
					<li><a href="{{ url('/home') }} ">Home</a></li>
					<li><a href="{{ url('/komunitas') }}">Komunitas</a></li>
					<li class="active">Edit Komunitas</li>
				</ul>

		 <div class="card">
			   	   <div class="card-header card-header-icon" data-background-color="purple">
                       <i class="material-icons">people</i>
                                </div>
                      <div class="card-content">
                         <h4 class="card-title"> Komunitas </h4>
                      
						{!! Form::model($komunitas, ['url' => route('komunitas.update', $komunitas->id), 'method' => 'put', 'files'=>'true','class'=>'form-horizontal']) !!}
							@include('komunitas._form')
						{!! Form::close() !!}
					</div>
				</div>
			</div>
		</div>
@endsection
	