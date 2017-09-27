@extends('layouts.app')

@section('content')

	<div class="row">
		<div class="col-md-12">
			<ul class="breadcrumb">
				<li><a href="{{ url('/home') }} ">Home</a></li>
				<li><a href="{{ url('/kategori-harga') }}">Kategori Harga</a></li>
				<li class="active">Tambah Kategori Harga</li>
			</ul>
			  <div class="card">
			   	   <div class="card-header card-header-icon" data-background-color="purple">
                       <i class="material-icons">monetization_on</i>
                   </div>
                      <div class="card-content">
                         <h4 class="card-title"> Kategori Harga </h4>
                      
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