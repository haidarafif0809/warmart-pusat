@extends('layouts.app')

@section('content')
	
		<div class="row">
			<div class="col-md-12">
				<ul class="breadcrumb">
					<li><a href="{{ url('/home') }} ">Home</a></li>
					<li><a href="{{ url('/bank') }}">Bank</a></li>
					<li class="active">Edit Bank</li>
				</ul>

		 <div class="card">
			   	   <div class="card-header card-header-icon" data-background-color="purple">
                       <i class="material-icons">payment</i>
                                </div>
                      <div class="card-content">
                         <h4 class="card-title"> Bank </h4>
                      
						{!! Form::model($bank, ['url' => route('bank.update', $bank->id), 'method' => 'put', 'files'=>'true','class'=>'form-horizontal']) !!}
							@include('bank._form')
						{!! Form::close() !!}
					</div>
				</div>
			</div>
		</div>
	
@endsection

@section('scripts')
<script type="text/javascript">
	$(document).ready(function(){

		var nama_bank = $("#nama_bank_error").text();
		var atas_nama = $("#atas_nama_error").text();
		var no_rek = $("#no_rek_error").text();

		if (nama_bank != "") {
			$("#nama_bank").focus();
		}
		else if (atas_nama != "") {
			$("#atas_nama").focus();
		}
		else if(no_rek != ""){
			$("#no_rek").focus();
		}
		else{
			$("#nama_bank").focus();
		}
	}); 
</script>
@endsection