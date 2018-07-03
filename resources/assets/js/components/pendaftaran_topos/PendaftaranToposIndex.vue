
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
					<h4 class="card-title"> Daftar Topos</h4>

					<div class="toolbar">

						<vue-simple-spinner v-if="loading"></vue-simple-spinner>

						<div class="row" id="">
							<div class="col-md-2"></div>
							<div class="col-md-8">
								<h3 id="timer" style="color:red"></h3>
							</div>
						</div>

						<span v-if="seen">
							<form v-on:submit.prevent="saveForm()" class="form-horizontal" v-if="status_daftar_topos == true"> 
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
									<div class="col-md-2">
										<selectize-component v-model="warung.lama_berlangganan" :settings="placeholder_lama_berlangganan" id="lama_berlangganan" ref='lama_berlangganan'> 
											<option v-bind:value="1">1 BULAN</option>
											<option v-bind:value="2">6 BULAN</option>
											<option v-bind:value="3">12 BULAN</option>
										</selectize-component>
										<br v-if="errors.lama_berlangganan">  <span v-if="errors.lama_berlangganan" id="lama_berlangganan_error" class="label label-danger">{{ errors.lama_berlangganan[0] }}</span>
									</div>
									<div class="col-md-2">									
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


							<form v-on:submit.prevent="kirimBuktiPembayaran()" class="form-horizontal" v-else> 

								<span id="timerOut">
									<div class="form-group">
										<label for="total" class="col-md-2 control-label"><b>Mohon Lakukan Pembayaran Sebesar</b></label>
										<div class="col-md-4">
											<money  readonly="" class="form-penjualan" id="total" name="total" placeholder="Total"  v-model="total" v-bind="separator" ></money> 
										</div>
									</div>

									<div class="form-group">
										<div class="col-md-2"></div>
										<div class="col-md-8">
											<h6 style="color:red"><b>Melalui Transfer Ke</b></h6>
										</div>
									</div>

									<div class="form-group">
										<label for="tujuan_transfer" class="col-md-2 control-label"><b>Tujuan Transfer</b></label>
										<div class="col-md-4">
											<input type="text" readonly="" class="form-control" id="tujuan_transfer" name="tujuan_transfer" placeholder="Tujuan Transfer"  v-model="tujuan_transfer">
										</div>
									</div>

									<div class="form-group">
										<label for="no_rek_transfer" class="col-md-2 control-label"><b>No. Rekening Transfer</b></label>
										<div class="col-md-4">									
											<input type="text" readonly="" class="form-control" id="no_rek_transfer" name="no_rek_transfer" placeholder="No. Rekening Transfer"  v-model="no_rek_transfer">
										</div>
									</div>

									<div class="form-group">
										<label for="atas_nama" class="col-md-2 control-label"><b>Atas Nama</b></label>
										<div class="col-md-4">									
											<input type="text" readonly="" class="form-control" id="atas_nama" name="atas_nama" placeholder="Atas Nama"  v-model="atas_nama">
										</div>
									</div>


									<div class="form-group">
										<div class="col-md-2"></div>
										<div class="col-md-8">
											<h6 style="color:red"><b>Lakukan Konfirmasi Pembayaran Dengan Meng-upload Foto Bukti Transfer</b></h6>
										</div>
									</div>

									<div class="form-group">
										<label for="foto" class="col-md-2 control-label"><b>Foto Bukti Transfer</b></label>
										<div class="col-md-10">
											<div class="fileinput fileinput-new text-center" data-provides="fileinput">
												<div class="fileinput-new thumbnail">
													<img v-if="buktiPembayaran.foto != ''" :src="url_picture+'/'+buktiPembayaran.foto" /> 
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
									<div class="form-group">
										<label for="foto" class="col-md-2 control-label"><b>Keterangan</b></label>
										<div class="col-md-4">
											<label class="control-label"> Tuliskan Keterangan Jika di Perlukan</label>
											<textarea class="form-control col-md-4" rows="5" name="keterangan" id="keterangan" v-model="buktiPembayaran.keterangan"></textarea>
										</div>
									</div> 

									<div class="form-group">
										<div class="col-md-4 col-md-offset-2">
											<button class="btn btn-primary" id="btnUbahProfil" type="submit"><i class="material-icons">send</i> Kirim Bukti Pembayaran</button>
										</div>
									</div>
								</span>
							</form>

						</span>

						<span v-if="selesai">
							<div class="alert alert-success">
								<span>
									<b> <h4>Terima Kasih Telah Mengirimkan Bukti Pembayaran, Aplikasi Yang Anda Pesan Akan Diproses. Kami Akan Segera Menghubungi Anda</h4> </b>
								</span>
							</div>
						</span>

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
			url_picture : window.location.origin+(window.location.pathname).replace("dashboard", "foto_produk"),
			url_origin : window.location.origin+(window.location.pathname).replace("dashboard", ""),
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
			buktiPembayaran :{
				foto : '',
				keterangan : ''
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
			id_pendaftaran_topos : '',
			status_daftar_topos : true,
			seen : false,			
			loading: true,		
			selesai: false,
			url : window.location.origin+(window.location.pathname).replace("dashboard", "daftar-topos"),
			errors: []
		}
	},
	mounted() {
		let app = this;	
		app.dataWarung()
		app.dataBank()
		app.cekPendaftaranTopos()
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
		cekPendaftaranTopos(){

			let app = this;
			axios.get(app.url)
			.then(function (resp) {

				app.seen = true
				app.loading = false

				if (resp.data.status_pembayaran >= 1) {		
					app.selesai = true	
					app.seen = false	
				}else{

					if (resp.data.status_daftar == 1) {
						app.status_daftar_topos = false
						app.id_pendaftaran_topos = resp.data.id
						app.atas_nama = resp.data.atas_nama
						app.no_rek_transfer = resp.data.no_rekening
						app.tujuan_transfer = resp.data.nama_bank
						app.total = resp.data.total
						app.cobaTimer(resp.data.batas_pendaftaran)
					}else{
						app.status_daftar_topos = true
					}				

				}

			})
			.catch(function () {
				alert("Tidak dapat Memuat Pendaftaran Topos")
			});

		},
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
				app.seen = true
				app.loading = false

			})
			.catch(function () {
				alert("Tidak dapat memuat Data Warung")
				app.seen = true
				app.loading = false
			});

		}, 
		dataBank(){

			let app = this;
			axios.get(app.url+"/data-bank")
			.then(function (resp) {
				app.bank = resp.data;	
				app.seen = true
				app.loading = false		

			})
			.catch(function () {
				alert("Tidak dapat memuat Data Bank")
				app.seen = true
				app.loading = false
			});

		},    
		saveForm() {
			var app = this;
			var newWarung = app.warung;
			app.seen = false
			app.loading = true
			axios.post(app.url,newWarung)
			.then(function (resp) {				
				app.message = 'Mendaftar';
				app.alert(app.message);
				app.cekPendaftaranTopos();
			})
			.catch(function (resp) {				
				app.seen = true
				app.loading = false
				console.log(resp);
				app.errors = resp.response.data.errors;
				alert("Periksa kembali data yang anda masukan");
			});
		},
		kirimBuktiPembayaran() {
			var app = this; 
			app.loadingSubmit()
			var newBuktiPembayaran = app.inputData();
			
			axios.post(app.url+'/kirim-bukti-pembayaran/'+app.id_pendaftaran_topos,newBuktiPembayaran)
			.then(function (resp) {
				app.selesai = true	
				app.seen = false	
				app.message = 'Mengirim Bukti Pembayaran';
				app.alert(app.message);
				app.$swal.close();
				app.displayNoneTimer();

			})
			.catch(function (resp) {
				app.errors = resp.response.data.errors;
				app.$swal.close();
			});
		},
		displayNoneTimer(){
			$("#timer").hide()
		},
		inputData(){
			var app = this;
			let newBuktiPembayaran = new FormData();
			if (document.getElementById('foto').files[0] != undefined) {
				newBuktiPembayaran.append('foto', document.getElementById('foto').files[0]);
			}
			newBuktiPembayaran.append('keterangan', app.buktiPembayaran.keterangan);
			return newBuktiPembayaran;
		},
		loadingSubmit(){
			this.$swal({
				title: "Sedang Memproses Data ...",
				text: "Harap Tunggu!",
				icon: "info",
				buttons:  false,
				closeOnClickOutside: false,
				closeOnEsc: false

			});
		},
		alert(pesan) {
			this.$swal({
				title: "Berhasil",
				text: pesan,
				icon: "success",
			});
		},
		startTimer() {
			var app = this
			var presentTime = app.timer
			var timeArray = presentTime.split(/[:]+/);
			var m = timeArray[0];
			var s = checkSecond((timeArray[1] - 1));
			if(s==59){m=m-1}
  			//if(m<0){alert('timer completed')}

  		app.timer = m + ":" + s;
  		if(m < "00"){

  		}
  		else{
  			setTimeout(startTimer, 1000);
  		}
  	},
  	checkSecond(sec) {
  		if (sec < 10 && sec >= 0) {
  			sec = "0" + sec
 			 }; // add zero in front of numbers < 10
 			 if (sec < 0) {
 			 	sec = "59"
 			 };
 			 return sec;
 			},
 			cobaTimer(batas_pendaftaran){

		 		// Tetapkan tanggal yang harus kita hitung
		 		var countDownDate = new Date(batas_pendaftaran).getTime();

				// Perbarui hitungan mundur setiap 1 detik
				var x = setInterval(function() {

		  		// Dapatkan tanggal dan waktu sekarang
		  		var now = new Date().getTime();

		  		// Temukan jarak antara sekarang hitungan mundur
		  		var distance = countDownDate - now;

				  // Perhitungan waktu berhari-hari, jam, menit dan detik
				  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
				  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
				  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
				  var seconds = Math.floor((distance % (1000 * 60)) / 1000);

				  // Display the result in the element with id="timer"
				  document.getElementById("timer").innerHTML = "Selesaikan Pembayaran Sebelum : " + hours + " Jam "
				  + minutes + " Menit " + seconds + " Detik";

				  //  Jika hitungan mundur selesai, tuliskan beberapa teks
				  if (distance < 0) {
				  	clearInterval(x);
				  	document.getElementById("timer").innerHTML = "Waktu Pembayaran Anda Habis, Karena Telah Melebihi Batas Waktu yang ditentukan";
				  	$("#timerOut").hide();
				  }
				}, 1000);
			}
		}
	}
	</script>