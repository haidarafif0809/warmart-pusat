@extends('layouts.app') 

@section('content') 
<!--FORM EDIT JUMLAH TBS PEMBELIAN--> 
{!! Form::open(['url' => route('editPembelian.edit_jumlah_tbs_pembelian'),'method' => 'post', 'id'=>'form-edit-jumlah']) !!} 
<input type="hidden" name="id_tbs_pembelian" id="id_produk_edit_jumlah" > 
<input type="hidden" name="jumlah_edit_produk" id="jumlah_edit_produk" > 
{!! Form::close() !!} 
<!--FORM EDIT JUMLAH TBS PEMBELIAN--> 

<!--FORM EDIT HARGA TBS PEMBELIAN--> 
{!! Form::open(['url' => route('editPembelian.edit_harga_tbs_pembelian'),'method' => 'post', 'id'=>'form-edit-harga']) !!} 
<input type="hidden" name="id_harga" id="id_produk_edit_harga" > 
<input type="hidden" name="harga_edit_produk" id="harga_edit_produk" > 
{!! Form::close() !!} 
<!--FORM EDIT HARGA TBS PEMBELIAN--> 

<!--FORM EDIT POTONGAN TBS PEMBELIAN--> 
{!! Form::open(['url' => route('editPembelian.edit_potongan_tbs_pembelian'),'method' => 'post', 'id'=>'form-edit-potongan']) !!} 
<input type="hidden" name="id_potongan" id="id_produk_edit_potongan" > 
<input type="hidden" name="potongan_edit_produk" id="potongan_edit_produk" > 
{!! Form::close() !!} 
<!--FORM EDIT POTONGAN TBS PEMBELIAN--> 

<!--FORM EDIT PAJAK TBS PEMBELIAN--> 
{!! Form::open(['url' => route('editPembelian.edit_tax_tbs_pembelian'),'method' => 'post', 'id'=>'form-edit-tax']) !!} 
<input type="hidden" name="id_tax" id="id_produk_edit_tax" placeholder="id"> 
<input type="hidden" name="tax_edit_produk" id="tax_edit_produk" placeholder="tax"> 
<input type="hidden" name="ppn_produk" id="ppn_produk" placeholder="ppn"> 
{!! Form::close() !!} 
<!--FORM EDIT PAJAK TBS PEMBELIAN--> 

<!-- MODAL TOMBOL SELESAI --> 

