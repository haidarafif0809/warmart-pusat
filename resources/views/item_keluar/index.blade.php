@extends('layouts.app')
@section('content')
	<div class="row">
		<div class="col-md-12">
			<ul class="breadcrumb">
				<li><a href="{{ url('/home') }}">Home</a></li>
				<li style="color: purple">Persediaan</li>
				<li class="active">Item Keluar</li>
			</ul>
 
			
			   <div class="card">
			   	   <div class="card-header card-header-icon" data-background-color="purple">
                       <i class="material-icons">vertical_align_top</i>
                                </div>
                      <div class="card-content">
                         <h4 class="card-title"> Item Keluar </h4>
					
                       <div class="toolbar">
                         		<p> <a class="btn btn-primary" href="{{ route('item-keluar.create') }}" id="btnTambahItemKeluar"><i class="material-icons">add</i> Item Keluar</a> </p>
                         </div>
			
					<div class="table-responsive">
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
	// confirm delete
		$(document.body).on('submit', '.js-confirm', function () {
		var $el = $(this)
		var text = $el.data('confirm') ? $el.data('confirm') : 'Anda yakin melakukan tindakan ini\
	?'
		var c = confirm(text);
		return c;
	}); 
</script>
@endsection
