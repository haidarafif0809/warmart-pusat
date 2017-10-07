@extends('layouts.app')

@section('content')
	<div class="row">
		<div class="col-md-12">
			<ul class="breadcrumb">
				<li><a href="{{ url('/home') }} ">Home</a></li>
				<li><a href="{{ url('/warung') }}">Warung</a></li>
				<li class="active">Tambah Warung</li>
			</ul>
			  <div class="card">
			   	   <div class="card-header card-header-icon" data-background-color="purple">
                       <i class="material-icons">store</i>
                   </div>
                      <div class="card-content">
                         <h4 class="card-title"> Warung </h4>
                      
					{!! Form::open(['url' => route('warung.store'),'method' => 'post', 'class'=>'form-horizontal']) !!}
						@include('warung._form')
					{!! Form::close() !!}
				</div>
			</div>
		</div>
	</div>
@endsection


@section('scripts')
<script type="text/javascript">
	$(document).ready(function(){

		var nama_warung = $("#nama_warung_error").text();
		var no_telpon = $("#no_telp_error").text();
		var alamat_warung = $("#alamat_warung_error").text();
		var nama_bank = $("#nama_bank_error").text();
		var atas_nama = $("#atas_nama_error").text();
		var no_rek = $("#no_rek_error").text();

		if (nama_warung != "") {
			$("#nama_warung").focus();
		}
		else if (no_telpon != "") {
			$("#no_telpon").focus();
		}
		else if(alamat_warung != ""){
			$("#alamat_warung").focus();
		}
		else if (nama_bank != "") {
			$("#nama_bank").focus();
		}
		else if (atas_nama != "") {
			$("#atas_nama").focus();
		}
		else if(no_rek != ""){
			$("#no_rek").focus();
		}
		else{
			$("#nama_warung").focus();
		}
	}); 
</script>
@endsection