<div class="modal" id="modal_selesai" role="dialog" data-backdrop=""> 
	<div class="modal-dialog"> 
		<!-- Modal content--> 
		<div class="modal-content"> 
			<div class="modal-header"> 
				<button type="button" class="close" data-dismiss="modal" id="closeModalX">&times;</button> 
				<h4 class="modal-title"> 
					<div class="alert-icon"> 
						<b>Silahkan Lengkapi Pembayaran!</b> 
					</div> 
				</h4> 
			</div> 

			{!! Form::open(['url' => route('editPembelian.prosesEditPembelian'),'method' => 'post', 'class'=>'form-horizontal','id'=>'form_pembelian']) !!} 
			<div class="modal-body"> 
				<div class="row"> 
					<div class="col-md-3"> 
						<h5>Potongan(%)</h5> 
						{!! Form::text('potongan_persen', '', ['class'=>'form-control','autocomplete'=>'off', 'id' =>'potongan_persen','style'=>'height: 40px; width:90%; font-size:20px;']) !!} 
						{!! $errors->first('potongan_persen', '<p class="help-block" id="potongan_error">:message</p>') !!} 
					</div> 
					<div class="col-md-3"> 
						<h5>Potongan</h5> 
						{!! Form::text('potongan_faktur',  number_format($pembelian->potongan,2,',','.'), ['class'=>'form-control','autocomplete'=>'off', 'id' =>'potongan_faktur','style'=>'height: 40px; width:90%; font-size:20px;']) !!} 
						{!! $errors->first('potongan_faktur', '<p class="help-block" id="potongan_error">:message</p>') !!} 
					</div> 
					<div class="col-sm-6"> 
						<h5>Subtotal</h5> 
						{!! Form::text('subtotal', $subtotal_tbs, ['class'=>'form-control','autocomplete'=>'off', 'id' =>'subtotal','style'=>'height: 40px; width:90%; font-size:23px;']) !!} 
						{!! $errors->first('subtotal', '<p class="help-block" id="subtotal_error">:message</p>') !!} 
					</div> 
				</div>  
				<div class="row"> 
					<div class="col-md-6"> 
						<h5><i class="material-icons">info_outline</i> Pembayaran </h5> 
						{!! Form::number('pembayaran', $pembelian->tunai, ['class'=>'form-control','autocomplete'=>'off', 'id'=>'pembayaran','style'=>'height: 40px; width:90%; font-size:25px;','placeholder'=>'Silakan Isi Pembayaran']) !!} 
						{!! $errors->first('pembayaran', '<p class="help-block" id="pembayaran_error">:message</p>') !!} 
					</div> 
					<div class="col-md-6"> 
						<h5>Total Akhir</h5> 
						{!! Form::text('total_akhir', '', ['class'=>'form-control','required','autocomplete'=>'off', 'id'=>'total_akhir','style'=>'height: 40px; width:90%; font-size:25px;', 'readonly'=>'' ]) !!} 
					</div> 
				</div> 

				<div class="row"> 
					<div class="col-md-6"> 
						<h5>Kembalian</h5> 
						{!! Form::text('kembalian', '', ['class'=>'form-control','autocomplete'=>'off', 'id' =>'kembalian','style'=>'height: 40px; width:90%; font-size:25px;', 'readonly'=>'' ]) !!} 
						{!! $errors->first('kembalian', '<p class="help-block" id="kembalian_error">:message</p>') !!} 
					</div> 
					<div class="col-md-6"> 
						<h5>Kredit</h5> 
						{!! Form::text('kredit', '', ['class'=>'form-control','autocomplete'=>'off', 'id' =>'kredit','style'=>'height: 40px; width:90%; font-size:25px;', 'readonly'=>'' ]) !!} 
						{!! $errors->first('kredit', '<p class="help-block" id="kredit_error">:message</p>') !!} 
					</div> 

				</div> 

				<div class="row"> 
					<div class="col-md-6"> 
						<h5>Jatuh Tempo</h5> 
						{!! Form::text('jatuh_tempo', $pembelian->tanggal_jt_tempo, ['class'=>'form-control datepicker','autocomplete'=>'off', 'id' =>'jatuh_tempo','placeholder' => 'Jatuh Tempo']) !!} 
						{!! $errors->first('jatuh_tempo', '<p class="help-block" id="jatuh_tempo_error">:message</p>') !!} 
					</div> 
					<div class="col-md-6"> 
						<h5>Keterangan</h5> 
						<textarea class="form-control" name="keterangan" id="keterangan" placeholder="...." rows="1">{{$pembelian->keterangan}}</textarea> 

					</div> 
				</div> 

				<span style="display: none;"> 
					{!! Form::hidden('suplier_id', $pembelian->suplier_id, ['class'=>'form-control','autocomplete'=>'off', 'id'=>'suplier_id']) !!}					
					{!! Form::hidden('no_faktur_edit', $pembelian->no_faktur, ['class'=>'form-control','required','autocomplete'=>'off', 'id'=>'no_faktur_edit']) !!}  

					@if($kas_default->count() == 0) 
					{!! Form::hidden('id_cara_bayar', $pembelian->cara_bayar, ['class'=>'form-control','autocomplete'=>'off', 'id'=>'id_cara_bayar']) !!} 
					@else 
					{!! Form::hidden('id_cara_bayar', $pembelian->cara_bayar, ['class'=>'form-control','autocomplete'=>'off', 'id'=>'id_cara_bayar']) !!} 
					@endif 
					{!! Form::hidden('status_pembelian', $pembelian->status_pembelian, ['class'=>'form-control','autocomplete'=>'off', 'id'=>'status_pembelian']) !!}   
					{!! Form::hidden('ppn', $pembelian->ppn, ['class'=>'form-control','autocomplete'=>'off', 'id'=>'ppn']) !!} 
					{!! Form::hidden('potongan', $pembelian->potongan, ['class'=>'form-control','autocomplete'=>'off', 'id'=>'potongan']) !!} 
					{!! Form::hidden('id_pembelian', $pembelian->id, ['class'=>'form-control','autocomplete'=>'off', 'id'=>'id_pembelian']) !!} 
				</span> 
			</div> 
			<div class="modal-footer">  
				@if($pembelian->status_pembelian == 'Tunai')
				
				<button type="submit"  id="btn-tunai-pembelian" class="btn btn-success"><i class="material-icons" >credit_card</i> Tunai</button> 
				<button type="submit"  id="btn-hutang-pembelian" class="btn btn-success" style="display: none"><i class="material-icons" >credit_card</i> Hutang</button> 

				@elseif($pembelian->status_pembelian == 'Hutang')
				<button type="submit"  id="btn-tunai-pembelian" class="btn btn-success" style="display: none"><i class="material-icons" >credit_card</i> Tunai</button> 
				<button type="submit"  id="btn-hutang-pembelian" class="btn btn-success"><i class="material-icons" >credit_card</i> Hutang</button> 
				@endif

				<button type="button" class="btn btn-default" data-dismiss="modal" id="btnCloseModal"><i class="material-icons">close</i> Close</button> 
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
			<li><a href="{{ url('/pembelian') }}">Pembelian</a></li> 
			<li class="active">Edit Pembelian </li> 
		</ul> 

		<div class="row"><!-- ROW --> 
			<div class="col-md-8"><!-- COL SM 8 --> 

				<div class="card"><!-- CARD --> 

					<div class="card-header card-header-icon" data-background-color="purple"> 
						<i class="material-icons">add_shopping_cart</i> 
					</div> 

					<div class="card-content"> 
						<h4 class="card-title">Edit Pembelian <b>{{$no_faktur}}</b></h4> 
						<div class="row"> 

							<!--COL MD 8--> 
							<div class="col-md-8"> 
								{!! Form::open(['url' => route('editPembelian.proses_tambah_tbs_pembelian'),'method' => 'post', 'class'=>'form-inline','id' => 'form-produk']) !!} 
								<div class="col-md-4"><br> 
									<div class="{{ $errors->has('id_produk') ? ' has-error' : '' }}"> 
										{!! Form::select('id_produk', []+App\Barang::where('status_aktif',1)->where('id_warung',Auth::user()->id_warung)->select([DB::raw('CONCAT(kode_barang, " - ", nama_barang) AS data_produk'),DB::raw('CONCAT(id, "-", nama_barang,"-",harga_beli) AS id')])->pluck('data_produk','id')->all(), null, ['class'=>'', 'placeholder' => '-- PILIH PRODUK --', 'id'=>'pilih_produk','autofocus' => 'true']) !!} 
										{!! $errors->first('id_produk', '<p class="help-block">:message</p>') !!} 
									</div> 

									{!! Form::hidden('id_produk_tbs', null, ['class'=>'form-control','placeholder'=>'Jumlah Produk','required','autocomplete'=>'off', 'id'=>'id_produk_tbs']) !!} 
									{!! Form::hidden('jumlah_produk', null, ['class'=>'form-control','placeholder'=>'Jumlah Produk','required','autocomplete'=>'off', 'id'=>'jumlah_produk']) !!} 
									{!! Form::hidden('harga_produk', null, ['class'=>'form-control','placeholder'=>'Harga Produk','required','autocomplete'=>'off', 'id'=>'harga_produk']) !!} 
									{!! Form::hidden('no_faktur', $pembelian->no_faktur, ['class'=>'form-control','required','autocomplete'=>'off', 'id'=>'no_faktur']) !!} 
								</div> 

								{!! Form::close() !!} 
							</div><!--/COL MD 8--> 

						</div> 

						<!--TABEL TBS ITEM   KELUAR --> 
						<div class="table-responsive"> 
							{!! $html->table(['class'=>'table-striped table']) !!} 
						</div> 
						<p style="color: red; font-style: italic;">*Note : Klik Kolom Jumlah, Harga, Potongan & Tax Untuk Mengubah Nilai.</p> 

					</div><!-- / PANEL BODY --> 

				</div><!-- CARD --> 

			</div><!-- COL SM 8 --> 

			<div class="col-md-4"><!-- COL SM 4 --> 
				<div class="card"><!-- CARD --> 
					<div class="card-content"> 
						<div class="row"> 
							<div class="col-md-6"> 
								<div class="{{ $errors->has('suplier') ? ' has-error' : '' }}"> 
									<h4>Suplier</h4> 
									{!! Form::select('suplier', []+App\Suplier::where('warung_id',Auth::user()->id_warung)->pluck('nama_suplier','id')->all(), $pembelian->suplier_id, ['class'=>'', 'placeholder' => '-- PILIH SUPLIER --', 'id'=>'pilih_suplier','autofocus' => 'true']) !!} 
									{!! $errors->first('suplier', '<p class="help-block">:message</p>') !!} 
								</div> 
							</div> 

							<div class="col-md-6"> 
								<div class="{{ $errors->has('cara_bayar') ? ' has-error' : '' }}"> 
									<h4>Cara Bayar</h4> 
									@if($kas_default->count() == 0) 

									{!! Form::select('cara_bayar', []+App\Kas::where('warung_id',Auth::user()->id_warung)->where('status_kas',1)->pluck('nama_kas','id')->all(), $pembelian->cara_bayar, ['class'=>'', 'id'=>'cara_bayar','autofocus' => 'true','placeholder' => 'Anda Belum Punya Kas',]) !!} 
									<span class="label label-danger"><a href="{{ route('kas.create') }}" target="blank" >Tambah Kas Disini</a></span> 

									@else 

									{!! Form::select('cara_bayar', []+App\Kas::where('warung_id',Auth::user()->id_warung)->where('status_kas',1)->pluck('nama_kas','id')->all(), $pembelian->cara_bayar, ['class'=>'', 'id'=>'cara_bayar','autofocus' => 'true']) !!} 
									{!! $errors->first('cara_bayar', '<p class="help-block">:message</p>') !!} 

									@endif 
								</div> 
							</div> 
						</div> 

						{!! Form::open(['url' => route('editPembelian.batal_transaksi_pembelian'),'method' => 'post', 'class' => 'form-group js-confirm', 'data-confirm' => 'Apakah Anda Ingin Membatalkan Pembelian ?']) !!} 
						<!--- TOMBOL SELESAI --> 
						<button type="button" class="btn btn-primary" id="btnSelesai" data-toggle="modal"><i class="material-icons">send</i> Selesai (F2)</button> 
						{!! Form::hidden('no_faktur_batal', $pembelian->no_faktur, ['class'=>'form-control','required','autocomplete'=>'off', 'id'=>'no_faktur_batal']) !!} 
						<button type="submit" class="btn btn-danger" id="btnBatal"><i class="material-icons">cancel</i> Batal (F3)</button> 
						{!! Form::close() !!} 
					</div> 
				</div>             
			</div><!-- COL SM 4 --> 

		</div><!-- ROW --> 
	</div> 
