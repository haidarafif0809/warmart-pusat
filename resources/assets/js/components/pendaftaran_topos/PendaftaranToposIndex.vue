
<style scoped>
.modal {
	overflow-y:auto;
}

.form-penjualan{
	width: 100%;
	padding: 12px 20px;
	margin: 8px 0;
	display: inline-block;
	border: 3px solid #555;
	border-radius: 4px;
	box-sizing: border-box;
	font-size: 30px;
}

</style>
<template>
	
	<div class="row">
		<div class="col-md-12">
			<ul class="breadcrumb">
				<li><router-link :to="{name: 'indexDashboard'}">Home</router-link></li>
				<li class="active">Pendaftaran Topos</li>
			</ul>


			<div class="card">
				<div class="card-header card-header-icon" data-background-color="purple">
					<i class="material-icons">store</i>
				</div>
				<div class="card-content">
					<h4 class="card-title"> Daftar Topos </h4>
					<div class="toolbar">

						<form v-on:submit.prevent="saveForm()" class="form-horizontal"> 
							<div class="form-group">
								<label for="nama" class="col-md-2 control-label">Nama Toko</label>
								<div class="col-md-4">
									<input class="form-control" autocomplete="off" placeholder="Nama" v-model="warung.name" type="text" name="name" id="name"  autofocus="">
									<span v-if="errors.name" id="name_error" class="label label-danger">{{ errors.name[0] }}</span>
								</div>
							</div>
							<div class="form-group">
								<label for="no_telpon" class="col-md-2 control-label">No. Telp</label>
								<div class="col-md-4">
									<input class="form-control" autocomplete="off" placeholder="No. Telp" v-model="warung.no_telpon" type="number" name="no_telpon" id="no_telpon"  autofocus="">
									<span v-if="errors.no_telpon" id="no_telpon_error" class="label label-danger">{{ errors.no_telpon[0] }}</span>
								</div>
							</div>
							<div class="form-group">
								<label for="email" class="col-md-2 control-label">Email</label>
								<div class="col-md-4">
									<input class="form-control" autocomplete="off" placeholder="Email" v-model="warung.email" type="email" name="email" id="email"  autofocus="">
									<span v-if="errors.email" id="email_error" class="label label-danger">{{ errors.email[0] }}</span>
								</div>
							</div>
							<div class="form-group">
								<label for="alamat" class="col-md-2 control-label">Alamat</label>
								<div class="col-md-4">
									<input class="form-control" autocomplete="off" placeholder="Alamat" v-model="warung.alamat" type="text" name="alamat" id="alamat"  autofocus="">
									<span v-if="errors.alamat" id="alamat_error" class="label label-danger">{{ errors.alamat[0] }}</span>
								</div>
							</div>		

							<div class="form-group">
								<label for="lama_berlangganan" class="col-md-2 control-label">Lama Berlangganan</label>
								<div class="col-md-3">
									<selectize-component v-model="warung.lama_berlangganan" :settings="placeholder_lama_berlangganan" id="lama_berlangganan" ref='lama_berlangganan'> 
										<option v-bind:value="1">1 BULAN</option>
										<option v-bind:value="2">6 BULAN</option>
										<option v-bind:value="3">12 BULAN</option>
									</selectize-component>
									<br v-if="errors.lama_berlangganan">  <span v-if="errors.lama_berlangganan" id="lama_berlangganan_error" class="label label-danger">{{ errors.lama_berlangganan[0] }}</span>
								</div>
								<div class="col-md-1">									
									<input class="form-control" autocomplete="off" v-model="biaya_langganan" type="text" name="biaya_langganan" id="biaya_langganan"  autofocus="" readonly="">
								</div>
							</div>

							<div class="form-group">
								<label for="berlaku_hingga" class="col-md-2 control-label">Berlaku Hingga</label>
								<div class="col-md-4">
									<input class="form-control" autocomplete="off" placeholder="Berlaku Hingga" v-model="warung.berlaku_hingga" type="text" name="berlaku_hingga" id="berlaku_hingga"  autofocus="" readonly="">
									<span v-if="errors.berlaku_hingga" id="berlaku_hingga_error" class="label label-danger">{{ errors.berlaku_hingga[0] }}</span>
								</div>
							</div>

							<div class="form-group">
								<label for="pembayaran" class="col-md-2 control-label">Pembayaran</label>
								<div class="col-md-4">
									<input class="form-control" autocomplete="off" placeholder="Pembayaran" v-model="warung.pembayaran" type="text" name="pembayaran" id="pembayaran"  autofocus="" readonly="">
									<span v-if="errors.pembayaran" id="pembayaran_error" class="label label-danger">{{ errors.pembayaran[0] }}</span>
								</div>
							</div>

							<div class="form-group">
								<label for="total" class="col-md-2 control-label">Total Bayar</label>
								<div class="col-md-4">
									<money  readonly="" class="form-penjualan" id="total" name="total" placeholder="Total"  v-model="warung.total" v-bind="separator" ></money> 
									<span v-if="errors.total" id="total_error" class="label label-danger">{{ errors.total[0] }}</span>
								</div>
							</div>

							<div class="form-group">
								<label for="tujuan_transfer" class="col-md-2 control-label">Tujuan Transfer</label>
								<div class="col-md-4">
									<selectize-component v-model="warung.tujuan_transfer" :settings="placeholder_tujuan_transfer" id="tujuan_transfer" ref='tujuan_transfer'> 
										<option v-for="banks, index in bank" v-bind:value="banks.id+'|'+banks.no_rek+'|'+banks.atas_nama">{{ banks.nama_bank }}</option>
									</selectize-component>
									<br v-if="errors.tujuan_transfer">  <span v-if="errors.tujuan_transfer" id="tujuan_transfer_error" class="label label-danger">{{ errors.tujuan_transfer[0] }}</span>
								</div>
							</div>

							<div class="form-group">
								<label for="no_rek_transfer" class="col-md-2 control-label">No. Rekening Transfer</label>
								<div class="col-md-4">									
									<input type="text" readonly="" class="form-control" id="no_rek_transfer" name="no_rek_transfer" placeholder="No. Rekening Transfer"  v-model="warung.no_rek_transfer">
									<span v-if="errors.no_rek_transfer" id="no_rek_transfer_error" class="label label-danger">{{ errors.no_rek_transfer[0] }}</span>
								</div>
							</div>

							<div class="form-group">
								<label for="atas_nama" class="col-md-2 control-label">Atas Nama</label>
								<div class="col-md-4">									
									<input type="text" readonly="" class="form-control" id="atas_nama" name="atas_nama" placeholder="Atas Nama"  v-model="warung.atas_nama">
									<span v-if="errors.atas_nama" id="atas_nama_error" class="label label-danger">{{ errors.atas_nama[0] }}</span>
								</div>
							</div>


							<input class="form-control" autocomplete="off" v-model="warung.id" type="hidden" name="id" id="id"  autofocus="">
							<div class="form-group">
								<div class="col-md-4 col-md-offset-2">
									<button class="btn btn-primary" id="btnUbahProfil" type="submit"><i class="material-icons">send</i> Selesai</button>
								</div>
							</div>
						</form>

					</div>

				</div>
			</div>
		</div>
	</div>

