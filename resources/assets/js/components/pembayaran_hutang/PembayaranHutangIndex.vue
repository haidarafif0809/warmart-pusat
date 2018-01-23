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
				<li class="active">Pembayaran Hutang</li>
			</ul>

			
			<div class="card">
				<div class="card-header card-header-icon" data-background-color="purple">
					<i class="material-icons">add_shopping_cart</i>
				</div>
				<div class="card-content">
					<h4 class="card-title"> Pembayaran Hutang </h4>
					
					<div class="toolbar">
						<p> <router-link :to="{name: 'createPembayaranHutang'}" class="btn btn-primary">Tambah Pembayaran Hutang</router-link></p>
					</div>

					<div class=" table-responsive ">
						<div class="pencarian">
							<input type="text" class="form-control pencarian" autocomplete="off" name="pencarian" v-model="pencarian" placeholder="Pencarian" >
						</div>

						<table class="table table-striped table-hover">
							<thead class="text-primary">
								<tr>
									<th>No. Transaksi</th>
									<th>Waktu</th>
									<th>Suplier</th>
									<th>Kas</th>
									<th>Keterangan</th>
									<th style="text-align:right;">Total</th>
									<th style="text-align:right;">Edit</th>								
									<th style="text-align:right;">Delete</th>
								</tr>
							</thead>
							<tbody v-if="pembayaranhutang.length > 0 && loading == false"  class="data-ada">
								<tr v-for="pembayaranhutangs, index in pembayaranhutang" >

									<td>{{ pembayaranhutangs.no_faktur }}</td>
									<td>{{ pembayaranhutangs.waktu }}</td>
									<td>{{ pembayaranhutangs.suplier }}</td>
									<td>{{ pembayaranhutangs.kas }}</td>
									<td>{{ pembayaranhutangs.keterangan }}</td>
									<td style="text-align:right;" >Rp. {{ pembayaranhutangs.total }}</td>
									<td style="text-align:right;"><router-link :to="{name: 'editPembayaranHutangProses', params: {id: pembayaranhutangs.id}}" class="btn btn-xs btn-default" v-bind:id="'edit-' + pembayaranhutangs.id" >
									Edit </router-link> </td>
									<td style="text-align:right;"> 
										<a  href="#" class="btn btn-xs btn-danger" v-bind:id="'delete-' + pembayaranhutangs.id" v-on:click="deleteEntry(pembayaranhutangs.id, index,pembayaranhutangs.no_faktur)">Delete</a>
									</td>
								</tr>
							</tbody>					
							<tbody class="data-tidak-ada" v-else-if="pembayaranhutang.length == 0 && loading == false">
								<tr ><td colspan="7"  class="text-center">Tidak Ada Data</td></tr>
							</tbody>
						</table>	

						<vue-simple-spinner v-if="loading"></vue-simple-spinner>

						<div align="right"><pagination :data="pembayaranhutangData" v-on:pagination-change-page="getResults" :limit="7"></pagination></div>

					</div>
					 <p style="color: red; font-style: italic;">*Note : Klik Kolom No Transaksi, Untuk Melihat Detail Transaksi Pembayaranan Hutang .</p> 
				</div>
			</div>

		</div>
	</div>

</template>


<script>
export default {
	data: function () {
		return {
			pembayaranhutang: [],
			pembayaranhutangData: {},
			url : window.location.origin+(window.location.pathname).replace("dashboard", "pembayaran-hutang"),
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
    			app.pembayaranhutang = resp.data.data;
    			app.pembayaranhutangData = resp.data;
    			app.loading = false;
    		})
    		.catch(function (resp) {
    			console.log(resp);
    			app.loading = false;
    			alert("Tidak Dapat Memuat Pembayaran Hutang");
    		});
    	},
    	getHasilPencarian(page){
    		var app = this;
    		if (typeof page === 'undefined') {
    			page = 1;
    		}
    		axios.get(app.url+'/pencarian?search='+app.pencarian+'&page='+page)
    		.then(function (resp) {
    			app.pembayaranhutang = resp.data.data;
    			app.pembayaranhutangData = resp.data;
    			app.loading = false;
    		})
    		.catch(function (resp) {
    			console.log(resp);
    			alert("Tidak Dapat Memuat Pembayaran Hutang");
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
			                    app.$swal('Oops...','Pembayaran Hutang Tidak Dapat Dihapus, Karena Sudah Terpakai','error');
			                    app.loading = false;

			                }else{
			                    app.getResults();
			                    app.alert("Menghapus Pembelian "+no_faktur);
			                    app.loading = false;  
			                }
                  })
                  .catch(function (resp) {
                      alert("Tidak dapat Menghapus Pembelian");
                  });
               }else {
    				app.$swal.close();
    			}
            });
    	}
    }
}
</script>

