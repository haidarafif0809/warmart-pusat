@extends('layouts.app')

@section('content')

		<div class="row">
			<div class="col-md-12">
				<ul class="breadcrumb">
					<li><a href="{{ url('/home') }} ">Home</a></li>
					<li><a href="{{ url('/otoritas') }}">Otoritas</a></li>
					<li class="active">Setting  Permisson</li>
				</ul>

		 <div class="card">
			   	   <div class="card-header card-header-icon" data-background-color="purple">
						<h2 class="panel-title"><i class="material-icons">settings</i> Setting  Permisson <b>{{ $otoritas->display_name }}</b></h2>
                   </div>
                      <div class="card-content"> 
                      
						{!! Form::model($otoritas, ['url' => route('otoritas.permission.edit', $otoritas->id), 'method' => 'put', null,'class'=>'form-horizontal']) !!}
						@include('otoritas._form_permission')
						{!! Form::close() !!}
					</div>
				</div>
			</div>
		</div>
@endsection
	