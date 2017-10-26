@extends('layouts.app')

@section('content')

<!--FORM EDIT JUMLAH TBS ITEM KELUAR-->
	{!! Form::open(['url' => route('item-keluar.edit_jumlah_tbs_item_keluar'),'method' => 'post', 'id'=>'form-edit-jumlah']) !!}
		<input type="hidden" name="id_tbs_item_keluar" id="id_produk_edit_jumlah"  >
		<input type="hidden" name="jumlah_keluar" id="jumlah_keluar"  >
	{!! Form::close() !!}

<!-- MODAL TOMBOL SELESAI -->
<div class="modal" id="modal_selesai" role="dialog" data-backdrop="">
    <div class="modal-dialog">
    <!-- Modal content-->
	    <div class="modal-content">
	        <div class="modal-header">
	        	<button type="button" class="close" data-dismiss="modal">&times;</button>
	          		<h4 class="modal-title">
						<div class="alert-icon">
							<i class="material-icons">info_outline</i> <b>Anda Yakin Ingin Menyelesaikan Transaksi Ini ?</b>
						</div>
					</h4>
	        </div>

	        {!! Form::open(['url' => route('item-keluar.store'),'method' => 'post', 'class'=>'form-horizontal']) !!}
		        <div class="modal-body">
		        	<textarea class="form-control" name="keterangan" id="keterangan" placeholder="Keterangan" rows="5"></textarea>
		        </div>
		        <div class="modal-footer"> 
		    		<button type="submit"  id="btn-simpan-item-keluar" class="btn btn-success"><i class="material-icons">save</i> Simpan</button>
		    		<button type="button" class="btn btn-default" data-dismiss="modal"><i class="material-icons">close</i> Close</button>
		        </div>
		    {!! Form::close() !!}
	    </div>      
    </div>
</div>
<!-- / MODAL TOMBOL SELESAI -->

<div class="row">
	<div class="col-md-12">
		<ul class="breadcrumb">
			<li><a href="{{ url('/home') }} ">Home</a></li>
			<li style="color: purple">Persediaan</li>
			<li><a href="{{ url('/item-keluar') }}">Item Keluar</a></li>
			<li class="active">Tambah Item Keluar</li>
		</ul>

		<div class="card">

			<div class="card-header card-header-icon" data-background-color="purple">
				<i class="material-icons">vertical_align_top</i>
			</div>

			<div class="card-content">
				<h4 class="card-title"> Item Keluar </h4>
					<div class="row">

					<!--COL MD 8-->
						<div class="col-md-8">
							{!! Form::open(['url' => route('item-keluar.proses_tambah_tbs_item_keluar'),'method' => 'post', 'class'=>'form-inline','id' => 'form-produk']) !!}
					          	<div class="col-md-4"><br>
					          		<div class="{{ $errors->has('id_produk') ? ' has-error' : '' }}">
					          			{!! Form::select('id_produk', []+App\Barang::where('status_aktif',1)->select([DB::raw('CONCAT(kode_barang, " - ", nama_barang) AS data_produk'),'id'])->pluck('data_produk','id')->all(), null, ['class'=>'', 'placeholder' => '-- PILIH PRODUK --', 'id'=>'pilih_produk','autofocus' => 'true']) !!}
										{!! $errors->first('id_produk', '<p class="help-block">:message</p>') !!}
									</div>

										{!! Form::hidden('jumlah_produk', null, ['class'=>'form-control','placeholder'=>'Jumlah Produk','required','autocomplete'=>'off', 'id'=>'jumlah_produk']) !!}
										{!! $errors->first('jumlah_produk', '<p class="help-block" id="eror_jumlah_produk">:message</p>') !!}
					          	</div>
							
						 	{!! Form::close() !!}
						</div>
					<!--/COL MD 8-->

						<div class="col-md-1"></div>

						<div class="col-md-3">
					 		{!! Form::open(['url' => route('item-keluar.proses_hapus_semua_tbs_item_keluar'),'method' => 'post', 'class' => 'form-group js-confirm', 'data-confirm' => 'Apakah Anda Ingin Membatalkan Item Keluar ?']) !!}
						 	<!--- TOMBOL SELESAI -->
							 		<button type="button" class="btn btn-primary" id="btnSelesai" data-toggle="modal" data-target="#modal_selesai"><i class="material-icons">send</i> Selesai (F2)</button>

							 		<button type="submit" class="btn btn-danger" id="btnBatal"><i class="material-icons">cancel</i> Batal (F3)</button>
					 		{!! Form::close() !!}
						</div>

					</div>


					<!--TABEL TBS ITEM 	KELUAR -->
					<div class="table-responsive">
						{!! $html->table(['class'=>'table-striped table']) !!}
					</div>
					<p style="color: red; font-style: italic;">*Note : Klik Kolom Jumlah Untuk Mengubah Jumlah Item Keluar.</p>

			</div><!-- / PANEL BODY -->

		</div>
	</div>
