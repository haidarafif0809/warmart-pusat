@extends('layouts.app')

@section('content')

		<div class="row">
			<div class="col-md-12">
				<ul class="breadcrumb">
					<li><a href="{{ url('/home') }} ">Home</a></li>
					<li><a href="{{ url('/otoritas') }}">Otoritas</a></li>
					<li class="active">Edit Otoritas</li>
				</ul>

		 <div class="card">
			   	   <div class="card-header card-header-icon" data-background-color="purple">
                       <i class="material-icons">settings</i>
                                </div>
                      <div class="card-content">
                         <h4 class="card-title"> Otoritas </h4>
                      
						{!! Form::model($otoritas, ['url' => route('otoritas.update', $otoritas->id), 'method' => 'put', 'files'=>'true','class'=>'form-horizontal']) !!}
							@include('otoritas._form')
						{!! Form::close() !!}
					</div>
				</div>
			</div>
		</div>
@endsection
	