@extends('layouts.app')
@section('content')
	<div class="row">
		<div class="col-md-12">
			<ul class="breadcrumb">
				<li><a href="{{ url('/home') }}">Home</a></li>
					<li><a href="{{ url('/barang') }}">Produk</a></li>
					<li class="active">Deskripsi Produk</li>
				</ul>

				<div class="card">
					<div class="card-header card-header-icon" data-background-color="purple">
                       <i class="material-icons">description</i>
                   	</div>
                   	<div class="card-content">
                   		<h4 class="card-title"> Deskripsi Produk </h4>
                   		{!! Form::open(['url' => route('barang.update_deskripsi_produk'),'method' => 'post', 'class'=>'form-horizontal']) !!}
	                   		<div class="form-group{{ $errors->has('deskripsi_produk') ? ' has-error' : '' }}">
									{!! Form::textarea('deskripsi_produk', $deskripsi_produk, ['class' => 'form-control deskripsi-produk']) !!}
									{!! $errors->first('deskripsi_produk', '<p class="help-block">:message</p>') !!}
							</div>

							{!! Form::hidden('id', $id, ['class'=>'form-control','required','autocomplete'=>'off', 'id' =>'id_produk']) !!}
							{!! Form::button('<i class="material-icons">save</i> Simpan', ['class'=>'btn btn-success', 'id'=>'btnSimpanDeskripsi', 'type'=>'submit']) !!}

							<a href="{{ route('barang.lihat_deskripsi_produk', $id) }}" type="button" class="btn btn-info"><i class="material-icons">remove_red_eye</i> Lihat</a>
						{!! Form::close() !!}
                   	</div>
				</div>
		</div>
	</div>
@endsection
@section('scripts')
	<script type="text/javascript">
		CKEDITOR.replace('deskripsi_produk');
	</script>
@endsection
