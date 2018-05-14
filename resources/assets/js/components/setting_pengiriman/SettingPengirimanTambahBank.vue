<template>
	<div class="row">
        <div class="col-md-12">
            <ul class="breadcrumb">
                <li><router-link :to="{name: 'indexDashboard'}">Home</router-link></li>
                <li><router-link :to="{name: 'indexSettingPengiriman'}">Setting Pengiriman</router-link></li>
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
									<label for="tampilkan_bank" class="col-md-2 control-label">Pilih</label>
									<div class="col-md-4 checkbox">
										<label>
											<input type="checkbox" v-model="bank_transfer.tampilkan_bank" true-value="1" false-value="0" name="tampilkan_bank" id="tampilkan_bank"> Tampilkan Bank
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

		},
		data: function () {
			return {
				url_gambar_bank: window.location.origin+(window.location.pathname).replace("dashboard", "jasa_pengiriman"),
				url_origin: window.location.origin+(window.location.pathname).replace("dashboard", ""),
				url: window.location.origin+(window.location.pathname).replace("dashboard", "setting-pengiriman"),
				bank_transfer: {
					nama_bank: '',
					logo_bank: '',
					tampilkan_bank: ''
				}
			}
		},
		watch: {
			'bank_transfer.tampilkan_bank': function (val) {
				console.log(val);
			}
		},
		methods: {
			saveForm() {
				var app = this;
				var newBankTransfer = new FormData();
				if (document.getElementById('logo_bank').files[0] != undefined) {
					newBankTransfer.append('logo_bank', document.getElementById('logo_bank').files[0]);
				}
				newBankTransfer.append('nama_bank', app.bank_transfer.nama_bank);

				if (app.bank_transfer.tampilkan_bank == '') {
					newBankTransfer.append('tampilkan_bank', 0);
				} else {
					newBankTransfer.append('tampilkan_bank', app.bank_transfer.tampilkan_bank);
				}

				axios.post(app.url + '/tambah-bank-transfer', newBankTransfer)
				.then((resp) => {
					console.log('then:', resp);
					swal({
						title: 'Berhasil!',
						type: 'success',
						showConfirmButton: false,
						text: 'Berhasil Menambah Bank Transfer.',
						timer: 1800
					});
				})
				.catch((resp) => {
					console.log('catch:', resp);					
					swal({
						title: 'Gagal!',
						type: 'warning',
						showConfirmButton: false,
						text: 'Gagal Menambahkan Bank Transfer.',
						timer: 2000
					});
				});
			}	
		}
	}
</script>