</div> 
@endsection 

@section('scripts') 

{!! $html->scripts() !!} 


<script type="text/javascript"> 
	Number.prototype.format = function(n, x, s, c) { 
		var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\D' : '$') + ')', 
		num = this.toFixed(Math.max(0, ~~n)); 

		return (c ? num.replace('.', c) : num).replace(new RegExp(re, 'g'), '$&' + (s || ',')); 
	}; 

	function titleCase(str) { 
		var newstr = str.split(" "); 
		for(i=0;i<newstr.length;i++){ 
			if(newstr[i] == "") continue; 
			var copy = newstr[i].substring(1).toLowerCase(); 
			newstr[i] = newstr[i][0].toUpperCase() + copy; 
		} 
		newstr = newstr.join(" "); 
		return newstr; 
	}  
</script> 
<script type="text/javascript"> 
	$(document).ready(function(){ 
		$('#btn-hutang-pembelian').click(function() { 

			$("#form_pembelian").submit(function(){ 
				return false; 
			}); 

			var jatuh_tempo = $("#jatuh_tempo").val();       
			if (jatuh_tempo == '') { 
				alert("Jatuh Tempo Belum Diisi!");  
				$("#jatuh_tempo").focus(); 

			}else{ 
				var kas = $("#id_cara_bayar").val(); 
				var pembayaran = $("#pembayaran").val(); 

				if (pembayaran == '') { 

					document.getElementById("form_pembelian").submit(); 
				}else{ 

					$.post('{{ route('cek_total_kas') }}',{'_token': $('meta[name=csrf-token]').attr('content'),kas:kas}, function(data){  
						if (data == '')  { 
							data = 0; 
						} 
						var hitung_sisa_kas = parseFloat(data) - parseFloat(pembayaran); 
						if (hitung_sisa_kas >= 0) { 
							document.getElementById("form_pembelian").submit(); 
						}else{ 
							swal( 
								'Oops...', 
								'Kas Anda Tidak Cukup Untuk Melakukan Pembayaran', 
								'error' 
								) 
						} 

					}); 
				} 

			} 

		}); 
	}); 

	$(document).ready(function(){ 
		$('#btn-tunai-pembelian').click(function() { 

			$("#form_pembelian").submit(function(){ 
				return false; 
			}); 

			var kas = $("#id_cara_bayar").val(); 
			var pembayaran = $("#pembayaran").val(); 
			var jumlah_lama = "{{$jumlah_kas_lama}}"; 

			$.post('{{ route('cek_total_kas') }}',{'_token': $('meta[name=csrf-token]').attr('content'),kas:kas}, function(data){  
				if (data == '')  { 
					data = 0; 
				} 
				var hitung_sisa_kas = (parseFloat(data) + parseFloat(jumlah_lama)) - parseFloat(pembayaran); 
				if (hitung_sisa_kas >= 0) { 
					document.getElementById("form_pembelian").submit(); 
				}else{ 
					swal( 
						'Oops...', 
						'Kas Anda Tidak Cukup Untuk Melakukan Pembayaran', 
						'error' 
						) 
				} 

			}); 

		}); 
	}); 

