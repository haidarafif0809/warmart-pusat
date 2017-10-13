@extends('layouts.app')

@section('content')

	<div class="row">
		<div class="col-md-12">
			<ul class="breadcrumb">
				<li><a href="{{ url('/home') }} ">Home</a></li>
				<li><a href="{{ url('/kategori_transaksi') }}">Kategori Transaksi</a></li>
				<li class="active">Tambah Kategori Transaksi</li>
			</ul>
			<div class="card">
			   	<div class="card-header card-header-icon" data-background-color="purple">
                	<i class="material-icons">label_outline</i>
                </div>
                    <div class="card-content">
                        <h4 class="card-title"> Kategori Transaksi </h4>                      
							{!! Form::open(['url' => route('kategori_transaksi.store'),'method' => 'post', 'class'=>'form-horizontal']) !!}
								@include('kategori_transaksi._form')
							{!! Form::close() !!}
						</div>
			</div>
		</div>
	</div>

@endsection

@section('scripts')
<script type="text/javascript">
	$(document).ready(function(){
		$("#nama_kategori_transaksi").focus();
	}); 
</script>
@endsection