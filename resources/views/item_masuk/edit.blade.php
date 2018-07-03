@extends('layouts.app')

@section('content')

{!! Form::open(['url' => route('item-masuk.edit_jumlah_edit'),'method' => 'post', 'id'=>'form-edit-jumlah']) !!}
<input type="hidden" name="id_edit_tbs_item_masuk" id="id_produk_edit_jumlah"  >
<input type="hidden" name="jumlah_beli_baru" id="jumlah_beli_baru"  >
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

			{!! Form::open(['url' => route('item-masuk.proses_edit_item_masuk',$item_masuk->id),'method' => 'post', 'class'=>'form-horizontal']) !!}
			<div class="modal-body">
				<textarea class="form-control" name="keterangan" id="keterangan" placeholder="Keterangan" rows="5">{{ $item_masuk->keterangan }}</textarea>
			</div>
			<div class="modal-footer"> 
				<button type="submit" class="btn btn-success" id="btn-simpan-item-masuk"><i class="material-icons">save</i> Simpan</button>
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
			<li style="color: purple">Persedian</li>
			<li><a href="{{ url('/item-masuk') }}">Item Masuk</a></li>
			<li class="active">Edit Item Masuk</li>
		</ul>

		<div class="card">
			<div class="card-header card-header-icon" data-background-color="purple">
				<i class="material-icons">vertical_align_bottom</i>
			</div>
			<div class="card-content">
				<h4 class="card-title">Edit Item Masuk : <b>{{$item_masuk->no_faktur}}</b> </h4>
				<div class="row">

					<div class="col-md-8">
						{!! Form::open(['url' => route('item-masuk.proses_tambah_tbs_item_masuk'),'method' => 'post', 'class'=>'form-inline','id' => 'form-produk']) !!}
						<div class="col-md-4"><br>
							<div class="{{ $errors->has('id_produk') ? ' has-error' : '' }}">
								{!! Form::select('id_produk', []+App\Barang::where('id_warung',Auth::user()->id_warung)->where('status_aktif',1)->select([DB::raw('CONCAT(kode_barang, " - ", nama_barang) AS data_produk'),DB::raw('CONCAT(id, "-", nama_barang) AS id')])->pluck('data_produk','id')->all(), null, ['class'=>'', 'placeholder' => '-- PILIH PRODUK --', 'id'=>'pilih_produk','autofocus' => 'true']) !!}
								{!! $errors->first('id_produk', '<p class="help-block">:message</p>') !!}
							</div>

							{!! Form::hidden('jumlah_produk', null, ['class'=>'form-control','placeholder'=>'Jumlah Produk','required','autocomplete'=>'off', 'id'=>'jumlah_produk']) !!}
							{!! $errors->first('jumlah_produk', '<p class="help-block" id="eror_jumlah_produk">:message</p>') !!}

							{!! Form::hidden('id_produk_tbs', null, ['class'=>'form-control','placeholder'=>'Jumlah Produk','required','autocomplete'=>'off', 'id'=>'id_produk_tbs']) !!} 
							{!! $errors->first('id_produk_tbs', '<p class="help-block" id="eror_id_produk_tbs">:message</p>') !!} 
						</div>
						
						{!! Form::close() !!}
					</div>
					<div class="col-md-1"></div>
					<div class="col-md-3">
						<!-- TOMBOL BATAL -->
						{!! Form::open(['url' => route('item-masuk.proses_hapus_semua_edit_tbs_item_masuk',$item_masuk->id),'method' => 'post', 'class' => 'form-group js-confirm', 'data-confirm' => 'Apakah Anda Ingin Membatalkan Item masuk ?']) !!} 						       		
						<!--- TOMBOL SELESAI -->
						<button type="button" class="btn btn-primary" id="btnSelesai" data-toggle="modal" data-target="#modal_selesai"><i class="material-icons">send</i> Selesai (F8)</button>

						<button type="submit" class="btn btn-danger" id="btnBatal"><i class="material-icons">cancel</i> Batal (F10)</button>

						{!! Form::close() !!}
					</div>

					<!--TOMBOL SELESAI & BATAL -->
					<div class="col-md-4">
						<div class="form-group col-md-3">


						</div>
						<div class="form-group col-md-2">												       			   

						</div>										
					</div>

				</div>
				<!--TABEL TBS ITEM 	MASUK -->
				{!! $html->table(['class'=>'table-striped table']) !!} 

			</div>
		</div>
	</div>
