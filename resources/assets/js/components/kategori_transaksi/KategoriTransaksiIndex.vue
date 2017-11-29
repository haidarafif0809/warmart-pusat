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
				<li class="active">Kategori Transaksi</li>
			</ul>
			
			<div class="card">
				<div class="card-header card-header-icon" data-background-color="purple">
					<i class="material-icons">local_offer</i>
				</div>
				<div class="card-content">
					<h4 class="card-title"> Kategori Transaksi</h4>

					<div class="toolbar">
                        <router-link :to="{name: 'createKategoriTransaksi'}" class="btn btn-primary" style="padding-bottom:10px"><i class="material-icons">add</i>  Kategori Transaksi</router-link>

                        <div class="pencarian">
                            <input type="text" name="pencarian" v-model="pencarian" placeholder="Pencarian" class="form-control pencarian" autocomplete="">
                        </div>
					</div>

                    <br>
					<div class=" table-responsive ">
						<table class="table table-striped table-hover ">
							<thead class="text-primary">
								<tr>

									<th>Kategori Transaksi</th>
									<th>Aksi</th>

                                </tr>
                            </thead>
                            <tbody v-if="kategoriTransaksi.length"  class="data-ada">
                                <tr v-for="kategoriTransaksi, index in kategoriTransaksi" >
                                     <td>{{ kategoriTransaksi.nama_kategori_transaksi }}</td>
                                     <td>
                                        <router-link :to="{name: 'editKategoriTransaksi', params: {id: kategoriTransaksi.id}}" class="btn btn-xs btn-default" v-bind:id="'edit-' + kategoriTransaksi.id" > Edit
                                        </router-link>

                                        <a v-if="kategoriTransaksi.status_transaksi == 0" href="#" class="btn btn-xs btn-danger" v-bind:id="'delete-' + kategoriTransaksi.id" v-on:click="deleteEntry(kategoriTransaksi.id, index,kategoriTransaksi.nama_kategori_transaksi)">Delete
                                        </a>

                                        <a v-else href="#" class="btn btn-xs btn-danger" v-bind:id="'delete-' + kategoriTransaksi.id" v-on:click="gagalHapus(kategoriTransaksi.id, index,kategoriTransaksi.nama_kategori_transaksi)">Delete
                                        </a>
                                    </td>
                                </tr>
                            </tbody>					
                            <tbody class="data-tidak-ada" v-else>
                                <tr ><td colspan="2"  class="text-center">Tidak Ada Data</td></tr>
                            </tbody>
                    </table>	

                 <vue-simple-spinner v-if="loading"></vue-simple-spinner>

                 <div align="right"><pagination :data="kategoriTransaksiData" v-on:pagination-change-page="getResults"></pagination></div>

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
			kategoriTransaksi: [],
			kategoriTransaksiData: {},
			url : window.location.origin+(window.location.pathname).replace("dashboard", "kategori-transaksi"),
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
    			app.kategoriTransaksi = resp.data.data;
    			app.kategoriTransaksiData = resp.data;
    			app.loading = false;
    		})
    		.catch(function (resp) {
    			console.log(resp);
    			app.loading = false;
    			alert("Tidak Dapat Memuat Kategori Transaksi");
    		});
    	},
    	getHasilPencarian(page){
    		var app = this;
    		if (typeof page === 'undefined') {
    			page = 1;
    		}
    		axios.get(app.url+'/pencarian?search='+app.pencarian+'&page='+page)
    		.then(function (resp) {
    			app.kategoriTransaksi = resp.data.data;
    			app.kategoriTransaksiData = resp.data;
    			app.loading = false;
    		})
    		.catch(function (resp) {
    			console.log(resp);
    			alert("Tidak Dapat Memuat Kategori Transaksi");
    		});
    	},
    	alert(pesan) {
    		this.$swal({
    			title: "Berhasil ",
    			text: pesan,
    			icon: "success",
    		});
    	},
        deleteEntry(id, index,name) {
            swal({
                title: "Konfirmasi Hapus",
                text : "Anda Yakin Ingin Menghapus "+name+" ?",
                icon : "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                  var app = this;
                  axios.delete(app.url+'/' + id)
                  .then(function (resp) {
                    app.getResults();
                    swal("Kategori Transaksi Berhasil Dihapus!  ", {
                      icon: "success",
                    });
                  })
                  .catch(function (resp) {
                    app.$router.replace('/kategori-transaksi/');
                    swal("Gagal Menghapus Kategori Transaksi!", {
                      icon: "warning",
                    });
                  });
               }
               this.$router.replace('/kategori-transaksi/');
            });
        },
         gagalHapus(id, index,nama_kategori_transaksi) {
            this.$swal({
                title: "Gagal ",
                text: "Kategori Transaksi '"+nama_kategori_transaksi+"' Sudah Terpakai",
                icon: "warning",
            });
        }
    }
}
</script>