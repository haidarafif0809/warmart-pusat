@extends('layouts.app')

@section('content')

	<div class="row">
		<div class="col-md-12">
			<ul class="breadcrumb">
				<li><a href="{{ url('/home') }} ">Home</a></li>
				<li><a href="{{ url('/kategori-harga') }}">Kategori harga</a></li>
				<li class="active">Tambah Kategori harga</li>
			</ul>
			<div class="panel panel-default">
				<div class="panel-heading">
					<h2 class="panel-title">Tambah Kategori harga</h2>
				</div>

				<div class="panel-body">
					{!! Form::open(['url' => route('kategori-harga.store'),'method' => 'post', 'class'=>'form-horizontal']) !!}
						@include('kategori_harga._form')
					{!! Form::close() !!}
				</div>
			</div>
		</div>
	</div>

@endsection

@section('scripts')
<script type="text/javascript">
	$(document).ready(function(){

		var nama_kategori_harga = $("#nama_kategori_harga_error").text(); ;

		if (nama_kategori_harga != "") {
			$("#nama_kategori_harga").focus();
		} 
	}); 
</script>
@endsection