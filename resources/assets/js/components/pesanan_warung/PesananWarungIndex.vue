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
        <!-- MODAL PEMESAN -->
        <div class="modal " id="data_pemesan" role="dialog" data-backdrop="">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Data Pemesan</h4>
                        </div>

                        <div class="modal-body">
                            <div class="responsive">
                                <table class="table table-striped table-hover ">
                                    <tbody>
                                        <tr>
                                            <tr><td width="25%"> Nama</td> <td> : </td> <td>  {{detailPesananWarung.nama_pemesan}} </td></tr>
                                            <tr><td width="25%"> Alamat</td> <td> : </td> <td>  {{detailPesananWarung.alamat_pemesan}} </td></tr>
                                            <tr><td width="25%"> No Telp</td> <td> : </td> <td>  {{detailPesananWarung.no_telp_pemesan}} </td></tr>
                                        </tr>
                                    </tbody>
                            </table>
                            </div>
                        </div>
                </div>
            </div>
        </div> <!--END MODAL PEMESAN-->

			<ul class="breadcrumb">
				<li><router-link :to="{name: 'indexDashboard'}">Home</router-link></li>
				<li class="active">Pesanan</li>
			</ul>
			
			<div class="card">
				<div class="card-header card-header-icon" data-background-color="purple">
					<i class="material-icons">archive</i>
				</div>
				<div class="card-content">
					<h4 class="card-title"> Pesanan</h4>

					<div class="toolbar">
                    <router-link :to="{name: 'createKategoriTransaksi'}" class="btn btn-primary" style="padding-bottom:10px"><i class="material-icons">add</i></router-link>

                        <div class="pencarian">
                            <input type="text" name="pencarian" v-model="pencarian" placeholder="Pencarian" class="form-control pencarian" autocomplete="">
                        </div>
					</div>

                    <br>
					<div class=" table-responsive ">
						<table class="table table-striped table-hover ">
							<thead class="text-primary">
								<tr>

                                    <th>Pesanan</th>
                                    <th>Pemesan</th>
                                    <th>Jumlah</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Waktu</th>
                                    <th>Detail</th>

                                </tr>
                            </thead>
                            <tbody v-if="pesananWarung.length > 0 && loading == false"  class="data-ada">
                                <tr v-for="pesananWarung, index in pesananWarung" >
                                     <td>{{ pesananWarung.pesanan_warung.id }}</td>
                                     <td>{{ pesananWarung.pemesan }}</td>
                                     <td>{{ pesananWarung.pesanan_warung.jumlah_produk }}</td>
                                     <td>{{ pesananWarung.subtotal }}</td>
                                     <td>
                                        <b style="color:red" v-if="pesananWarung.pesanan_warung.konfirmasi_pesanan == 0">Belum Di Konfirmasi</b>
                                        <b style="color:orange" v-else-if="pesananWarung.pesanan_warung.konfirmasi_pesanan == 1">Sudah Konfirmasi</b>
                                        <b style="color:#01573e" v-else-if="pesananWarung.pesanan_warung.konfirmasi_pesanan == 2">Selesai</b>
                                        <b style="color:#01573e" v-else>Batal</b>
                                     </td>
                                     <td>{{ pesananWarung.pesanan_warung.created_at }}</td>
                                     <td>
                                        <router-link :to="{name: 'detailPesananWarung', params: {id: pesananWarung.pesanan_warung.id}}" class="btn btn-sm btn-default" v-bind:id="'detail-' + pesananWarung.pesanan_warung.id" > Detail Pesanan
                                        </router-link>

                                        <button type="button" class="btn btn-sm btn-primary" id="btnDetail" data-toggle="modal" data-target="#data_pemesan" @click="detailPesanan(pesananWarung.pesanan_warung.id)">Pemesan</button>
                                    </td>
                                </tr>
                            </tbody>					
                            <tbody class="data-tidak-ada" v-else-if="pesananWarung.length == 0 && loading == false">
                                <tr ><td colspan="7"  class="text-center">Tidak Ada Data</td></tr>
                            </tbody>
                    </table>	

                 <vue-simple-spinner v-if="loading"></vue-simple-spinner>

                 <div align="right"><pagination :data="pesananWarungData" v-on:pagination-change-page="getResults" :limit="4"></pagination></div>

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
			pesananWarung: [],
            detailPesananWarung: {},
            pesananWarungData: {},
			url : window.location.origin+(window.location.pathname).replace("dashboard", "pesanan-warung"),
			pencarian: '',
			loading: true
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
    			app.pesananWarung = resp.data.data;
    			app.pesananWarungData = resp.data;
    			app.loading = false;
                console.log(resp.data)
    		})
    		.catch(function (resp) {
    			console.log(resp);
    			app.loading = false;
    			alert("Tidak Dapat Memuat Pesanan");
    		});
    	},
        getHasilPencarian(page){
            var app = this;
            if (typeof page === 'undefined') {
                page = 1;
            }
            axios.get(app.url+'/pencarian?search='+app.pencarian+'&page='+page)
            .then(function (resp) {
                app.pesananWarung = resp.data.data;
                app.pesananWarungData = resp.data;
                app.loading = false;
            })
            .catch(function (resp) {
                console.log(resp);
                alert("Tidak Dapat Memuat Pesanan");
            });
        },
        detailPesanan(id){
            var app = this; 

            axios.get(app.url+'/detail-view/'+id)
            .then(function (resp) {
                app.detailPesananWarung = resp.data;
            })
            .catch(function (resp) {
                app.loading = false;
                alert("Tidak Dapat Memuat Detail Pesanan");
            });
        }
    }
}
</script>