</script> 

<script type="text/javascript"> 
	$(document).ready(function(){ 
		$(document).on("click", "#btnSelesai", function(){ 
			var jumlah_item = "{{$jumlah_item}}";
			var cara_bayar = $("#cara_bayar").val(); 
			var suplier = $("#pilih_suplier").val(); 

			if (jumlah_item == 0) {
				alert("Belum Ada Produk Yang Di Input!"); 
			}
			else if (suplier == '') { 
				alert("Suplier Belum Dipilih!"); 
				var $select = $('#pilih_suplier').selectize({ 
					sortField: 'text' 
				}); 
				$select[0].selectize.focus(); 
			}else if (cara_bayar == '') { 
				alert("Cara Bayar Belum Dipilih!"); 
				var $select = $('#cara_bayar').selectize({ 
					sortField: 'text' 
				}); 
				$select[0].selectize.focus(); 
			} 
			else{ 
				$("#modal_selesai").show(); 
				$("#pembayaran").focus(); 
			} 
		}); 

		$("#btnCloseModal").click(function(){ 
			$("#modal_selesai").hide(); 
		}); 

		$("#closeModalX").click(function(){ 
			$("#modal_selesai").hide(); 
		}); 
	}); 
</script> 

<!-- CARA BAYAR DAN SUPLIER --> 
<script type="text/javascript"> 
	$(document).ready(function(){ 
		$('#cara_bayar').change(function() { 
			var id_cara_bayar = $(this).val(); 
			$("#id_cara_bayar").val(id_cara_bayar); 
		}); 
		$('#pilih_suplier').change(function() { 
			var suplier_id = $(this).val(); 
			$("#suplier_id").val(suplier_id); 
		}); 
	}); 
</script> 
<!-- CARA BAYAR DAN SUPLIER --> 

