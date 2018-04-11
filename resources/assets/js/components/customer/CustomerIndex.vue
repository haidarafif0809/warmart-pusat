<style scoped>
.pencarian {
  color: red; 
  float: right;
}
</style>
<template>

  <div class="row" >
    <div class="col-md-12">

      <ul class="breadcrumb">
        <li><a href="#">Home</a></li>
        <li class="active">Customer</li>
      </ul>

      <div class="card">
        <div class="card-header card-header-icon" data-background-color="purple">
          <i class="material-icons">person_add</i>
        </div>


        <!--MODAL IMPORT-->
        <div class="modal" id="modal_import" role="dialog" data-backdrop=""> 
          <div class="modal-dialog">
            <!-- Modal content--> 
            <div class="modal-content"> 
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"> <i class="material-icons">close</i></button> 
                <h4 class="modal-title"> 
                  <div class="alert-icon" style="font-weight: bold;"> 
                    Import Pelanggan
                  </div> 
                </h4> 
              </div>                         
              <form v-on:submit.prevent="importExcel()" class="form-horizontal">
                <div class="modal-body">
                  <div class="form-group">
                    <p style="font-weight: bold;">
                      Download <a :href="urlTemplate">Template</a> Excel Untuk Import Pelanggan.
                    </p>
                  </div>
                  <div class="form-group form-file-upload">
                    <input type="file" id="excel" multiple="">
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

                  <p style="color: red; font-style: italic;">*Note : Kolom Yang Berwarna Wajib Diisi.</p>
                </div>
                <div class="modal-footer">  
                  <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="material-icons">close</i> Batal</button> 
                </div> 
              </form>
            </div>       
          </div> 
        </div> 
        <!-- / MODAL IMPORT EXCEL --> 

        <div class="card-content">
          <h4 class="card-title"> Customer </h4>

          <div class="toolbar">
            <p> <router-link :to="{name: 'createCustomer'}" class="btn btn-primary" v-if="otoritas.tambah_customer == 1"><i class="material-icons">add</i> Tambah Customer</router-link>
              <!-- IMPORT EXCEL -->
              <button id="btnImport" class="btn btn-info" data-toggle="modal" data-target="#modal_import">
                <i class="material-icons">file_upload</i> Import Excel
              </button></p>
            </div>

            <div class=" table-responsive ">
              <div  class="pencarian">
               <input type="text" class="form-control pencarian" autocomplete="off" name="pencarian" v-model="pencarian" placeholder="Pencarian" >
             </div>

             <table class="table table-striped table-hover">
              <thead class="text-primary">
                <tr>
                  <th>ID Customer</th>
                  <th>No. Telpon</th>
                  <th>Kode</th>
                  <th>Nama</th>
                  <th>Alamat</th>
                  <th>Waktu</th>
                  <th>Aksi</th>
                </tr>
              </thead>

              <tbody v-if="customers.length > 0 && loading== false" class="data-ada">
                <tr v-for="customer, index in customers">
                  <td>{{ customer.customer.id }}</td>
                  <td>{{ customer.customer.no_telp }}</td>
                  <td>{{ customer.customer.kode_pelanggan }}</td>
                  <td>{{ customer.customer.name }}</td>
                  <td>{{ customer.customer.alamat }}</td>
                  <td>{{ customer.customer.created_at | tanggal }}</td>
                  <td> 
                    <a v-bind:href="'customer/cetak-customer/'+customer.customer.no_telp" class="btn btn-xs btn-warning" target="blank"> Cetak
                    </a>
                    <router-link :to="{name: 'detailCustomer', params: {id: customer.customer.id}}" class="btn btn-xs btn-info" v-bind:id="'detail-' + customer.customer.id" >
                      Detail 
                    </router-link>
                    <router-link :to="{name: 'editCustomer', params: {id: customer.customer.id}}" class="btn btn-xs btn-default" v-bind:id="'edit-' + customer.customer.id" v-if="otoritas.edit_customer == 1">
                      Edit 
                    </router-link>
                    <a href="#/customer" class="btn btn-xs btn-danger" v-bind:id="'delete-' + customer.customer.id" v-on:click="deleteEntry(customer.customer.id, index,customer.customer.name)" v-if="otoritas.hapus_customer == 1 && customer.status_pelanggan == 0"> Delete
                    </a>
                    <button class="btn btn-xs btn-danger" v-bind:id="'delete-' + customer.customer.id" v-on:click="deletePelangganTerpakai(customer.customer.id, index,customer.customer.name)" v-else-if="otoritas.hapus_customer == 1 && customer.status_pelanggan == 1"> Delete
                    </button>
                  </td>
                </tr>
              </tbody>
              <!--JIKA DATA CUSTOMER KOSONG-->
              <tbody class="data-tidak-ada" v-else-if="customers.length == 0 && loading== false">
                <tr>
                  <td colspan="4"  class="text-center">Tidak Ada Data</td>
                </tr>
              </tbody>
            </table>

            <!--LOADING-->
            <vue-simple-spinner v-if="loading"></vue-simple-spinner>
            <!--PAGINATION TABLE-->
            <div align="right"><pagination :data="customersData" v-on:pagination-change-page="getResults" :limit="4"></pagination></div>

          </div><!-- /END RESPONSIVE-->

        </div>
      </div>

    </div><!--/END-COL-SM-10-->
  </div><!--/END ROW-->

