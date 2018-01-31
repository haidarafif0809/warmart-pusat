<style scoped>
	.pencarian {
		color: red; 
		float: right;
		padding-bottom: 10px;
	}
</style>

<template>
	<div class="row">
		<div class="col-md-12">
			<ul class="breadcrumb">
				<li><router-link :to="{name: 'indexDashboard'}">Home</router-link></li>
				<li class="active">Laporan Kas</li>
			</ul>
			<div class="card">
				<div class="card-header card-header-icon" data-background-color="purple">
					<i class="material-icons">local_atm</i>
				</div>

				<div class="card-content">
					<h4 class="card-title"> Laporan Kas </h4>

					<div class="row">
						<div class="form-group col-md-2">
							<selectize-component v-model="filter.kas" :settings="placeholder_kas" id="kas" ref="kas"> 
								<option v-for="data_kas, index in kas" v-bind:value="data_kas.id">
									{{ data_kas.nama_kas }}
								</option>
							</selectize-component>
							<input class="form-control" type="hidden"  v-model="filter.kas"  name="kas" id="kas"  v-shortkey="['f1']" @shortkey="pilihKas()">
						</div>
						<div class="form-group col-md-2">
							<selectize-component v-model="filter.jenis_laporan" :settings="placeholder_laporan" id="jenis_laporan" ref="jenis_laporan"> 
								<option v-bind:value="0" > Laporan Detail </option>
								<option v-bind:value="1" > Laporan Rekap </option>
							</selectize-component>
							<input class="form-control" type="hidden"  v-model="filter.jenis_laporan"  name="jenis_laporan" id="jenis_laporan"  v-shortkey="['f1']" @shortkey="pilihJenisLaporan()">
						</div>
						<div class="form-group col-md-2">
							<datepicker :input-class="'form-control'" placeholder="Dari Tanggal" v-model="filter.dari_tanggal" name="dari_tanggal" v-bind:id="'dari_tanggal'">
							</datepicker>
						</div>
						<div class="form-group col-md-2">
							<datepicker :input-class="'form-control'" placeholder="Sampai Tanggal" v-model="filter.sampai_tanggal" name="sampai_tanggal" v-bind:id="'sampai_tanggal'">								
							</datepicker>
						</div>
						<div class="form-group col-md-2">
							<button class="btn btn-primary" id="btnSubmit" type="submit" style="margin: 0px 0px;" @click="submitLaporan()"><i class="material-icons">search</i> Cari</button>
						</div>
					</div>

					<!-- JENIS LAPORAN == LAPORAN DETAIL -->
					<span id="span-detail" style="display:none">

						<!--KAS MASUK-->
						<div class=" table-responsive">
							<div class="pencarian">
								<input type="text" name="pencarian" v-model="pencarian_kas_masuk" placeholder="Pencarian" class="form-control">
							</div>

							<h4><b>KAS MASUK : <span style="color:red">Rp. {{subtotalLaporanKasDetail | pemisahTitik}}</span></b></h4>
							<table class="table table-striped table-hover">
								<thead class="text-primary">
									<tr>
										<th>No. Transaksi</th>
										<th>Jenis Transaksi</th>
										<th>Ke Kas</th>
										<th style="text-align:right">Total</th>
										<th style="text-align:center">Waktu</th>
									</tr>
								</thead>
								<tbody v-if="laporanKasDetail.length > 0 && loading == false"  class="data-ada">
									<tr v-for="laporanKasDetails, index in laporanKasDetail" >

										<td>{{ laporanKasDetails.data_laporan.no_faktur }}</td>
										<td>{{ laporanKasDetails.jenis_transaksi }}</td>
										<td>{{ laporanKasDetails.data_laporan.nama_kas }}</td>
										<td align="right">{{ laporanKasDetails.data_laporan.jumlah_masuk | pemisahTitik }}</td>
										<td align="center">{{ laporanKasDetails.data_laporan.created_at | tanggal }}</td>

									</tr>
								</tbody>					
								<tbody class="data-tidak-ada" v-else-if="laporanKasDetail.length == 0 && loading == false">
									<tr ><td colspan="5"  class="text-center">Tidak Ada Data</td></tr>
								</tbody>
							</table>
						</div><!--RESPONSIVE-->

						<vue-simple-spinner v-if="loading"></vue-simple-spinner>
						<div align="right"><pagination :data="laporanKasDetailData" v-on:pagination-change-page="prosesLaporan" :limit="4"></pagination></div>
						<hr style="margin-top: 30px; margin-bottom: 15px; border-top: 5px solid #eeeeee;">

						<!--KAS KELUAR-->
						<div class=" table-responsive">
							<div class="pencarian">
								<input type="text" name="pencarian" v-model="pencarian_kas_keluar" placeholder="Pencarian" class="form-control">
							</div>

							<h4><b>KAS KELUAR : <span style="color:red">Rp. {{subtotalLaporanKasKeluarDetail | pemisahTitik}}</span></b></h4>
							<table class="table table-striped table-hover">
								<thead class="text-primary">
									<tr>
										<th>No. Transaksi</th>
										<th>Jenis Transaksi</th>
										<th>Dari Kas</th>
										<th style="text-align:right">Total</th>
										<th style="text-align:center">Waktu</th>
									</tr>
								</thead>
								<tbody v-if="laporanKasKeluarDetail.length > 0 && loading == false"  class="data-ada">
									<tr v-for="laporanKasKeluarDetails, index in laporanKasKeluarDetail" >

										<td>{{ laporanKasKeluarDetails.data_laporan.no_faktur }}</td>
										<td>{{ laporanKasKeluarDetails.jenis_transaksi }}</td>
										<td>{{ laporanKasKeluarDetails.data_laporan.nama_kas }}</td>
										<td align="right">{{ laporanKasKeluarDetails.data_laporan.jumlah_keluar | pemisahTitik }}</td>
										<td align="center">{{ laporanKasKeluarDetails.data_laporan.created_at | tanggal }}</td>

									</tr>
								</tbody>					
								<tbody class="data-tidak-ada" v-else-if="laporanKasKeluarDetail.length == 0 && loading == false">
									<tr ><td colspan="5"  class="text-center">Tidak Ada Data</td></tr>
								</tbody>
							</table>
						</div><!--RESPONSIVE-->

						<vue-simple-spinner v-if="loading"></vue-simple-spinner>
						<div align="right"><pagination :data="laporanKasKeluarDetailData" v-on:pagination-change-page="prosesLaporanKeluar" :limit="4"></pagination></div>
						<hr style="margin-top: 30px; margin-bottom: 15px; border-top: 5px solid #eeeeee;">

						<!--KAS MUTASI (MASUK)-->
						<div class=" table-responsive">
							<div class="pencarian">
								<input type="text" name="pencarian" v-model="pencarian_mutasi_masuk" placeholder="Pencarian" class="form-control">
							</div>

							<h4><b>KAS MUTASI (MASUK) : <span style="color:red">Rp. {{subtotalLaporanKasMutasiMasukDetail | pemisahTitik}}</span></b></h4>
							<table class="table table-striped table-hover">
								<thead class="text-primary">
									<tr>
										<th>No. Transaksi</th>
										<th>Jenis Transaksi</th>
										<th>Ke Kas</th>
										<th style="text-align:right">Total</th>
										<th style="text-align:center">Waktu</th>
									</tr>
								</thead>
								<tbody v-if="laporanKasMutasiMasukDetail.length > 0 && loading == false"  class="data-ada">
									<tr v-for="laporanKasMutasiMasukDetails, index in laporanKasMutasiMasukDetail" >

										<td>{{ laporanKasMutasiMasukDetails.data_laporan.no_faktur }}</td>
										<td>{{ laporanKasMutasiMasukDetails.jenis_transaksi }}</td>
										<td>{{ laporanKasMutasiMasukDetails.data_laporan.nama_kas }}</td>
										<td align="right">{{ laporanKasMutasiMasukDetails.data_laporan.jumlah_masuk | pemisahTitik }}</td>
										<td align="center">{{ laporanKasMutasiMasukDetails.data_laporan.created_at | tanggal }}</td>

									</tr>
								</tbody>					
								<tbody class="data-tidak-ada" v-else-if="laporanKasMutasiMasukDetail.length == 0 && loading == false">
									<tr ><td colspan="5"  class="text-center">Tidak Ada Data</td></tr>
								</tbody>
							</table>
						</div><!--RESPONSIVE-->

						<vue-simple-spinner v-if="loading"></vue-simple-spinner>
						<div align="right"><pagination :data="laporanKasMutasiMasukDetailData" v-on:pagination-change-page="prosesLaporanMutasiMasuk" :limit="4"></pagination></div>
						<hr style="margin-top: 30px; margin-bottom: 15px; border-top: 5px solid #eeeeee;">

						<!--KAS MUTASI (MASUK)-->
						<div class=" table-responsive">
							<div class="pencarian">
								<input type="text" name="pencarian" v-model="pencarian_mutasi_keluar" placeholder="Pencarian" class="form-control">
							</div>

							<h4><b>KAS MUTASI (KELUAR) : <span style="color:red">Rp. {{subtotalLaporanKasMutasiKeluarDetail | pemisahTitik}}</span></b></h4>
							<table class="table table-striped table-hover">
								<thead class="text-primary">
									<tr>
										<th>No. Transaksi</th>
										<th>Jenis Transaksi</th>
										<th>Ke Kas</th>
										<th style="text-align:right">Total</th>
										<th style="text-align:center">Waktu</th>
									</tr>
								</thead>
								<tbody v-if="laporanKasMutasiKeluarDetail.length > 0 && loading == false"  class="data-ada">
									<tr v-for="laporanKasMutasiKeluarDetails, index in laporanKasMutasiKeluarDetail" >

										<td>{{ laporanKasMutasiKeluarDetails.data_laporan.no_faktur }}</td>
										<td>{{ laporanKasMutasiKeluarDetails.jenis_transaksi }}</td>
										<td>{{ laporanKasMutasiKeluarDetails.data_laporan.nama_kas }}</td>
										<td align="right">{{ laporanKasMutasiKeluarDetails.data_laporan.jumlah_keluar | pemisahTitik }}</td>
										<td align="center">{{ laporanKasMutasiKeluarDetails.data_laporan.created_at | tanggal }}</td>

									</tr>
								</tbody>					
								<tbody class="data-tidak-ada" v-else-if="laporanKasMutasiKeluarDetail.length == 0 && loading == false">
									<tr ><td colspan="5"  class="text-center">Tidak Ada Data</td></tr>
								</tbody>
							</table>
						</div><!--RESPONSIVE-->

						<vue-simple-spinner v-if="loading"></vue-simple-spinner>
						<div align="right"><pagination :data="laporanKasMutasiKeluarDetailData" v-on:pagination-change-page="prosesLaporanMutasiMasuk" :limit="4"></pagination></div>
						<hr style="margin-top: 30px; margin-bottom: 15px; border-top: 5px solid #eeeeee;">

					</span>
					<!-- JENIS LAPORAN == LAPORAN DETAIL -->

					<!-- JENIS LAPORAN == LAPORAN REKAP -->
					<span id="span-rekap" style="display:none">

						<!--KAS MASUK-->
						<div class=" table-responsive">
							<div class="pencarian">
								<input type="text" name="pencarian" v-model="pencarian_kas_masuk_rekap" placeholder="Pencarian" class="form-control">
							</div>

							<h4><b>KAS MASUK : <span style="color:red">Rp. {{subtotalLaporanKasRekap | pemisahTitik}}</span></b></h4>
							<table class="table table-striped table-hover">
								<thead class="text-primary">
									<tr>
										<th>Waktu</th>
										<th>Ke Kas</th>
										<th style="text-align:right">Total</th>
									</tr>
								</thead>
								<tbody v-if="laporanKasRekap.length > 0 && loading == false"  class="data-ada">
									<tr v-for="laporanKasRekaps, index in laporanKasRekap" >

										<td>{{ laporanKasRekaps.data_laporan.created_at | tanggal }}</td>
										<td>{{ laporanKasRekaps.data_laporan.nama_kas }}</td>
										<td align="right">{{ laporanKasRekaps.data_laporan.jumlah_masuk | pemisahTitik }}</td>

									</tr>
								</tbody>					
								<tbody class="data-tidak-ada" v-else-if="laporanKasRekap.length == 0 && loading == false">
									<tr ><td colspan="3"  class="text-center">Tidak Ada Data</td></tr>
								</tbody>
							</table>
						</div><!--RESPONSIVE-->

						<vue-simple-spinner v-if="loading"></vue-simple-spinner>
						<div align="right"><pagination :data="laporanKasRekapData" v-on:pagination-change-page="prosesLaporanRekap" :limit="4"></pagination></div>
						<hr style="margin-top: 30px; margin-bottom: 15px; border-top: 5px solid #eeeeee;">

						<!--KAS KELUAR-->
						<div class=" table-responsive">
							<div class="pencarian">
								<input type="text" name="pencarian" v-model="pencarian_kas_keluar_rekap" placeholder="Pencarian" class="form-control">
							</div>

							<h4><b>KAS KELUAR : <span style="color:red">Rp. {{subtotalLaporanKasKeluarRekap | pemisahTitik}}</span></b></h4>
							<table class="table table-striped table-hover">
								<thead class="text-primary">
									<tr>
										<th>Waktu</th>
										<th>Ke Kas</th>
										<th style="text-align:right">Total</th>
									</tr>
								</thead>
								<tbody v-if="laporanKasKeluarRekap.length > 0 && loading == false"  class="data-ada">
									<tr v-for="laporanKasKeluarRekaps, index in laporanKasKeluarRekap" >

										<td>{{ laporanKasKeluarRekaps.data_laporan.created_at | tanggal }}</td>
										<td>{{ laporanKasKeluarRekaps.data_laporan.nama_kas }}</td>
										<td align="right">{{ laporanKasKeluarRekaps.data_laporan.jumlah_keluar | pemisahTitik }}</td>

									</tr>
								</tbody>					
								<tbody class="data-tidak-ada" v-else-if="laporanKasKeluarRekap.length == 0 && loading == false">
									<tr ><td colspan="3"  class="text-center">Tidak Ada Data</td></tr>
								</tbody>
							</table>
						</div><!--RESPONSIVE-->

						<vue-simple-spinner v-if="loading"></vue-simple-spinner>
						<div align="right"><pagination :data="laporanKasKeluarRekapData" v-on:pagination-change-page="prosesLaporanRekap" :limit="4"></pagination></div>
						<hr style="margin-top: 30px; margin-bottom: 15px; border-top: 5px solid #eeeeee;">

						<!--KAS MUTASI MASUK-->
						<div class=" table-responsive">
							<div class="pencarian">
								<input type="text" name="pencarian" v-model="pencarian_mutasi_masuk_rekap" placeholder="Pencarian" class="form-control">
							</div>

							<h4><b>KAS MUTASI MASUK : <span style="color:red">Rp. {{subtotalLaporanKasMutasiMasukRekap | pemisahTitik}}</span></b></h4>
							<table class="table table-striped table-hover">
								<thead class="text-primary">
									<tr>
										<th>Waktu</th>
										<th>Ke Kas</th>
										<th style="text-align:right">Total</th>
									</tr>
								</thead>
								<tbody v-if="laporanKasMutasiMasukRekap.length > 0 && loading == false"  class="data-ada">
									<tr v-for="laporanKasMutasiMasukRekaps, index in laporanKasMutasiMasukRekap" >

										<td>{{ laporanKasMutasiMasukRekaps.data_laporan.created_at | tanggal }}</td>
										<td>{{ laporanKasMutasiMasukRekaps.data_laporan.nama_kas }}</td>
										<td align="right">{{ laporanKasMutasiMasukRekaps.data_laporan.jumlah_masuk | pemisahTitik }}</td>

									</tr>
								</tbody>					
								<tbody class="data-tidak-ada" v-else-if="laporanKasMutasiMasukRekap.length == 0 && loading == false">
									<tr ><td colspan="3"  class="text-center">Tidak Ada Data</td></tr>
								</tbody>
							</table>
						</div><!--RESPONSIVE-->

						<vue-simple-spinner v-if="loading"></vue-simple-spinner>
						<div align="right"><pagination :data="laporanKasMutasiMasukRekapData" v-on:pagination-change-page="prosesLaporanRekap" :limit="4"></pagination></div>
						<hr style="margin-top: 30px; margin-bottom: 15px; border-top: 5px solid #eeeeee;">

					</span>
					<!-- JENIS LAPORAN == LAPORAN REKAP -->

					<!--TOTAL KAS-->
					<span id="span-kas" style="display:none">

						<h4><b>TOTAL KAS</b></h4>
						<table>
							<tbody>
								<tr>
									<td width="50%">Kas Awal</td>
									<td> :&nbsp;</td>
									<td class="text-right"> Rp. {{totalAwalLaporanKasDetail | pemisahTitik}} </td>
								</tr>
								<tr>
									<td width="50%">Perubahan Kas</td>
									<td> :&nbsp;</td>
									<td class="text-right"> Rp. {{totalPerubahanLaporanKasDetail | pemisahTitik}} </td>
								</tr>
								<tr>
									<td width="50%">Kas Akhir</td>
									<td> :&nbsp;</td>
									<td class="text-right"> Rp. {{totalAkhirLaporanKasDetail | pemisahTitik}} </td>
								</tr>
							</tbody>
						</table>

					</span>

					<!--DOWNLOAD EXCEL-->
					<a href="#" class='btn btn-warning' id="btnExcel" target='blank' :style="'display: none'">
						<i class="material-icons">file_download</i> Download Excel
					</a>

					<!--CETAK LAPORAN-->
					<a href="#" class='btn btn-success' id="btnCetak" target='blank' :style="'display: none'">
						<i class="material-icons">print</i> Cetak Laporan
					</a>
				</div>
			</div>
		</div>
	</div>

