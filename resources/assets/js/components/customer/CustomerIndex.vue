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

        <div class="card-content">
          <h4 class="card-title"> Customer </h4>

          <div class="toolbar">
            <p> <router-link :to="{name: 'createCustomer'}" class="btn btn-primary" v-if="otoritas.tambah_customer == 1"><i class="material-icons">add</i> Tambah Customer</router-link></p>
          </div>

          <div class=" table-responsive ">
            <div  class="pencarian">
             <input type="text" class="form-control pencarian" autocomplete="off" name="pencarian" v-model="pencarian" placeholder="Pencarian" >
           </div>

           <table class="table table-striped table-hover">
            <thead class="text-primary">
              <tr>
                <th>No. Telpon</th>
                <th>Nama</th>
                <th>Alamat</th>
                <th>Waktu</th>
                <th>Aksi</th>
              </tr>
            </thead>

            <tbody v-if="customers.length > 0 && loading== false" class="data-ada">
              <tr v-for="customer, index in customers">
                <td>{{ customer.no_telp }}</td>
                <td>{{ customer.name }}</td>
                <td>{{ customer.alamat }}</td>
                <td>{{ customer.created_at | tanggal }}</td>
                <td> 
                  <router-link :to="{name: 'detailCustomer', params: {id: customer.id}}" class="btn btn-xs btn-info" v-bind:id="'detail-' + customer.id" >
                    Detail 
                  </router-link>
                  <router-link :to="{name: 'editCustomer', params: {id: customer.id}}" class="btn btn-xs btn-default" v-bind:id="'edit-' + customer.id" v-if="otoritas.edit_customer == 1">
                    Edit 
                  </router-link>
                  <a href="#" class="btn btn-xs btn-danger" v-bind:id="'delete-' + customer.id" v-on:click="deleteEntry(customer.id, index,customer.name)" v-if="otoritas.hapus_customer == 1"> Delete
                  </a>
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
        app.customers = resp.data.customer.data;
        app.customersData = resp.data.customer;
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
      axios.get(app.url+'/pencarian?search='+app.pencarian+'&page='+page)
      .then(function (resp) {
        app.customers = resp.data.customer.data;
        app.customersData = resp.data.customer;
        app.otoritas = resp.data.otoritas.original;
      })
      .catch(function (resp) {
        console.log(resp);
        alert("Tidak Bisa Memuat Customer");
      });
    }, 
    alert(pesan) { 
      this.$swal({ 
        title: "Berhasil Menghapus Customer!", 
        text: pesan, 
        icon: "success", 
      }); 
    }, 
    deleteEntry(id, index,name) {
      swal({ 
        title: "Konfirmasi Hapus", 
        text : "Anda Yakin Ingin Menghapus Customer "+name+" ?", 
        icon : "warning", 
        buttons: true, 
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) { 
          var app = this; 
          axios.delete(app.url+'/' + id) 
          .then(function (resp) { 
            app.$router.replace('/customer'); 
            app.getResults(); 
            swal("Customer Berhasil Dihapus!  ", { 
              icon: "success", 
            }); 
          }) 
          .catch(function (resp) { 
            swal("Gagal Menghapus Customer!  ", { 
              icon: "warning", 
            }); 
          });  
        }
      });
    }
  }
}
</script>
