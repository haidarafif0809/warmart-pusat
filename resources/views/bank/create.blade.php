@extends('layouts.app')

@section('content')

	<div class="row">
		<div class="col-md-12">
			<ul class="breadcrumb">
				<li><a href="{{ url('/home') }} ">Home</a></li>
				<li><a href="{{ url('/bank') }}">Bank</a></li>
				<li class="active">Tambah Bank</li>
			</ul>
			<div class="panel panel-default">
				<div class="panel-heading">
					<h2 class="panel-title">Tambah Bank</h2>
				</div>

				<div class="panel-body">
					{!! Form::open(['url' => route('bank.store'),'method' => 'post', 'class'=>'form-horizontal']) !!}
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