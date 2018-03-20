<style scoped>
.pencarian {
	color: red; 
	float: right;
	padding-bottom: 10px;
}
.card-detail{
	margin-top: 5px;
	margin-bottom: 1px;
}
.breadcrumb{
	margin-top: 1px;
	margin-bottom: 1px;
}
</style>

<template>	

	<div class="row">

		<div class="col-md-12" v-if="dataAgent == 1">
			<ul class="breadcrumb">
				<li><router-link :to="{name: 'indexDashboard'}">Home</router-link></li>
				<li><router-link :to="{name: 'indexPesananWarung'}">Pesanan</router-link></li>
				<li class="active">Detail Pesanan</li>
			</ul>

			<!-- small modal -->
			<div class="modal" id="modalSelesaiPesanan" role="dialog" tabindex="-1"  aria-labelledby="myModalLabel" aria-hidden="true" >
				<div class="modal-dialog modal-medium">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" v-on:click="closeModalSelesaiPesanan()"> &times;</button> 
						</div>
						<form class="form-horizontal" v-on:submit.prevent="submitSelesaiPesanan(pesananData.pesanan.id, selesaiPesanan.id_kas)"> 
							<div class="modal-body">
								<h3 class="text-center"><b>Pilih Kas</b> </h3>

								<span v-if="kas_error == 1" class="label label-danger">Silakan pilih Kas terlebih dahulu</span>
								<selectize-component v-model="selesaiPesanan.id_kas" :settings="placeholder_kas" id="kas" ref='kas'>  
									<option v-for="kass, index in kas" v-bind:value="kass.id">{{ kass.nama_kas }}</option>
								</selectize-component>
								<p style="color: red; font-style: italic;">*Jika Anda Menyelesaikan Pesanan Ini, Maka "Kas" Anda Akan Bertambah & "Stok Produk(Jika Produk Bukan Jasa)" Akan Berkurang.</p>    
								<div class="modal-footer">
									<button type="button" class="btn btn-simple" v-on:click="closeModalSelesaiPesanan()">Close</button>
									<button type="button" class="btn btn-info btn-lg" v-on:click="submitSelesaiPesanan(pesananData.pesanan.id, selesaiPesanan.id_kas)">Selesai</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
			<!--    end small modal -->

			<!-- DATA PESANAN -->
			<div class="card" style="margin-top: 5px; margin-bottom: 1px;">
				<div class="card-content">

					<div class="row">

						<div class="col-md-1">Order #{{pesananData.pesanan.id}}
							<!-- MODAL PEMESAN -->
							<div class="modal " id="data_pemesan" role="dialog" data-backdrop="">
								<div class="modal-dialog">
									<!-- Modal content-->
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal">&times;</button>
											<h4 class="modal-title"><b>Data Pemesan</b></h4>
										</div>

										<div class="modal-body">
											<div class="responsive">
												<table>
													<tbody>
														<tr><td width="25%"> Nama</td> <td> : </td> <td>  {{pesananData.pesanan.nama_pemesan}} </td></tr>
														<tr><td width="25%"> Alamat</td> <td> : </td> <td>  {{pesananData.pesanan.alamat_pemesan}} </td></tr>
														<tr><td width="25%"> No Telp</td> <td> : </td> <td>  {{pesananData.pesanan.no_telp_pemesan}} </td></tr>
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
							</div> <!--END MODAL PEMESAN-->

						</div> <!--END ORDER PESANAN ID-->

						<div class="col-md-3">
							<table>
								<tbody>
									<tr><td> Waktu Pesan </td><td style="padding:3px">:</td><td  style="padding:3px"></td><td  style="padding:3px">{{ pesananData.pesanan.created_at}}</td></tr>
									<tr><td> Kurir </td><td style="padding:3px">:</td><td  style="padding:3px"></td><td  style="padding:3px"> {{pesananData.pesanan.kurir }}</td></tr>
									<tr><td> Service </td><td style="padding:3px">:</td><td  style="padding:3px"></td><td  style="padding:3px">{{ servicePengiriman }}</td></tr>
									<tr><td> Waktu Pengiriman </td><td style="padding:3px">:</td><td  style="padding:3px"></td><td  style="padding:3px"> {{ waktuPengiriman }}</td></tr>
								</tbody>
							</table>

						</div>					
						<div class="col-md-2">
							<table>
								<tbody>
									<tr><td> Subtotal </td><td style="padding:3px" align="right">:</td><td  align="right" style="padding:3px"></td><td  align="right" style="padding:3px">{{ pesananData.subtotal | pemisahTitik }}</td></tr>
									<tr><td> Biaya Kirim </td><td style="padding:3px" align="right">:</td><td  align="right" style="padding:3px"></td><td  align="right" style="padding:3px">{{pesananData.pesanan.biaya_kirim | pemisahTitik}}</td></tr>
									<tr><td> Total </td><td style="padding:3px" align="right">:</td><td  align="right" style="padding:3px"></td><td  align="right" style="padding:3px">{{ parseInt(pesananData.pesanan.biaya_kirim) + parseInt(pesananData.subtotal) | pemisahTitik}}</td></tr>
								</tbody>
							</table>
						</div>					
						<div class="col-md-2">Status : 
							<b style="color:red" v-if="pesananData.pesanan.konfirmasi_pesanan == 0" >Belum Di Konfirmasi</b>
							<b style="color:orange" v-else-if="pesananData.pesanan.konfirmasi_pesanan == 1" >Sudah Di Konfirmasi</b>
							<b style="color:#01573e" v-else-if="pesananData.pesanan.konfirmasi_pesanan == 2" >Selesai</b>
							<b style="color:red" v-else-if="pesananData.pesanan.konfirmasi_pesanan == 3" >Batal</b>
							<b style="color:orange" v-else > Batal Pelanggan</b>
							<br>Metode Pembayaran : {{pesananData.pesanan.metode_pembayaran}}
						</div>

						<div class="col-md-4" v-if="pesananData.pesanan.konfirmasi_pesanan != 4">
							<p v-if="pesananData.pesanan.konfirmasi_pesanan == 1">Selesai ? :</p>
							<p v-else>Lanjut ? :</p>

							<p v-if="pesananData.pesanan.konfirmasi_pesanan == 0">
								<button id="konfirmasi-pesanan-warung" :id-pesanan="pesananData.pesanan.id" class="btn btn-sm btn-info" @click="konfirmasiPesanan(pesananData.pesanan.id)"><font style="font-size: 12px;">Lanjut</font></button>
								<button id="batalkan-pesanan-warung" :id-pesanan="pesananData.pesanan.id" class="btn btn-sm btn-danger" @click="batalPesanan(pesananData.pesanan.id)"><font style="font-size: 12px;">Batal</font></button>
								<!--PEMESAN-->
								<button type="button" class="btn btn-sm btn-primary" id="btnDetail" data-toggle="modal" data-target="#data_pemesan"><font style="font-size: 12px;">Pemesan</font></button>
							</p>

							<p v-else-if="pesananData.pesanan.konfirmasi_pesanan == 1">
								<button class="btn btn-info btn-sm" :data-id="pesananData.pesanan.id" id="selesaikan_pesanan" @click="selesaikanPesanan(pesananData.pesanan.id)">  <font style="font-size: 12px;" >Selesai</font></button>
								<button id="batalkan-konfirmasi-pesanan-warung" :id-pesanan="pesananData.pesanan.id" class="btn btn-sm btn-danger" @click="batalKonfirmasiPesanan(pesananData.pesanan.id)"><font style="font-size: 12px;">Batal</font></button>
								<!--PEMESAN-->
								<button type="button" class="btn btn-sm btn-primary" id="btnDetail" data-toggle="modal" data-target="#data_pemesan"><font style="font-size: 12px;">Pemesan</font></button>
							</p>

							<p v-else-if="pesananData.pesanan.konfirmasi_pesanan == 2">
								<button id="batalkan-pesanan-warung" :id-pesanan="pesananData.pesanan.id" class="btn btn-sm btn-danger" @click="batalPesanan(pesananData.pesanan.id)"><font style="font-size: 12px;">Batal</font></button>	
								<!--PEMESAN-->
								<button type="button" class="btn btn-sm btn-primary" id="btnDetail" data-toggle="modal" data-target="#data_pemesan"><font style="font-size: 12px;">Pemesan</font></button>		  
							</p>

							<p v-else-if="pesananData.pesanan.konfirmasi_pesanan == 3">
								<button id="konfirmasi-pesanan-warung" :id-pesanan="pesananData.pesanan.id" class="btn btn-sm btn-info" @click="konfirmasiPesanan(pesananData.pesanan.id)"><font style="font-size: 12px;">Lanjut</font></button>	
								<!--PEMESAN-->
								<button type="button" class="btn btn-sm btn-primary" id="btnDetail" data-toggle="modal" data-target="#data_pemesan"><font style="font-size: 12px;">Pemesan</font></button>
							</p>

						</div>

					</div> <!--END ROW-->
				</div> <!--END CARD CONTENT-->
			</div> <!--END CARD-->

			<!--DATA DETAIL PESANAN -->			
			<div class="card card-detail">
				<div class="card-content">

					<div class="responsive">
						<table class="table table-hover table-responsive">
							<thead>
								<tr>
									<th><b>Produk</b></th>
									<th><b>Harga</b> </th>
									<th><center><b>Jumlah</b></center></th>
									<th><b>Subtotal</b></th>
									<th><b>Status</b> </th>
								</tr>
							</thead>
							<tbody  v-if="detailPesanan.length > 0 && loading == false"  class="data-ada">

								<tr v-for="detailPesanans, index in detailPesanan">
									<td style="text-transform: capitalize">{{ detailPesanans.produk.nama_barang }}</td>
									<td>Rp. {{ detailPesanans.harga_produk | pemisahTitik }}</td>
									<td>
										<center>
											<div class="btn-group" v-if="detailPesanans.pesanan_pelanggan.konfirmasi_pesanan == 0">
												<a v-if="detailPesanans.jumlah_produk == 0" disabled="true" class="btn btn-round btn-xs"> <i class="material-icons">remove</i></a>
												<a v-else href="#" class="btn btn-round btn-xs" @click="kurangProduk(detailPesanans.id)"> <i class="material-icons">remove</i></a>

												<a id="edit-jumlah-pesanan" :data-nama="detailPesanans.produk.nama_barang" :data-id="detailPesanans.id"  class="btn btn-round btn-xs" rel="tooltip" data-placement="top" title="Edit Jumlah" @click="editProduk(detailPesanans.id, index, detailPesanans.produk.nama_barang)"> <font style="font-size: 11.5px;">{{ detailPesanans.jumlah_produk }}</font> </a>

												<a href="#" class="btn btn-round btn-xs" @click="tambahProduk(detailPesanans.id)"> <i class="material-icons">add</i></a>
											</div>
											<div v-else>
												{{detailPesanans.jumlah_produk}}
											</div>
										</center>
									</td>
									<td>Rp. {{ detailPesanans.harga_produk * detailPesanans.jumlah_produk | pemisahTitik }}</td>		          			
									<td v-if="detailPesanans.jumlah_produk > 0">
										<b style="color:red" v-if="detailPesanans.pesanan_pelanggan.konfirmasi_pesanan == 0">Belum Di Konfirmasi</b>
										<b style="color:orange" v-else-if="detailPesanans.pesanan_pelanggan.konfirmasi_pesanan == 1" >Sudah Di Konfirmasi</b>
										<b style="color:#01573e" v-else-if="detailPesanans.pesanan_pelanggan.konfirmasi_pesanan == 2" >Selesai</b>
										<b style="color:red" v-else-if="detailPesanans.pesanan_pelanggan.konfirmasi_pesanan == 3" >Batal</b>
										<b style="color:orange" v-else > Batal Pelanggan</b>
									</td>	
									<td v-else>
										<b style="color:red" >Batal</b>
									</td>		          			
								</tr>

							</tbody>	

							<tbody class="data-tidak-ada" v-else-if="detailPesanan.length == 0 && loading == false">
								<tr ><td colspan="4"  class="text-center">Tidak Ada Pesanan</td></tr>
							</tbody>
						</table>

						<vue-simple-spinner v-if="loading"></vue-simple-spinner>
						<div align="right"><pagination :data="detailPesananData" v-on:pagination-change-page="getResults" :limit="4"></pagination></div>
					</div>
				</div>
			</div>

		</div> <!-- JIKA DIAKSES VIA KOMPUTER-->

		<div class="col-md-12" style="padding-left:25px; padding-right:25px" v-else> <!-- JIKA DIAKSES VIA MOBILE-->        	
			<ul class="breadcrumb">
				<li><router-link :to="{name: 'indexDashboard'}">Home</router-link></li>
				<li><router-link :to="{name: 'indexPesananWarung'}">Pesanan</router-link></li>
				<li class="active">Detail Pesanan</li>
			</ul>

			<div class="card" style="margin-bottom: 5px; margin-top: 1px;">
				<div class="card-content">

					<b class="card-title" style="margin-top: 1px; margin-bottom: 1px;">Order #{{pesananData.pesanan.id}}</b>
					<hr style="margin-top: 1x; margin-bottom: 1px;">

					<b class="card-title" style="margin-top: 1px; margin-bottom: 1px;">Waktu Pesan</b>
					<p style="margin-top: 1px; margin-bottom: 1px;">{{pesananData.pesanan.created_at}}</p>
					<hr style="margin-top: 1x; margin-bottom: 1px;">

					<b class="card-title" style="margin-top: 1px; margin-bottom: 1px;">Pengiriman</b>
					<div class="row">
						<div class="col-sm-6 col-xs-6">Kurir </div>
						<div class="col-sm-6 col-xs-6"><p align="right">{{ pesananData.pesanan.kurir }}</p></div>
						<div class="col-sm-6 col-xs-6">Service </div>
						<div class="col-sm-6 col-xs-6"><p align="right">{{servicePengiriman}}</p></div>
						<div class="col-sm-6 col-xs-6">Waktu Pengiriman </div>
						<div class="col-sm-6 col-xs-6"><p align="right">{{ waktuPengiriman }}</p></div>
					</div>
					<hr style="margin-top: 1x; margin-bottom: 1px;">

					<b class="card-title" style="margin-top: 1px; margin-bottom: 1px;">Alamat Pelanggan</b>
					<p style="margin-top: 1px; margin-bottom: 1px;"> {{ pesananData.pesanan.nama_pemesan }}</p>
					<p style="margin-top: 1px; margin-bottom: 1px;">{{ pesananData.pesanan.no_telp_pemesan }}</p>
					<p style="margin-top: 1px; margin-bottom: 1px;">{{ pesananData.pesanan.alamat_pemesan }}</p>
					<hr style="margin-top: 1x; margin-bottom: 1px;">

					<div class="row">
						<div class="col-md-12">
							<div class="row">
								<div class="col-xs-12" style="padding-left:10px; padding-right:10px">
									<p> <b class="card-title" style="margin-top: 1px; margin-bottom: 1px;">Status Pesanan</b></p>

									<b style="color:red" v-if="pesananData.pesanan.konfirmasi_pesanan == 0" >Belum Di Konfirmasi</b>
									<b style="color:orange" v-else-if="pesananData.pesanan.konfirmasi_pesanan == 1" >Sudah Di Konfirmasi</b>
									<b style="color:#01573e" v-else-if="pesananData.pesanan.konfirmasi_pesanan == 2" >Selesai</b>
									<b style="color:red" v-else-if="pesananData.pesanan.konfirmasi_pesanan == 3" >Batal</b>
									<b style="color:orange" v-else > Batal Pelanggan</b>
								</div>
							</div>
						</div>
					</div>
					<hr style="margin-top: 1x; margin-bottom: 1px;">

					<b class="card-title" style="margin-top: 1px; margin-bottom: 1px;">Info Pembayaran</b>
					<div class="row">
						<div class="col-sm-6 col-xs-6">Subotal </div>
						<div class="col-sm-6 col-xs-6"><p align="right" class="text-danger"><b>Rp. {{ pesananData.subtotal | pemisahTitik }}</b></p></div>
						<div class="col-sm-6 col-xs-6">Biaya Kirim </div>
						<div class="col-sm-6 col-xs-6"><p align="right" class="text-danger"><b>Rp. {{pesananData.pesanan.biaya_kirim | pemisahTitik}}</b></p></div>
						<div class="col-sm-6 col-xs-6">Total </div>
						<div class="col-sm-6 col-xs-6"><p align="right" class="text-danger"><b>Rp. {{ parseInt(pesananData.subtotal) + parseInt(pesananData.pesanan.biaya_kirim) | pemisahTitik }}</b></p></div>
						<div class="col-sm-6 col-xs-6">Metode Pembayaran </div>
						<div class="col-sm-6 col-xs-6"><p align="right"><b>{{ pesananData.pesanan.metode_pembayaran  }}</b></p></div>
					</div>
					<hr style="margin-top: 1x; margin-bottom: 1px;">

					<!--KONFIRMASI / BATAL -->
					<b class="card-title" style="margin-top: 1px; margin-bottom: 1px;" v-if="pesananData.pesanan.konfirmasi_pesanan != 4">Konfirmasi/Batal</b><br>

					<div class="btn-group" v-if="pesananData.pesanan.konfirmasi_pesanan == 0">
						<button id="konfirmasi-pesanan-warung" :id-pesanan="pesananData.pesanan.id" class="btn btn-xs btn-info" @click="konfirmasiPesanan(pesananData.pesanan.id)" style="padding: 5px 50px;"><font style="font-size: 12px;">Lanjut</font>
						</button>
						<button id="batalkan-pesanan-warung" :id-pesanan="pesananData.pesanan.id" class="btn btn-xs btn-danger" @click="batalPesanan(pesananData.pesanan.id)" style="padding: 5px 50px;"><font style="font-size: 12px;">Batal</font>
						</button>
					</div>

					<div class="btn-group" v-else-if="pesananData.pesanan.konfirmasi_pesanan == 1">
						<button class="btn btn-info btn-xs" :data-id="pesananData.pesanan.id" id="selesaikan_pesanan" style="padding: 5px 50px;" @click="selesaikanPesanan(pesananData.pesanan.id)">  <font style="font-size: 12px;" >Selesai</font>
						</button>
						<button id="batalkan-konfirmasi-pesanan-warung" :id-pesanan="pesananData.pesanan.id" class="btn btn-xs btn-danger" @click="batalKonfirmasiPesanan(pesananData.pesanan.id)" style="padding: 5px 50px;"><font style="font-size: 12px;">Batal</font>
						</button>
					</div>

					<div class="btn-group" v-else-if="pesananData.pesanan.konfirmasi_pesanan == 2">  	
						<button id="batalkan-pesanan-warung" :id-pesanan="pesananData.pesanan.id" class="btn btn-xs btn-danger" @click="batalPesanan(pesananData.pesanan.id)" style="padding: 5px 50px;"><font style="font-size: 12px;">Batal</font>
						</button>		  
					</div>

					<div class="btn-group" v-else-if="pesananData.pesanan.konfirmasi_pesanan == 3">  	
						<button id="konfirmasi-pesanan-warung" :id-pesanan="pesananData.pesanan.id" class="btn btn-xs btn-info" @click="konfirmasiPesanan(pesananData.pesanan.id)" style="padding: 5px 50px;"><font style="font-size: 12px;">Lanjut</font>
						</button>	
					</div>

				</div>
			</div>

			<div class="card" style="margin-bottom: 1px; margin-top: 1px;" v-for="detailPesanans, index in detailPesanan">
				<div class="row">
					<div class="col-md-12">
						<div class="row">
							<div class="col-xs-4" style="padding-right:0px">
								<div class="img-container" style="margin-bottom:10px;margin-top: 10px; margin-left: 10px; margin-right: 10px;">
									<img v-if="detailPesanans.produk.foto != null" :src="urlPicture+'/'+detailPesanans.produk.foto" /> 
									<img v-else :src="urlOrigin+'/image/foto_default.png'">
								</div>
							</div>

							<div class="col-xs-8" style="padding-right:0px; padding-left:0px">
								<p style="margin-bottom:1px;margin-top: 1px; padding-top:5px">
									<b style="text-transform: capitalize">{{detailPesanans.produk.nama_barang}}</b>
								</p>

								<div class="row">
									<div class="col-xs-4" style="padding-right:0px; padding-top:13px">
										<p style="margin-bottom:1px;margin-top: 1px;">
											Rp {{ detailPesanans.harga_produk | pemisahTitik }}
										</p>
										<p style="color:red; font-weight:bold; padding-top:13px">
											Rp {{ detailPesanans.harga_produk * detailPesanans.jumlah_produk | pemisahTitik }}
										</p>
									</div>
									<div class="col-xs-8" style="padding-left:0px;">
										<div class="btn-group" align="right" v-if="detailPesanans.pesanan_pelanggan.konfirmasi_pesanan == 0">
											<a id="edit-jumlah-pesanan" :data-nama="detailPesanans.produk.nama_barang" :data-id="detailPesanans.id"  class="btn btn-info btn-xs" @click="editProduk(detailPesanans.id, index, detailPesanans.produk.nama_barang)">
												<font style="font-size: 11.5px;">{{ detailPesanans.jumlah_produk }}</font> 
											</a>
											<a v-if="detailPesanans.jumlah_produk == 0" disabled="true" class="btn btn-xs"> <i class="material-icons">remove</i></a>
											<a v-else href="#" class="btn btn-xs" @click="kurangProduk(detailPesanans.id)"> <i class="material-icons">remove</i></a>
											<a href="#" class="btn btn-xs" @click="tambahProduk(detailPesanans.id)"> <i class="material-icons">add</i></a>
										</div>

										<div v-else style="padding-top:13px;">
											x {{ detailPesanans.jumlah_produk }}
										</div>
									</div>
								</div>

								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<b style="margin-bottom:1px;margin-top: 1px;" class="text-danger" v-if="detailPesanans.jumlah_produk == 0">Dibatalkan</b>
							</div>
						</div>

						<vue-simple-spinner v-if="loading"></vue-simple-spinner>
						<div align="right"><pagination :data="detailPesananData" v-on:pagination-change-page="getResults" :limit="4"></pagination></div>
					</div>
				</div>
			</div>

		</div><!-- JIKA DIAKSES VIA MOBILE-->
	</div>
