@extends('layouts.app')

@section('content')
	
		<div class="row">
			<div class="col-md-12">
				<ul class="breadcrumb">
					<li><a href="{{ url('/home') }} ">Home</a></li>
					<li><a href="{{ url('/customer') }}">Customer</a></li>
					<li class="active">Edit Customer</li>
				</ul>

				<div class="panel panel-default">
					<div class="panel-heading">
						<h2 class="panel-title">Edit Customer</h2>
					</div>

					<div class="panel-body">
						{!! Form::model($customer, ['url' => route('customer.update', $customer->id), 'method' => 'put', 'files'=>'true','class'=>'form-horizontal']) !!}
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
		}
	}); 
</script>
@endsection