<script type="text/javascript"> 
	$(document).ready(function(){ 
		$('#potongan_persen').keyup(function() { 
			var potongan_persen = $(this).val(); 
			var subtotal = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#subtotal").val())))); 
			var pembayaran = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#pembayaran").val())))); 
			if (pembayaran == '') { 
				pembayaran = 0; 
			} 
			if(potongan_persen == '') { 
				potongan_persen = 0; 
			} 
			if (potongan_persen > 100) { 
				alert("Potongan Yang Anda Masukan Lebih Dari 100%!");     
				$(this).focus();   
				$(this).val('');     
				$("#total_akhir").val(subtotal.format(2, 3, '.', ','));   
				$("#total_akhir_display").text(subtotal.format(2, 3, '.', ','));   
				$("#potongan_faktur").val(''); 
				$("#potongan_persen").val(''); 
				$("#potongan").val(''); 
				var kembalian = parseFloat(pembayaran,10) - parseFloat(subtotal,10);         
				var kredit = parseFloat(subtotal,10) - parseFloat(pembayaran,10);   
				if (kembalian >= 0.00) { 
					$("#kembalian").val(kembalian.format(2, 3, '.', ',')); 
					$("#kredit").val(''); 
					$("#btn-tunai-pembelian").show(); 
					$("#btn-hutang-pembelian").hide(); 
					$("#status_pembelian").val("Tunai"); 
				}else if(kredit >= 0.00){   
					$("#kembalian").val(''); 
					$("#kredit").val(kredit.format(2, 3, '.', ',')); 
					$("#btn-hutang-pembelian").show(); 
					$("#btn-tunai-pembelian").hide(); 
					$("#status_pembelian").val("Hutang"); 
				} 
			}else{ 
				var potongan_nominal = parseFloat(subtotal) * (parseFloat(potongan_persen) / 100); 
				var total_akhir = parseFloat(subtotal,10) - parseFloat(potongan_nominal,10); 
				$("#total_akhir").val(total_akhir.format(2, 3, '.', ','));   
				$("#total_akhir_display").text(total_akhir.format(2, 3, '.', ','));         
				$("#potongan_faktur").val(potongan_nominal.format(2, 3, '.', ',')); 
				$("#potongan").val(potongan_nominal);   

				var kembalian = parseFloat(pembayaran,10) - parseFloat(total_akhir,10);         
				var kredit = parseFloat(total_akhir,10) - parseFloat(pembayaran,10);   
				if (kembalian >= 0) { 
					$("#kembalian").val(kembalian.format(2, 3, '.', ',')); 
					$("#kredit").val(''); 
					$("#btn-tunai-pembelian").show(); 
					$("#btn-hutang-pembelian").hide(); 
					$("#status_pembelian").val("Tunai"); 
				}else if(kredit >= 0){ 
					$("#kembalian").val(''); 
					$("#kredit").val(kredit.format(2, 3, '.', ',')); 
					$("#btn-hutang-pembelian").show(); 
					$("#btn-tunai-pembelian").hide(); 
					$("#status_pembelian").val("Hutang"); 
				}   

			} 
		}); 
	}); 
</script> 

<!-- POTONGAN FAKTUR parseFloat--> 
<script type="text/javascript"> 
	$(document).ready(function(){ 
		$('#potongan_faktur').keyup(function() { 
			var potongan = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($(this).val())))); 
			if(potongan == '') { 
				potongan = 0; 
			}       
			var pembayaran = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#pembayaran").val())))); 
			if (pembayaran == '') { 
				pembayaran = 0; 
			} 
			var subtotal = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#subtotal").val())))); 
			var total_akhir = parseFloat(subtotal) - parseFloat(potongan); 
			var potongan_persen = parseFloat(potongan) / parseFloat(subtotal) * 100; 
			if (total_akhir < 0) { 
				alert("Potongan Yang Anda Masukan Melebihi Subtotal!");     

				$(this).val('');     
				$("#total_akhir").val(subtotal.format(2, 3, '.', ','));   
				$("#total_akhir_display").text(subtotal.format(2, 3, '.', ',')); 
				$("#potongan_persen").val('');   
				$("#potongan").val('');   
				$(this).focus();   

				var kembalian = parseFloat(pembayaran) - parseFloat(subtotal);         
				var kredit = parseFloat(subtotal) - parseFloat(pembayaran);   
				if (kembalian >= 0) { 
					$("#kembalian").val(kembalian.format(2, 3, '.', ',')); 
					$("#kredit").val(''); 
					$("#btn-tunai-pembelian").show(); 
					$("#btn-hutang-pembelian").hide(); 
					$("#status_pembelian").val("Tunai"); 
				}else if(kredit >= 0){ 
					$("#kembalian").val(''); 
					$("#kredit").val(kredit.format(2, 3, '.', ',')); 
					$("#btn-hutang-pembelian").show(); 
					$("#btn-tunai-pembelian").hide(); 
					$("#status_pembelian").val("Hutang"); 
				}   
			}else{       
				$("#total_akhir").val(total_akhir.format(2, 3, '.', ','));   
				$("#total_akhir_display").text(total_akhir.format(2, 3, '.', ',')); 
				$("#potongan_persen").val(potongan_persen.format(2, 3, '.', ','));   
				$("#potongan").val(potongan); 
				var kembalian = parseFloat(pembayaran) - parseFloat(total_akhir);         
				var kredit = parseFloat(total_akhir) - parseFloat(pembayaran);   
				if (kembalian >= 0) { 
					$("#kembalian").val(kembalian.format(2, 3, '.', ',')); 
					$("#kredit").val(''); 
					$("#btn-tunai-pembelian").show(); 
					$("#btn-hutang-pembelian").hide(); 
					$("#status_pembelian").val("Tunai"); 
				}else if(kredit >= 0){ 
					$("#kembalian").val(''); 
					$("#kredit").val(kredit.format(2, 3, '.', ',')); 
					$("#btn-hutang-pembelian").show(); 
					$("#btn-tunai-pembelian").hide(); 
					$("#status_pembelian").val("Hutang"); 
				}   
			} 

		}); 
	}); 
