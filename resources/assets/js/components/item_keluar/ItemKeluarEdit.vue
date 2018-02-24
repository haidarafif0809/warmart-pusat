<template>



	<div class="row">
		<div class="col-md-12">
			<ul class="breadcrumb">

				<li><router-link :to="{name: 'indexDashboard'}">Home</router-link></li>
				<li style="color: purple">Persediaan</li>
				<li><router-link :to="{name: 'indexItemKeluar'}">Item Keluar</router-link></li>
				<li class="active">Edit Item Keluar</li>

			</ul>

			<div class="card">
				<div class="card-header card-header-icon" data-background-color="purple">
					<i class="material-icons">vertical_align_top</i>
				</div>
				<div class="card-content">
					<h4 class="card-title">Edit Item Keluar {{ inputTbsItemKeluar.no_faktur }}</h4>
					<div class="row">

						<div class="col-md-8">
							<form class="form-horizontal"> 

								<div class="form-group">
									<div class="col-md-4"><br>
										<selectize-component v-model="inputTbsItemKeluar.produk" :settings="placeholder_produk" id="produk" ref='produk'> 
											<option v-for="produks, index in produk" v-bind:value="produks.produk">{{ produks.nama_produk }}</option>
										</selectize-component>
										<span v-if="errors.produk" id="produk_error" class="label label-danger">{{ errors.produk[0] }}</span>
									</div> 
								</div>

								<input class="form-control" type="hidden"  v-model="inputTbsItemKeluar.jumlah_produk"  name="jumlah_produk" id="jumlah_produk">
								<input class="form-control" type="hidden"  v-model="inputTbsItemKeluar.id_tbs"  name="id_tbs" id="id_tbs">


							</form>
						</div>
						<!-- / col md 7 -->
						<div class="col-md-1"></div>
						<div class="col-md-3">
							<!-- TOMBOL BATAL -->

							<!--- TOMBOL SELESAI -->
							<button type="button" class="btn btn-primary" id="btnSelesai" v-on:click="selesaiItemKeluar()"><i class="material-icons">send</i> Selesai</button>

							<button type="submit" class="btn btn-danger" id="btnBatal" v-on:click="batalItemKeluar()"><i class="material-icons">cancel</i> Batal </button>
							<textarea class="form-control" v-model="inputTbsItemKeluar.keterangan" style="display: none;"></textarea>

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
							<tbody v-if="tbs_item_keluar.length"  class="data-ada">
								<tr v-for="tbs_item_keluar, index in tbs_item_keluar" >

									<td>{{ tbs_item_keluar.kode_produk }} - {{ tbs_item_keluar.nama_produk }}</td>
									<td>
										<a v-bind:href="'#edit-item-keluar/'+tbs_item_keluar.id_item_keluar" v-bind:id="'edit-' + tbs_item_keluar.id_edit_tbs_item_keluar" v-on:click="editEntry(tbs_item_keluar.id_edit_tbs_item_keluar, index,tbs_item_keluar.nama_produk)">{{ tbs_item_keluar.jumlah_produk | pemisahTitik }}
										</a>
									</td>
									<td> 
										<a v-bind:href="'#edit-item-keluar/'+tbs_item_keluar.id_item_keluar" class="btn btn-xs btn-danger" v-bind:id="'delete-' + tbs_item_keluar.id_edit_tbs_item_keluar" v-on:click="deleteEntry(tbs_item_keluar.id_edit_tbs_item_keluar, index,tbs_item_keluar.nama_produk)">Delete</a>
									</td>
								</tr>
							</tbody>					
							<tbody class="data-tidak-ada" v-else>
								<tr ><td colspan="7"  class="text-center">Tidak Ada Data</td></tr>
							</tbody>
						</table>	

						<vue-simple-spinner v-if="loading"></vue-simple-spinner>

						<div align="right"><pagination :data="tbsItemKeluarData" v-on:pagination-change-page="getResults" :limit="4"></pagination></div>

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
			tbs_item_keluar: [],
			tbsItemKeluarData : {},
			url : window.location.origin+(window.location.pathname).replace("dashboard", "item-keluar"),
			url_produk : window.location.origin+(window.location.pathname).replace("dashboard", "produk"),
			inputTbsItemKeluar: {
				produk : '',
				jumlah_produk : '',
				id_tbs : '',
				keterangan : '',
        no_faktur : ''
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
    app.getFakturItemKeluar();
    app.getResults();

  },
   watch: {
        // whenever question changes, this function will run
        pencarian: function (newQuestion) {
        	this.getHasilPencarian();
        	this.loading = true;  
        },
        'inputTbsItemKeluar.produk': function (newQuestion) {
        	this.pilihProduk();  
        },

      },
    filters: {
        pemisahTitik: function (value) {      
            var angka = [value];
            var numberFormat = new Intl.NumberFormat('es-ES');
            var formatted = angka.map(numberFormat.format);
            return formatted.join('; ');
        }
      },
      methods: {
       getResults(page) {
        var app = this;	
        var id = app.$route.params.id;
        if (typeof page === 'undefined') {
         page = 1;
       }
       axios.get(app.url+'/view-edit-tbs-item-keluar/'+id+'?page='+page)
       .then(function (resp) {

         app.tbs_item_keluar = resp.data.data;
         app.tbsItemKeluarData = resp.data;
         app.loading = false;
         app.seen = true;

       })
       .catch(function (resp) {

         console.log(resp);
         app.loading = false;
         app.seen = true;
         alert("Tidak Dapat Memuat Item Keluar");

       });
     },
     getHasilPencarian(page){
      var app = this;
      var id = app.$route.params.id;
      if (typeof page === 'undefined') {
       page = 1;
     }
     axios.get(app.url+'/pencarian-edit-tbs-item-keluar/'+id+'?search='+app.pencarian+'&page='+page)
     .then(function (resp) {

       app.tbs_item_keluar = resp.data.data;
       app.tbsItemKeluarData = resp.data;
       app.loading = false;
       app.seen = true;
     })
     .catch(function (resp) {

       console.log(resp);
       alert("Tidak Dapat Memuat Item Keluar");

     });

   }, 
   getFakturItemKeluar(){
    var app = this;
    var id = app.$route.params.id;
    axios.get(app.url+'/ambil-faktur-item-keluar/'+id).then(function (resp) {

      app.inputTbsItemKeluar.no_faktur = resp.data.no_faktur; 
      app.inputTbsItemKeluar.keterangan = resp.data.keterangan;        

    })
    .catch(function (resp) {

     app.loading = false;
     app.seen = true;
     alert("Tidak Bisa Memuat Item Keluar");

   });

  },   
  dataProduk() {
    var app = this;
    axios.get(app.url_produk+'/pilih-produk').then(function (resp) {

     app.produk = resp.data;

   })
    .catch(function (resp) {

     app.loading = false;
     app.seen = true;
     alert("Tidak Bisa Memuat Produk");

   });
  },	
  pilihProduk() {
    if (this.inputTbsItemKeluar.produk == '') {
      this.$swal({
        text: "Silakan Pilih Produk Telebih dahulu!",
      });
    }else{

      var app = this;
      var produk = app.inputTbsItemKeluar.produk.split("|");
      var nama_produk = produk[1];
      this.isiJumlahProduk(nama_produk);
    }
  },
  isiJumlahProduk(nama_produk){
    var app = this;
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
      }


    }).then((value) => {
      if (!value) throw null;
      this.submitProdukItemKeluar(value);
    });
  },
  submitProdukItemKeluar(value){

    if (value == 0) {

      this.$swal({
        text: "Jumlah Produk Tidak Boleh Nol!",
      });

    }else{

      var app = this;
      var produk = app.inputTbsItemKeluar.produk.split("|");
      var nama_produk = produk[1];

      app.inputTbsItemKeluar.jumlah_produk = value;
      var newinputTbsItemKeluar = app.inputTbsItemKeluar;
      app.loading = true;
      axios.post(app.url+'/proses-tambah-edit-tbs-item-keluar', newinputTbsItemKeluar)
      .then(function (resp) {

        if (resp.data == 0) {

          app.alertTbs("Produk "+nama_produk+" Sudah Ada, Silakan Pilih Produk Lain!");
          app.loading = false;

        }else{

          app.getResults();
          app.alert("Menambahkan Produk "+nama_produk);
          app.loading = false;
          app.inputTbsItemKeluar.jumlah_produk = ''

        }

      })
      .catch(function (resp) {                    
        app.loading = false;
        alert("Tidak dapat Menambahkan Produk");
      });
    }
  },
  editEntry(id, index,nama_produk) {    
    var app = this;

    app.$swal({
      title: nama_produk,
      content: {
        element: "input",
        attributes: {
          placeholder: "Edit Jumlah Produk",
          type: "number",
        },
      },
      buttons: {
        cancel: true,
        confirm: "Submit"                   
      },

    }).then((value) => {
      if (!value) throw null;
      this.editJumlahProdukItemKeluar(value,id,nama_produk);
    });

  },
  editJumlahProdukItemKeluar(value,id,nama_produk){
    if (value == 0) {

      this.$swal({
        text: "Jumlah Produk Tidak Boleh Nol!",
      });

    }else{

      var app = this;

      app.inputTbsItemKeluar.id_tbs = id;
      app.inputTbsItemKeluar.jumlah_produk = value;
      var newinputTbsItemKeluar = app.inputTbsItemKeluar;
      app.loading = true;
      axios.post(app.url+'/edit-jumlah-edit-tbs-item-keluar', newinputTbsItemKeluar)
      .then(function (resp) {

        app.getResults();
        app.alert("Mengubah Jumlah Produk "+nama_produk);
        app.loading = false;
        app.inputTbsItemKeluar.jumlah_produk = ''
        app.inputTbsItemKeluar.id_tbs = ''

      })
      .catch(function (resp) { 

        app.loading = false;
        alert("Tidak dapat Mengubah Jumlah Produk");

      });
    }
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

        this.prosesDelete(id,nama_produk);

      } else {

        app.$swal.close();

      }
    });

  },
  prosesDelete(id,nama_produk){

    var app = this;
    app.loading = true;
    axios.delete(app.url+'/proses-hapus-edit-tbs-item-keluar/'+id)
    .then(function (resp) {
      app.getResults();
      app.alert("Menghapus Produk "+nama_produk);
      app.loading = false;
      app.inputTbsItemKeluar.id_tbs = ''
    })
    .catch(function (resp) {

      app.loading = false;
      alert("Tidak dapat Menghapus Produk "+nama_produk);
    });
  },
  batalItemKeluar(){

    var app = this;
    app.$swal({
      text: "Anda Yakin Ingin Membatalkan Transaksi Item Keluar Ini ?",
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {

        app.loading = true;
        var newinputTbsItemKeluar = app.inputTbsItemKeluar;
        axios.post(app.url+'/proses-hapus-semua-edit-tbs-item-keluar',newinputTbsItemKeluar)
        .then(function (resp) {

          app.getResults();
          app.alert("Membatalkan Transaksi Item Keluar");
          app.$router.replace('/item-keluar');

        })
        .catch(function (resp) {

          app.loading = false;
          alert("Tidak dapat Membatalkan Transaksi Item Keluar");
        });

      } else {

        app.$swal.close();

      }
    });
  },
  selesaiItemKeluar(){
    var app = this;
    var newinputTbsItemKeluar = app.inputTbsItemKeluar;
    app.$swal({
      text: "Anda Yakin Ingin Menyelesaikan Transaksi Ini ?",
      content: {
        element: "input",
        attributes: {
          placeholder: "*Silakan Isi Keterangan"
        },
      },
      buttons: {
        closeModal: false,
        cancel: true,
        confirm: "Submit"                 
      },

    }).then((value) => {

      if (!value) throw null;

      this.prosesSelesaiItemKeluar(value);

    });


  },
  prosesSelesaiItemKeluar(value){

    var app = this;    
    var id = app.$route.params.id;
    app.inputTbsItemKeluar.keterangan = value;
    var newinputTbsItemKeluar = app.inputTbsItemKeluar;
    app.loading = true;

    axios.post(app.url+'/proses-edit-item-keluar/'+id,newinputTbsItemKeluar)
    .then(function (resp) {

      if (resp.data == 0) {

        app.alertTbs("Anda Belum Memasukan Produk");
        app.loading = false;

      }else if(resp.data == 2){

        app.alertTbs("Terjadi Kesalahan, Silakan Coba Lagi");
        app.loading = false;


      }else if(resp.data.respons == 1){

        
        app.alertTbs("Gagal : Stok " + resp.data.nama_produk + " Tidak Mencukupi Untuk Dikeluarkan, Sisa Produk = "+resp.data.stok_produk);
        app.loading = false;


      }else{

        app.getResults();
        app.alert("Menyelesaikan Transaksi Item Keluar");
        app.$router.replace('/item-keluar');

      }

    })
    .catch(function (resp) {                
      app.loading = false;
      alert("Tidak dapat Menyelesaikan Transaksi Item Keluar");
    });

  },
  alert(pesan) {
    this.$swal({

     title: "Berhasil ",
     text: pesan,
     icon: "success",

   });
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