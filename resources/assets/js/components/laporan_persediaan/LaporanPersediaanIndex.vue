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
					<li class="active">Laporan Persediaan</li>
				</ul>
				<div class="card">
					<div class="card-header card-header-icon" data-background-color="purple">
						<i class="material-icons">storage</i>
					</div>
					<div class="card-content">
						<h4 class="card-title"> Laporan Persediaan </h4>
						<div class="toolbar">
						</div>

						<div class=" table-responsive ">
							<div class="pencarian">
								<input type="text" name="pencarian" v-model="pencarian" placeholder="Pencarian" class="form-control pencarian" autocomplete="">
							</div>

							
							<table class="table table-striped table-hover" v-if="seen">
								<thead class="text-primary">
									<tr>

										<th>Kode Produk</th>
										<th>Nama Produk</th>
										<th>Satuan</th>
										<th class="text-right">Stok</th>
										<th class="text-right">Hpp</th>
										<th class="text-right">Nilai</th>

									</tr>
								</thead>
								<tbody v-if="lap_persediaan.length"  class="data-ada">
									<tr v-for="lap_persediaan, index in lap_persediaan" >

										<td>{{ lap_persediaan.kode_produk }}</td>
										<td>{{ lap_persediaan.nama_produk }}</td>
										<td>{{ lap_persediaan.satuan }}</td>
										<td align="right">{{ lap_persediaan.stok }}</td>
										<td align="right">{{ lap_persediaan.hpp }}</td>
										<td align="right">{{ lap_persediaan.nilai }}</td>
									</tr>
									<tr><td style="color:red">Total Nilai</td>
										<td></td>
										<td></td>
										<td align="right"></td>
										<td align="right"></td>
										<td style="color:red" align="right">{{totalNilai}}</td>
									</tr>
								</tbody>					
								<tbody class="data-tidak-ada" v-else>
									<tr ><td colspan="7"  class="text-center">Tidak Ada Data</td></tr>
								</tbody>
							</table>	


							<vue-simple-spinner v-if="loading"></vue-simple-spinner>

							<div align="right"><pagination :data="lapPersediaanData" v-on:pagination-change-page="getResults" :limit="4"></pagination></div>

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
				lap_persediaan: [],
				totalNilai: '',
				url : window.location.origin+(window.location.pathname).replace("dashboard", "laporan-persediaan"),
				pencarian: '',
				loading: true,
				seen : false
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
    			app.lap_persediaan = resp.data.data;
    			app.lapPersediaanData = resp.data;
    			app.totalNilai = resp.data.totalnilai;
    			app.loading = false;
    			app.seen = true;
    		})
    		.catch(function (resp) {
    			console.log(resp);
    			app.loading = false;
    			app.seen = true;
    			alert("Tidak Dapat Memuat Laporan Persediaan");
    		});
    	},
    	getHasilPencarian(page){
    		var app = this;
    		if (typeof page === 'undefined') {
    			page = 1;
    		}
    		axios.get(app.url+'/pencarian?search='+app.pencarian+'&page='+page)
    		.then(function (resp) {
    			app.lap_persediaan = resp.data.data;
    			app.lapPersediaanData = resp.data;
    			app.loading = false;
    			app.seen = true;
    		})
    		.catch(function (resp) {
    			console.log(resp);
    			alert("Tidak Dapat Memuat Laporan Persediaan");
    		});
    	}
    }
}
</script>