</template>

<script>
	export default {
		data: function () {
			return {
				kas : [],
				laporanKasDetail: [],
				laporanKasDetailData: {},
				subtotalLaporanKasDetail: '',

				laporanKasKeluarDetail: [],
				laporanKasKeluarDetailData: {},
				subtotalLaporanKasKeluarDetail: '',

				laporanKasMutasiMasukDetail: [],
				laporanKasMutasiMasukDetailData: {},
				subtotalLaporanKasMutasiMasukDetail: '',

				laporanKasMutasiKeluarDetail: [],
				laporanKasMutasiKeluarDetailData: {},
				subtotalLaporanKasMutasiKeluarDetail: '',

				totalAwalLaporanKasDetail: '',
				totalPerubahanLaporanKasDetail: '',
				totalAkhirLaporanKasDetail: '',				

				laporanKasRekap: [],
				laporanKasRekapData: {},
				subtotalLaporanKasRekap: '',	

				laporanKasKeluarRekap: [],
				laporanKasKeluarRekapData: {},
				subtotalLaporanKasKeluarRekap: '',

				laporanKasMutasiMasukRekap: [],
				laporanKasMutasiMasukRekapData: {},
				subtotalLaporanKasMutasiMasukRekap: '',

				filter: {
					dari_tanggal: '',
					sampai_tanggal: new Date(),
					jenis_laporan: '',
					kas: '',
				},

				url : window.location.origin+(window.location.pathname).replace("dashboard", "laporan-kas"),
				urlDownloadExcel : window.location.origin+(window.location.pathname).replace("dashboard", "laporan-kas/download-excel"),
				urlCetak : window.location.origin+(window.location.pathname).replace("dashboard", "laporan-kas/cetak-laporan"),

				pencarian_kas_masuk: '',
				pencarian_kas_keluar: '',
				pencarian_mutasi_masuk: '',
				pencarian_mutasi_keluar: '',

				pencarian_kas_masuk_rekap: '',
				pencarian_kas_keluar_rekap: '',
				pencarian_mutasi_masuk_rekap: '',

				placeholder_laporan: {
					placeholder: '--JENIS LAPORAN--'
				},
				placeholder_kas: {
					placeholder: '--PILIH KAS--'
				},

				loading: false,
			}
		},
		mounted() {
			var app = this;
			var awal_tanggal = new Date();
			awal_tanggal.setDate(1);

			app.dataKas();
			app.filter.dari_tanggal = awal_tanggal;

		},
		watch: {
			pencarian_kas_masuk: function (newQuestion) {
				this.getHasilPencarian();
			},
			pencarian_kas_keluar: function (newQuestion) {
				this.getHasilPencarianKeluar();
			},
			pencarian_mutasi_masuk: function (newQuestion) {
				this.getHasilPencarianMutasiMasuk();
			},
			pencarian_mutasi_keluar: function (newQuestion) {
				this.getHasilPencarianMutasiKeluar();
			},

			pencarian_kas_masuk_rekap: function (newQuestion) {
				this.getHasilPencarianRekap();
			},
			pencarian_kas_keluar_rekap: function (newQuestion) {
				this.getHasilPencarianKeluarRekap();
			},
			pencarian_mutasi_masuk_rekap: function (newQuestion) {
				this.getHasilPencarianMutasiMasukRekap();
			},
		},
		filters: {
			pemisahTitik: function (value) {
				var angka = [value];
				var numberFormat = new Intl.NumberFormat('es-ES');
				var formatted = angka.map(numberFormat.format);
				return formatted.join('; ');
			},
			tanggal: function (value) {
				return moment(String(value)).format('DD/MM/YYYY hh:mm')
			}
		},
		methods: {
			pilihJenisLaporan(){      
				this.$refs.jenis_laporan.$el.selectize.focus();
			},
			pilihKas(){      
				this.$refs.kas.$el.selectize.focus();
			},
			dataKas() {
				var app = this;
				axios.get(app.url+'/pilih-kas').then(function (resp) {
					app.kas = resp.data;
					app.pilihKas();
					app.pilihJenisLaporan();
				})
				.catch(function (resp) {
					alert("Tidak Bisa Memuat Kas");
				});
			},
			submitLaporan(){
				var app = this;

				if (app.filter.kas == "") {
					app.alertGagal('Silakan Pilih Kas Terlebih Dahulu');
					app.pilihKas();
				}else if (app.filter.jenis_laporan == "") {    			
					app.alertGagal('Silakan Pilih Jenis Laporan Terlebih Dahulu');
					app.pilihJenisLaporan();
				}else{
					if (app.filter.jenis_laporan == 0) {
						app.prosesLaporan();
						app.totalLaporanKasDetail();   

						app.prosesLaporanKeluar();
						app.totalLaporanKasKeluarDetail(); 

						app.prosesLaporanMutasiMasuk();
						app.totalLaporanKasMutasiMasukDetail(); 

						app.prosesLaporanMutasiKeluar();
						app.totalLaporanKasMutasiKeluarDetail(); 

						app.totalLaporanKas();

						$("#span-detail").show();
						$("#span-rekap").hide();
						$("#span-kas").show();
					}else{

						app.prosesLaporanRekap();
						app.totalLaporanKasRekap();

						app.prosesLaporanKeluarRekap();
						app.totalLaporanKasKeluarRekap();

						app.prosesLaporanMutasiMasukRekap();
						app.totalLaporanKasMutasiMasukRekap();

						$("#span-detail").hide();
						$("#span-rekap").show();
						$("#span-kas").show();
					}
					app.showButton();     			
				} 

			},
			prosesLaporan(page) {
				var app = this;	
				var newFilter = app.filter;
				if (typeof page === 'undefined') {
					page = 1;
				}
				app.loading = true,
				axios.post(app.url+'/view?page='+page, newFilter)
				.then(function (resp) {
					console.log(resp.data.data);
					app.laporanKasDetail = resp.data.data;
					app.laporanKasDetailData = resp.data;
					app.loading = false
				})
				.catch(function (resp) {
					console.log(resp);
					alert("Tidak Dapat Memuat Laporan Kas Masuk Detail");
				});
			},
			prosesLaporanKeluar(page) {
				var app = this;	
				var newFilter = app.filter;
				if (typeof page === 'undefined') {
					page = 1;
				}
				app.loading = true,
				axios.post(app.url+'/view-keluar?page='+page, newFilter)
				.then(function (resp) {
					console.log(resp.data.data);
					app.laporanKasKeluarDetail = resp.data.data;
					app.laporanKasKeluarDetailData = resp.data;
					app.loading = false
				})
				.catch(function (resp) {
					console.log(resp);
					alert("Tidak Dapat Memuat Laporan Kas Keluar Detail");
				});
			},
			prosesLaporanMutasiMasuk(page) {
				var app = this;	
				var newFilter = app.filter;
				if (typeof page === 'undefined') {
					page = 1;
				}
				app.loading = true,
				axios.post(app.url+'/view-mutasi-masuk?page='+page, newFilter)
				.then(function (resp) {
					console.log(resp.data.data);
					app.laporanKasMutasiMasukDetail = resp.data.data;
					app.laporanKasMutasiMasukDetailData = resp.data;
					app.loading = false
				})
				.catch(function (resp) {
					console.log(resp);
					alert("Tidak Dapat Memuat Laporan Kas Mutasi (Masuk) Detail");
				});
			},
			prosesLaporanMutasiKeluar(page) {
				var app = this;	
				var newFilter = app.filter;
				if (typeof page === 'undefined') {
					page = 1;
				}
				app.loading = true,
				axios.post(app.url+'/view-mutasi-keluar?page='+page, newFilter)
				.then(function (resp) {
					console.log(resp.data.data);
					app.laporanKasMutasiKeluarDetail = resp.data.data;
					app.laporanKasMutasiKeluarDetailData = resp.data;
					app.loading = false
				})
				.catch(function (resp) {
					console.log(resp);
					alert("Tidak Dapat Memuat Laporan Kas Mutasi (Keluar) Detail");
				});
			},

			prosesLaporanRekap(page) {
				var app = this;	
				var newFilter = app.filter;
				if (typeof page === 'undefined') {
					page = 1;
				}
				app.loading = true,
				axios.post(app.url+'/view-rekap?page='+page, newFilter)
				.then(function (resp) {
					console.log(resp.data.data);
					app.laporanKasRekap = resp.data.data;
					app.laporanKasRekapData = resp.data;
					app.loading = false
				})
				.catch(function (resp) {
					console.log(resp);
					alert("Tidak Dapat Memuat Laporan Kas Masuk Rekap");
				});
			},
			prosesLaporanKeluarRekap(page) {
				var app = this;	
				var newFilter = app.filter;
				if (typeof page === 'undefined') {
					page = 1;
				}
				app.loading = true,
				axios.post(app.url+'/view-keluar-rekap?page='+page, newFilter)
				.then(function (resp) {
					console.log(resp.data.data);
					app.laporanKasKeluarRekap = resp.data.data;
					app.laporanKasKeluarRekapData = resp.data;
					app.loading = false
				})
				.catch(function (resp) {
					console.log(resp);
					alert("Tidak Dapat Memuat Laporan Kas Keluar Rekap");
				});
			},
			prosesLaporanMutasiMasukRekap(page) {
				var app = this;	
				var newFilter = app.filter;
				if (typeof page === 'undefined') {
					page = 1;
				}
				app.loading = true,
				axios.post(app.url+'/view-mutasi-masuk-rekap?page='+page, newFilter)
				.then(function (resp) {
					console.log(resp.data.data);
					app.laporanKasMutasiMasukRekap = resp.data.data;
					app.laporanKasMutasiMasukRekapData = resp.data;
					app.loading = false
				})
				.catch(function (resp) {
					console.log(resp);
					alert("Tidak Dapat Memuat Laporan Kas Mutasi (Masuk) Rekap");
				});
			},


			getHasilPencarian(page){
				var app = this;
				var newFilter = app.filter;
				if (typeof page === 'undefined') {
					page = 1;
				}
				axios.post(app.url+'/pencarian?search='+app.pencarian_kas_masuk+'&page='+page, newFilter)
				.then(function (resp) {
					console.log(resp.data.data)
					app.laporanKasDetail = resp.data.data;
					app.laporanKasDetailData = resp.data;
				})
				.catch(function (resp) {
					console.log(resp);
					alert("Tidak Dapat Memuat Laporan Kas Masuk Detail");
				});
			},
			getHasilPencarianKeluar(page){
				var app = this;
				var newFilter = app.filter;
				if (typeof page === 'undefined') {
					page = 1;
				}
				axios.post(app.url+'/pencarian-keluar?search='+app.pencarian_kas_keluar+'&page='+page, newFilter)
				.then(function (resp) {
					console.log(resp.data.data)
					app.laporanKasKeluarDetail = resp.data.data;
					app.laporanKasKeluarDetailData = resp.data;
				})
				.catch(function (resp) {
					console.log(resp);
					alert("Tidak Dapat Memuat Laporan Kas Keluar Detail");
				});
			},
			getHasilPencarianMutasiMasuk(page){
				var app = this;
				var newFilter = app.filter;
				if (typeof page === 'undefined') {
					page = 1;
				}
				axios.post(app.url+'/pencarian-mutasi-masuk?search='+app.pencarian_mutasi_masuk+'&page='+page, newFilter)
				.then(function (resp) {
					console.log(resp.data.data)
					app.laporanKasMutasiMasukDetail = resp.data.data;
					app.laporanKasMutasiMasukDetailData = resp.data;
				})
				.catch(function (resp) {
					console.log(resp);
					alert("Tidak Dapat Memuat Laporan Kas Mutasi (Masuk) Detail");
				});
			},
			getHasilPencarianMutasiKeluar(page){
				var app = this;
				var newFilter = app.filter;
				if (typeof page === 'undefined') {
					page = 1;
				}
				axios.post(app.url+'/pencarian-mutasi-keluar?search='+app.pencarian_mutasi_keluar+'&page='+page, newFilter)
				.then(function (resp) {
					console.log(resp.data.data)
					app.laporanKasMutasiKeluarDetail = resp.data.data;
					app.laporanKasMutasiKeluarDetailData = resp.data;
				})
				.catch(function (resp) {
					console.log(resp);
					alert("Tidak Dapat Memuat Laporan Kas Mutasi (Keluar) Detail");
				});
			},

			getHasilPencarianRekap(page){
				var app = this;
				var newFilter = app.filter;
				if (typeof page === 'undefined') {
					page = 1;
				}
				axios.post(app.url+'/pencarian-rekap?search='+app.pencarian_kas_masuk_rekap+'&page='+page, newFilter)
				.then(function (resp) {
					console.log(resp.data.data)
					app.laporanKasRekap = resp.data.data;
					app.laporanKasRekapData = resp.data;
				})
				.catch(function (resp) {
					console.log(resp);
					alert("Tidak Dapat Memuat Laporan Kas Masuk Rekap");
				});
			},
			getHasilPencarianKeluarRekap(page){
				var app = this;
				var newFilter = app.filter;
				if (typeof page === 'undefined') {
					page = 1;
				}
				axios.post(app.url+'/pencarian-keluar-rekap?search='+app.pencarian_kas_keluar_rekap+'&page='+page, newFilter)
				.then(function (resp) {
					console.log(resp.data.data)
					app.laporanKasKeluarRekap = resp.data.data;
					app.laporanKasKeluarRekapData = resp.data;
				})
				.catch(function (resp) {
					console.log(resp);
					alert("Tidak Dapat Memuat Laporan Kas Keluar Rekap");
				});
			},
			getHasilPencarianMutasiMasukRekap(page){
				var app = this;
				var newFilter = app.filter;
				if (typeof page === 'undefined') {
					page = 1;
				}
				axios.post(app.url+'/pencarian-mutasi-masuk-rekap?search='+app.pencarian_mutasi_masuk_rekap+'&page='+page, newFilter)
				.then(function (resp) {
					console.log(resp.data.data)
					app.laporanKasMutasiMasukRekap = resp.data.data;
					app.laporanKasMutasiMasukRekapData = resp.data;
				})
				.catch(function (resp) {
					console.log(resp);
					alert("Tidak Dapat Memuat Laporan Kas Mutasi (Masuk) Rekap");
				});
			},


			totalLaporanKasDetail() {
				var app = this;	
				var newFilter = app.filter;

				app.loading = true,
				axios.post(app.url+'/subtotal-laporan-kas-detail-masuk', newFilter)
				.then(function (resp) {
					app.subtotalLaporanKasDetail = resp.data;
					app.loading = false
					console.log(resp.data)
				})
				.catch(function (resp) {
					console.log(resp);
					alert("Tidak Dapat Memuat Subtotal Laporan Kas");
				});
			},
			totalLaporanKasKeluarDetail() {
				var app = this;	
				var newFilter = app.filter;

				app.loading = true,
				axios.post(app.url+'/subtotal-laporan-kas-detail-keluar', newFilter)
				.then(function (resp) {
					app.subtotalLaporanKasKeluarDetail = resp.data;
					app.loading = false
					console.log(resp.data)
				})
				.catch(function (resp) {
					console.log(resp);
					alert("Tidak Dapat Memuat Subtotal Laporan Kas");
				});
			},
			totalLaporanKasMutasiMasukDetail() {
				var app = this;	
				var newFilter = app.filter;

				app.loading = true,
				axios.post(app.url+'/subtotal-laporan-kas-detail-mutasi-masuk', newFilter)
				.then(function (resp) {
					app.subtotalLaporanKasMutasiMasukDetail = resp.data;
					app.loading = false
					console.log(resp.data)
				})
				.catch(function (resp) {
					console.log(resp);
					alert("Tidak Dapat Memuat Subtotal Laporan Kas");
				});
			},
			totalLaporanKasMutasiKeluarDetail() {
				var app = this;	
				var newFilter = app.filter;

				app.loading = true,
				axios.post(app.url+'/subtotal-laporan-kas-detail-mutasi-keluar', newFilter)
				.then(function (resp) {
					app.subtotalLaporanKasMutasiKeluarDetail = resp.data;
					app.loading = false
					console.log(resp.data)
				})
				.catch(function (resp) {
					console.log(resp);
					alert("Tidak Dapat Memuat Subtotal Laporan Kas");
				});
			},
			totalLaporanKas() {
				var app = this;	
				var newFilter = app.filter;

				app.loading = true,
				axios.post(app.url+'/subtotal-laporan-kas-detail', newFilter)
				.then(function (resp) {
					console.log(resp.data);
					app.totalAwalLaporanKasDetail = resp.data.total_awal;
					app.totalAkhirLaporanKasDetail = resp.data.total_akhir;
					app.totalPerubahanLaporanKasDetail = resp.data.perubahan_kas;
					app.loading = false
				})
				.catch(function (resp) {
					console.log(resp);
					alert("Tidak Dapat Memuat Total Kas");
				});
			},
			totalLaporanKasKeluarDetail() {
				var app = this;	
				var newFilter = app.filter;

				app.loading = true,
				axios.post(app.url+'/subtotal-laporan-kas-detail-keluar', newFilter)
				.then(function (resp) {
					app.subtotalLaporanKasKeluarDetail = resp.data;
					app.loading = false
					console.log(resp.data)
				})
				.catch(function (resp) {
					console.log(resp);
					alert("Tidak Dapat Memuat Subtotal Laporan Kas");
				});
			},


			totalLaporanKasRekap() {
				var app = this;	
				var newFilter = app.filter;

				app.loading = true,
				axios.post(app.url+'/subtotal-laporan-kas-rekap-masuk', newFilter)
				.then(function (resp) {
					app.subtotalLaporanKasRekap = resp.data;
					app.loading = false
					console.log(resp.data)
				})
				.catch(function (resp) {
					console.log(resp);
					alert("Tidak Dapat Memuat Subtotal Laporan Kas");
				});
			},
			totalLaporanKasKeluarRekap() {
				var app = this;	
				var newFilter = app.filter;

				app.loading = true,
				axios.post(app.url+'/subtotal-laporan-kas-rekap-keluar', newFilter)
				.then(function (resp) {
					app.subtotalLaporanKasKeluarRekap = resp.data;
					app.loading = false
					console.log(resp.data)
				})
				.catch(function (resp) {
					console.log(resp);
					alert("Tidak Dapat Memuat Subtotal Laporan Kas");
				});
			},
			totalLaporanKasMutasiMasukRekap() {
				var app = this;	
				var newFilter = app.filter;

				app.loading = true,
				axios.post(app.url+'/subtotal-laporan-kas-rekap-mutasi-masuk', newFilter)
				.then(function (resp) {
					app.subtotalLaporanKasMutasiMasukRekap = resp.data;
					app.loading = false
					console.log(resp.data)
				})
				.catch(function (resp) {
					console.log(resp);
					alert("Tidak Dapat Memuat Subtotal Laporan Kas");
				});
			},
			downloadExcel() {
				var app = this;	
				var newFilter = app.filter;
				if (newFilter.pelanggan == "" || newFilter.pelanggan == null) {
					newFilter.pelanggan = 0;
				}
				axios.get(app.urlDownloadExcel+'/'+newFilter.dari_tanggal+'/'+newFilter.sampai_tanggal+'/'+newFilter.pelanggan)
				.then(function (resp) {
				})
				.catch(function (resp) {
					console.log(resp);
					alert("Tidak Dapat Memuat Subtotal Laporan Kas");
				});
			},
			showButton() {
				var app =  this;
				var filter = app.filter;

				if (filter.pelanggan == "") {
					filter.pelanggan = 0;
				};

				var date_dari_tanggal = filter.dari_tanggal;
				var date_sampai_tanggal = filter.sampai_tanggal;
				var dari_tanggal = "" + date_dari_tanggal.getFullYear() +'-'+ ((date_dari_tanggal.getMonth() + 1) > 9 ? '' : '0') + (date_dari_tanggal.getMonth() + 1) +'-'+ (date_dari_tanggal.getDate() > 9 ? '' : '0') + date_dari_tanggal.getDate();
				var sampai_tanggal = "" + date_sampai_tanggal.getFullYear() +'-'+ ((date_sampai_tanggal.getMonth() + 1) > 9 ? '' : '0') + (date_sampai_tanggal.getMonth() + 1) +'-'+ (date_sampai_tanggal.getDate() > 9 ? '' : '0') + date_sampai_tanggal.getDate();


				$("#btnExcel").show();
				$("#btnCetak").show();
				$("#btnExcel").attr('href', app.urlDownloadExcel+'/'+dari_tanggal+'/'+sampai_tanggal+'/'+filter.pelanggan);
				$("#btnCetak").attr('href', app.urlCetak+'/'+dari_tanggal+'/'+sampai_tanggal+'/'+filter.pelanggan);  
			},
			alertGagal(pesan) {
				this.$swal({
					title: "Peringatan!",
					text: pesan,
					icon: "warning",
				});
			},
		}
	}
</script>