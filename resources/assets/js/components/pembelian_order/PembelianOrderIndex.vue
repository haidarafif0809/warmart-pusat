<style scoped>
	.pencarian {
		color: red; 
		float: right;
	}
</style>
<template>
	<div class="row">
		<div class="col-md-12">

			<ul class="breadcrumb">
				<li><router-link :to="{name: 'indexDashboard'}">Home</router-link></li>
				<li class="active">Order Pembelian</li>
			</ul>


			<div class="card">
				<div class="card-header card-header-icon" data-background-color="purple">
					<i class="material-icons">add_shopping_cart</i>
				</div>
				<div class="card-content">
					<h4 class="card-title"> Order Pembelian </h4>

					<div class="toolbar">
						<p> <router-link :to="{name: 'createPembelianOrder'}" class="btn btn-primary">Tambah Order Pembelian</router-link></p>
					</div>

					<div class="modal" id="modal_detail_transaksi" role="dialog" data-backdrop=""> 
						<div class="modal-dialog"> 
							<!-- Modal content--> 
							<div class="modal-content"> 
								<div class="modal-header"> 
									<button type="button" class="close" v-on:click="closeModal()"> <i class="material-icons">close</i></button> 
									<h4 class="modal-title"> 
										<div class="alert-icon"> 
											<b>Detail Pembelian #{{no_faktur}}</b> 
										</div> 
									</h4> 
								</div> 
								<form class="form-horizontal" > 
									<div class="modal-body"> 
										<div class="card" style="margin-bottom:1px; margin-top:1px;">
											<div class="table-responsive">
												<table class="table" style="margin-bottom:10px; margin-top:10px; margin-right:10px; margin-left:10px;">

													<tbody style="margin-bottom:10px; margin-top:10px; margin-right:10px; margin-left:10px;">
														<tr>
															<td class="text-primary"><b># Kas </b> </td>
															<td class="text-primary"><b>: {{kas}} </b> </td>
														</tr>
														<tr>
															<td class="text-primary"><b># Total </b> </td>
															<td class="text-primary"><b style="text-align:right;">: {{ total }} </b> </td>
														</tr>
														<tr>
															<td class="text-primary"><b># Potongan </b> </td>
															<td class="text-primary"><b style="text-align:right;">: {{ potongan }} </b> </td>
														</tr>
														<tr>
															<td class="text-primary"><b># Tunai </b> </td>
															<td class="text-primary"><b style="text-align:right;">: {{ tunai }} </b> </td>
														</tr>
														<tr>
															<td class="text-primary"><b># Kembalian </b> </td>
															<td class="text-primary"><b style="text-align:right;">: {{ kembalian }} </b> </td>
														</tr>
														<tr>
															<td class="text-primary"><b># Jatuh Tempo </b> </td>
															<td class="text-primary"><b>: {{jatuh_tempo}} </b> </td>
														</tr>
														<tr>
															<td class="text-primary"><b># User Buat </b> </td>
															<td class="text-primary"><b>: {{user_buat}} </b> </td>
														</tr>
													</tbody>
												</table>  
											</div>
										</div> 
									</div>
									<div class="modal-footer">  
										<button type="button" class="btn btn-default" v-on:click="closeModal()" v-shortkey.push="['esc']" @shortkey="closeModal()"><i class="material-icons">close</i> Tutup(Esc)</button>
									</div> 
								</form>
							</div>       
						</div> 
					</div> 
					<!-- / MODAL TOMBOL SELESAI --> 


					<div class=" table-responsive ">
						<div class="pencarian">
							<input type="text" class="form-control pencarian" autocomplete="off" name="pencarian" v-model="pencarian" placeholder="Pencarian" >
						</div>

						<table class="table table-striped table-hover">
							<thead class="text-primary">
								<tr>
									<th>No. Transaksi</th>
									<th>Waktu</th>
									<th>Supplier</th>
									<th>Status</th>
									<th style="text-align:right;">Total</th>
									<th style="text-align:right;">Edit</th>
									<th style="text-align:right;">Detail</th>
									<th style="text-align:right;">Cetak</th>									
									<th style="text-align:right;">Delete</th>
								</tr>
							</thead>
							<tbody v-if="pembelianOrder.length > 0 && loading == false"  class="data-ada">
								<tr v-for="pembelianOrders, index in pembelianOrder" >

									<td>{{ pembelianOrders.data.no_faktur_order }}</td>
									<td>{{ pembelianOrders.data.created_at | tanggal }}</td>
									<td>{{ pembelianOrders.data.nama_suplier }}</td>
									<td>{{ pembelianOrders.status_order }}</td>
									<td style="text-align:right;" >Rp. {{ pembelianOrders.data.total | pemisahTitik }}</td>

									<td style="text-align:right;">
										<router-link :to="{name: 'prosesEditPembelianOrder', params: {id: pembelianOrders.data.id}}" class="btn btn-xs btn-default" v-bind:id="'edit-' + pembelianOrders.data.id" >
											Edit 
										</router-link>
									</td>
									<td style="text-align:right;">
										<router-link :to="{name: 'detailPembelianOrder', params: {id: pembelianOrders.data.id}}" class="btn btn-xs btn-info" v-bind:id="'detail-' + pembelianOrders.data.no_faktur_order" >
											Detail
										</router-link> 
									</td>
									<td style="text-align:right;">
										<a target="blank" class="btn btn-primary btn-xs" v-bind:href="'pembelian-order/cetak-besar-pembelian-order/'+pembelianOrders.data.id">Cetak Ulang</a>
									</td>
									<td style="text-align:right;"> 
										<a  href="#order-pembelian" class="btn btn-xs btn-danger" v-bind:id="'delete-' + pembelianOrders.data.id" v-on:click="deleteEntry(pembelianOrders.data.id, index,pembelianOrders.data.no_faktur_order)">Delete</a>
									</td>
								</tr>
							</tbody>					
							<tbody class="data-tidak-ada" v-else-if="pembelianOrder.length == 0 && loading == false">
								<tr ><td colspan="7"  class="text-center">Tidak Ada Data</td></tr>
							</tbody>
						</table>	

						<vue-simple-spinner v-if="loading"></vue-simple-spinner>

						<div align="right"><pagination :data="pembelianOrderData" v-on:pagination-change-page="getResults" :limit="4"></pagination></div>

					</div>
					<p style="color: red; font-style: italic;">*Note : Klik Kolom No Transaksi, Untuk Melihat Detail Transaksi Pembelian .</p> 
				</div>
			</div>

		</div>
	</div>