</div>
@endsection

@section('scripts')

{!! $html->scripts() !!}

	<script type="text/javascript">
		$(document).ready(function(){

			var $select = $('#pilih_produk').selectize({
				sortField: 'text'
			});


			$select[0].selectize.focus();

			$select.on('change', function(){

				var produk = $("#pilih_produk").val();
				var jumlah = $("#jumlah_produk").val();

				if (produk == "") {
		 			swal('Oops...','Produk Harus Dipilih Dahulu !','error');
		 			return false;
		 		}
		 		else if(jumlah == ""){
		 			swal({
		 				title: 'Jumlah Produk',
		 				input: 'number',
				  		inputPlaceholder : 'Jumlah Produk',
						type: 'question',
						html:'Berapa Jumlah Yang akan di keluarkan?',
						animation: false,
						showCloseButton: true,
						showCancelButton: true,
						focusConfirm: true,
						confirmButtonText:'<i class="fa fa-thumbs-o-up"></i> Submit',
						confirmButtonAriaLabel: 'Thumbs up, great!',
						cancelButtonText:'<i class="fa fa-thumbs-o-down"> Batal',
						closeOnConfirm: true,
						cancelButtonAriaLabel: 'Thumbs down',
						inputAttributes: {
						    'name': 'qty_produk',
						  },
					    inputValidator : function (value) {
					    	return new Promise(function (resolve, reject) {

					    		if (value) {
					    			resolve();
					    		}
					    		else {
					    			reject('Jumlah Harus Di isi!');
					    		}
						    });
						}
					}).then(function (jumlah) {

						if (jumlah != "0")  {
							$("#jumlah_produk").val(jumlah);
							$("#form-produk").submit();
						}
						else {
							swal(
							  'Oops...',
							  'Jumlah Tidak Boleh 0 !',
							  'error'
							);
							return false;
						}
						
					});

					return false;
		 		}

		 		else if (jumlah != "" && produk != ""){
		 			return true;
		 		}

		 	}); 


		});
	</script>

	<script type="text/javascript">
		$(document).on('click', '.edit-jumlah', function () {

			var id_produk = $(this).attr('data-id');

			swal({
				title: 'Jumlah Item Keluar',
				input: 'number',
				inputPlaceholder : 'Jumlah Item Keluar',				
				type: 'question',
				html:'Berapa Jumlah Item Baru Yang Akan Dimasukkan ?',
				animation: false,
				showCloseButton: true,
				showCancelButton: true,
				focusConfirm: true,
				confirmButtonText: '<i class="fa fa-thumbs-o-up"></i> Submit',
				confirmButtonAriaLabel: 'Thumbs up, great!',
				cancelButtonText: '<i class="fa fa-thumbs-o-down">Batal',
				closeOnConfirm: true,
				cancelButtonAriaLabel: 'Thumbs down',
				inputAttributes: {
						'name': 'edit_qty_produk',
					},
				inputValidator : function (value) {
					return new Promise(function (resolve, reject) {
						if (value) {
				        	resolve();
					    } 
					    else {
					    	reject('Jumlah Harus Di Isi!');
					    }
					})
				}
			}).then(function (jumlah_keluar) {

				if (jumlah_keluar != "0") {
					$("#jumlah_keluar").val(jumlah_keluar);
					$("#id_produk_edit_jumlah").val(id_produk);
					$("#form-edit-jumlah").submit();
				}
				else {
					swal('Oops...', 'Jumlah Tidak Boleh 0 !', 'error');
					return false;
				}
					
			});

		});
	</script>

	<script type="text/javascript">

		$(document.body).on('submit', '.js-confirm', function () {
			var $btnHapus = $(this);
			var text = $btnHapus.data('confirm') ? $btnHapus.data('confirm') : 'Anda yakin melakukan tindakan ini ?';
			var pesan_konfirmasi = confirm(text);
			return pesan_konfirmasi;
		});  
	</script>

	<script type="text/javascript">
		$(document).ready(function(){

			var pesan_error = $("#eror_jumlah_produk").text();

			if (pesan_error != "") {				
				$("#modal_produk").modal('show');
				$("#jumlah_produk").focus();
			}
			else{
				$("#modal_produk").modal('hide');
			}
		});	
	</script>


 	<script type="text/javascript">    

	 	shortcut.add("f2", function() {
	        $("#btnSelesai").click();
	        $("#keterangan").focus();
	    });
	    

	 	shortcut.add("f3", function() {
	        $("#btnBatal").click();
	    });
 	</script>

 	<script type="text/javascript">
 		$(document).on("click", "#btnSelesai", function(){
 			$("#keterangan").focus();
 		});
 	</script>
@endsection