</script> 
<!-- POTONGAN FAKTUR --> 

<!-- PEMBAYARAN FAKTUR --> 
<script type="text/javascript"> 
	$(document).ready(function(){ 
		$('#pembayaran').keyup(function() { 
			var pembayaran = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($(this).val())))); 
			var total_akhir = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#total_akhir").val())))); 

			if (pembayaran == '') { 
				pembayaran = 0; 
			} 
			var kembalian = parseFloat(pembayaran,10) - parseFloat(total_akhir,10);         
			var kredit = parseFloat(total_akhir,10) - parseFloat(pembayaran,10);   
			if (kembalian >= 0) { 
				$("#kembalian").val(kembalian.format(2, 3, '.', ',')); 
				$("#kredit").val(''); 
				$("#btn-tunai-pembelian").show(); 
				$("#btn-hutang-pembelian").hide(); 
				$("#status_pembelian").val("Tunai"); 
			}else if(kredit >= 0){ 
				$("#kembalian").val(''); 
				$("#kredit").val(kredit.format(2, 3, '.', ',')); 
				$("#btn-hutang-pembelian").show(); 
				$("#btn-tunai-pembelian").hide(); 
				$("#status_pembelian").val("Hutang"); 
			}   

		}); 
	}); 
</script> 
<!-- PEMBAYARAN FAKTUR --> 
<div class="row"> 
	<div class="col-md-6"></div> 
	<div class="col-md-6"></div> 
</div> 
<!-- TAMBAH PRODUK --> 
<script type="text/javascript"> 
	$(document).ready(function(){ 
		$('#cara_bayar').selectize({ 
			sortField: 'text' 
		}); 

		$('#pilih_suplier').selectize({ 
			sortField: 'text' 
		}); 
		var $select = $('#pilih_produk').selectize({ 
			sortField: 'text' 
		}); 
		$select[0].selectize.focus(); 
		$select.on('change', function(){ 

			var pilih_produk = $("#pilih_produk").val(); 
			var produk = pilih_produk.split("-"); 
			var id_produk = produk[0]; 
			var nama_produk = produk[1]; 
			var harga_produk = produk[2]; 
			var jumlah = $("#jumlah_produk").val(); 

			if (pilih_produk == "") { 
				swal('Oops...','Produk Harus Dipilih Dahulu !','error'); 
				return false; 
			} 
			else if(jumlah == ""){ 
				swal({ 
					title : titleCase(nama_produk), 
					html: 
					'<div class="row">'+ 
					'<div class="col-sm-6"><lable> Jumlah Produk </lable>'+ 
					'<input id="jadwal_produk_swal" class="swal2-input" placeholder="JUMLAH PRODUK"></div>'+ 
					'<div class="col-sm-6"><lable>Harga Produk </lable>'+ 
					'<input id="harga_produk_swal" class="swal2-input" placeholder="HARGA PRODUK" value="'+harga_produk+'"></div>'+ 
					'</div>', 
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
					preConfirm: function () { 
						return new Promise(function (resolve) { 
							resolve([ 
								$('#jadwal_produk_swal').val(), 
								$('#harga_produk_swal').val() 
								]) 
						}) 
					},       
				}).then(function (result) { 

					if (result[0] == '' || result[0] == 0) { 

						swal('Oops...', 'Jumlah Produk Tidak Boleh 0 atau Kosong !', 'error'); 
						return false; 
					}else if (result[1] == '' || result[1] == 0) { 

						swal('Oops...', 'Harga Produk Tidak Boleh 0 atau Kosong !', 'error'); 
						return false; 
					}else if (result[1] != harga_produk) { 

						swal({ 
							title: 'Anda Yakin?', 
							text: "Anda Yakin Ingin Merubah Harga Beli Produk?", 
							type: 'warning', 
							showCancelButton: true, 
							confirmButtonColor: '#3085d6', 
							cancelButtonColor: '#d33', 
							cancelButtonText: 'Batal', 
							confirmButtonText: 'Ya' 
						}).then(function () { 
							$("#id_produk_tbs").val(id_produk); 
							$("#jumlah_produk").val(result[0]); 
							$("#harga_produk").val(result[1]); 
							$("#form-produk").submit(); 
						}) 
					}else{ 
						$("#id_produk_tbs").val(id_produk); 
						$("#jumlah_produk").val(result[0]); 
						$("#harga_produk").val(harga_produk); 
						$("#form-produk").submit(); 
					} 
				}); 
				return false; 
			} 
			else if (jumlah != "" && pilih_produk != ""){ 
				return true; 
			} 

		});  


	}); 