</template>


<script>
	export default {
		data: function () {
			return {
				pembelianOrder: [],
				pembelianOrderData: {},
				otoritas: {},
				detailPembelianOrders: [],
				url : window.location.origin+(window.location.pathname).replace("dashboard", "pembelian-order"),
				pencarian: '',
				loading: true,
				no_faktur : 0,
				kas : '',
				total : 0,
				potongan : 0,
				tunai : 0,
				kembalian : 0,
				jatuh_tempo : '',
				user_buat : '',
			}
		},
		mounted() {
			var app = this;
			app.getResults();
		},
		watch: {
			pencarian: function (newQuestion) {
				this.getHasilPencarian();
				this.loading = true;  
			}
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
			getResults(page) {
				var app = this;	
				if (typeof page === 'undefined') {
					page = 1;
				}
				axios.get(app.url+'/view?page='+page)
				.then(function (resp) {
					console.log(resp.data.data)
					app.pembelianOrder = resp.data.data;
					app.pembelianOrderData = resp.data;
					app.loading = false;
				})
				.catch(function (resp) {
					console.log(resp);
					app.loading = false;
					alert("Tidak Dapat Memuat Order Pembelian");
				});
			},
			getHasilPencarian(page){
				var app = this;
				if (typeof page === 'undefined') {
					page = 1;
				}
				axios.get(app.url+'/pencarian?search='+app.pencarian+'&page='+page)
				.then(function (resp) {
					app.pembelianOrder = resp.data.data;
					app.pembelianOrderData = resp.data;
					app.loading = false;
				})
				.catch(function (resp) {
					console.log(resp);
					alert("Tidak Dapat Memuat Order Pembelian");
				});
			},
			alert(pesan) {
				this.$swal({
					title: "Berhasil ",
					text: pesan,
					icon: "success",
				});
			},
			deleteEntry(id, index,no_faktur) {
				this.$swal({
					title: "Konfirmasi Hapus",
					text : "Anda Yakin Ingin Menghapus Faktur "+no_faktur+" ?",
					icon : "warning",
					buttons: true,
					dangerMode: true,
				})
				.then((willDelete) => {
					if (willDelete) {
						var app = this;
						app.loading = true;
						axios.delete(app.url+'/' + id)
						.then(function (resp) {
							if (resp.data == 0) {
								app.$swal('Oops...','Order Pembelian Tidak Dapat Dihapus, Karena Sudah Terpakai','error');
								app.loading = false;

							}else{
								app.getResults();
								app.alert("Menghapus Order Pembelian Faktur "+no_faktur);
								app.loading = false;  
							}
						})
						.catch(function (resp) {
							alert("Tidak dapat Menghapus Order Pembelian");
						});
					}else {
						app.$swal.close();
					}
				});
			},
			detailModalPembelian(id,index,no_faktur){
				var app = this;
				axios.get(app.url+'/detail-view?id='+id+'&no_faktur='+no_faktur)
				.then(function (resp) {
					app.detailPembelianOrders = resp.data.data;
				})
				.catch(function (resp) {
					app.loading = false;
					alert("Tidak Dapat Memuat Detail Order Pembelian");
				});
			},
			closeModal(){
				$("#modal_detail_transaksi").hide();
			},
		}
	}
</script>

