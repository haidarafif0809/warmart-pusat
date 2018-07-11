@extends('layouts.app') 
@section('content') 
<div class="row"> 
	<div class="col-md-12"> 
		<ul class="breadcrumb"> 
			<li><a href="{{ url('/home') }}">Home</a></li> 
			<li class="active">Pembelian</li> 
		</ul> 
		
		
		<div class="card"> 
			<div class="card-header card-header-icon" data-background-color="purple"> 
				<i class="material-icons">add_shopping_cart</i> 
			</div> 
			<div class="card-content"> 
				<h4 class="card-title"> Pembelian </h4> 
				
				<div class="toolbar"> 
					<p> <a class="btn btn-primary" href="{{ route('pembelian.create') }}" id="btnTambahPembelian"><i class="material-icons">add</i> Tambah Pembelian</a> </p> 
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
	$(document.body).on('submit', '.js-confirm', function () { 
		var $el = $(this); 
		var text = $el.data('confirm') ? $el.data('confirm') : 'Anda yakin melakukan tindakan ini ?'; 
		var c = confirm(text); 
		return c; 
	});  
</script> 

<script> 
	$(function() { 
		$(document).on('click','#closeModalFaktur',function(){   
			var id = $(this).attr('data-id');   
			$("#modal-faktur-"+id).hide(); 
		}); 
		$(document).on('click','#detail_faktur_beli',function(){   
			var id = $(this).attr('data-id');  
			var no_faktur = $(this).attr('data-no_faktur'); 
			$("#modal-faktur-"+id).show(); 

			$('#table-faktur-'+id).DataTable().destroy(); 
			$('#table-faktur-'+id).DataTable({ 
				scrollX: true,
				processing: true, 
				serverSide: true, 
				"ajax": { 
					url: '{{ route("datatable_detail_faktur_beli") }}', 
					"data": function ( d ) { 
						d.no_faktur = no_faktur; 
					}, 
					type:'POST', 
					'headers': { 
						'X-CSRF-TOKEN': '{{ csrf_token() }}' 
					}, 
				}, 
				columns: [ 
				{ data: 'status_pembelian', name: 'status_pembelian' }, 
				{ data: 'kas.nama_kas', name: 'kas.nama_kas' , orderable : false, searchable : false}, 
				{ data: 'tanggal_jt_tempo', name: 'tanggal_jt_tempo' }, 
				{ data: 'potongan', name: 'potongan' }, 
				{ data: 'total', name: 'total' }, 
				{ data: 'tunai', name: 'tunai' }, 
				{ data: 'kembalian', name: 'kembalian' }, 
				{ data: 'kredit', name: 'kredit' } 
				] 
			});              
			
		}); 

		$(document).on('click','#closeModalX',function(){   
			var id = $(this).attr('data-id');   
			$("#myModal-"+id).hide(); 
		}); 
		
		$(document).on('click','#btnDetail',function(){ 
			var id = $(this).attr('data-id'); 
			var no_faktur = $(this).attr('data-faktur'); 
			$("#myModal-"+id).show(); 
			
			$('#table-detail-'+id).DataTable().destroy(); 
			$('#table-detail-'+id).DataTable({ 
				scrollX: true,
				processing: true, 
				serverSide: true, 
				"ajax": { 
					url: '{{ route("datatable_detail.pembelian") }}', 
					"data": function ( d ) { 
						d.no_faktur = no_faktur; 
					}, 
					type:'POST', 
					'headers': { 
						'X-CSRF-TOKEN': '{{ csrf_token() }}' 
					}, 
				}, 
				columns: [ 
				{ data: 'no_faktur', name: 'no_faktur' }, 
				{ data: 'produk', name: 'produk', orderable : false, searchable : false }, 
				{ data: 'jumlah_produk', name: 'jumlah_produk' }, 
				{ data: 'harga_produk', name: 'harga_produk' }, 
				{ data: 'potongan', name: 'potongan' }, 
				{ data: 'tax', name: 'tax' }, 
				{ data: 'subtotal', name: 'subtotal' } 
				] 
			});              
			
			
		}); 
		
	}); 
</script> 
@endsection 