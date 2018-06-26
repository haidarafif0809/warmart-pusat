@extends('layouts.app')

@section('content')
	<div class="row">
		<div class="col-md-12">
			<ul class="breadcrumb">
				<li><a href="{{ url('/home') }} ">Home</a></li>
				<li><a href="{{ url('/kas') }}">Kas</a></li>
				<li class="active">Tambah Kas</li>
			</ul>
			  <div class="card">
			   	   <div class="card-header card-header-icon" data-background-color="purple">
                       <i class="material-icons">payment</i>
                   </div>
                      <div class="card-content">
                         <h4 class="card-title"> Kas </h4>
                      
					{!! Form::open(['url' => route('kas.store'),'method' => 'post', 'class'=>'form-horizontal']) !!}
						@include('kas._form')
					{!! Form::close() !!}
				</div>
			</div>
		</div>
	</div>
@endsection


@section('scripts')
<script type="text/javascript">
	$(document).ready(function(){

		var kode_kas = $("#kode_kas_error").text();
		var nama_kas = $("#nama_kas_error").text();

		if (kode_kas != "") {
			$("#kode_kas").focus();
		}
		else if (nama_kas != "") {
			$("#nama_kas").focus();
		}
		else{
			$("#kode_kas").focus();
		}

	}); 
</script>

<script type="text/javascript">
	$(document).ready(function(){
		$(document).on("click", "#default_kas", function(){
			var data_toogle = $(this).attr("data_toogle");
			if ( data_toogle == 1) {
				var pesan_alert = confirm('Apakah Anda Yakin Ingin Mengubah Kas Utama ?');
				
				if (pesan_alert == true) {
					$("#default_kas").prop('checked', true);
					$(this).attr("data_toogle", 0);
				}
				else{
					$("#default_kas").prop('checked', false);
					$(this).attr("data_toogle", 1);
				}
			}
			else{
				$(this).attr("data_toogle", 1);
			}
		});
	}); 
</script>
@endsection