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
				<li style="color: purple">Persediaan</li>
				<li class="active">Item Masuk</li>
			</ul>

            <!--MODAL PINTAS TAMBAH KAS-->
            <div class="modal" id="modal_import" role="dialog" data-backdrop=""> 
                <div class="modal-dialog">
                    <!-- Modal content--> 
                    <div class="modal-content"> 
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"> <i class="material-icons">close</i></button> 
                            <h4 class="modal-title"> 
                                <div class="alert-icon" style="font-weight: bold;"> 
                                    Import Item Masuk
                                </div> 
                            </h4> 
                        </div> 
                        <div class="modal-body">
                            <div class="form-group">
                                <p style="font-weight: bold;">
                                    Download <a :href="urlTemplate">Template</a> Excel Untuk Import Item Masuk.
                                </p>
                            </div>
                            <div class="form-group form-file-upload">
                                <input type="file" id="inputFile2" multiple="">
                                <div class="input-group">
                                    <input type="text" readonly="" class="form-control" placeholder="Browse File...">
                                    <span class="input-group-btn input-group-s">
                                        <button type="button" class="btn btn-just-icon btn-round btn-primary">
                                            <i class="material-icons">attach_file</i>
                                        </button>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">                                
                                <button class="btn btn-primary" id="btnImport" type="submit"><i class="material-icons">file_upload</i> Import</button>
                            </div>
                        </div>
                        <div class="modal-footer">  
                            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="material-icons">close</i> Batal</button> 
                        </div> 
                    </div>       
                </div> 
            </div> 
            <!-- / MODAL PINTAS TAMBAH KAS --> 

            <div class="card">
                <div class="card-header card-header-icon" data-background-color="purple">
                 <i class="material-icons">vertical_align_bottom</i>
             </div>
             <div class="card-content">
                 <h4 class="card-title"> Item Masuk </h4>

                 <div class="toolbar">
                    <div class="row">
                        <div class="panel panel-default">
                            <router-link :to="{name: 'createItemMasuk'}" class="btn btn-primary"><i class="material-icons">add</i>  Tambah Item Masuk</router-link>

                            <button id="btnFilter" class="btn btn-info" data-toggle="modal" data-target="#modal_import">
                                <i class="material-icons">file_upload</i> Import Excel
                            </button>
                        </div>
                    </div>
                </div>

                <div class=" table-responsive ">
                 <div  class="pencarian">
                     <input type="text" name="pencarian" v-model="pencarian" placeholder="Pencarian" class="form-control" autocomplete="">
                 </div>
                 <table class="table table-striped table-hover" v-if="seen">
                   <thead class="text-primary">
                    <tr>

                     <th>No. Transaksi</th>
                     <th>Total</th>
                     <th>Keterangan</th>
                     <th>Waktu</th>
                     <th>Waktu Edit</th>
                     <th>Detail</th>
                     <th>Edit</th>
                     <th>Aksi</th>

                 </tr>
             </thead>
             <tbody v-if="item_masuk.length"  class="data-ada">
                <tr v-for="item_masuk, index in item_masuk" >

                   <td>{{ item_masuk.no_faktur }}</td>
                   <td>{{ item_masuk.total  }}</td>
                   <td>{{ item_masuk.keterangan }}</td>
                   <td>{{ item_masuk.waktu }}</td>
                   <td>{{ item_masuk.waktu_edit }}</td>
                   <td>
                      <router-link :to="{name: 'detailItemMasuk', params: {id: item_masuk.id}}" class="btn btn-xs btn-info" v-bind:id="'detail-' + item_masuk.id" >
                          Detail </router-link> </td>
                      </td>
                      <td><router-link :to="{name: 'editItemMasukProses', params: {id: item_masuk.id}}" class="btn btn-xs btn-default" v-bind:id="'edit-' + item_masuk.id" >
                          Edit </router-link> </td>

                          <td> 
                              <a href="#item-masuk" class="btn btn-xs btn-danger" v-bind:id="'delete-' + item_masuk.id" v-on:click="deleteEntry(item_masuk.id, index,item_masuk.no_faktur)">Delete</a>
                          </td>
                      </tr>
                  </tbody>					
                  <tbody class="data-tidak-ada" v-else>
                    <tr ><td colspan="8"  class="text-center">Tidak Ada Data</td></tr>
                </tbody>
            </table>	

            <vue-simple-spinner v-if="loading"></vue-simple-spinner>

            <div align="right"><pagination :data="itemMasukData" v-on:pagination-change-page="getResults" :limit="4"></pagination></div>

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
       item_masuk: [],
       itemMasukData: {},
       url : window.location.origin+(window.location.pathname).replace("dashboard", "item-masuk"),
       urlTemplate : window.location.origin+(window.location.pathname).replace("dashboard", "item-masuk/template-excel"),
       pencarian: '',
       loading: true,
       seen : false
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
 getResults(page) {
  var app = this;	
  if (typeof page === 'undefined') {
   page = 1;
}
axios.get(app.url+'/view?page='+page)
.then(function (resp) {
   app.item_masuk = resp.data.data;
   app.itemMasukData = resp.data;
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
axios.get(app.url+'/pencarian?search='+app.pencarian+'&page='+page)
.then(function (resp) {
   app.item_masuk = resp.data.data;
   app.itemMasukData = resp.data;
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

  var app = this;
  app.$swal({
   text: "Anda Yakin Ingin Menghapus Transaksi "+no_faktur+ " ?",
   buttons: true,
   dangerMode: true,
})
  .then((willDelete) => {
   if (willDelete) {

    this.prosesDelete(id,no_faktur);

} else {

    app.$swal.close();

}
});


},
prosesDelete(id,no_faktur){
  var app = this;
  app.loading = true;
  axios.delete(app.url+'/' + id)
  .then(function (resp) {

    if (resp.data == 0) {

        app.alertGagal("Item Masuk Tidak Dapat Dihapus, Karena Sudah Terpakai");
        app.loading = false;

    }else{

        app.getResults();
        app.alert("Menghapus Item Masuk "+no_faktur);
        app.loading = false;  
    }

})
  .catch(function (resp) {
   alert("Tidak dapat Menghapus Item Masuk");
});
},
alertGagal(pesan) {
    this.$swal({
      text: pesan,
      icon: "warning",
  });
}
}
}
</script>