</template>

<script>
import { mapState } from 'vuex';
export default {
	data: function () {
		return {
			errors: [],
			detailPesanan: [],
			detailPesananData: {},
			pesananData: {},
			editJumlahProduk: {
				id : '',
				jumlah_produk : '',
			}, 
			selesaiPesanan: {
				id_pesanan : '',
				id_kas : '',
			}, 	
			placeholder_kas: {
				placeholder: '--PILIH KAS--',
				sortField: 'text',
				openOnFocus : true
			},	
			dataAgent: '',
			servicePengiriman : '',
			waktuPengiriman : '',
			detailPesananId: null,
			loading: true,
			kas_error : 0,
			url: window.location.origin + (window.location.pathname).replace("dashboard", "pesanan-warung"),
			urlTambahProduk: window.location.origin + (window.location.pathname).replace("dashboard", "tambah-produk-pesanan-warung"),
			urlKurangProduk: window.location.origin + (window.location.pathname).replace("dashboard", "kurang-produk-pesanan-warung"),
			urlKonfirmasiPesanan: window.location.origin + (window.location.pathname).replace("dashboard", "konfirmasi-pesanan-warung"),
			urlBatalKonfirmasiPesanan: window.location.origin + (window.location.pathname).replace("dashboard", "batalkan-konfirmasi-pesanan-warung"),
			urlBatalPesanan: window.location.origin + (window.location.pathname).replace("dashboard", "batalkan-pesanan-warung"),
			urlOrigin: window.location.origin + (window.location.pathname).replace("dashboard", ""),
			urlTambahKas: window.location.origin + (window.location.pathname).replace("dashboard", "dashboard#/kas"),
			urlPicture : window.location.origin+(window.location.pathname).replace("dashboard", "foto_produk"),
		}
	},
	mounted() {
		var app = this;
		let id = app.$route.params.id;
		app.detailPesananId = id;		
		app.$store.dispatch('LOAD_KAS_LIST')  
		app.getResults();
	},
	computed : mapState ({    
		kas(){
			return this.$store.state.kas
		},
		default_kas : state => state.default_kas
	}),
	filters: {
		pemisahTitik: function (value) {
			return new Intl.NumberFormat().format(value)
		}
	},
	methods: {
		getResults(page) {
			var app = this;	
			if (typeof page === 'undefined') {
				page = 1;
			}
			axios.get(app.url+'/detail/'+app.detailPesananId+'?page='+page)
			.then(function (resp) {
				app.detailPesanan = resp.data.data.detail_pesanan.data;
				app.detailPesananData = resp.data.data.detail_pesanan;
				app.pesananData = resp.data.data;
				app.dataAgent = resp.data.data.agent;
				app.loading = false;
				app.selesaiPesanan.id_kas = app.default_kas
				if (resp.data.data.pesanan.kurir == 'cod' || resp.data.data.pesanan.kurir == '') {

					app.servicePengiriman = "Bayar di Tempat"
					app.waktuPengiriman	= "-"

				}else if(resp.data.data.pesanan.kurir == 'ojek') {

					app.servicePengiriman = resp.data.data.pesanan.metode_pembayaran
					app.waktuPengiriman	= "-"

				}else{
					let layanan_kurir = resp.data.data.pesanan.layanan_kurir.split(" | ");
					app.servicePengiriman = layanan_kurir[0]+" | "+layanan_kurir[2]
					app.waktuPengiriman	= layanan_kurir[1]+" Hari" 
				}

			})
			.catch(function (resp) {
				app.loading = false;
				alert("Tidak Dapat Memuat Pesanan");
			});
		},
		tambahProduk(id){
			var app = this;
			axios.get(app.urlTambahProduk+'/'+ id)
			.then(function (resp) {
				app.getResults();
				app.$router.replace('/detail-pesanan-warung/'+app.detailPesananId);
			});
		},
		kurangProduk(id){
			var app = this;
			axios.get(app.urlKurangProduk+'/'+ id)
			.then(function (resp) {
				app.getResults();
				app.$router.replace('/detail-pesanan-warung/'+app.detailPesananId);
			});
		},
		editProduk(id, index, nama_produk){
			var app = this;

			swal({
				title: nama_produk,
				input: 'number',
				inputPlaceholder : 'Jumlah Produk',
				html:'Masukkan Jumlah Produk Yang Di Inginkan',
				showCloseButton: true,
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Simpan',
				cancelButtonText: 'Batal',
				confirmButtonClass: 'btn btn-success',
				cancelButtonClass: 'btn btn-danger',
				buttonsStyling: false,
				inputAttributes: {
					'name': 'jumlah_produk',
					'data-id': id,
					'data-nama': nama_produk,
				}
			}).then((jumlah_produk) => {
				if (!jumlah_produk) throw null;
				app.submitProduk(jumlah_produk, id, nama_produk);
			});
		},
		alert(pesan) {
			this.$swal({
				title: "Berhasil ",
				text: pesan,
				icon: "success",
			});
		},
		submitProduk(jumlah_produk, id, nama_produk){

			if (jumlah_produk == 0 || jumlah_produk == "") {
				this.$swal({
					text: "Jumlah Produk Tidak Boleh Nol!",
				});
			}else{
				var app = this;
				app.editJumlahProduk.id = id;
				app.editJumlahProduk.jumlah_produk = jumlah_produk;
				var newJumlahProduk = app.editJumlahProduk;	        	
				app.loading = true;

				axios.post(app.urlOrigin+'edit-jumlah-produk-warung', newJumlahProduk)
				.then(function (resp) {
					app.getResults();
					app.alert("Mengubah Jumlah Produk "+nama_produk);
					app.loading = false;
					app.editJumlahProduk.jumlah_produk = ''
					app.editJumlahProduk.id = ''
					app.$router.replace('/detail-pesanan-warung/'+app.detailPesananId);
				})
				.catch(function (resp) {
					alert("Tidak Dapat Mengubah Jumlah Produk");
					console.log(resp)
				});
			}
		},
		konfirmasiPesanan(id){
			var app = this;

			swal({
				text: "Anda Yakin Ingin Melanjutkan Pesanan Ini??",
				type: 'question',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Ya!',
				cancelButtonText: 'Tidak',
				confirmButtonClass: 'btn btn-success',
				cancelButtonClass: 'btn btn-danger',
				buttonsStyling: false
			}).then(function () {
				app.submitKonfirmasiPesanan(id)
				console.log(id)
			})
		},
		submitKonfirmasiPesanan(id){      		
			var app = this;
			axios.get(app.urlKonfirmasiPesanan+'/'+ id)
			.then(function (resp) {
				app.getResults();
				app.$router.replace('/detail-pesanan-warung/'+id);
			});
		},
		batalKonfirmasiPesanan(id){
			var app = this;

			swal({
				text: "Anda Yakin Ingin Membatalkan Konfirmasi Pesanan Ini ?",
				type: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Ya!',
				cancelButtonText: 'Tidak',
				confirmButtonClass: 'btn btn-success',
				cancelButtonClass: 'btn btn-danger',
				buttonsStyling: false
			}).then(function () {
				app.submitBatalKonfirmasiPesanan(id)
				console.log(id)
			})
		},
		submitBatalKonfirmasiPesanan(id){      		
			var app = this;
			axios.get(app.urlBatalKonfirmasiPesanan+'/'+ id)
			.then(function (resp) {
				app.getResults();
				app.$router.replace('/detail-pesanan-warung/'+id);
			});
		},
		batalPesanan(id){
			var app = this;
			swal({
				text: "Anda Yakin Ingin Membatalkan Pesanan Ini??",
				type: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Ya!',
				cancelButtonText: 'Tidak',
				confirmButtonClass: 'btn btn-success',
				cancelButtonClass: 'btn btn-danger',
				buttonsStyling: false
			}).then(function () {
				app.submitBatalPesanan(id)
				console.log(id)
			})
		},
		submitBatalPesanan(id){
			var app = this;
			axios.get(app.urlBatalPesanan+'/'+ id)
			.then(function (resp) {
				app.getResults();
				app.$router.replace('/detail-pesanan-warung/'+id);
			});
		},
		selesaikanPesanan(id){
			$("#modalSelesaiPesanan").show()
		},
		submitSelesaiPesanan(id, id_kas){
			var app = this

			if (id_kas == '') {
				app.kas_error = 1
			}else{

				app.$swal({
					text: "Anda Yakin Ingin Menyelesaikan Transaksi Ini ?",
					buttons: {
						cancel: true,
						confirm: "OK"                   
					},

				}).then((value) => {

					if (!value) throw null;

					app.prosesSelesaiPesanan(id, id_kas)

				});
			}
		},
		prosesSelesaiPesanan(id, id_kas){
			var app = this;
			app.selesaiPesanan.id_pesanan = id;
			app.selesaiPesanan.id_kas = id_kas;
			var newSelesaiPesanan = app.selesaiPesanan;	  

			app.closeModalSelesaiPesanan()
			app.loading = true;
			axios.post(app.urlOrigin+'selesai-konfirmasi-pesanan-warung', newSelesaiPesanan)
			.then(function (resp) {

				if(resp.data.respons == 1){

					app.alertTbs("Gagal : Stok " + resp.data.nama_produk + " Tidak Mencukupi Untuk di Jual, Sisa Produk = "+resp.data.stok_produk);
					app.loading = false;

				}else{
					app.getResults();
					app.loading = false;
					app.selesaiPesanan.id_pesanan = ''    
					app.alert("Pesanan order #"+id+" Berhasil Di Selesaikan");  
					window.open('pesanan-warung/cetak-kecil-penjualan/'+resp.data.respons_penjualan,'_blank');   
				}


			})
			.catch(function (resp) {
				alert("Tidak Dapat Menyelesaikan Pesanan");
			});
		},
		closeModalSelesaiPesanan(){
			$("#modalSelesaiPesanan").hide()
		},
		alertTbs(pesan) {
			this.$swal({
				text: pesan,
				icon: "warning",
			});
		},
		alert(pesan) {
			this.$swal({
				title: "Berhasil ",
				text: pesan,
				icon: "success",
				buttons: false,
				timer: 1000,

			});
		}
	}
}
</script>