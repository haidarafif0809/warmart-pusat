<template>
<div class="row"> 
	<div class="col-md-12"> 
		<ul class="breadcrumb"> 
			<li><router-link :to="{name: 'indexDashboard'}">Home</router-link></li> 
			<li><router-link :to="{name: 'indexPembelian'}">Pembelian</router-link></li> 
			<li class="active">Tambah Pembelian</li> 
		</ul> 

		<div class="row"><!-- ROW --> 
			<div class="col-md-8"><!-- COL SM 8 --> 

				<div class="card"><!-- CARD --> 

					<div class="card-header card-header-icon" data-background-color="purple"> 
						<i class="material-icons">add_shopping_cart</i> 
					</div> 

					<div class="card-content"> 
						<h4 class="card-title"> Pembelian </h4> 
						<div class="row"> 

							<!--COL MD 8--> 
							<div class="col-md-8"> 
								<form class="form-horizontal" id="form-produk"> 
									<div class="form-group">
										<div class="col-md-4"><br> 
											<selectize-component v-model="inputTbsPembelian.produk" :settings="placeholder_produk" id="produk" ref='produk' name="jumlah_produk"> 
											<option v-for="produks, index in produk" v-bind:value="produks.produk">{{ produks.nama_produk }}</option>
											</selectize-component>
											
											<span v-if="errors.produk" id="produk_error" class="label label-danger">{{ errors.produk[0] }}</span>

								<input class="form-control" type="hidden"  v-model="inputTbsPembelian.jumlah_produk"  name="jumlah_produk" id="jumlah_produk">
								<input class="form-control" type="hidden"  v-model="inputTbsPembelian.harga_produk"  name="harga_produk" id="harga_produk">
								<input class="form-control" type="hidden"  v-model="inputTbsPembelian.id_produk_tbs"  name="id_produk_tbs" id="id_produk_tbs">

									</div>
								</div><!--/COL MD 8--> 
							</form>
						</div> 
					</div>

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
									<th>Harga</th>
									<th>Potongan</th>
									<th>Pajak</th>
									<th>Subtotal</th>
									<th>Hapus</th>

								</tr>
							</thead>
							<tbody v-if="tbs_pembelians.length"  class="data-ada">
								<tr v-for="tbs_pembelian, index in tbs_pembelians" >

									<td>{{ tbs_pembelian.kode_produk }} - {{ tbs_pembelian.nama_produk }}</td>
									<td>
										<a href="#create-pembelian" v-bind:id="'edit-' + tbs_pembelian.id_tbs_pembelian" ><p align='right'>{{ tbs_pembelian.jumlah_produk }}</p>
										</a>
									</td>
									<td>
										<a href="#create-pembelian" v-bind:id="'edit-' + tbs_pembelian.id_tbs_pembelian" ><p align='right'>{{ tbs_pembelian.harga_pemisah }}</p>
										</a>
									</td>
									<td>
										<a href="#create-pembelian" v-bind:id="'edit-' + tbs_pembelian.id_tbs_pembelian" ><p align='right'>{{ Math.round(tbs_pembelian.potongan,2) }} | {{ Math.round(tbs_pembelian.potongan_persen,2) }} %</p>
										</a>
									</td>
									<td>
										<a href="#create-pembelian" v-bind:id="'edit-' + tbs_pembelian.id_tbs_pembelian"><p align='right'>{{ Math.round(tbs_pembelian.tax,2) }} | {{ Math.round(tbs_pembelian.tax_persen, 2) }} %</p>
										</a>
									</td>
									<td><p id="table-subtotal" align="right">{{ tbs_pembelian.subtotal_tbs }}</p></td>
									<td> 
										<a href="#create-pembelian" class="btn btn-xs btn-danger" v-bind:id="'delete-' + tbs_pembelian.id_tbs_pembelian" v-on:click="deleteEntry(tbs_pembelian.id_tbs_pembelian, index,tbs_pembelian.nama_produk)">Delete</a>
									</td>
								</tr>
							</tbody>					
							<tbody class="data-tidak-ada" v-else>
								<tr ><td colspan="7"  class="text-center">Tidak Ada Data</td></tr>
							</tbody>
						</table>	

						<vue-simple-spinner v-if="loading"></vue-simple-spinner>

						<div align="right"><pagination :data="tbsPembelianData" v-on:pagination-change-page="getResults" :limit="4"></pagination></div>

					</div>
					<p style="color: red; font-style: italic;">*Note : Klik Kolom Jumlah, Harga, Potongan & Tax Untuk Mengubah Nilai.</p> 


					</div><!-- / PANEL BODY --> 

				</div><!-- CARD --> 

			</div><!-- COL SM 8 --> 

			<div class="col-md-4"><!-- COL SM 4 --> 
				<div class="card"><!-- CARD --> 
					<div class="card-content"> 
						<div class="row"> 
							<div class="col-md-6"> 
									<h4>Suplier</h4> 
									<selectize-component v-model="inputTbsPembelian.suplier" :settings="placeholder_suplier" id="suplier" name="suplier" ref='suplier'> 
											<option v-for="supliers, index in suplier" v-bind:value="supliers.id">{{ supliers.nama_suplier }}</option>
									</selectize-component>
							</div> 

								<div class="col-md-6"> 
									<h4>Cara Bayar</h4> 
									<div v-if="tbs_pembelians.kas_default == 0">
									<selectize-component v-model="inputTbsPembelian.cara_bayar" :settings="placeholder_cara_bayar" id="cara_bayar" name="cara_bayar" ref='cara_bayar'> 
									<option v-for="cara_bayars, index in cara_bayar" v-bind:value="cara_bayars.id">{{ cara_bayars.nama_kas }}</option>
									</selectize-component>
									<span class="label label-danger"><a href="" target="blank" >Tambah Kas Disini</a></span> 
									</div>
									<div v-else>
									<selectize-component v-model="inputTbsPembelian.cara_bayar" :settings="placeholder_cara_bayar" id="cara_bayar" name="cara_bayar" ref='cara_bayar'> 
									<option v-for="cara_bayars, index in cara_bayar" v-bind:value="cara_bayars.id">{{ cara_bayars.nama_kas }}</option>
									</selectize-component>
									</div>

								</div> 
							</div> 
						

						<!--- TOMBOL SELESAI --> 
						<button type="button" class="btn btn-primary" id="btnSelesai" data-toggle="modal"><i class="material-icons">send</i> Selesai (F2)</button> 

						<button type="submit" class="btn btn-danger" id="btnBatal"  ><i class="material-icons">cancel</i> Batal (F3)</button> 
					</div> 
				</div>             
			</div><!-- COL SM 4 --> 

		</div><!-- ROW --> 
	</div> 
