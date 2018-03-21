<style scoped>
.modal {
  overflow-y:auto;
}
.pencarian {
  color: red; 
  float: right;
}
.card-produk{
  background-color:#82B1FF;
}
</style>
<template>

	<div class="row">
		<div class="col-md-12">
			<ul class="breadcrumb">

				<li><router-link :to="{name: 'indexDashboard'}">Home</router-link></li>
				<li style="color: purple">Persediaan</li>
				<li><router-link :to="{name: 'indexItemKeluar'}">Item Keluar</router-link></li>
				<li class="active">Tambah Item Keluar</li>

			</ul>



            <!-- small modal Jumlah Produk-->
            <div class="modal" id="modalJumlahProduk" role="dialog" tabindex="-1"  aria-labelledby="myModalLabel" aria-hidden="true" >
              <div class="modal-dialog modal-medium">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close"  v-on:click="closeModalJumlahProduk()"> &times;</button> 
                </div>
                <form class="form-horizontal" v-on:submit.prevent="submitProdukItemKeluar(inputTbsItemKeluar.jumlah_produk)"> 
                    <div class="modal-body text-center">
                        <h3><b>{{inputTbsItemKeluar.nama_produk}}</b> </h3>
                        <input class="form-control" type="number" v-model="inputTbsItemKeluar.jumlah_produk" placeholder="Isi Jumlah Produk" name="jumlah_produk" id="jumlah_produk" ref="jumlah_produk" autocomplete="off" step="0.01">
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-simple" v-on:click="closeModalJumlahProduk()">Close</button>
                      <button type="button" class="btn btn-info btn-lg" v-on:click="submitProdukItemKeluar(inputTbsItemKeluar.jumlah_produk)">Tambah</button>
                  </div>
              </form>
          </div>
      </div>
  </div>
  <!-- small modal Jumlah Produk-->


  <!-- small modal Selesai Item Masuk -->
  <div class="modal" id="modalSelesaiItemKeluar" role="dialog" tabindex="-1"  aria-labelledby="myModalLabel" aria-hidden="true" >
      <div class="modal-dialog modal-medium">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close"  v-on:click="closeModalSelesaiItemKeluar()"> &times;</button> 
        </div>
        <form class="form-horizontal" v-on:submit.prevent="prosesSelesaiItemKeluar"> 
            <div class="modal-body text-center">
                <h4><b>Anda Yakin Ingin Menyelesaikan Transaksi Ini ?</b> </h4>
                <input class="form-control" type="text" v-model="inputTbsItemKeluar.keterangan" placeholder="Silakan isi keterangan jika diperlukan" name="keterangan" id="keterangan" ref="keterangan" autocomplete="off">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-simple" v-on:click="closeModalSelesaiItemKeluar()">Close</button>
                <button type="button" class="btn btn-info btn-lg" v-on:click="prosesSelesaiItemKeluar">Selesai</button>
            </div>
        </form>
    </div>
</div>
</div>
<!-- small modal Selesai Item Masuk -->

