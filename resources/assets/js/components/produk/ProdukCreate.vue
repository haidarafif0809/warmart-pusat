	<style scoped>

		.btn-icon{
			border-radius: 1px solid;
			padding: 10px 10px;
		}

	</style>
	<template>
		<div class="row">
			<div class="col-md-12">
				<ul class="breadcrumb">
					<li><router-link :to="{name: 'indexDashboard'}">Home</router-link></li>
					<li><router-link :to="{name: 'indexProduk'}">Produk</router-link></li>
					<li class="active">Tambah Produk</li>
				</ul>
				<div class="card">


					<!--MODAL kategori produk BARU -->
					<div class="modal" id="modal_kategori" role="dialog" data-backdrop="">
						<div class="modal-dialog">
							<!-- Modal content-->
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close"  v-on:click="tutupModal()" v-shortkey.push="['esc']" @shortkey="tutupModal()"> <i class="material-icons">close</i></button>
									<h4 class="modal-title">
										<div class="alert-icon">
											<b>Silahkan Isi Kategori Produk !</b>
										</div>
									</h4>
								</div>
								<div class="modal-body">
									<form v-on:submit.prevent="saveFormKategori()" class="form-horizontal">
										<div class="form-group">
											<label for="nama_kelompok" class="col-md-2 control-label">Nama</label>
											<div class="col-md-10">
												<input class="form-control" autocomplete="off" placeholder="Nama" v-model="kelompok_produk.nama_kelompok" type="text" name="nama_kelompok" id="nama_kelompok"  autofocus="">
												<span v-if="errors.nama_kelompok" id="nama_kelompok_error" class="label label-danger">{{ errors.nama_kelompok[0] }}</span>
											</div>
										</div>
										<div class="form-group">
											<div class="col-md-4">
												<input  class="form-control" autocomplete="off" placeholder="Icon"  v-model="kelompok_produk.icon_kelompok"  type="hidden" name="icon_kelompok" id="icon_kelompok" >
												<span v-if="errors.icon_kelompok" id="icon_kelompok_error" class="label label-danger">{{ errors.icon_kelompok[0] }}</span>
											</div>
										</div>
										<div class="form-group">
											<div class="col-md-4 col-md-offset-2">
												<button class="btn btn-primary" id="btnSimpanKategoriTransaksi" type="submit"><i class="material-icons">send</i> Submit</button>
											</div>
										</div>
									</form>
								</div>
								<div class="modal-footer"></div> 
							</div>       
						</div> 
					</div> 


					<!--MODAL Satuan BARU -->
					<div class="modal" id="modal_satuan" role="dialog" data-backdrop="">
						<div class="modal-dialog">
							<!-- Modal content-->
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close"  v-on:click="tutupModal()" v-shortkey.push="['esc']" @shortkey="tutupModal()"> <i class="material-icons">close</i></button>
									<h4 class="modal-title">
										<div class="alert-icon">
											<b>Silahkan Isi Satuan !</b>
										</div>
									</h4>
								</div>
								<div class="modal-body">
									<form v-on:submit.prevent="saveFormSatuan()" class="form-horizontal">
										<div class="form-group">
											<label for="name" class="col-md-2 control-label">Nama Satuan</label>
											<div class="col-md-4">
												<input class="form-control" required autocomplete="off" placeholder="Nama Satuan" type="text" v-model="satuan.nama_satuan" name="nama_satuan"  autofocus="">
												<span v-if="errors.nama_satuan" id="nama_satuan_error" class="label label-danger">{{ errors.nama_satuan[0] }}</span>
											</div>
										</div>
										<div class="form-group">
											<div class="col-md-4 col-md-offset-2">
												<button class="btn btn-primary" id="btnSimpanKategoriTransaksi" type="submit"><i class="material-icons">send</i> Submit</button>
											</div>
										</div>
									</form>
								</div>
								<div class="modal-footer"></div> 
							</div>       
						</div> 
					</div> 


					<div class="card-header card-header-icon" data-background-color="purple">
						<i class="material-icons">dns</i>
					</div>

					<div class="card-content">
						<h4 class="card-title"> Produk </h4>
						<form v-on:submit.prevent="saveForm()" class="form-horizontal"> 
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label for="kode_barcode" class="col-md-2 control-label">Kode Barcode</label>
										<div class="col-md-10">
											<input class="form-control" autocomplete="off" placeholder="Kode Barcode (Jika Ada)" v-model="produk.kode_barcode" type="text" name="kode_barcode" id="kode_barcode"  autofocus="">
											<span v-if="errors.kode_barcode" id="kode_barcode_error" class="label label-danger">{{ errors.kode_barcode[0] }}</span>
										</div>
									</div>

									<div class="form-group">
										<label for="kode_barang" class="col-md-2 control-label">Kode Produk</label>
										<div class="col-md-10">
											<input class="form-control" autocomplete="off" placeholder="Kode Produk" v-model="produk.kode_barang" type="text" name="kode_barang" id="kode_barang"  autofocus="">
											<span v-if="errors.kode_barang" id="kode_barang_error" class="label label-danger">{{ errors.kode_barang[0] }}</span>
										</div>
									</div>

									<div class="form-group">
										<label for="nama_barang" class="col-md-2 control-label">Nama Produk</label>
										<div class="col-md-10">
											<input class="form-control" autocomplete="off" placeholder="Nama Produk" v-model="produk.nama_barang" type="text" name="nama_barang" id="nama_barang"  autofocus="">
											<span v-if="errors.nama_barang" id="nama_barang_error" class="label label-danger">{{ errors.nama_barang[0] }}</span>
										</div>
									</div>

									<div class="form-group">
										<label for="kategori_barang_id" class="col-md-2 control-label">Kategori Produk</label>
										<div class="col-md-9">
											<selectize-component v-model="produk.kategori_barang_id" :settings="placeholder_kategori" id="pilih_kategori_barang_id"> 
												<option v-for="kategoris, index in kategori_barang_id" v-bind:value="kategoris.id" >{{ kategoris.nama_kategori_barang }}</option>
											</selectize-component>
											<span v-if="errors.kategori_barang_id" id="kategori_barang_id_error" class="label label-danger">{{ errors.kategori_barang_id[0] }}</span>
										</div>
										<div class="col-md-1" style="padding-left:0px">
											<div class="row" style="margin-top:-10px">
												<button class="btn btn-primary btn-icon waves-effect waves-light" v-on:click="tambahKategori()" type="button" id="btnKategori"> <i class="material-icons" >add</i> </button>
											</div>
										</div> 
									</div>

									<div class="form-group">
										<label for="satuan_id" class="col-md-2 control-label">Satuan Produk</label>
										<div class="col-md-9">
											<selectize-component v-model="produk.satuan_id" :settings="placeholder_satuan" id="pilih_satuan_id"> 
												<option v-for="satuans, index in satuan_id" v-bind:value="satuans.id" >{{ satuans.nama_satuan }}</option>
											</selectize-component>
											<span v-if="errors.satuan_id" id="satuan_id_error" class="label label-danger">{{ errors.satuan_id[0] }}</span>
										</div> 
										<div class="col-md-1" style="padding-left:0px">
											<div class="row" style="margin-top:-10px">
												<button class="btn btn-primary btn-icon waves-effect waves-light" v-on:click="tambahSatuan()" type="button" id="btnSatuan"> <i class="material-icons" >add</i> </button>
											</div>
										</div> 
									</div>

									<div class="form-group">
										<label for="harga_beli" class="col-md-2 control-label">Harga Beli</label>
										<div class="col-md-10">
											<money class="form-control" autocomplete="off" placeholder="Harga Beli" v-model="produk.harga_beli" type="text" name="harga_beli" id="harga_beli"  autofocus="" v-bind="separator"></money>
											<span v-if="errors.harga_beli" id="harga_beli_error" class="label label-danger">{{ errors.harga_beli[0] }}</span>
										</div>
									</div>

									<div class="form-group">
										<label for="harga_jual" class="col-md-2 control-label">Harga Jual 1</label>
										<div class="col-md-10">
											<money class="form-control" autocomplete="off" placeholder="Harga Jual" v-model="produk.harga_jual" type="text" name="harga_jual" id="harga_jual"  autofocus="" v-bind="separator"></money>
											<span v-if="errors.harga_jual" id="harga_jual_error" class="label label-danger">{{ errors.harga_jual[0] }}</span>
										</div>
									</div>

									<div class="form-group">
										<label for="harga_jual2" class="col-md-2 control-label">Harga Jual 2</label>
										<div class="col-md-10">
											<money class="form-control" autocomplete="off" placeholder="Harga Jual 2(Jika Ada)" v-model="produk.harga_jual2" type="text" name="harga_jual2" id="harga_jual2"  autofocus="" v-bind="separator"></money>
											<span v-if="errors.harga_jual2" id="harga_jual2_error" class="label label-danger">{{ errors.harga_jual2[0] }}</span>
										</div>
									</div>

									<div class="form-group">
										<label for="perkiraan_berat" class="col-md-2 control-label">Perkiraan Berat</label>
										<div class="col-md-6">
											<money class="form-control" autocomplete="off" placeholder="Perkiraan Berat(Jika Barang Dijual Online)" v-model="produk.perkiraan_berat" type="text" name="perkiraan_berat" id="perkiraan_berat"  autofocus="" v-bind="separator"></money>
											<span v-if="errors.perkiraan_berat" id="perkiraan_berat_error" class="label label-danger">{{ errors.perkiraan_berat[0] }}</span>
										</div>
										<div class="col-md-4">
											<p style="color: grey; font-style: italic;" id="textPerkiraanBerat">Satuan(Berat) dalam bentuk Gram.</p>     
										</div>
									</div>

									<div class="form-group">
										<label for="hitung_stok" class="col-md-2 control-label">Hitung Stok</label>
										<div class="togglebutton col-md-10">
											<label>
												<input type="checkbox" v-model="produk.hitung_stok" name="hitung_stok" id="hitung_stok" value="1">
												<font v-if="produk.hitung_stok == 1" class="hitung_stok">Ya</font>
												<font v-else class="hitung_stok">Tidak</font>
											</label>
										</div>
									</div>

									<div class="form-group">
										<label for="status_aktif" class="col-md-2 control-label">Bisa Dijual</label>
										<div class="togglebutton col-md-10">
											<label>
												<input type="checkbox" v-model="produk.status_aktif" name="status_aktif" id="status_aktif" value="1">
												<font v-if="produk.status_aktif == 1" class="status_aktif">Ya</font>
												<font v-else class="status_aktif">Tidak</font>
											</label>
										</div>
									</div>

									<div class="form-group" v-if="data_agent == 0">
										<div class="col-md-2"></div>
										<div class="col-md-10">
											<button type="button" class="btn btn-info btn-xs" id="btnDeskripsi" data-toggle="collapse" data-target="#collDeskripsi"><i class="material-icons">add</i>Deskripsi Produk</button>
										</div>									
										<div class="col-md-12 col-xs-12 collapse" id="collDeskripsi">
											<quill-editor v-model="produk.deskripsi_produk"
											ref="myQuillEditor"
											:options="editorOption">
										</quill-editor>
									</div>
								</div>

								<div class="form-group">
									<label for="foto" class="col-md-2 control-label">Foto Produk</label>
									<div class="col-md-10">
										<div class="fileinput fileinput-new text-center" data-provides="fileinput" id="div-foto">
											<div class="fileinput-new thumbnail">
												<img v-if="produk.foto != ''" :src="url_picture+'/'+produk.foto" /> 
												<img :src="url_origin+'/assets/img/image_placeholder.jpg'" alt="Foto Akan Tampil Disini" v-else>
											</div>
											<div class="fileinput-preview fileinput-exists thumbnail"></div>
											<div>
												<span class="btn btn-rose btn-round btn-file">
													<span class="fileinput-new">Ambil Foto</span>
													<span class="fileinput-exists">Ubah</span>
													<input class="form-control" type="file" name="foto" id="foto">
												</span>
												<a href="#pablo" class="btn btn-danger btn-round fileinput-exists" data-dismiss="fileinput"><i class="fa fa-times"></i> Batal</a>
											</div>
											<span v-if="errors.foto" id="foto_error" class="label label-danger">{{ errors.foto[0] }}</span>
											<a style="color: red;">Size Foto (Ukuran Max : 3MB)</a>
										</div>
									</div>
								</div> 
							</div>
							<div class="col-md-6" v-if="data_agent == 1">
								<quill-editor v-model="produk.deskripsi_produk"
								ref="myQuillEditor"
								:options="editorOption" style="height:10%" id="deskripsi_produk_web">
							</quill-editor>
						</div>
					</div>

					<div class="form-group">
						<div class="col-md-10 col-md-offset-1">
							<button class="btn btn-primary" id="btnSimpanProduk" type="submit"><i class="material-icons">send</i> Submit</button>
						</div>
					</div>
				</form>
			</div>

		</div>
	</div>

	<v-tour name="myTour" :steps="steps"></v-tour>
