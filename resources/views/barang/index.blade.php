@extends('layouts.app')
@section('content')

	<div class="row">
		<div class="col-md-12">
			<ul class="breadcrumb">
				<li><a href="{{ url('/home') }}">Home</a></li>
				<li class="active">Produk</li>
			</ul>
 
			
			   <div class="card">
			   	   <div class="card-header card-header-icon" data-background-color="purple">
                       <i class="material-icons">dns</i>
                                </div>
                      <div class="card-content">
                         <h4 class="card-title"> Produk </h4>
                       <div class="toolbar">
                         	<p> <a class="btn btn-primary" href="{{ route('barang.create') }}"><i class="material-icons">add</i> Tambah Produk</a> </p>
                         </div>
					<div class="table-responsive material-datatables">
						{!! $html->table(['class'=>'table-striped table']) !!}
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('scripts')
{!! $html->scripts() !!}
<script type="text/javascript">
		$(document.body).on('submit', '.js-confirm', function () {
		var $el = $(this);
		var text = $el.data('confirm') ? $el.data('confirm') : 'Anda yakin melakukan tindakan ini\
	?';	
		var c = confirm(text);
		return c;
	}); 
</script>
@endsection
