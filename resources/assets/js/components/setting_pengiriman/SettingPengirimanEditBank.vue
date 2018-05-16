<template>
	<div class="row">
        <div class="col-md-12">
            <ul class="breadcrumb">
                <li><router-link :to="{name: 'indexDashboard'}">Home</router-link></li>
                <li><router-link :to="{name: 'indexSettingPengiriman', params: {tab: 'bankTransfer'}}">Setting Pengiriman</router-link></li>
                <li class="active">Tambah Bank Transfer</li>
            </ul>
            <div class="card">
                <div class="card-header card-header-icon" data-background-color="purple">
                    <i class="material-icons">settings_applications</i>
                </div>
                <div class="card-content">
                    <h4 class="card-title">Tambah Bank Transfer</h4>
                    <form v-on:submit.prevent="saveForm()" class="form-horizontal"> 
                    	<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="nama_bank" class="col-md-2 control-label">Nama Bank</label>
									<div class="col-md-10">
										<input class="form-control" v-model="bank_transfer.nama_bank" autocomplete="off" placeholder="Nama Bank" type="text" name="nama_bank" id="nama_bank">
									</div>
								</div>
								<div class="form-group">
									<label for="logo_bank" class="col-md-2 control-label">Logo Bank</label>
									<div class="col-md-4">
										<div class="fileinput fileinput-new text-center" data-provides="fileinput">
											<div class="fileinput-new thumbnail">
												<img v-if="bank_transfer.logo_bank != ''" :src="url_gambar_bank + '/' + bank_transfer.logo_bank" />
												<img v-else :src="url_origin + '/assets/img/image_placeholder.jpg'" alt="Foto Akan Tampil Disini">
											</div>
											<div class="fileinput-preview fileinput-exists thumbnail"></div>
											<div>
												<span class="btn btn-rose btn-round btn-file">
													<span class="fileinput-new">Ambil Foto</span>
													<span class="fileinput-exists">Ubah Foto</span>
													<input class="form-control" type="file" name="logo_bank" id="logo_bank">
												</span>
												<a href="#pablo" class="btn btn-danger btn-round fileinput-exists" data-dismiss="fileinput"><i class="fa fa-times"></i> Batal</a>
											</div>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label for="tampil_bank" class="col-md-2 control-label">Pilih</label>
									<div class="col-md-4 checkbox">
										<label>
											<input type="checkbox" v-model="bank_transfer.tampil_bank" true-value="1" false-value="0" name="tampil_bank" id="tampil_bank"> Tampilkan Bank
										</label>
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-10 col-md-offset-1">
										<button class="btn btn-primary" id="btnSimpanProduk" type="submit"><i class="material-icons">send</i> Submit</button>
									</div>
								</div>
							</div>
						</div>
					</form>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
	mounted() {
		let app = this;
        let id_bank = app.$route.params.id;

        app.getDataSettingBanks(id_bank);
	},
	data: function () {
		return {
			url_gambar_bank: window.location.origin + (window.location.pathname).replace("dashboard", "jasa_pengiriman"),
			url_origin: window.location.origin + (window.location.pathname).replace("dashboard", ""),
			url: window.location.origin + (window.location.pathname).replace("dashboard", "setting-pengiriman"),
			bank_transfer: {}
		}
	},
	methods: {
		saveForm() {
			var app = this;

			axios.patch(app.url + '/edit-bank-transfer', app.bank_transfer)
			.then((resp) => {
				swal({
					title: 'Berhasil!',
					type: 'success',
					showConfirmButton: false,
					text: 'Berhasil Mengedit Bank Transfer.',
				})
				.then(() => {
					app.$router.replace('/setting-pengiriman');

					// kirim bankTransfer sebagai parameter untuk diproses di halaman index setting pengiriman
					app.$route.params.tab = 'bankTransfer';
				})
				.catch((resp) => {
					console.log('catch edit bank: ', resp);
				});

				setTimeout(() => {
					swal.clickConfirm();
				}, 1800);
			})
			.catch((resp) => {
				console.log('catch:', resp);					
				swal({
					title: 'Gagal!',
					type: 'warning',
					showConfirmButton: false,
					text: 'Gagal Mengedit Bank Transfer.',
					timer: 2000
				});
			});
		},
		getDataSettingBanks(id_bank) {
			let app = this;
			axios.get(app.url + '/get-data-bank-transfer/' + id_bank)
			.then((resp) => {
				app.bank_transfer = resp.data;
			})
			.catch((resp) => {
				console.log('catch: ', resp);
			});
		}	
	}
}
</script>