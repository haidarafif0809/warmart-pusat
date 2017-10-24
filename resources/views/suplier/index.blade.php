@extends('layouts.app')
@section('content')

	<div class="row">
		<div class="col-md-12">
			<ul class="breadcrumb">
				<li><a href="{{ url('/home') }}">Home</a></li>
				<li class="active">Suplier</li>
			</ul>
 
			
			   <div class="card">
			   	   <div class="card-header card-header-icon" data-background-color="purple">
                       <i class="material-icons">assignment_return</i>
                                </div>
                      <div class="card-content">
                         <h4 class="card-title"> Suplier </h4>
                       <div class="toolbar">
                         	<p> <a class="btn btn-primary" href="{{ route('suplier.create') }}"><i class="material-icons">add</i> Tambah Suplier</a> </p>
                         </div>
					<div class="table-responsive material-datatables">
					{!! $html->table(['class'=>'table table-striped table-no-bordered table-hover','cellspacing' => '0','width' => '100%','style' => 'width:100%']) !!}
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
