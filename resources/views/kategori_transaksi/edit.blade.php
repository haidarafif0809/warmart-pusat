@extends('layouts.app')

@section('content')
	@if($user_warung == $kategori_transaksi->id_warung)
			<div class="row">
				<div class="col-md-12">
					<ul class="breadcrumb">
						<li><a href="{{ url('/home') }} ">Home</a></li>
						<li><a href="{{ url('/kategori_transaksi') }}">Kategori Transaksi</a></li>
						<li class="active">Edit Kategori Transaksi</li>
					</ul>

			 		<div class="card">
				   	   	<div class="card-header card-header-icon" data-background-color="purple">
	                       <i class="material-icons">local_offer</i>
	                   	</div>
	                    <div class="card-content">

	                    	<h4 class="card-title"> Kategori Transaksi </h4>
	                    	{!! Form::model($kategori_transaksi, ['url' => route('kategori_transaksi.update', $kategori_transaksi->id), 'method' => 'put', 'files'=>'true','class'=>'form-horizontal']) !!}
								@include('kategori_transaksi._form')
							{!! Form::close() !!}
						</div>
					</div>
				</div>
			</div>
	@else
		@include('error.403')
	@endif
@endsection

@section('scripts')
<script type="text/javascript">
	$(document).ready(function(){
		$("#nama_kategori_transaksi").focus();
	}); 
</script>
@endsection