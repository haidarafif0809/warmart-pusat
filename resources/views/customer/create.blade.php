@extends('layouts.app')

@section('content')
	<div class="row">
		<div class="col-md-12">
			<ul class="breadcrumb">
				<li><a href="{{ url('/home') }} ">Home</a></li>
				<li><a href="{{ url('/customer') }}">Customer</a></li>
				<li class="active">Tambah Customer</li>
			</ul>
			  <div class="card">
			   	   <div class="card-header card-header-icon" data-background-color="purple">
                       <i class="material-icons">person_add</i>
                   </div>
                      <div class="card-content">
                         <h4 class="card-title"> Customer </h4>
                      
					{!! Form::open(['url' => route('customer.store'),'method' => 'post', 'class'=>'form-horizontal']) !!}
						@include('customer._form')
					{!! Form::close() !!}
				</div>
			</div>
		</div>
	</div> 
@endsection


@section('scripts')
<script type="text/javascript">
	$(document).ready(function(){

		var no_telp = $("#no_telp_error").text();
		var email = $("#email_error").text();

		if (no_telp != "") {
			$("#telpon_customer").focus();
		}
		else if (email_error != "") {
			$("#email_customer").focus();
		}
		else{
			$("#nama_customer").focus();
			console.log("1")
		}

	}); 
</script>
@endsection