</template>



<script>
export default {
	
	data: function () {
		return {
			bank: [],
			warung: {
				id : '',
				name : '',
				no_telpon : '',
				email : '',
				alamat : '',
				lama_berlangganan : '',
				berlaku_hingga : '',
				pembayaran : 'ATM/BANK TRANSFER',
				total : '0',
				tujuan_transfer : '',
				no_rek_transfer : '',
				atas_nama : ''
			},
			placeholder_lama_berlangganan: {
				placeholder: '--LAMA BERLANGANAN--'
			},
			placeholder_tujuan_transfer : {
				placeholder: '--TUJUAN TRANSFER--'
			},
			biaya_langganan : '',
			message : '',
			separator: {
				decimal: ',',
				thousands: '.',
				prefix: '',
				suffix: '',
				precision: 2,
				masked: false /* doesn't work with directive */
			},
			url : window.location.origin+(window.location.pathname).replace("dashboard", "daftar-topos"),
			errors: []
		}
	},
	mounted() {
		let app = this;	
		app.dataWarung()
		app.dataBank()
	},
	watch: {
		'warung.lama_berlangganan': function () {
			this.berlakuHingga()
		},
		'warung.tujuan_transfer': function () {
			this.cekNoRekening()
		}

	},
	methods: {   
		cekNoRekening(){
			var app =  this
			var bank_transfer = app.warung.tujuan_transfer.split("|")			
			app.warung.no_rek_transfer = bank_transfer[1]
			app.warung.atas_nama = bank_transfer[2]
		},
		berlakuHingga(){
			var app = this
			var now = new Date(); // HARI INI
			var nowDay= now.getDate();// TANGGAL SEKARANG
			var nowMonth = now.getMonth();  // BULAN SEKARANG
			var nowYear= now.getFullYear(); // TAHUN SEKARANG

			var monthNames = [
			"Januari", "Februari", "Maret",
			"April", "Mei", "Juni", "Juli",
			"Agustus", "September", "Oktober",
			"November", "Desember"
			];
			if (app.warung.total > 0) {					
				app.warung.total = 0
			}
			if (app.warung.lama_berlangganan == 1) {
				var satu_bulan = monthNames[nowMonth + 1]
				var harga_perbulan = 500000
				app.biaya_langganan = 'Rp. 500.000,00 / Bulan'
				app.warung.berlaku_hingga = nowDay + ' ' + satu_bulan + ' ' + nowYear;
				app.warung.total += harga_perbulan

			}else if (app.warung.lama_berlangganan == 2) {
				var enam_bulan = monthNames[nowMonth + 6]
				var harga_perbulan = 300000
				app.biaya_langganan = 'Rp. 300.000,00 / Bulan'
				app.warung.berlaku_hingga = nowDay + ' ' + enam_bulan + ' ' + nowYear;
				app.warung.total = parseInt(harga_perbulan) * parseInt(6)

			}else if (app.warung.lama_berlangganan == 3) {

				var duabelas_bulan = nowYear + 1
				var harga_perbulan = 200000
				app.biaya_langganan = 'Rp. 200.000,00 / Bulan'
				app.warung.berlaku_hingga = nowDay + ' ' + monthNames[nowMonth] + ' ' + duabelas_bulan;
				app.warung.total = parseInt(harga_perbulan) * parseInt(12)

			}
		},
		dataWarung(){

			let app = this;
			axios.get(app.url+"/data-warung")
			.then(function (resp) {
				app.warung.id = resp.data.id;
				app.warung.name = resp.data.name;
				app.warung.no_telpon = resp.data.no_telpon;
				app.warung.email = resp.data.email;
				app.warung.alamat = resp.data.alamat;

			})
			.catch(function () {
				alert("Tidak dapat memuat Data Warung")
			});

		}, 
		dataBank(){

			let app = this;
			axios.get(app.url+"/data-bank")
			.then(function (resp) {
				app.bank = resp.data;			

			})
			.catch(function () {
				alert("Tidak dapat memuat Data Bank")
			});

		},    
		saveForm() {
			var app = this;
			var newWarung = app.warung;
			axios.post(app.url,newWarung)
			.then(function (resp) {
				app.message = 'Berhasil';
				app.alert(app.message);
			})
			.catch(function (resp) {
				console.log(resp);
				app.errors = resp.response.data.errors;
				alert("Periksa kembali data yang anda masukan");
			});
		},
		alert(pesan) {
			this.$swal({
				title: "Berhasil !",
				text: pesan,
				icon: "success",
			});
		}
	}
}
</script>