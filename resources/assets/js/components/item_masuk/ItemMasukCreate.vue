<template>



	<div class="row">
		<div class="col-md-12">
			<ul class="breadcrumb">

				<li><router-link :to="{name: 'indexDashboard'}">Home</router-link></li>
				<li style="color: purple">Persediaan</li>
				<li><router-link :to="{name: 'indexItemMasuk'}">Item Masuk</router-link></li>
				<li class="active">Tambah Item Masuk</li>

			</ul>

			<div class="card">
				<div class="card-header card-header-icon" data-background-color="purple">
					<i class="material-icons">vertical_align_bottom</i>
				</div>
				<div class="card-content">
					<h4 class="card-title"> Item Masuk </h4>
					<div class="row">

						<div class="col-md-8">
							<form v-on:submit.prevent="formSubmitProduk()" class="form-horizontal"> 

								<div class="form-group">
									<div class="col-md-4"><br>
										<selectize-component v-model="inputTbsItemMasuk.produk" :settings="placeholder_produk" id="produk" ref='produk'> 
											<option v-for="produks, index in produk" v-bind:value="produks.produk">{{ produks.nama_produk }}</option>
										</selectize-component>
										<span v-if="errors.produk" id="produk_error" class="label label-danger">{{ errors.produk[0] }}</span>
									</div> 
								</div>

								<input class="form-control" type="hidden"  v-model="inputTbsItemMasuk.jumlah_produk"  name="jumlah_produk" id="jumlah_produk">


							</form>
						</div>
						<!-- / col md 7 -->
						<div class="col-md-1"></div>
						<div class="col-md-3">
							<!-- TOMBOL BATAL -->

							<form v-on:submit.prevent="formBatalItemMasuk()" class="form-horizontal"> 						       		
								<!--- TOMBOL SELESAI -->
								<button type="button" class="btn btn-primary" id="btnSelesai" data-toggle="modal" data-target="#modal_selesai"><i class="material-icons">send</i> Selesai (F8)</button>

								<button type="submit" class="btn btn-danger" id="btnBatal"><i class="material-icons">cancel</i> Batal (F10)</button>

							</form>
						</div>

						<!--TOMBOL SELESAI & BATAL -->
						<div class="col-md-4">
							<div class="form-group col-md-3">


							</div>
							<div class="form-group col-md-2">												       			   

							</div>										
						</div>

					</div>

					<!--TABEL TBS ITEM 	MASUK -->

					<div class=" table-responsive ">
						<div  align="right">
							pencarian
							<input type="text" name="pencarian" v-model="pencarian" placeholder="Kolom Pencarian" >
						</div>

						<table class="table table-striped table-hover" v-if="seen">
							<thead class="text-primary">
								<tr>

									<th>Produk</th>
									<th>Jumlah</th>
									<th>Hapus</th>

								</tr>
							</thead>
							<tbody v-if="tbs_item_masuk.length"  class="data-ada">
								<tr v-for="tbs_item_masuk, index in tbs_item_masuk" >

									<td>{{ tbs_item_masuk.kode_produk }} - {{ tbs_item_masuk.nama_produk }}</td>
									<td>
										<a href="#" v-bind:id="'edit-' + tbs_item_masuk.id" v-on:click="editEntry(tbs_item_masuk.id, index,tbs_item_masuk.nama_produk)">{{ tbs_item_masuk.jumlah_produk }}
										</a>
									</td>
									<td> 
										<a href="#" class="btn btn-xs btn-danger" v-bind:id="'delete-' + tbs_item_masuk.id" v-on:click="deleteEntry(tbs_item_masuk.id, index,tbs_item_masuk.nama_produk)">Delete</a>
									</td>
								</tr>
							</tbody>					
							<tbody class="data-tidak-ada" v-else>
								<tr ><td colspan="7"  class="text-center">Tidak Ada Data</td></tr>
							</tbody>
						</table>	

						<vue-simple-spinner v-if="loading"></vue-simple-spinner>

						<div align="right"><pagination :data="tbsItemMasukData" v-on:pagination-change-page="getResults" :limit="4"></pagination></div>

					</div>
					<p style="color: red; font-style: italic;">*Note : Klik Kolom Jumlah Untuk Mengubah Jumlah Produk.</p> 



				</div><!-- / PANEL BODY -->

			</div>
		</div>
	</div>

</template>


