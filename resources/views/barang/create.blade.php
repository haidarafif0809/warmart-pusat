@extends('layouts.app')

@section('content')
	<div class="row">
		<div class="col-md-12">
			<ul class="breadcrumb">
				<li><a href="{{ url('/home') }} ">Home</a></li>
				<li><a href="{{ url('/barang') }}">Produk</a></li>
				<li class="active">Tambah Produk</li>
			</ul>
			  <div class="card">
			   	   <div class="card-header card-header-icon" data-background-color="purple">
                       <i class="material-icons">dns</i>
                   </div>
                      <div class="card-content">
                         <h4 class="card-title"> Produk </h4>
                      
					{!! Form::open(['url' => route('barang.store'),'method' => 'post', 'files'=>'true','class'=>'form-horizontal']) !!}
						@include('barang._form')
					{!! Form::close() !!}
				</div>
			</div>
		</div>
	</div>
@endsection

@section('scripts')


<script type="text/javascript">
	
$('select').selectize({
 sortField: 'text'
});
</script>
 
<script type="text/javascript"> 
 
    $(document).ready(function(){ 

		$('#hitung_stok').change(function() {

			if(this.checked){
					            
				$("#label_hitung_stok").text("Ya");

			}else{

				$("#label_hitung_stok").text("Tidak");

			}
		               
    	}); 

    	$('#status_aktif').change(function() {

			if(this.checked){
					            
				$("#label_status_aktif").text("Ya");

			}else{

				$("#label_status_aktif").text("Tidak");
				
			}
		               
    	}); 
    	      
    }); 
             
</script> 
@endsection 