</div>
</template>


<script>
	export default {
		data: function () {
			return {
				errors: [],
				kategori_barang_id: [],
				satuan_id: [],
				url : window.location.origin+(window.location.pathname).replace("dashboard", "produk"),
				url_picture : window.location.origin+(window.location.pathname).replace("dashboard", "foto_produk"),
				url_origin : window.location.origin+(window.location.pathname).replace("dashboard", ""),
				url_kategori : window.location.origin+(window.location.pathname).replace("dashboard", "kelompok-produk"),
				url_satuan : window.location.origin+(window.location.pathname).replace("dashboard", "satuan"),
				produk: {
					foto : '',
					kode_barcode : '',
					kode_barang : '',
					nama_barang : '',
					kategori_barang_id : '',
					satuan_id : '',
					harga_beli : '',
					harga_jual : '',
					harga_jual2 : '',
					perkiraan_berat : '',
					deskripsi_produk : '',
					hitung_stok : 1,
					status_aktif : 1
				},
				kelompok_produk: {
					nama_kelompok : '',
					icon_kelompok : ''
				},
				satuan: {
					nama_satuan: '',
				},
				message : '',
				data_agent : '',
				placeholder_kategori: {
					placeholder: '--PILIH KATEGORI--'
				}, 
				placeholder_satuan: {
					placeholder: '--PILIH SATUAN--'
				},
				placeholder_deskripsi: {
					placeholderText: 'Edit Deskripsi Produk',
				},
				editorOption: {
				},
				separator: {
					decimal: ',',
					thousands: '.',
					prefix: '',
					suffix: '',
					precision: 0,
					masked: false /* doesn't work with directive */
				},
				steps: [{
					target: '#kode_barcode',
					content: 'Silahkan Isi Kolom <strong>Kode Barcode</strong> Jika Produk Anda <br> Menggunakan <strong>Kode Barcode</strong>. Jika Tidak Maka Boleh Dikosongkan.',
					params: {
						placement: 'right',
					} 
				},{
					target: '#kode_barang',
					content: 'Inputkan <strong>Kode Produk</strong> Untuk Produk Anda.',
					params: {
						placement: 'right',
					}
				},{
					target: '#nama_barang',
					content: 'Inputkan <strong>Nama Produk</strong> Untuk Produk Anda.',
					params: {
						placement: 'right',
					}
				},{
					target: '#btnKategori',
					content: 'Pilih <strong>Kategori Produk</strong> Yang Sudah Tersedia, <br> Atau Anda Bisa Membuat <strong>Kategori Produk</strong> Baru <br> Dengan Mengklik Tombol [ + ] Disamping Kolom.',
					params: {
						placement: 'right',
					}
				},{
					target: '#btnSatuan',
					content: 'Pilih <strong>Satuan Produk</strong> Yang Sudah Tersedia, <br> Atau Anda Bisa Membuat <strong>Satuan Produk</strong> Baru <br> Dengan Mengklik Tombol [ + ] Disamping Kolom.',
					params: {
						placement: 'right',
					}
				},{
					target: '#harga_beli',
					content: 'Tentukan <strong>Harga Beli</strong> Dari Produk Yang Sedang Kita Buat.',
					params: {
						placement: 'right',
					}
				},{
					target: '#harga_jual',
					content: 'Tentukan <strong>Harga Jual 1</strong> Dari Produk Yang Sedang Kita Buat.',
					params: {
						placement: 'right',
					}
				},{
					target: '#harga_jual2',
					content: 'Tentukan <strong>Harga Jual 2</strong> Dari Produk Yang Sedang Kita Buat. <br> Jika Tidak Ada, Maka Boleh Dikosongkan.',
					params: {
						placement: 'right',
					}
				},				{
					target: '#textPerkiraanBerat',
					content: 'Masukan <strong>Perkiraan Berat</strong> Produk Yang Sedang Kita Buat, <br> Dalam Bentuk Gram.',
					params: {
						placement: 'right',
					}
				},{
					target: '.hitung_stok',
					content: 'Pada <strong>Hitung Stok</strong> Silahkan Pilih <strong>Ya</strong> Atau <strong>Tidak</strong>.<br> JIka <strong>Ya</strong>, Maka Produk Yang Kita Buat Berupa Barang Dan Memiliki Stok. <br> JIka <strong>Tidak</strong>, Maka Produk Yang Kita Buat Berupa Jasa',
					params: {
						placement: 'right',
					}
				},{
					target: '.status_aktif',
					content: 'Pada <strong>Bisa Dijual</strong> Silahkan Pilih <strong>Ya</strong> Atau <strong>Tidak</strong>.<br> JIka <strong>Ya</strong>, Maka Produk Yang Kita Buat Akan Muncul Di Semua Transaksi. <br> JIka <strong>Tidak</strong>, Maka Produk Yang Kita Buat Tidak Akan Muncul Di Semua Transaksi',
					params: {
						placement: 'right',
					}
				},{
					target: '#div-foto',
					content: 'Pilih <strong>Foto Produk</strong> Yang Sudah Sesuai Dengan Produk yang Kita Buat. <br> Jika <strong>Foto Produk</strong> Belum Ada, Bisa Dikosongkan.',
					params: {
						placement: 'right',
					}
				},{
					target: '#deskripsi_produk_web',
					content: 'Tuliskan <strong>Deskripsi Produk</strong> Dari Produk Yang Kita Buat. <br> <strong>Deskripsi Produk</strong> Bisa Dikosongkan.',
					params: {
						placement: 'left',
					}
				},{
					target: '#btnSimpanProduk',
					content: 'Klik Tombol <strong>Submit</strong> Untuk Menyimpan Produk Yang Kita Buat.',
					params: {
						placement: 'right',
					}
				}]
			}
		},
		mounted() {
			var app = this;
			app.dataKategori();
			app.dataSatuan();
			app.dataAgent();
			if (app.$route.fullPath == "/create-produk?tour") {
				this.$tours['myTour'].start();
			}
		},
		computed: {
			editor() {
				return this.$refs.myQuillEditor.quill
			}
		},
		methods: {
			saveForm() {
				var app = this; 
				app.loading();
				var newProduk = app.inputData();

				axios.post(app.url, newProduk)
				.then(function (resp) {
					app.message = 'Menambah Produk '+ app.produk.nama_barang;
					app.alert(app.message);
					app.kosongkanData();
					app.$router.replace('/produk/');
					app.$swal.close();
				})
				.catch(function (resp) {
					alert("Periksa Kembali Inputan Anda!")
					app.success = false;
					app.errors = resp.response.data.errors;
					app.$swal.close();
				});
			},
			alert(pesan) {
				this.$swal({
					title: "Berhasil!",
					text: pesan,
					icon: "success",
					buttons: false,
					timer: 1000,
				});
			},
			dataKategori() {
				var app = this;
				axios.get(app.url+'/pilih-kategori').then(function (resp) {
					app.kategori_barang_id = resp.data;
				})
				.catch(function (resp) {
					alert("Tidak Bisa Memuat Kategori");
				});
			},
			dataSatuan() {
				var app = this;
				axios.get(app.url+'/pilih-satuan').then(function (resp) {
					app.satuan_id = resp.data;
				})
				.catch(function (resp) {
					alert("Tidak Bisa Memuat Satuan");
				});
			},
			dataAgent() {
				var app = this;
				axios.get(app.url+'/pilih-agent').then(function (resp) {
					app.data_agent = resp.data;
				})
				.catch(function (resp) {
					alert("Tidak Bisa Memuat Agent");
				});
			},
			loading(){
				this.$swal({
					title: "Sedang Memproses Data ...",
					text: "Harap Tunggu!",
					icon: "info",
					buttons:  false,
					closeOnClickOutside: false,
					closeOnEsc: false

				});
			},
			inputData(){
				var app = this;

				let newProduk = new FormData();
				if (document.getElementById('foto').files[0] != undefined) {
					newProduk.append('foto', document.getElementById('foto').files[0]);
				}
				newProduk.append('kode_barcode', app.produk.kode_barcode);
				newProduk.append('kode_barang', app.produk.kode_barang);
				newProduk.append('nama_barang', app.produk.nama_barang);
				newProduk.append('kategori_barang_id', app.produk.kategori_barang_id);
				newProduk.append('satuan_id', app.produk.satuan_id);
				newProduk.append('harga_beli', app.produk.harga_beli);
				newProduk.append('harga_jual', app.produk.harga_jual);
				newProduk.append('harga_jual2', app.produk.harga_jual2);
				newProduk.append('perkiraan_berat', app.produk.perkiraan_berat);
				newProduk.append('hitung_stok', app.produk.hitung_stok);
				newProduk.append('status_aktif', app.produk.status_aktif);
				newProduk.append('deskripsi_produk', app.produk.deskripsi_produk);

				return newProduk;
			},
			tambahKategori(){
				$("#modal_kategori").show();
				this.$refs.nama_kategori_transaksi.$el.focus(); 
			},
			tambahSatuan(){
				$("#modal_satuan").show();
				this.$refs.nama_satuan.$el.focus(); 
			},
			saveFormKategori() {
				var app = this;
				var newkelompok_produk = app.kelompok_produk;
				axios.post(app.url_kategori, newkelompok_produk)
				.then(function (resp) {
					app.message = 'Menambah Kelompok Produk '+ app.kelompok_produk.nama_kelompok;
					app.alert(app.message);
					app.kelompok_produk.nama_kelompok = ''
					app.kelompok_produk.icon_kelompok = ''
					app.errors = '';
					app.dataKategori();
					$("#modal_kategori").hide();
				})
				.catch(function (resp) {
					app.success = false;
					app.errors = resp.response.data.errors;
				});
			},
			saveFormSatuan() {
				var app = this;
				var newsatuan = app.satuan;
				axios.post(app.url_satuan, newsatuan)
				.then(function (resp) {
					app.message = 'Berhasil Menambah Satuan '+ app.satuan.nama_satuan;
					app.alert(app.message);
					app.satuan.nama_satuan = ''
					app.errors = '';
					app.dataSatuan();
					$("#modal_satuan").hide();
				})
				.catch(function (resp) {
					app.success = false;
					app.errors = resp.response.data.errors;
				});
			},
			tutupModal(){
				$("#modal_kategori").hide();  
				$("#modal_satuan").hide();  
			},
			kosongkanData(){
				var app = this;

				app.produk.foto = '';
				app.produk.kode_barcode = '';
				app.produk.kode_barang = '';
				app.produk.nama_barang = '';
				app.produk.kategori_barang_id = '';
				app.produk.satuan_id = '';
				app.produk.harga_beli = '';
				app.produk.harga_jual = '';
				app.produk.harga_jual2 = '';
				app.produk.perkiraan_berat = '';
				app.produk.hitung_stok = 'true';
				app.produk.status_aktif = 'true';
				app.produk.deskripsi_produk = '';
				app.errors = '';
			}
		}
	}
</script>