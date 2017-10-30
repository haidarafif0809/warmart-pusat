@extends('layouts.app')
@section('content')

<div class="row">
	<div class="col-md-12">  
		<div class="card"> 
			<div class="card-content"> 
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
@endsection