</script> 
<!-- TAMBAH PRODUK --> 

<!-- EDIT PRODUK --> 
<script type="text/javascript"> 
	$(document).on('click', '.edit-jumlah', function () { 
		var id_tbs = $(this).attr('data-id'); 
		var nama_produk = $(this).attr('data-nama'); 
		swal({ 
			title: titleCase(nama_produk), 
			input: 'number', 
			inputPlaceholder : 'Jumlah Produk',         
			html:'Berapa Jumlah Produk Yang Akan Dimasukkan ?', 
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
		}).then(function (jumlah_produk) { 

			if (jumlah_produk != "0") { 
				$("#jumlah_edit_produk").val(jumlah_produk); 
				$("#id_produk_edit_jumlah").val(id_tbs); 
				$("#form-edit-jumlah").submit(); 
			} 
			else { 
				swal('Oops...', 'Jumlah Tidak Boleh 0 !', 'error'); 
				return false; 
			} 

		}); 

	}); 
</script> 
<!-- EDIT PRODUK --> 

<!-- EDIT HARGA PRODUK --> 
<script type="text/javascript"> 
	$(document).on('click', '.edit-harga', function () { 
		var id_tbs = $(this).attr('data-id'); 
		var nama_produk = $(this).attr('data-nama'); 
		swal({ 
			title: titleCase(nama_produk), 
			input: 'number', 
			inputPlaceholder : 'Harga Produk',         
			html:'Berapa Harga Produk Yang Akan Dimasukkan ?', 
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
				'name': 'edit_harga_produk', 
			}, 
			inputValidator : function (value) { 
				return new Promise(function (resolve, reject) { 
					if (value) { 
						resolve(); 
					}  
					else { 
						reject('Harga Harus Di Isi!'); 
					} 
				}) 
			} 
		}).then(function (harga_produk) { 

			if (harga_produk != "0") { 

				swal({ 
					title: 'Anda Yakin?', 
					text: "Anda Yakin Ingin Merubah Harga Beli Produk?", 
					type: 'warning', 
					showCancelButton: true, 
					confirmButtonColor: '#3085d6', 
					cancelButtonColor: '#d33', 
					cancelButtonText: 'Batal', 
					confirmButtonText: 'Ya' 
				}).then(function () { 
					$("#id_produk_edit_harga").val(id_tbs); 
					$("#harga_edit_produk").val(harga_produk); 
					$("#form-edit-harga").submit(); 
				}); 

			} 
			else { 
				swal('Oops...', 'Harga Tidak Boleh 0 !', 'error'); 
				return false; 
			} 

		}); 

	}); 
</script> 
<!-- EDIT HARGA PRODUK --> 

<!-- EDIT POTONGAN PRODUK --> 
<script type="text/javascript"> 
	$(document).on('click', '.edit-potongan', function () { 
		var id_tbs = $(this).attr('data-id'); 
		var nama_produk = $(this).attr('data-nama'); 
		var harga = $(this).attr('data-harga'); 
		var jumlah = $(this).attr('data-jumlah'); 
		var subtotal = parseFloat(jumlah) * parseFloat(harga); 
		swal({ 
			title: titleCase(nama_produk), 
			input: 'text', 
			inputPlaceholder : 'Potongan Produk',         
			html:'Sertakan <b>%</b> Jika Ingin Potongan Dalam Bentuk Persentase', 
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
				'name': 'edit_potongan_produk', 
			}, 
			inputValidator : function (value) { 
				return new Promise(function (resolve, reject) { 
					if (value) { 
						resolve(); 
					}  
					else { 
						reject('Potongan Harus Di Isi!'); 
					} 
				}) 
			} 
		}).then(function (potongan) { 
			var pos = potongan.search("%"); 
			if (pos > 0)  
			{   
				var potongan_produk = potongan; 
				potongan_produk = potongan_produk.replace("%","");       
				if (potongan_produk > 100) { 
					swal('Oops...', 'Potongan Tidak Boleh Lebih Dari 100%!', 'error'); 
					return false;         
				} 
				else if (potongan != "0") { 
					$("#id_produk_edit_potongan").val(id_tbs); 
					$("#potongan_edit_produk").val(potongan); 
					$("#form-edit-potongan").submit(); 
				} 
				else { 
					swal('Oops...', 'Potongan Tidak Boleh 0 !', 'error'); 
					return false; 
				} 

			}else{ 

				if (subtotal < potongan) { 

					swal('Oops...', 'Potongan Tidak Boleh Melebihi Subtotal!', 'error'); 
					return false;         
				} 
				else if (potongan != "0") { 
					$("#id_produk_edit_potongan").val(id_tbs); 
					$("#potongan_edit_produk").val(potongan); 
					$("#form-edit-potongan").submit(); 
				} 
				else { 
					swal('Oops...', 'Potongan Tidak Boleh 0 !', 'error'); 
					return false; 
				} 
			} 


		}); 

	}); 
</script> 
<!-- EDIT HARGA PRODUK --> 


