@extends('layouts.app')

@section('content') 
		<div class="row">
			<div class="col-md-12">
				<ul class="breadcrumb">
					<li><a href="{{ url('/home') }} ">Home</a></li>
					<li><a href="{{ url('/kas_masuk') }}">Kas Masuk</a></li>
					<li class="active">Edit Kas Masuk</li>
				</ul>

		 <div class="card">
			   	   <div class="card-header card-header-icon" data-background-color="purple">
                       <i class="material-icons">credit_card</i>
                                </div>
                      <div class="card-content">
                         <h4 class="card-title"> Kas Masuk </h4>
                      
						{!! Form::model($kas_masuk, ['url' => route('kas_masuk.update', $kas_masuk->id), 'method' => 'put', 'files'=>'true','class'=>'form-horizontal']) !!}
							@include('kas_masuk._form')
						{!! Form::close() !!}
					</div>
				</div>
			</div>
		</div> 
@endsection 
@section('scripts')

<script type="text/javascript">	
$('select').selectize({
 sortField: 'text'
});
</script>
@endsection