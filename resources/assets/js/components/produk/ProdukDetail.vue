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
                    
                    <textarea v-model="produk.deskripsi_produk" class="form-control"></textarea>
                        <input class="form-control" autocomplete="off" v-model="produk.id" type="hidden" name="id" id="id"  autofocus="">
                        <div class="form-group">
                            <button class="btn btn-primary" id="btnSimpanProduk" type="submit"><i class="material-icons">send</i> Submit</button>
                            <a :href="urlLihat+produk.id" class="btn btn-info" id="lihatDeskripsi" type="submit"><i class="material-icons">remove_red_eye</i> Lihat</a>
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
                id:'',
                deskripsi_produk: '',
            },
            url : window.location.origin+(window.location.pathname).replace("dashboard", "produk"),
            urlLihat : window.location.origin+(window.location.pathname).replace("dashboard", "produk/lihat-deskripsi-produk/"),
            errors: []
        }
    },
    methods: {
        saveForm() {
            var app = this;
            var newproduk = app.produk;

            axios.put('update-deskripsi', newproduk)
            .then(function (resp) {
                app.alert();
                app.$router.replace('/produk/');
            })
            .catch(function (resp) {
                console.log(resp);
                app.errors = resp.response.data.errors;
                alert("Could not create your produk");
            });
        },
        alert() {
          this.$swal({
              title: "Sukses",
              text: "Deskripsi Produk Berhasil Diubah!",
              icon: "success",
          });
        }
  }
}
</script>