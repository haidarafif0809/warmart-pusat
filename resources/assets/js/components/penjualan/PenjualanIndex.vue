<style scoped>
.pencarian {
	color: red; 
	float: right;
}
.dropdown-menu {
	top: -50;
	left: 80%;
}
</style>

<template>
	<div class="row">
		<div class="col-md-12">
			<ul class="breadcrumb" style="margin-bottom:1px;"> 
				<li><router-link :to="{name: 'indexDashboard'}">Home</router-link></li>
				<li class="active">Laporan Penjualan</li>
			</ul>

			<div class="card" style="margin-top:5px;">
				<ul class="nav nav-pills nav-pills-rose" role="tablist" style="margin-top:5px;">
	    						<!--
	    							color-classes: "nav-pills-primary", "nav-pills-info", "nav-pills-success", "nav-pills-warning","nav-pills-danger"
	    						-->
	    						<li class="active">
	    							<a href="#penjualan_pos" role="tab" data-toggle="tab" style="margin-left:10px;">
	    								Penjualan POS
	    							</a>
	    						</li>
	    						<li>
	    							<a href="#penjualan_online" role="tab" data-toggle="tab" style="margin-right:10px; " v-on:click="getResultsOnline()">
	    								Penjualan Online
	    							</a>
	    						</li>
	    					</ul>
	    					<div class="tab-content tab-space" style="margin-top:5px;margin-bottom:5px;">
	    						<div class="tab-pane active" id="penjualan_pos"  style="margin-top:5px;margin-bottom:5px;">

	    							<div class="table-responsive" style="margin-right:10px; margin-left:10px;">
	    								<div class="pencarian">
	    									<input type="text" name="pencarian" v-model="pencarian" placeholder="Pencarian" class="form-control pencarian" autocomplete="" ref="pencarian">
	    								</div>

	    								<table class="table table-striped table-hover" v-if="seen">
	    									<thead class="text-primary">
	    										<tr>

	    											<th>No Transaksi</th>
	    											<th style="width:1px;">Waktu</th>
	    											<th>Pelanggan</th>
	    											<th>Status</th>
	    											<th class="text-right">Total</th>
	    											<th class="text-center">Cetak</th>
	    											<th>Detail</th>
	    											<th>Edit</th>
	    											<th>Hapus</th>

	    										</tr>
	    									</thead>
	    									<tbody v-if="penjualan.length"  class="data-ada">
	    										<tr v-for="penjualan, index in penjualan" >

	    											<td>
	    												<a href="#penjualan" v-bind:id="'edit-' + penjualan.id" v-on:click="detailTransaksi(penjualan.id,penjualan.total,penjualan.potongan,
	    												penjualan.tunai,penjualan.kembalian,penjualan.jatuh_tempo,penjualan.waktu_edit,penjualan.user_edit,penjualan.kas,penjualan.piutang,penjualan.user_buat)">{{ penjualan.id }}</a>
	    											</td>
	    											<td>{{ penjualan.waktu }}</td>
	    											<td>{{ penjualan.pelanggan }}</td>
	    											<td>{{ penjualan.status_penjualan }}</td>
	    											<td align="right"> {{ penjualan.total }}</td>
	    											<td align="center">
	    												<div class="dropdown">
	    													<button href="#pablo" class="dropdown-toggle btn btn-primary btn-xs" data-toggle="dropdown">Cetak <b class="caret"></b></button>
	    													<ul class="dropdown-menu dropdown-menu-right">
	    														<li><a target="blank" v-bind:href="'penjualan/cetak-besar-penjualan/'+penjualan.id">Cetak Besar</a></li>
	    														<li><a target="blank" v-bind:href="'penjualan/cetak-kecil-penjualan/'+penjualan.id">Cetak Kecil</a></li>
	    													</ul>
	    												</div>
	    											</td>
	    											<td>
	    												<router-link :to="{name: 'detailPenjualan', params: {id: penjualan.id}}" class="btn btn-xs btn-info" v-bind:id="'detail-' + penjualan.id" >
	    												Detail </router-link> 
	    											</td>
	    											<td><router-link :to="{name: 'prosesEditPenjualan', params: {id: penjualan.id}}" class="btn btn-xs btn-default" v-bind:id="'edit-' + penjualan.id" >
	    											Edit </router-link> </td> 
	    											<td><a href="#penjualan" class="btn btn-xs btn-danger" v-bind:id="'delete-' + penjualan.id" v-on:click="deleteEntry(penjualan.id, index,penjualan.id,penjualan.subtotal)">Delete</a></td>
	    										</tr>
	    									</tbody>                    
	    									<tbody class="data-tidak-ada" v-else>
	    										<tr ><td colspan="7"  class="text-center">Tidak Ada Data</td></tr>
	    									</tbody>
	    								</table>    

	    								<vue-simple-spinner v-if="loading"></vue-simple-spinner>

	    								<div align="right"><pagination :data="penjualanData" v-on:pagination-change-page="getResults" :limit="4"></pagination></div>
	    							</div>

	    						</div>
	    						<div class="tab-pane" id="penjualan_online" style="margin-top:2px;">
	    							
	    							<div class="table-responsive" style="margin-right:10px; margin-left:10px;">
	    								<div class="pencarian">
	    									<input type="text" name="pencarian" v-model="pencarianOnline" placeholder="Pencarian" class="form-control pencarian" autocomplete="" ref="pencarian_online">
	    								</div>

	    								<table class="table table-striped table-hover" v-if="seenOnline">
	    									<thead class="text-primary">
	    										<tr>

	    											<th>No Transaksi</th>
	    											<th style="width:1px;">Waktu</th>
	    											<th>Pelanggan</th>
	    											<th>Kas</th>
	    											<th class="text-right">Total</th>

	    										</tr>
	    									</thead>
	    									<tbody v-if="penjualanOnline.length"  class="data-ada">
	    										<tr v-for="penjualanOnline, index in penjualanOnline" >

	    											<td>{{ penjualanOnline.id }}</td>
	    											<td>{{ penjualanOnline.waktu }}</td>
	    											<td>{{ penjualanOnline.pelanggan }}</td>
	    											<td>{{ penjualanOnline.kas }}</td>
	    											<td align="right"> {{ penjualanOnline.total }}</td></tr>
	    										</tbody>                    
	    										<tbody class="data-tidak-ada" v-else>
	    											<tr ><td colspan="7"  class="text-center">Tidak Ada Data</td></tr>
	    										</tbody>
	    									</table>    

	    									<vue-simple-spinner v-if="loadingOnline"></vue-simple-spinner>

	    									<div align="right"><pagination :data="penjualanOnlineData" v-on:pagination-change-page="getResultsOnline" :limit="4"></pagination></div>
	    								</div>
	    							</div>
	    						</div>
	    					</div>

	    					<!-- MODAL DETAIL TRANSAKSI PENJUALAN -->

	    					<div class="modal" id="modal_detail_transaksi" role="dialog" data-backdrop=""> 
	    						<div class="modal-dialog"> 
	    							<!-- Modal content--> 
	    							<div class="modal-content"> 
	    								<div class="modal-header"> 
	    									<button type="button" class="close" v-on:click="closeModal()"> <i class="material-icons">close</i></button> 
	    									<h4 class="modal-title"> 
	    										<div class="alert-icon"> 
	    											<b>Detail Penjualan POS #{{penjualan_pos.id_penjualan_pos}}</b> 
	    										</div> 
	    									</h4> 
	    								</div> 
	    								<form class="form-horizontal" > 
	    									<div class="modal-body"> 
	    										<div class="card" style="margin-bottom:1px; margin-top:1px;">

	    											<table class="table" style="margin-bottom:10px; margin-top:10px; margin-right:10px; margin-left:10px;">

	    												<tbody style="margin-bottom:10px; margin-top:10px; margin-right:10px; margin-left:10px;">
	    													<tr>
	    														<td class="text-primary"><b># Kas </b> </td>
	    														<td class="text-primary"><b>: {{penjualan_pos.kas}} </b> </td>
	    													</tr>
	    													<tr>
	    														<td class="text-primary"><b># Total </b> </td>
	    														<td class="text-primary"><b>: {{ penjualan_pos.total }} </b> </td>
	    													</tr>
	    													<tr>
	    														<td class="text-primary"><b># Potongan </b> </td>
	    														<td class="text-primary"><b>: {{ penjualan_pos.potongan }} </b> </td>
	    													</tr>
	    													<tr>
	    														<td class="text-primary"><b># Tunai </b> </td>
	    														<td class="text-primary"><b>: {{ penjualan_pos.tunai }} </b> </td>
	    													</tr>
	    													<tr>
	    														<td class="text-primary"><b># Kembalian </b> </td>
	    														<td class="text-primary"><b>: {{ penjualan_pos.kembalian }} </b> </td>
	    													</tr>
	    													<tr>
	    														<td class="text-primary"><b># Piutang </b> </td>
	    														<td class="text-primary"><b>: {{ penjualan_pos.piutang }}</b> </td>
	    													</tr>
	    													<tr>
	    														<td class="text-primary"><b># Jatuh Tempo </b> </td>
	    														<td class="text-primary"><b>: {{penjualan_pos.jatuh_tempo}} </b> </td>
	    													</tr>
	    													<tr>
	    														<td class="text-primary"><b># User Buat </b> </td>
	    														<td class="text-primary"><b>: {{penjualan_pos.user_buat}} </b> </td>
	    													</tr>
	    													<tr>
	    														<td class="text-primary"><b># Waktu Edit </b> </td>
	    														<td class="text-primary"><b>: {{penjualan_pos.waktu_edit}} </b> </td>
	    													</tr>
	    													<tr>
	    														<td class="text-primary"><b># User Edit </b> </td>
	    														<td class="text-primary"><b>: {{penjualan_pos.user_edit}} </b> </td>
	    													</tr>
	    												</tbody>
	    											</table>  

	    										</div> 
	    									</div>
	    									<div class="modal-footer">  
	    									</div> 
	    								</form>
	    							</div>       
	    						</div> 
	    					</div> 
	    					<!-- / MODAL DETAIL TRANSAKSI PENJUALAN --> 


	    				</div>
	    			</div>
	    		</div>

	    	</template>


	    	<script>
	    	export default {
	    		data: function () {
	    			return {
	    				errors: [],
	    				penjualan: [],
	    				penjualanData : {},
	    				penjualanOnline: [],
	    				penjualanOnlineData : {},
	    				url : window.location.origin+(window.location.pathname).replace("dashboard", "penjualan"),
	    				pencarian: '',
	    				loading: true,
	    				seen : false,  
	    				pencarianOnline: '',
	    				loadingOnline: true,
	    				seenOnline : false,  
	    				penjualan_pos :{
	    					id_penjualan_pos : 0,
	    					kas : '',
	    					total : 0,
	    					potongan : 0,
	    					tunai : 0,
	    					kembalian : 0,
	    					piutang : 0,
	    					jatuh_tempo : '',
	    					user_buat : '',
	    					waktu_edit : '',
	    					user_edit : ''
	    				}


	    			}
	    		},
	    		mounted() {   
	    			var app = this;
	    			app.getResults();		
	    		},
	    		watch: {
			// whenever question changes, this function will run
			pencarian: function (newQuestion) {
				this.getHasilPencarian()
				this.loading = true
			},
			// whenever question changes, this function will run
			pencarianOnline: function (newQuestion) {
				this.getHasilPencarianOnline()
				this.loadingOnline = true
			}
		},
		methods: {
			getResults(page) {
				var app = this; 
				if (typeof page === 'undefined') {
					page = 1;
				}
				axios.get(app.url+'/view?page='+page)
				.then(function (resp) {
					app.penjualan = resp.data.data;
					app.penjualanData = resp.data;
					app.loading = false;
					app.seen = true;			
					app.$refs.pencarian.focus()
				})
				.catch(function (resp) {
					console.log(resp);
					app.loading = false;
					app.seen = true;
					alert("Tidak Dapat Memuat Penjualan");
				});
			}, 
			getHasilPencarian(page){
				var app = this;
				if (typeof page === 'undefined') {
					page = 1;
				}
				axios.get(app.url+'/pencarian?search='+app.pencarian+'&page='+page)
				.then(function (resp) {
					app.penjualan = resp.data.data;
					app.penjualanData = resp.data;
					app.loading = false;
					app.seen = true;
				})
				.catch(function (resp) {
					console.log(resp);
					alert("Tidak Dapat Memuat Penjualan");
				});
			},  
			getResultsOnline(page) {
				var app = this; 
				if (typeof page === 'undefined') {
					page = 1;
				}
				axios.get(app.url+'/view-online?page='+page)
				.then(function (resp) {
					app.penjualanOnline = resp.data.data;
					app.penjualanOnlineData = resp.data;
					app.loadingOnline = false;
					app.seenOnline = true;			
					app.$refs.pencarian_online.focus()
				})
				.catch(function (resp) {
					console.log(resp);
					app.loadingOnline = false;
					app.seenOnline = true;
					alert("Tidak Dapat Memuat Penjualan Online");
				});
			}, 
			getHasilPencarianOnline(page){
				var app = this;
				if (typeof page === 'undefined') {
					page = 1;
				}
				axios.get(app.url+'/pencarian-online?search='+app.pencarianOnline+'&page='+page)
				.then(function (resp) {
					app.penjualanOnline = resp.data.data;
					app.penjualanOnlineData = resp.data;
					app.loadingOnline = false;
					app.seenOnline = true;
				})
				.catch(function (resp) {
					console.log(resp);
					alert("Tidak Dapat Memuat Penjualan Online");
				});
			},    
			deleteEntry(id, index,no_faktur) {

				var app = this;
				app.$swal({
					text: "Anda Yakin Ingin Menghapus Penjualan "+no_faktur+ " ?",
					buttons: true,
					dangerMode: true,
				})
				.then((willDelete) => {
					if (willDelete) {

						this.prosesDelete(id,no_faktur);

					} else {

						app.$swal.close();

					}
				});

			},
			prosesDelete(id,no_faktur){

				var app = this;
				app.loading = true;
				axios.delete(app.url+'/' + id)
				.then(function (resp) {

					if (resp.data == 0) {

						app.alertTbs("Penjualan "+no_faktur+" Gagal Dihapus!")
						app.loading = false

					}else{

						app.getResults()
						app.alert("Menghapus Penjualan "+no_faktur)
						app.loading = false
					}


				})
				.catch(function (resp) {

					console.log(resp);
					app.loading = false;
					alert("Tidak dapat Menghapus Penjualan "+no_faktur);
				});
			},
			detailTransaksi(id_penjualan_pos,total,potongan,tunai,kembalian,jatuh_tempo,waktu_edit,user_edit,kas,piutang,user_buat){

				this.penjualan_pos.id_penjualan_pos = id_penjualan_pos
				this.penjualan_pos.kas = kas
				this.penjualan_pos.total = total		
				this.penjualan_pos.potongan = potongan
				this.penjualan_pos.tunai = tunai
				this.penjualan_pos.kembalian = kembalian
				this.penjualan_pos.jatuh_tempo = jatuh_tempo
				this.penjualan_pos.waktu_edit = waktu_edit
				this.penjualan_pos.user_edit = user_edit
				this.penjualan_pos.piutang = piutang
				this.penjualan_pos.user_buat = user_buat
				$("#modal_detail_transaksi").show()

			},
			closeModal(){
				$("#modal_detail_transaksi").hide();
			},
			alert(pesan) {
				this.$swal({
					title: "Berhasil ",
					text: pesan,
					icon: "success",
				});
			}
		}
	}
	</script>

