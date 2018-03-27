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
				<li class="active">Pembayaran Piutang</li>
			</ul>


			<div class="card">
				<div class="card-header card-header-icon" data-background-color="purple">
					<i class="material-icons">local_atm</i>
				</div>
				<div class="card-content">
					<h4 class="card-title"> Pembayaran Piutang </h4>

					<div class="toolbar">
						<p> <router-link :to="{name: 'createPembayaranPiutang'}" class="btn btn-primary" v-if="otoritas.tambah_pembayaran_piutang == 1">Tambah Pembayaran Piutang</router-link></p>
					</div>

					<div class=" table-responsive ">
						<div class="pencarian">
							<input type="text" class="form-control pencarian" autocomplete="off" name="pencarian" v-model="pencarian" placeholder="Pencarian" >
						</div>

						<table class="table table-striped table-hover">
							<thead class="text-primary">
								<tr>
									<th>No. Transaksi</th>
									<th style="text-align:right;">Total</th>
									<th style="text-align:center;">Kas</th>
									<th>Waktu</th>
									<th>Keterangan</th>
									<th style="text-align:right;">Cetak</th>
									<th v-if="otoritas.edit_pembayaran_piutang == 1" style="text-align:right;">Edit</th>	
									<th v-if="otoritas.hapus_pembayaran_piutang == 1" style="text-align:right;">Delete</th>
								</tr>
							</thead>
							<tbody v-if="pembayaranPiutang.length > 0 && loading == false"  class="data-ada">
								<tr v-for="pembayaranPiutangs, index in pembayaranPiutang" >

									<td>
										<router-link :to="{name: 'detailPembayaranPiutang', params: {id: pembayaranPiutangs.id}}" v-bind:id="'detail-' + pembayaranPiutangs.id" >
											{{pembayaranPiutangs.no_faktur}}
										</router-link>
									</td>

									<td style="text-align:right;" >{{ pembayaranPiutangs.total }}</td>
									<td align="center">{{ pembayaranPiutangs.kas }}</td>
									<td>{{ pembayaranPiutangs.waktu }}</td>
									<td>{{ pembayaranPiutangs.keterangan }}</td>

									<td style="text-align:right;">
										<a target="blank" class="btn btn-primary btn-xs" v-bind:href="'pembayaran-piutang/cetak-pembayaran-piutang/'+pembayaranPiutangs.id">Cetak Ulang</a>
									</td>

									<td v-if="otoritas.edit_pembayaran_piutang == 1" style="text-align:right;">
										<router-link :to="{name: 'prosesEditPembayaranPiutang', params: {id: pembayaranPiutangs.id}}" class="btn btn-xs btn-default" v-bind:id="'edit-' + pembayaranPiutangs.id" >
											Edit
										</router-link>
									</td>

									<td style="text-align:right;"> 
										<a v-if="otoritas.hapus_pembayaran_piutang == 1" href="#pembayaran-piutang" class="btn btn-xs btn-danger" v-bind:id="'delete-' + pembayaranPiutangs.id" v-on:click="deleteEntry(pembayaranPiutangs.id, index,pembayaranPiutangs.no_faktur)">Delete</a>
									</td>
								</tr>
							</tbody>					
							<tbody class="data-tidak-ada" v-else-if="pembayaranPiutang.length == 0 && loading == false">
								<tr ><td colspan="7"  class="text-center">Tidak Ada Data</td></tr>
							</tbody>
						</table>	

						<vue-simple-spinner v-if="loading"></vue-simple-spinner>

						<div align="right"><pagination :data="pembayaranPiutangData" v-on:pagination-change-page="getResults" :limit="7"></pagination></div>

					</div>
					<p style="color: red; font-style: italic;">*Note : Klik Kolom No Transaksi, Untuk Melihat Detail Transaksi Pembayaranan Piutang .</p> 
				</div>
			</div>

		</div>
	</div>

</template>


<script>
export default {
	data: function () {
		return {
			pembayaranPiutang: [],
			pembayaranPiutangData: {},
			otoritas: {},
			url : window.location.origin+(window.location.pathname).replace("dashboard", "pembayaran-piutang"),
			pencarian: '',
			loading: true,
		}
	},
	mounted() {
		var app = this;
		app.getResults();
	},
	watch: {
// whenever question changes, this function will run
pencarian: function (newQuestion) {
	this.getHasilPencarian();
	this.loading = true;  
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
			app.pembayaranPiutang = resp.data.data;
			app.pembayaranPiutangData = resp.data;
			app.otoritas = resp.data.otoritas.original;
			app.loading = false;
		})
		.catch(function (resp) {
			console.log(resp);
			app.loading = false;
			alert("Tidak Dapat Memuat Pembayaran Piutang");
		});
	},
	getHasilPencarian(page){
		var app = this;
		if (typeof page === 'undefined') {
			page = 1;
		}
		axios.get(app.url+'/pencarian?search='+app.pencarian+'&page='+page)
		.then(function (resp) {
			app.pembayaranPiutang = resp.data.data;
			app.pembayaranPiutangData = resp.data;
			app.otoritas = resp.data.otoritas.original;
			app.loading = false;
		})
		.catch(function (resp) {
			console.log(resp);
			alert("Tidak Dapat Memuat Pembayaran Piutang");
		});
	},
	alert(pesan) {
		this.$swal({
			title: "Berhasil ",
			text: pesan,
			icon: "success",
			buttons: false,
			timer: 1000
		});
	},
	deleteEntry(id, index,no_faktur) {
		this.$swal({
			title: "Konfirmasi Hapus",
			text : "Anda Yakin Ingin Menghapus "+no_faktur+" ?",
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
						app.$swal('Oops...','Pembayaran Piutang Gagal Terhapus','error');
						app.loading = false;
					}else{
						app.getResults();
						app.alert("Menghapus Pembayaran Piutang "+no_faktur);
						app.loading = false;
					}
				})
				.catch(function (resp) {
					alert("Tidak dapat Menghapus Pembayaran Piutang");
				});
			}else {
				app.$swal.close();
			}
		});
	}
}
}
</script>