<div class="card">
    <div class="card-header card-header-icon" data-background-color="purple">
       <i class="material-icons">vertical_align_top</i>
   </div>
   <div class="card-content">
       <h4 class="card-title"> Item Keluar </h4>
       <div class="row">

          <div class="col-md-8">
             <form class="form-horizontal"> 

               <div class="col-md-4">                   
                <div class="card card-produk" style="margin-bottom: 1px; margin-top: 1px;">
                    <div class="form-group" style="margin-right: 10px; margin-left: 10px;">
                      <selectize-component v-model="inputTbsItemKeluar.produk" :settings="placeholder_produk" id="produk" ref='produk'> 
                       <option v-for="produks, index in produk" v-bind:value="produks.produk">{{produks.barcode}} || {{produks.kode_produk}} || {{ produks.nama_produk }}</option>
                   </selectize-component>
                   <span v-if="errors.produk" id="produk_error" class="label label-danger">{{ errors.produk[0] }}</span>
               </div> 
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
          <a href="#create-item-keluar" v-bind:id="'edit-' + tbs_item_keluar.id_tbs_item_keluar" v-on:click="editEntry(tbs_item_keluar.id_tbs_item_keluar, index,tbs_item_keluar.nama_produk)">{{ tbs_item_keluar.jumlah_produk  | pemisahTitik }}
          </a>
      </td>
      <td> 
          <a href="#create-item-keluar" class="btn btn-xs btn-danger" v-bind:id="'delete-' + tbs_item_keluar.id_tbs_item_keluar" v-on:click="deleteEntry(tbs_item_keluar.id_tbs_item_keluar, index,tbs_item_keluar.nama_produk)">Delete</a>
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
import { mapState } from 'vuex';
export default {
	data: function () {
		return {
			errors: [],
			tbs_item_keluar: [],
			tbsItemKeluarData : {},
			url : window.location.origin+(window.location.pathname).replace("dashboard", "item-keluar"),
			url_produk : window.location.origin+(window.location.pathname).replace("dashboard", "produk"),
			inputTbsItemKeluar: {
                nama_produk : '',
                produk : '',
                jumlah_produk : '',
                id_tbs : '',
                keterangan : ''
            }, 
            placeholder_produk: {
                placeholder: 'Cari Produk ...',
                sortField: 'text',
                scrollDuration : 10,
                loadThrottle : 150,
                openOnFocus : false
            },
            pencarian: '',
            loading: true,
            seen : false
        }
    },
    mounted() {
      var app = this;
      app.$store.dispatch('LOAD_PRODUK_LIST')
      app.getResults();
      app.openSelectizeProduk()

  },
  computed : mapState ({    
      produk(){
        return this.$store.state.produk
    }
}),
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
        },
        tanggal: function (value) {
            return moment(String(value)).format('DD/MM/YYYY')
        }
    },
    methods: {
        openSelectizeProduk(){      
          this.$refs.produk.$el.selectize.focus();
      },
      getResults(page) {
          var app = this;	
          if (typeof page === 'undefined') {
           page = 1;
       }
       axios.get(app.url+'/view-tbs-item-keluar?page='+page)
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
      if (typeof page === 'undefined') {
       page = 1;
   }
   axios.get(app.url+'/pencarian-tbs-item-keluar?search='+app.pencarian+'&page='+page)
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
dataProduk() {
  var app = this;
  axios.get(app.url_produk+'/pilih-produk').then(function (resp) {
   app.produk = resp.data;
})
  .catch(function (resp) {

    console.log(resp);
    alert("Tidak Bisa Memuat Produk");
});
},	
alert(pesan) {
  this.$swal({
   title: "Berhasil ",
   text: pesan,
   icon: "success",
   buttons: false,
   timer: 1000,
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

    this.prosesDelete(id,nama_produk);

} else {

    app.$swal.close();

}
});

},
prosesDelete(id,nama_produk){

  var app = this;
  app.loading = true;
  axios.delete(app.url+'/proses-hapus-tbs-item-keluar/'+id)
  .then(function (resp) {

    if (resp.data == 0) {

        app.alertTbs("Produk "+nama_produk+" Gagal Dihapus!");
        app.loading = false;

    }else{

        app.getResults();
        app.alert("Menghapus Produk "+nama_produk);
        app.loading = false;
        app.inputTbsItemKeluar.id_tbs = ''  
    }


})
  .catch(function (resp) {

    console.log(resp);
    app.loading = false;
    alert("Tidak dapat Menghapus Produk "+nama_produk);
});
},
pilihProduk() {
  if (this.inputTbsItemKeluar.produk != '') {    

   var app = this;
   var produk = app.inputTbsItemKeluar.produk.split("|");
   var nama_produk = produk[1];
   this.inputJumlahProduk(nama_produk);
    // this.isiJumlahProduk(nama_produk);
}
},
inputJumlahProduk(nama_produk){
    var app = this
    app.inputTbsItemKeluar.nama_produk = nama_produk
    $("#modalJumlahProduk").show();    
    app.$refs.jumlah_produk.focus(); 
},
submitProdukItemKeluar(value){

  if (value == 0) {

      this.$swal("Jumlah Produk Tidak Boleh Nol!")
      .then((value) => {
        this.$refs.jumlah_produk.focus(); 
    });


  }else{

   var app = this;
   var produk = app.inputTbsItemKeluar.produk.split("|");
   var nama_produk = produk[1];

   var newinputTbsItemKeluar = app.inputTbsItemKeluar;
   app.loading = true;
   axios.post(app.url+'/proses-tambah-tbs-item-keluar', newinputTbsItemKeluar)
   .then(function (resp) {

    if (resp.data == 0) {
     app.loading = false;
     app.$swal("Produk "+nama_produk+" Sudah Ada, Silakan Pilih Produk Lain!")
     .then((value) => {
         app.inputTbsItemKeluar.jumlah_produk = ''
         app.inputTbsItemKeluar.produk = ''
         app.closeModalJumlahProduk() 
     });

 }else{

     app.getResults();
     app.closeModalJumlahProduk() 
     app.alert("Menambahkan Produk "+nama_produk);
     app.loading = false;
     app.inputTbsItemKeluar.jumlah_produk = ''
     app.inputTbsItemKeluar.produk = ''

 }

})
   .catch(function (resp) {

    console.log(resp);    				
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
   axios.post(app.url+'/edit-jumlah-tbs-item-keluar', newinputTbsItemKeluar)
   .then(function (resp) {
    app.getResults();
    app.alert("Mengubah Jumlah Produk "+nama_produk);
    app.loading = false;
    app.inputTbsItemKeluar.jumlah_produk = ''
    app.inputTbsItemKeluar.id_tbs = ''
})
   .catch(function (resp) { 

    console.log(resp);   				
    app.loading = false;
    alert("Tidak dapat Mengubah Jumlah Produk");
});
}
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
    axios.post(app.url+'/proses-hapus-semua-tbs-item-keluar')
    .then(function (resp) {

     app.getResults();
     app.alert("Membatalkan Transaksi Item Keluar");
     app.$router.replace('/item-keluar');

 })
    .catch(function (resp) {

        console.log(resp);
        app.loading = false;
        alert("Tidak dapat Membatalkan Transaksi Item Keluar");
    });

} else {

    app.$swal.close();

}
});
},
selesaiItemKeluar(){  
    $("#modalSelesaiItemKeluar").show();    
    this.$refs.keterangan.focus(); 
},
prosesSelesaiItemKeluar(){

    var app = this;
    var newinputTbsItemKeluar = app.inputTbsItemKeluar;
    app.loading = true;

    axios.post(app.url,newinputTbsItemKeluar)
    .then(function (resp) {

       if (resp.data == 0) {

        app.alertTbs("Anda Belum Memasukan Produk");
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

        console.log(resp);  			
        app.loading = false;
        alert("Tidak dapat Menyelesaikan Transaksi Item Keluar");
    });

},
alertTbs(pesan) {
  this.$swal({
   text: pesan,
   icon: "warning",
});
},
closeModalJumlahProduk(){  
  $("#modalJumlahProduk").hide(); 
  this.openSelectizeProduk();
},
closeModalSelesaiItemKeluar(){    
    $("#modalSelesaiItemKeluar").hide(); 
}
}
}
</script>