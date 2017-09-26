@extends('layouts.app')

@section('content')

		<div class="row">
			<div class="col-md-12">
				<ul class="breadcrumb">
					<li><a href="{{ url('/home') }} ">Home</a></li>
					<li><a href="{{ url('/warung') }}">Warung</a></li>
					<li class="active">Edit Warung</li>
				</ul>

		 <div class="card">
			   	   <div class="card-header card-header-icon" data-background-color="purple">
                       <i class="material-icons">store</i>
                                </div>
                      <div class="card-content">
                         <h4 class="card-title"> Warung </h4>
                      
						{!! Form::model($warung, ['url' => route('warung.update', $warung->id), 'method' => 'put', 'files'=>'true','class'=>'form-horizontal']) !!}
							@include('master_warung._form')
						{!! Form::close() !!}
					</div>
				</div>
			</div>
		</div>
@endsection
	