<script>
export default {
	data: function () {
		return {
			errors: [],
			produk: [],
			tbs_item_masuk: [],
			tbsItemMasukData : {},
			url : window.location.origin+(window.location.pathname).replace("dashboard", "item-masuk"),
			url_produk : window.location.origin+(window.location.pathname).replace("dashboard", "produk"),
			inputTbsItemMasuk: {
				produk : '',
				jumlah_produk : ''
			}, 
			placeholder_produk: {
				placeholder: '--PILIH PRODUK--'
			},
			pencarian: '',
			loading: true,
			seen : false
		}
	},
	mounted() {
		var app = this;
		app.dataProduk();
		app.getResults();

	},
	watch: {
        // whenever question changes, this function will run
        pencarian: function (newQuestion) {
        	this.getHasilPencarian();
        	this.loading = true;  
        },
        'inputTbsItemMasuk.produk': function (newQuestion) {
        	this.pilihProduk();  
        },

    },
    methods: {
    	getResults(page) {
    		var app = this;	
    		if (typeof page === 'undefined') {
    			page = 1;
    		}
    		axios.get(app.url+'/view-tbs-item-masuk?page='+page)
    		.then(function (resp) {
    			app.tbs_item_masuk = resp.data.data;
    			app.tbsItemMasukData = resp.data;
    			app.loading = false;
    			app.seen = true;
    		})
    		.catch(function (resp) {
    			console.log(resp);
    			app.loading = false;
    			app.seen = true;
    			alert("Tidak Dapat Memuat Item Masuk");
    		});
    	},
    	getHasilPencarian(page){
    		var app = this;
    		if (typeof page === 'undefined') {
    			page = 1;
    		}
    		axios.get(app.url+'/pencarian-tbs-item-masuk?search='+app.pencarian+'&page='+page)
    		.then(function (resp) {
    			app.tbs_item_masuk = resp.data.data;
    			app.tbsItemMasukData = resp.data;
    			app.loading = false;
    			app.seen = true;
    		})
    		.catch(function (resp) {
    			console.log(resp);
    			alert("Tidak Dapat Memuat Item Masuk");
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
    		if (confirm("Yakin Ingin Menghapus Item Masuk "+no_faktur+" ?")) {
    			var app = this;
    			axios.delete(app.url+'/' + id)
    			.then(function (resp) {
    				app.getResults();
    				app.alert("Menghapus Item Masuk "+no_faktur)
    			})
    			.catch(function (resp) {
    				alert("Tidak dapat Menghapus Item Masuk");
    			});
    		}
    	},
    	dataProduk() {
    		var app = this;
    		axios.get(app.url_produk+'/pilih-produk').then(function (resp) {
    			app.produk = resp.data;
    		})
    		.catch(function (resp) {
    			alert("Tidak Bisa Memuat Produk");
    		});
    	},
    	pilihProduk() {
    		if (this.inputTbsItemMasuk.produk == '') {
    			this.$swal({
    				text: "Silakan Pilih Produk Telebih dahulu!",
    			});
    		}else{

    			this.isiJumlahProduk();
    		}
    	},
    	isiJumlahProduk(){
    		var app = this;
    		var produk = app.inputTbsItemMasuk.produk.split("|");
    		var nama_produk = produk[1];

    		app.$swal({
    			title: nama_produk,
    			content: {
    				element: "input",
    				attributes: {
    					placeholder: "Jumlah Produk",
    					type: "number",
    				},
    			},
    			buttons: {
    				cancel: true,
    				confirm: "Submit"    				
    			},

    		}).then((value) => {
    			if (!value) throw null;
    			this.submitProdukItemMasuk(value);
    		});
    	},
    	submitProdukItemMasuk(value){

    		if (value == 0) {

    			this.$swal({
    				text: "Jumlah Produk Tidak Boleh Nol!",
    			});

    		}else{

    			var app = this;
    			var produk = app.inputTbsItemMasuk.produk.split("|");
    			var nama_produk = produk[1];

    			app.inputTbsItemMasuk.jumlah_produk = value;
    			var newinputTbsItemMasuk = app.inputTbsItemMasuk;

    			axios.post(app.url+'/proses-tambah-tbs-item-masuk', newinputTbsItemMasuk)
    			.then(function (resp) {
    				app.getResults();
    				app.alert("Menambahkan Produk "+nama_produk);
    				app.inputTbsItemMasuk.jumlah_produk = ''
    			})
    			.catch(function (resp) {
    				alert("Tidak dapat Menambahkan Produk");
    			});
    		}
    	},
    	editEntry(id, index,nama_produk) {
    		
    		var app = this;
    		axios.delete(app.url+'/' + id)
    		.then(function (resp) {
    			app.getResults();
    			app.alert("Menghapus Item Masuk "+no_faktur)
    		})
    		.catch(function (resp) {
    			alert("Tidak dapat Menghapus Item Masuk");
    		});
    		
    	},
    }
}
</script>