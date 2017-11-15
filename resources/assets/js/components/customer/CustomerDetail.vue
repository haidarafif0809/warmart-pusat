<template>

  <div class="row" >
    <div class="col-md-12">

      <ul class="breadcrumb">
        <li><a href="#">Home</a></li>
        <li class="active">Detail Customer</li>
      </ul>

      <div class="card">
        <div class="card-header card-header-icon" data-background-color="purple">
          <i class="material-icons">person_add</i>
        </div>

         <div class="card-content">
          <h4 class="card-title"> Detail Customer </h4>

          <div class="toolbar">
            <p> <router-link :to="{name: 'indexCustomer'}" class="btn btn-primary"><i class="material-icons">reply</i> Kembali</router-link></p>
          </div>

          <div class=" table-responsive ">

            <table class="table table-striped table-hover">
              <thead class="text-primary">
                <tr>
                  <th>Nama</th>
                  <th>Email</th>
                  <th>Alamat</th>
                  <th>No. Telpon</th>
                  <th>Tanggal Lahir</th>
                  <th>Komunitas</th>
                </tr>
              </thead>

              <tbody>
                <tr>
                  <td>{{ customers.name }}</td>
                  <td>{{ customers.email }}</td>
                  <td>{{ customers.alamat }}</td>
                  <td>{{ customers.no_telp }}</td>
                  <td>{{ customers.tgl_lahir }}</td>
                  <td>{{ customers.komunitas }}</td>
                </tr>
              </tbody>
            </table>

            <!--LOADING-->
            <vue-simple-spinner v-if="loading"></vue-simple-spinner>

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
      customers: {},
      url : window.location.origin+(window.location.pathname).replace("dashboard", "customer"),
      contoh : '',
      loading: true
    }
  },
  mounted() {
    var app = this;
    let id = app.$route.params.id;

    app.getResults(id);
  },
  methods: {
        getResults(id) {
          var app = this;

          axios.get(app.url+'/view-detail/'+ id)
          .then(function (resp) {
            app.customers = resp.data;
            app.loading = false;
          })
          .catch(function (resp) {
            console.log(resp);
            app.loading = false;
            alert("Tidak Bisa Memuat Customer");
          });
        }
      }
    }
</script>