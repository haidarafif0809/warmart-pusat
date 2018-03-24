<template>
		<div class="row">
			<div class="col-md-12">
				<ul class="breadcrumb">
					<li><router-link :to="{name: 'indexDashboard'}">Home</router-link></li>
					<li><router-link :to="{name: 'indexSettingPromo'}">Setting Promo</router-link></li>
					<li class="active">Tambah Setting Promo</li>
				</ul>
				<div class="card">

					<div class="card-header card-header-icon" data-background-color="purple">
						<i class="material-icons">settings_applications</i>
					</div>

					<div class="card-content">
						<h4 class="card-title"> Setting Promo </h4>
						<form v-on:submit.prevent="saveForm()" class="form-horizontal"> 
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										
										<label for="kode_barcode" class="col-md-2 control-label">Produk</label>
										<div class="col-md-5">
										<selectize-component v-model="setting.produk":settings="placeholder_produk" id="pilih_produk"> 
										<option v-for="produks, index in produk" v-bind:value="produks.id+'|'+produks.harga_jual" >{{ produks.nama_produk }}</option>
										</selectize-component>
									</div>
									</div>

									<div class="form-group">
										<label for="harga_produk" class="col-md-2 control-label">Harga Produk</label>
										<div class="col-md-5">
											<input class="form-control" autocomplete="off" readonly="" placeholder="Harga Produk" v-model="setting.harga_produk" type="text" name="harga_produk" id="harga_produk" ref="harga_produk">
											<span v-if="errors.harga_produk" v-shortkey="['f1']" @shortkey="openSelectizeProduk()" id="harga_produk_error" class="label label-danger">{{ errors.harga_produk[0] }}</span>
										</div>
									</div>


									<div class="form-group">
										<label for="harga_coret" class="col-md-2 control-label">Harga Promo</label>
										<div class="col-md-5">
											<input class="form-control" autocomplete="off" placeholder="Harga Coret" v-model="setting.harga_coret" type="text" name="harga_coret" id="harga_coret" ref="harga_coret" autofocus="">
											<span v-if="errors.harga_coret" id="harga_coret_error" class="label label-danger">{{ errors.harga_coret[0] }}</span>
										</div>
									</div>

									
								<div class="form-group">
									<label for="foto" class="col-md-2 control-label">Baner Promo</label>
										<div class="col-md-10">
										<div class="fileinput fileinput-new text-center" data-provides="fileinput" id="div-foto">
											<div class="fileinput-new thumbnail">
												<img v-if="setting.baner_promo != ''" :src="url_picture+'/'+setting.baner_promo" /> 
												<img :src="url_origin+'/assets/img/image_placeholder.jpg'" alt="Baner Akan Tampil Disini" v-else>
											</div>
											<div class="fileinput-preview fileinput-exists thumbnail"></div>
											<div>
												<span class="btn btn-rose btn-round btn-file">
													<span class="fileinput-new">Ambil Baner</span>
													<span class="fileinput-exists">Ubah</span>
													<input class="form-control" type="file" name="baner_promo" id="baner_promo">
												</span>
												<a href="#pablo" class="btn btn-danger btn-round fileinput-exists" data-dismiss="fileinput"><i class="fa fa-times"></i> Batal</a>
											</div>
											<span v-if="errors.baner_promo" id="baner_promo_error" class="label label-danger">{{ errors.baner_promo[0] }}</span>
											<a style="color: red;">Size Baner (Ukuran Max : 3MB , Ukuran Dimensi : 1450 × 750 )</a>
										</div>
									</div>
								</div> 

								<div class="form-group">
									<label for="foto" class="col-md-2 control-label">Hari</label>
										<div class="col-md-10">
							            <div class="checkbox" v-for="permission_users, index in permission_user">
							              <label>
							                <input type="checkbox" name="setting_user" v-bind:value="permission_users.id" v-model="setting_otoritas.user"> {{permission_users.display_name}}
							              </label>
							            </div> 
									</div>
								</div> 
							         

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

</div>
</template>


<script>
import { mapState } from 'vuex';
	export default {
		data: function () {
			return {
				errors: [],
				url : window.location.origin+(window.location.pathname).replace("dashboard", "setting-promo"),
				url_picture : window.location.origin+(window.location.pathname).replace("dashboard", "baner_setting_promo"),
				url_origin : window.location.origin+(window.location.pathname).replace("dashboard", ""),
				setting: {
					baner_promo : '',
					harga_coret : '',
					produk : '',
					harga_produk : ''
				},
				message : '',
				separator: {
					decimal: ',',
					thousands: '.',
					prefix: '',
					suffix: '',
					precision: 0,
					masked: false /* doesn't work with directive */
				},
				placeholder_produk: {
		        placeholder: '--PILIH PRODUK--',
		        sortField: 'text',
		        maxOptions : 8,
		        scrollDuration : 10,
		        loadThrottle : 150,
		        openOnFocus : false
		      },
			}
		},

		mounted() {
		var app = this;
		app.$store.dispatch('LOAD_PRODUK_LAPORAN_LIST');
		},
		computed : mapState ({    
		produk(){
				return this.$store.getters.produk_setting
			}
		}),
		watch: {
			'setting.produk': function () {
	      		this.pilihProduk()
	    }
		},
		methods: {
			openSelectizeProduk(){      
			      this.$refs.produk.$el.selectize.focus();
			},
			pilihProduk(){
				if (this.setting.produk != '') {
			      var app = this;
			      var produk = app.setting.produk.split("|");
			      var harga_produk = produk[1];
			      app.setting.harga_produk = harga_produk;			      
			    }
			},
			saveForm() {
				var app = this; 
				app.loading();
				var newSettingPromo = app.inputData();
				axios.post(app.url, newSettingPromo)
				.then(function (resp) {
					app.message = 'Menambah Setting Promo ';
					app.alert(app.message);
					app.kosongkanData();
					app.$router.replace('/setting-promo');
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

				let newSettingPromo = new FormData();
				if (document.getElementById('baner_promo').files[0] != undefined) {
					newSettingPromo.append('baner_promo', document.getElementById('baner_promo').files[0]);
				}
				newSettingPromo.append('produk', app.setting.produk);
				newSettingPromo.append('harga_coret', app.setting.harga_coret);

				return newSettingPromo;
			},
			kosongkanData(){
				var app = this;

				app.setting.baner_promo = '';
				app.setting.produk = '';
				app.setting.harga_coret = '';
				app.errors = '';
			}
		}
	}
</script>