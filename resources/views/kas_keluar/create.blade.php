@extends('layouts.app')

@section('content')

	<div class="row">
		<div class="col-md-12">
			<ul class="breadcrumb">
				<li><a href="{{ url('/home') }} ">Home</a></li>
				<li><a href="{{ url('/kas_keluar') }}">Kas Keluar</a></li>
				<li class="active">Tambah Kas Keluar</li>
			</ul>
			<div class="card">
			   	<div class="card-header card-header-icon" data-background-color="purple">
                    <i class="material-icons">money_off</i>
                </div>

                <div class="card-content">
                	<h4 class="card-title"> Kas Keluar </h4>
                	{!! Form::open(['url' => route('kas_keluar.store'),'method' => 'post', 'class'=>'form-horizontal']) !!}
						@include('kas_keluar._form')
					{!! Form::close() !!}
				</div>
			</div>
		</div>
	</div>

@endsection

@section('scripts')
<script type="text/javascript">
	$(document).ready(function(){
		$(document).on('change', '#nama_kas', function(){

			var kas = $("#nama_kas").val();

			$.post('{{ route('cek_total_kas')}}',{'_token': $('meta[name=csrf-token]').attr('content'), kas:kas}, function(data){
				$("#total_kas").val(tandaPemisahTitik(data));
			});
		});	
	}); 
</script>

<script type="text/javascript">
	$(document).ready(function(){
		$(document).on('blur', '#jumlah_kas', function(){

			var total_kas = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#total_kas").val()))));
			var jumlah_keluar = $("#jumlah_kas").val();

				if (jumlah_keluar == "") {
					jumlah_keluar = 0;
				};

				if (total_kas == "") {
					total_kas = 0;
				};

			var sisa_kas = parseInt(total_kas, 10) - parseInt(jumlah_keluar);
			if (sisa_kas < 0) {
				alert("Total Kas Tidak Mencukupi !");
				$("#jumlah_kas").val('');
				$("#jumlah_kas").focus();
			}

		});	
	}); 
</script>
@endsection