</div>

@endsection

@section('scripts')
{!! $html->scripts() !!}

<script type="text/javascript"> 
	$(document).on('click', '.edit-jumlah', function () {
		var id_produk = $(this).attr('data-id');
		var nama_produk = $(this).attr('data-nama');

		swal({
			title: nama_produk,
			input: 'number',
			inputPlaceholder : 'Jumlah Produk',

			type: 'question',
			html:'Jumlah Produk',
			animation: false,
			showCloseButton: true,
			showCancelButton: true,
			focusConfirm: true,
			confirmButtonText:
			'<i class="fa fa-thumbs-up"></i> Submit',
			confirmButtonAriaLabel: 'Thumbs up, great!',
			cancelButtonText:
			'Batal',
			closeOnConfirm: true,

			cancelButtonAriaLabel: 'Thumbs down',
			inputAttributes: {
				'name': 'edit_qty_produk',
			},
			inputValidator : function (value) {
				return new Promise(function (resolve, reject) {
					if (value) {
						resolve();
					} else {

						reject('Jumlah Harus Di isi!');

					}
				});
			}
		}).then(function (jumlah_beli) {

			if (jumlah_beli != "0")  {
				$("#jumlah_beli_baru").val(jumlah_beli);
				$("#id_produk_edit_jumlah").val(id_produk);
				$("#form-edit-jumlah").submit();

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
	});
</script>
<script type="text/javascript">
	$(document).ready(function(){


		var $select = $('#pilih_produk').selectize({
			sortField: 'text'
		}); 
		$select[0].selectize.focus();
		
		$select.on('change', function(){

			var pilih_produk = $("#pilih_produk").val(); 
			var produk = pilih_produk.split("-"); 
			var id_produk = produk[0]; 
			var nama_produk = produk[1]; 
			var jumlah = $("#jumlah_produk").val();

			if (produk == "") {
				swal('Oops...','Produk Harus Dipilih Dahulu !','error');

				return false;

			}
			else if(jumlah == ""){

				swal({
					title: titleCase(nama_produk),
					input: 'number',
					inputPlaceholder : 'Jumlah Produk',

					type: 'question',
					html:'Jumlah Produk',
					animation: false,
					showCloseButton: true,
					showCancelButton: true,
					focusConfirm: true,
					confirmButtonText:
					'<i class="fa fa-thumbs-up"></i> Submit',
					confirmButtonAriaLabel: 'Thumbs up, great!',
					cancelButtonText:
					'Batal',
					closeOnConfirm: true,

					cancelButtonAriaLabel: 'Thumbs down',
					inputValidator : function (value) {
						return new Promise(function (resolve, reject) {
							if (value) {
								resolve();
							} else {

								reject('Jumlah Harus Di isi!');

							}
						});
					}
				}).then(function (jumlah) {

					if (jumlah != "0")  {
						$("#jumlah_produk").val(jumlah);
						$("#id_produk_tbs").val(id_produk); 					
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
				}
				, function (dismiss) {

					if (dismiss === 'cancel') {
						$select[0].selectize.clear()
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
	$(document.body).on('submit', '.js-confirm', function () {
		var $btnHapus = $(this);
		var text = $btnHapus.data('confirm') ? $btnHapus.data('confirm') : 'Anda yakin melakukan tindakan ini ?'
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
	shortcut.add("f1", function() {
		$("#cari_produk").click();
	});

	shortcut.add("f2", function() {
		$("#btnBarcode").click();
	});

	shortcut.add("f8", function() {
		$("#btnSelesai").click();
	});

	shortcut.add("f10", function() {
		$("#btnBatal").click();
	});
</script>  
@endsection