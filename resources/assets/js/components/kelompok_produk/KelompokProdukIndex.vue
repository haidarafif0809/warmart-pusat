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
						<p> <router-link :to="{name: 'createKelompokProduk'}" class="btn btn-primary">Tambah Kelompok Produk</router-link></p>
					</div>

					<div class=" table-responsive ">
						<div  align="right">
							pencarian
							<input type="text" name="pencarian" v-model="pencarian" placeholder="Kolom Pencarian" >
						</div>

						<table class="table table-striped table-hover ">
							<thead class="text-primary">
								<tr>

									<th>Kelompok Produk</th>
									<th>Icon</th>
									<th>Edit</th>
                                    <th>Delete</th>

                                </tr>
                            </thead>
                            <tbody v-if="kelompok_produk.length"  class="data-ada">
                                <tr v-for="kelompok_produk, index in kelompok_produk" >

                                 <td>{{ kelompok_produk.nama_kategori_barang }}</td>
                                 <td><i class="material-icons">{{ kelompok_produk.kategori_icon }}</i></td>
                                 <td><router-link :to="{name: 'editKelompokProduk', params: {id: kelompok_produk.id}}" class="btn btn-xs btn-default" v-bind:id="'edit-' + kelompok_produk.id" >
                                 Edit </router-link> </td>

                                 <td> 
                                    <a  v-if="kelompok_produk.status_kelompok_produk == 0" href="#" class="btn btn-xs btn-danger" v-bind:id="'delete-' + kelompok_produk.id" v-on:click="deleteEntry(kelompok_produk.id, index,kelompok_produk.nama_kategori_barang)">Delete</a>
                                    <a  v-else href="#" class="btn btn-xs btn-danger" v-bind:id="'delete-' + kelompok_produk.id" v-on:click="KelompokProdukTerpakai(kelompok_produk.id, index,kelompok_produk.nama_kategori_barang)">Delete</a>
                                </td>
                            </tr>
                        </tbody>					
                        <tbody class="data-tidak-ada" v-else>
                         <tr ><td colspan="7"  class="text-center">Tidak Ada Data</td></tr>
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
			url : window.location.origin+(window.location.pathname).replace("dashboard", "kelompok-produk"),
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
    			app.kelompok_produk = resp.data.data;
    			app.kelompokProdukData = resp.data;
    			app.loading = false;
    		})
    		.catch(function (resp) {
    			console.log(resp);
    			app.loading = false;
    			alert("Tidak Dapat Memuat Kelompok Produk");
    		});
    	},
    	getHasilPencarian(page){
    		var app = this;
    		if (typeof page === 'undefined') {
    			page = 1;
    		}
    		axios.get(app.url+'/pencarian?search='+app.pencarian+'&page='+page)
    		.then(function (resp) {
    			app.kelompok_produk = resp.data.data;
    			app.kelompokProdukData = resp.data;
    			app.loading = false;
    		})
    		.catch(function (resp) {
    			console.log(resp);
    			alert("Tidak Dapat Memuat Kelompok Produk");
    		});
    	},
    	alert(pesan) {
    		this.$swal({
    			title: "Berhasil ",
    			text: pesan,
    			icon: "success",
    		});
    	},
        alertTidakBisaHapus(pesan) {
            this.$swal({
                title: "Perhatian!! ",
                text: pesan,
                icon: "warning",
            });
        },
        deleteEntry(id, index,nama_kategori_barang) {
          if (confirm("Yakin Ingin Menghapus Kelompok Produk "+nama_kategori_barang+" ?")) {
             var app = this;
             axios.delete(app.url+'/' + id)
             .then(function (resp) {
                app.getResults();
                app.alert("Menghapus Kelompok Produk "+nama_kategori_barang)
            })
             .catch(function (resp) {
                alert("Tidak dapat Menghapus Kelompok Produk");
            });
         }
     },
     KelompokProdukTerpakai(id, index,nama_kategori_barang) {
        var app = this;                  
        app.alertTidakBisaHapus("Kelompok Produk "+nama_kategori_barang+" Sudah Terpakai")

    }
}
}
</script>