<!-- EDIT PAJAK PRODUK --> 
<script type="text/javascript"> 
	$(document).on('click', '.edit-tax', function () { 
		var id_tbs = $(this).attr('data-id'); 
		var nama_produk = $(this).attr('data-nama'); 
		var ppn = $(this).attr('data-ppn'); 
		var harga = $(this).attr('data-harga'); 
		var jumlah = $(this).attr('data-jumlah'); 
		var potongan = $(this).attr('data-potongan'); 
		var subtotal = (parseFloat(jumlah) * parseFloat(harga)) - parseFloat(potongan); 
		if (ppn == '') { 
			var ppn_produk = '<select id="ppn_swal" name="ppn_swal" class="swal2-input js-selectize-reguler">'+ 
			'<option value"Include>Include</option>'+ 
			'<option value"Exclude>Exclude</option>'+ 
			'</select></div>'; 
		}else { 
			var ppn_produk = '<select id="ppn_swal" name="ppn_swal" class="swal2-input js-selectize-reguler">'+ 
			'<option selected="selected" value="'+ppn+'">'+ppn+'</option>'+ 
			'</select></div>'; 
		} 

		swal({ 
			title: titleCase(nama_produk), 
			html:'Sertakan <b>%</b> Jika Ingin Pajak Dalam Bentuk Persentase<br><br>'+ 
			'<div class="row">'+ 
			'<div class="col-sm-6">'+ppn_produk+''+ 
			'<div class="col-sm-6">'+ 
			'<input id="tax_swal" class="swal2-input" placeholder="PAJAK PRODUK"></div>'+ 
			'</div>', 
			animation: false, 
			showCloseButton: true, 
			showCancelButton: true, 
			confirmButtonText: '<i class="fa fa-thumbs-o-up"></i> Submit', 
			confirmButtonAriaLabel: 'Thumbs up, great!', 
			cancelButtonText: '<i class="fa fa-thumbs-o-down">Batal', 
			cancelButtonAriaLabel: 'Thumbs down', 
			preConfirm: function () { 
				return new Promise(function (resolve) { 
					resolve([ 
						$('#tax_swal').val(), 
						$('#ppn_swal').val() 
						]) 
				}) 
			} 
		}).then(function (result) {   
			if (result[0] == '' || result[0] == 0) { 

				swal('Oops...', 'Pajak Tidak Boleh 0 !', 'error'); 
				return false; 
			}   
			else if (result[1] == '') { 

				swal('Oops...', 'PPN Belum Di Isi', 'error'); 
				return false; 
			}else{ 

				var pajak = result[0]; 
				var pos = pajak.search("%"); 

				if (pos > 0) { 
					pajak = pajak.replace("%",""); 
					if (pajak > 100) { 

						swal('Oops...', 'Pajak Tidak Boleh Lebih Dari 100%!', 'error'); 
						return false; 
					}else{ 

						$("#id_produk_edit_tax").val(id_tbs); 
						$("#tax_edit_produk").val(result[0]); 
						$("#ppn_produk").val(result[1]); 
						$("#form-edit-tax").submit();   
					} 

				}else{ 

					if (subtotal < result[0]) { 

						swal('Oops...', 'Pajak Tidak Boleh Melebihi Subtotal!', 'error'); 
						return false; 
					}else{ 

						$("#id_produk_edit_tax").val(id_tbs); 
						$("#tax_edit_produk").val(result[0]); 
						$("#ppn_produk").val(result[1]); 
						$("#form-edit-tax").submit(); 
					} 

				} 
			} 
		}); 

	}); 
</script> 
<!-- EDIT TAX PRODUK --> 

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

		var potongan = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#potongan_faktur").val())))); 
		if(potongan == '') { 
			potongan = 0; 
		}  
		var pembayaran = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#pembayaran").val())))); 
		var subtotal = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#subtotal").val())))); 

		var potongan_persen = parseFloat(potongan) / parseFloat(subtotal) * 100; 
		$("#potongan_persen").val(potongan_persen.format(2, 3, '.', ',')); 

		var total_akhir = parseFloat(subtotal) - parseFloat(potongan); 
		$("#total_akhir").val(total_akhir.format(2, 3, '.', ',')); 

		if (pembayaran == '') { 
			pembayaran = 0; 
		} 
		var kembalian = parseFloat(pembayaran,10) - parseFloat(total_akhir,10);         
		var kredit = parseFloat(total_akhir,10) - parseFloat(pembayaran,10);   
		if (kembalian >= 0) { 
			$("#kembalian").val(kembalian.format(2, 3, '.', ',')); 
			$("#kredit").val(''); 
			$("#btn-tunai-pembelian").show(); 
			$("#btn-hutang-pembelian").hide(); 
			$("#status_pembelian").val("Tunai"); 
		}else if(kredit >= 0){ 
			$("#kembalian").val(''); 
			$("#kredit").val(kredit.format(2, 3, '.', ',')); 
			$("#btn-hutang-pembelian").show(); 
			$("#btn-tunai-pembelian").hide(); 
			$("#status_pembelian").val("Hutang"); 
		} 
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
	$(document).on("blur", "#potongan_faktur", function(){ 
		$("#pembayaran").focus(); 
	}); 
</script> 
@endsection