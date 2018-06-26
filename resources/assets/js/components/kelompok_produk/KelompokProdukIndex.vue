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
				<li class="active">Kelompok Produk</li>
			</ul>

			
			<div class="card">
				<div class="card-header card-header-icon" data-background-color="purple">
					<i class="material-icons">people</i>
				</div>
				<div class="card-content">
					<h4 class="card-title"> Kelompok Produk</h4>

					<div class="toolbar">
						<p> <router-link :to="{name: 'createKelompokProduk'}" class="btn btn-primary" v-if="otoritas.tambah_kelompok_produk == 1">Tambah Kelompok Produk</router-link></p>
					</div>

					<div class=" table-responsive ">
                        <div class="pencarian">
                            <input type="text" class="form-control pencarian" autocomplete="off" name="pencarian" v-model="pencarian" placeholder="Pencarian" >
                        </div>
                        <table class="table table-striped table-hover ">
                            <thead class="text-primary">
                                <tr>
                                    <th>Kelompok Produk</th>
                                    <th v-if="otoritas.edit_kelompok_produk == 1">Edit</th>
                                    <th v-if="otoritas.hapus_kelompok_produk == 1">Delete</th>
                                </tr>
                            </thead>
                            <tbody v-if="kelompok_produk.length"  class="data-ada">
                                <tr v-for="kelompok_produk, index in kelompok_produk" >
                                    <td>{{ kelompok_produk.nama_kategori_barang }}</td>
                                    <td v-if="otoritas.edit_kelompok_produk == 1">
                                        <router-link :to="{name: 'editKelompokProduk', params: {id: kelompok_produk.id}}" class="btn btn-xs btn-default" v-bind:id="'edit-' + kelompok_produk.id"> Edit </router-link> 
                                    </td>
                                    <td> 
                                        <a v-if="kelompok_produk.status_kelompok_produk == 0 && otoritas.hapus_kelompok_produk == 1" href="#kelompok-produk" class="btn btn-xs btn-danger" v-bind:id="'delete-' + kelompok_produk.id" v-on:click="deleteEntry(kelompok_produk.id, index,kelompok_produk.nama_kategori_barang)">Delete</a>
                                        <a  v-else-if="otoritas.hapus_kelompok_produk == 1 && kelompok_produk.status_kelompok_produk == 1" href="#kelompok-produk" class="btn btn-xs btn-danger" v-bind:id="'delete-' + kelompok_produk.id" v-on:click="KelompokProdukTerpakai(kelompok_produk.id, index,kelompok_produk.nama_kategori_barang)">Delete</a>
                                    </td>
                                </tr>
                            </tbody>					
                            <tbody class="data-tidak-ada" v-else>
                                <tr>
                                    <td colspan="7"  class="text-center">Tidak Ada Data</td>
                                </tr>
                            </tbody>
                        </table>	

                        <vue-simple-spinner v-if="loading"></vue-simple-spinner>

                        <div align="right"><pagination :data="kelompokProdukData" v-on:pagination-change-page="getResults" :limit="4"></pagination></div>

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
			kelompok_produk: [],
            kelompokProdukData: {},
            otoritas: {},
            url : window.location.origin + (window.location.pathname).replace("dashboard", "kelompok-produk"),
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
    		axios.get(app.url + '/view?page=' + page)
    		.then((resp) => {
    			app.kelompok_produk = resp.data.data;
                app.kelompokProdukData = resp.data;
                app.otoritas = resp.data.otoritas.original;
                app.loading = false;
            })
    		.catch((resp) => {
    			console.log('catch getResults: ', resp);
    			app.loading = false;

    			swal({
                    title: 'Gagal!',
                    type: 'warning',
                    html: 'Tidak Dapat Memuat Kelompok Produk.',
                });
    		});
    	},
    	getHasilPencarian(page){
    		var app = this;
    		if (typeof page === 'undefined') {
    			page = 1;
    		}
    		axios.get(app.url + '/pencarian?search=' + app.pencarian + '&page=' + page)
    		.then((resp) => {
    			app.kelompok_produk = resp.data.data;
    			app.kelompokProdukData = resp.data;
                app.otoritas = resp.data.otoritas.original;
                app.loading = false;
            })
    		.catch((resp) => {
    			console.log('catch getHasilPencarian: ', resp);
                swal({
                    title: 'Gagal!',
                    type: 'warning',
                    html: 'Tidak Dapat Memuat Pencarian Kelompok Produk.',
                });
    		});
    	},
        deleteEntry(id, index, nama_kategori_barang) {
            let app = this;
            swal({
                title: 'Hapus?',
                type: 'warning',
                showCancelButton: true,
                reverseButtons: true,
                text: "Yakin Ingin Menghapus Kelompok Produk " + nama_kategori_barang + " ?",
            })
            .then((willDelete) => {
                if (willDelete) {
                    axios.delete(app.url + '/' + id)
                    .then((resp) => {
                        app.getResults();
                        swal({
                            title: 'Berhasil!',
                            text: `Berhasil menghapus Kelompok Produk ${nama_kategori_barang}`,
                            type: 'success',
                            timer: 1800,
                            showConfirmButton: false
                        });
                    })
                    .catch((resp) => {
                        console.log('catch delete Kelompok Produk: ', resp);
                        swal({
                            title: 'Gagal!',
                            type: 'warning',
                            html: 'Tidak Dapat Menghapus Kelompok Produk.',
                        });
                    });
                } else {
                    swal.close();
                }
            });
        },
        KelompokProdukTerpakai(id, index,nama_kategori_barang) {
            var app = this;                  
            swal({
                title: 'Gagal!',
                type: 'warning',
                html: 'Kelompok Produk ' + nama_kategori_barang + ' Sudah Terpakai',
            });
        }
    }
}
</script>