</template>

<script>
export default {
  data: function () {
    return {
      customers: [],
      customersData: {},
      otoritas: {},
      url : window.location.origin+(window.location.pathname).replace("dashboard", "customer"),
      urlTemplate : window.location.origin+(window.location.pathname).replace("dashboard", "customer/template-excel"),
      pencarian: '',
      contoh : '',
      loading: true
    }
  },
  mounted() {
    var app = this;
    app.getResults();
  },
  watch: {
    pencarian: function (newQuestion) {
      this.getHasilPencarian()  
    }
  },
  filters: {
    tanggal: function (value) {
      return moment(String(value)).format('DD/MM/YYYY hh:mm')
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
        app.customers = resp.data.data;
        app.customersData = resp.data;
        app.otoritas = resp.data.otoritas.original;
        app.loading = false;
      })
      .catch(function (resp) {
        console.log(resp);
        app.loading = false;
        alert("Tidak Bisa Memuat Customer");
      });
    },
    getHasilPencarian(page){
      var app = this;
      if (typeof page === 'undefined') {
        page = 1;
      }
      app.loading = true;
      axios.get(app.url+'/pencarian?search='+app.pencarian+'&page='+page)
      .then(function (resp) {
        app.customers = resp.data.data;
        app.customersData = resp.data;
        app.otoritas = resp.data.otoritas.original;
        app.loading = false;
      })
      .catch(function (resp) {
        console.log(resp);
        app.loading = false;
        alert("Tidak Bisa Memuat Customer");
      });
    }, 
    alert(pesan) { 
      this.$swal({ 
        text: pesan, 
        icon: "success",
        buttons: false,
        timer: 1500, 
      }); 
    }, 
    deleteEntry(id, index,name) {

      var app = this;
      app.$swal({
        text: "Anda Yakin Ingin Menghapus Customer "+name+ " ?",
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) {

          this.prosesDelete(id, index,name);

        } else {

          app.$swal.close();

        }
      });

    },
    prosesDelete(id, index,name) {

      var app = this; 
      axios.delete(app.url+'/' + id) 
      .then(function (resp) { 

        app.customers.splice(index,1)
        app.alert(`Berhasil menghapus Customer ${name}`);

      }) 
      .catch(function (resp) { 

        alert(`Tidak dapat menghapus Customer!`)
        console.log(resp)

      });

    },
    importExcel(){
      var app = this;
      let newExcel = new FormData();
      let file = document.getElementById('excel').files[0];

      if (file != undefined) {
        newExcel.append('excel', file)
      }else{
        app.alertGagal("Silakan Pilih File Dahulu.");
        return;
      }
      app.loadingData();

      axios.post(app.url+'/import-excel', newExcel).then(function (resp){            
        console.log(resp.data);
        app.$swal.close();

        if (resp.data.pesanError !== undefined || resp.data.pesanErrorStatus !== undefined) {
          console.log(resp)
          return swal({
            title: 'Gagal!',
            type: 'warning',
            html: '<div style="text-align: left; font-size: 14px;">'+ resp.data.pesanError +' <br> '+ resp.data.pesanErrorStatus+'</div>',
          });
        }

        $("#excel").val('');
        $("#modal_import").hide();
        app.alertImport(resp.data.jumlahPelanggan + ' Pelanggan Berhasil Diimport.');
        app.getResults();
      }).catch(function (resp){
        app.$swal.close();
        if (resp.response.data.errors != undefined) {
          app.errors = resp.response.data.errors.excel[0];
        }
        else {
          app.errors = "Terjadi Kesalahan Pada Proses Import!";
        }

        app.alertGagal(app.errors);
      });
    },
    deletePelangganTerpakai(id, index,name){
      this.alertTbs(`Customer "${name}" tidak bisa dihapus, silakan hapus terlebih dahulu data yang berkaitan dengan customer ini`);
    },
    loadingData(){
      this.$swal({
        title: "Sedang Memproses Data ...",
        text: "Harap Tunggu!",
        icon: "info",
        buttons:  false,
        closeOnClickOutside: false,
        closeOnEsc: false,

      });
    },
    alertImport(pesan) {
      this.$swal({
        text: pesan,
        icon: "success",
        buttons: false,
        timer: 2000
      });
    },
    alertGagal(pesan) {
      this.$swal({
        text: pesan,
        icon: "warning",
        buttons: false,
        timer: 2000
      });
    },
    alertTbs(pesan) {
      this.$swal({
        text: pesan,
        icon: "warning",
      });
    },
  }
}
</script>
