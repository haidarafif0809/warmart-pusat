@extends('layouts.app')
@section('content')

	<div class="row">
		<div class="col-md-12">
			<ul class="breadcrumb">
				<li><a href="{{ url('/home') }}">Home</a></li>
				<li class="active">User</li>
			</ul>
 
			
			<div class="panel panel-default">
				<div class="panel-heading">
					<h2 class="panel-title">User</h2>
				</div>

				<div class="panel-body"> 
					<p> <a class="btn btn-primary" href="{{ route('user.create') }}">Tambah User</a> </p>
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
