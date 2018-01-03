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
				<li class="active">Laporan Penjualan</li>
			</ul>
			<div class="card">
				<div class="card-header card-header-icon" data-background-color="purple">
					<i class="material-icons">shopping_cart</i>
				</div>
				<div class="card-content">
					<h4 class="card-title"> Laporan Penjualan </h4>
					<div class="toolbar">
					</div>


					<div class="modal" id="modal_detail_transaksi" role="dialog" data-backdrop=""> 
						<div class="modal-dialog"> 
							<!-- Modal content--> 
							<div class="modal-content"> 
								<div class="modal-header"> 
									<button type="button" class="close" v-on:click="closeModal()"> <i class="material-icons">close</i></button> 
									<h4 class="modal-title"> 
										<div class="alert-icon"> 
											<b>Detail Penjualan POS #{{id_penjualan_pos}}</b> 
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
														<td class="text-primary"><b>: {{kas}} </b> </td>
													</tr>
													<tr>
														<td class="text-primary"><b># Total </b> </td>
														<td class="text-primary"><b>: {{ new Intl.NumberFormat().format(total) }},00 </b> </td>
													</tr>
													<tr>
														<td class="text-primary"><b># Potongan </b> </td>
														<td class="text-primary"><b>: {{ new Intl.NumberFormat().format(potongan) }},00 </b> </td>
													</tr>
													<tr>
														<td class="text-primary"><b># Tunai </b> </td>
														<td class="text-primary"><b>: {{ new Intl.NumberFormat().format(tunai) }},00 </b> </td>
													</tr>
													<tr>
														<td class="text-primary"><b># Kembalian </b> </td>
														<td class="text-primary"><b>: {{ new Intl.NumberFormat().format(kembalian) }},0 </b> </td>
													</tr>
													<tr>
														<td class="text-primary"><b># Piutang </b> </td>
														<td class="text-primary"><b>: {{ new Intl.NumberFormat().format(piutang) }},0 </b> </td>
													</tr>
													<tr>
														<td class="text-primary"><b># Jatuh Tempo </b> </td>
														<td class="text-primary"><b>: {{jatuh_tempo}} </b> </td>
													</tr>
													<tr>
														<td class="text-primary"><b># User Buat </b> </td>
														<td class="text-primary"><b>: {{user_buat}} </b> </td>
													</tr>
													<tr>
														<td class="text-primary"><b># Waktu Edit </b> </td>
														<td class="text-primary"><b>: {{waktu_edit}} </b> </td>
													</tr>
													<tr>
														<td class="text-primary"><b># User Edit </b> </td>
														<td class="text-primary"><b>: {{user_edit}} </b> </td>
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
					<!-- / MODAL TOMBOL SELESAI --> 

					<div class=" table-responsive ">

						<div class="pencarian">
							<input type="text" name="pencarian" v-model="pencarian" placeholder="Pencarian" class="form-control pencarian" autocomplete="">
						</div>

						<table class="table table-striped table-hover" v-if="seen">
							<thead class="text-primary">
								<tr>

									<th>No Transaksi</th>
									<th>Waktu</th>
									<th>Pelanggan</th>
									<th>Status</th>
									<th align="right">Total</th>
									<th>Cetak</th>
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
									<td align="center"> {{ new Intl.NumberFormat().format(penjualan.total) }},00</td>
									<td>
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
			url : window.location.origin+(window.location.pathname).replace("dashboard", "penjualan"),
			pencarian: '',
			loading: true,
			seen : false,  
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

		this.id_penjualan_pos = id_penjualan_pos
		this.kas = kas
		this.total = total		
		this.potongan = potongan
		this.tunai = tunai
		this.kembalian = kembalian
		this.jatuh_tempo = jatuh_tempo
		this.waktu_edit = waktu_edit
		this.user_edit = user_edit
		this.piutang = piutang
		this.user_buat = user_buat
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

