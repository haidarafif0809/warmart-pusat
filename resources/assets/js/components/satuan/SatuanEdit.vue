
<template>
    <div class="row" >
        <div class="col-md-12">
            <ul class="breadcrumb">
                <li><a href=" ">Home</a></li>
                <li><router-link :to="{name: 'indexSatuan'}">Satuan</router-link></li>
                <li class="active">Edit Satuan</li>
            </ul>
            <div class="card">
             <div class="card-header card-header-icon" data-background-color="purple">
                 <i class="material-icons">dns</i>
             </div>
             <div class="card-content">
               <h4 class="card-title"> Satuan </h4>
               <div>

                <form v-on:submit.prevent="saveForm()" class="form-horizontal"> 
                    <div class="form-group">
                        <label for="name" class="col-md-2 control-label">Nama Satuan</label>
                        <div class="col-md-4">
                            <input class="form-control" required autocomplete="off" placeholder="Nama Satuan" type="text" v-model="satuan.nama_satuan"  autofocus="" name="nama_satuan">
                            <span v-if="errors.nama_satuan" class="label label-danger">{{ errors.nama_satuan[0] }}</span>
                            
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-4 col-md-offset-2">
                            <button class="btn btn-primary" id="btnSimpanSatuan" type="submit"><i class="material-icons">send</i> Submit</button>
                        </div>
                    </div>
                </form>

            </div>
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
        app.satuanId = id;

        axios.get(app.url+'/' + id)
        .then(function (resp) {
            app.satuan = resp.data;
        })
        .catch(function () {
            alert("Could not load your satuan")
        });
    },
    data: function () {
        return {
            satuanId: null,
            satuan: {
                nama_satuan: '',
            },
            url : window.location.origin+(window.location.pathname).replace("dashboard", "satuan"),
            errors: []
        }
    },
    methods: {
        saveForm() {
            var app = this;
            var newSatuan = app.satuan;
            axios.patch(app.url+'/' + app.satuanId, newSatuan)
            .then(function (resp) {
                app.alert();
                app.$router.replace('/');
            })
            .catch(function (resp) {
                console.log(resp);
                app.errors = resp.response.data.errors;
                alert("Could not create your satuan");
            });
        }
        ,
        alert() {
          this.$swal({
              title: "Berhasil Mengubah Satuan!",
              icon: "success",
          });
      }
  }
}
</script>