<template>
    <div class="row">
        <div class="col-md-12">
            <ul class="breadcrumb">
                <li><router-link :to="{name: 'indexDashboard'}">Home</router-link></li>
                <li><router-link :to="{name: 'indexProduk'}">Produk</router-link></li>
                <li class="active">Deskripsi Produk</li>
            </ul>
            <div class="card">

                <div class="card-header card-header-icon" data-background-color="purple">
                    <i class="material-icons">dns</i>
                </div>

                <div class="card-content">
                    <h4 class="card-title">Deskripsi Produk </h4>
                    <form v-on:submit.prevent="saveForm()" class="form-horizontal">
                    <ckeditor v-model="produk.deskripsi_produk" :height="'300px'" :toolbar="[['Format']]"></ckeditor>
                                                
                        <div class="form-group">
                                <button class="btn btn-primary" id="btnSimpanProduk" type="submit"><i class="material-icons">send</i> Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
export default {
    mounted() {
        let app = this;
        let id = app.$route.params.id;
        app.produkId = id;

        axios.get(app.url+'/' + id)
        .then(function (resp) {
            app.produk = resp.data;
        })
        .catch(function () {
            alert("Could not load your produk")
        });
    },
    data: function () {
        return {
            produkId: null,
            produk: {
                deskripsi_produk: '',
            },
            url : window.location.origin+(window.location.pathname).replace("dashboard", "produk"),
            errors: []
        }
    },
    methods: {
        saveForm() {
            var app = this;
            var newproduk = app.produk;
            axios.patch(app.url+'/update-deskripsi-produk/' + app.produkId, newproduk)
            .then(function (resp) {
                app.alert();
                app.$router.replace('/produk/');
            })
            .catch(function (resp) {
                console.log(resp);
                app.errors = resp.response.data.errors;
                alert("Could not create your produk");
            });
        }
        ,
        alert() {
          this.$swal({
              title: "Berhasil Mengubah produk!",
              icon: "success",
          });
      }
  }
}
</script>