</div> 
</template>

<script>
export default {
	data: function () {
		return {
			errors: [],
			produk: [],
			suplier: [],
			cara_bayar: [],
			tbs_pembelians: [],
			tbsPembelianData : {},
			url : window.location.origin+(window.location.pathname).replace("dashboard", "pembelian"),
			url_produk : window.location.origin+(window.location.pathname).replace("dashboard", "produk"),
			url_kas : window.location.origin+(window.location.pathname).replace("dashboard", "kas-masuk"),
			inputTbsPembelian: {
				produk : '',
				jumlah_produk : '',
				harga_produk : '',
				id_tbs : '',
				keterangan : ''
			}, 
			placeholder_produk: {
				placeholder: '--PILIH PRODUK--'
			},
			placeholder_suplier: {
				placeholder: '--PILIH SUPLIER--'
			},
			placeholder_cara_bayar: {
				placeholder: '--PILIH CARA BAYAR--'
			},
			pencarian: '',
			loading: true,
			seen : false
		}
	},
	mounted() {
		var app = this;
		app.dataProduk();
		app.dataSuplier();
		app.dataCaraBayar();
		app.getResults();

	},
	watch: {
        // whenever question changes, this function will run
        pencarian: function (newQuestion) {
        	this.getHasilPencarian();
        	this.loading = true;  
        },
        'inputTbsPembelian.produk': function (newQuestion) {
        	this.pilihProduk();  
        }

    },
    methods: {
    	getResults(page) {
    		var app = this;	
    		if (typeof page === 'undefined') {
    			page = 1;
    		}
    		axios.get(app.url+'/view-tbs-pembelian?page='+page)
    		.then(function (resp) {
    			app.tbs_pembelians = resp.data.data;
    			app.tbsPembelianData = resp.data;
    			app.loading = false;
    			app.seen = true;
    		})
    		.catch(function (resp) {
    			console.log(resp);
    			app.loading = false;
    			app.seen = true;
    			alert("Tidak Dapat Memuat Pembelian");
    		});
    	},
    	getHasilPencarian(page){
    		var app = this;
    		if (typeof page === 'undefined') {
    			page = 1;
    		}
    		axios.get(app.url+'/pencarian-tbs-pembelian?search='+app.pencarian+'&page='+page)
    		.then(function (resp) {
    			app.tbs_pembelians = resp.data.data;
    			app.tbsPembelianData = resp.data;
    			app.loading = false;
    			app.seen = true;
    		})
    		.catch(function (resp) {
    			console.log(resp);
    			alert("Tidak Dapat Memuat Pembelian");
    		});
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
    	dataSuplier() {
    		var app = this;
    		axios.get(app.url+'/pilih-suplier').then(function (resp) {
    			app.suplier = resp.data;
    		})
    		.catch(function (resp) {
    			alert("Tidak Bisa Memuat Suplier");
    		});
    	},
    	dataCaraBayar() {
    		var app = this;
    		axios.get(app.url_kas+'/pilih-kas').then(function (resp) {
    			app.cara_bayar = resp.data;
    		})
    		.catch(function (resp) {
    			alert("Tidak Bisa Memuat Kas");
    		});
    	},	
    	alert(pesan) {
    		this.$swal({
    			title: "Berhasil ",
    			text: pesan,
    			icon: "success",
    		});
    	},
    	deleteEntry(id, index,nama_produk) {

    		var app = this;
    		app.$swal({
    			text: "Anda Yakin Ingin Menghapus Produk "+nama_produk+ " ?",
    			buttons: true,
    			dangerMode: true,
    		})
    		.then((willDelete) => {
    			if (willDelete) {


    			} else {

    				app.$swal.close();

    			}
    		});

    	},
    	pilihProduk() {
    		if (this.inputTbsPembelian.produk == '') {
    			this.$swal({
    				text: "Silakan Pilih Produk Telebih dahulu!",
    			});
    		}
    		else if(this.inputTbsPembelian.jumlah_produk == ''){

    			var app = this;
    			var produk = app.inputTbsPembelian.produk.split("|");
    			var id_produk = produk[0]; 
    			var nama_produk = produk[1];
				var harga_produk = produk[2]; 
				var jumlah = $("#jumlah_produk").val(); 

    			this.isiJumlahProduk(id_produk,nama_produk,harga_produk);
    		}
    		else if (this.inputTbsPembelian.jumlah_produk == '' && this.inputTbsPembelian.produk == ''){

    		}
    	},
    	isiJumlahProduk(id_produk,nama_produk,harga_produk){
    		var app = this;		
				swal({
				  title: titleCase(nama_produk),
				  html:
				    '<div class="col-sm-6  col-xs-6"><lable>Jumlah</lable><br><input type="number" id="swal-input1" class="swal2-input" autofocus></div>' +
				    '<div class="col-sm-6  col-xs-6"><lable>Harga</lable><br><input type="number" id="swal-input2" class="swal2-input" value="'+harga_produk+'"></div>',
				  	allowEnterKey : false,
					showCloseButton: true, 
					showCancelButton: true,                        
					focusConfirm: false, 
					confirmButtonText:'<i class="fa fa-thumbs-o-up"></i> Submit', 
					confirmButtonAriaLabel: 'Thumbs up, great!', 
					cancelButtonText:'<i class="fa fa-thumbs-o-down"> Batal', 
					closeOnConfirm: false, 
					cancelButtonAriaLabel: 'Thumbs down', 
					preConfirm: function () { 
						return new Promise(function (resolve) { 
							resolve([ 
								$('#swal-input1').val(), 
								$('#swal-input2').val() 
								]) 
						}) 
					}
				}).then(function (result) { 

					if (result[0] == '' || result[0] == 0) { 

						swal('Oops...', 'Jumlah Produk Tidak Boleh 0 atau Kosong !', 'error'); 
						return false; 
					}else if (result[1] == '' || result[1] == 0) { 

						swal('Oops...', 'Harga Produk Tidak Boleh 0 atau Kosong !', 'error'); 
						return false; 
					}
					else if (result[1] != harga_produk) { 
								swal({ 
									text: "Anda Yakin Ingin Merubah Harga Beli Produk <b>"+titleCase(nama_produk)+ "</b>?", 
									type: 'warning', 
									showCancelButton: true, 
									cancelButtonText: 'Batal',
									confirmButtonText:
									'<i class="fa fa-thumbs-up"></i> Ya',
									confirmButtonAriaLabel: 'Thumbs up, great!',
									cancelButtonText:
									'<i class="fa fa-thumbs-down"></i>',
									cancelButtonAriaLabel: 'Thumbs down'
								}).then(function () { 
									$("#id_produk_tbs").val(id_produk); 
									$("#jumlah_produk").val(result[0]); 
									$("#harga_produk").val(result[1]);
									

								}) 
							}else{ 
								$("#id_produk_tbs").val(id_produk); 
								$("#jumlah_produk").val(result[0]); 
								$("#harga_produk").val(harga_produk); 

							axios.get(app.url+'/cek-tbs-pembelian?id='+id_produk)
							.then(function (resp) {
								    	if (resp.data > 0) {
								        		swal({
								                title: "Peringatan",
								                text:"Produk "+titleCase(nama_produk)+" Sudah Ada, Silakan Pilih Produk Lain !",
								               });
								        }
								        else{

    											app.loading = true;
						                        axios.get(app.url+'/proses-tambah-tbs-pembelian?id_produk_tbs='+id_produk+'&jumlah_produk='+result[0]+'&harga_produk='+harga_produk)
						                        .then(function (resp) {
						                        app.alert("Menambahkan Produk "+titleCase(nama_produk));
						                        app.loading = false;
						                        app.getResults();
						                        app.$router.replace('/create-pembelian/');
						                        })
						                        .catch(function (resp) {
						                        app.loading = false;
						                        alert("Tbs Pembelian tidak bisa ditambahkan");
						                        });
								        }
							    });
							} 
						});
		},
    	submitProdukPembelian(value){

    	},
    	alertTbs(pesan) {
    		this.$swal({
    			text: pesan,
    			icon: "warning",
    		});
    	}
    }
}
</script>