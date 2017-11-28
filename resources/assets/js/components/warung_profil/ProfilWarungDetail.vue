<template>

  <div class="row" >
    <div class="col-md-12">

      <ul class="breadcrumb">
        <li><a href="#">Home</a></li>
        <li class="active">Detail Profil Warung</li>
      </ul>

      <div class="card">
        <div class="card-header card-header-icon" data-background-color="purple">
          <i class="material-icons">store</i>
        </div>

         <div class="card-content">
          <h4 class="card-title"> Detail Profil Warung </h4>

          <div class="toolbar">
            <p> <router-link :to="{name: 'indexProfilWarung'}" class="btn btn-primary"><i class="material-icons">reply</i> Kembali</router-link></p>
          </div>

          <div class=" table-responsive ">

            <table class="table table-striped table-hover">
              <thead class="text-primary">
                <tr>
                  <th>Nama</th>
                  <th>Provinsi</th>
                  <th>Kabupaten</th>
                  <th>Kecamatan</th>
                  <th>Desa / Kelurahan</th>
                </tr>
              </thead>

              <tbody>
                <tr>
                  <td>{{ profilWarung.name }}</td>
                  <td>{{ profilWarung.provinsi.name }}</td>
                  <td>{{ profilWarung.kabupaten.name }}</td>
                  <td>{{ profilWarung.kecamatan.name }}</td>
                  <td>{{ profilWarung.kelurahan.name }}</td>
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
      profilWarung: {},
      url : window.location.origin+(window.location.pathname).replace("dashboard", "profil-warung"),
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
            app.profilWarung = resp.data;
            app.loading = false;
          })
          .catch(function (resp) {
            console.log(resp);
            app.loading = false;
            alert("Tidak Bisa Memuat Profil Warung");
          });
        }
      }
    }
</script>