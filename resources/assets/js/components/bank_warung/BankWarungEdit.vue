
<template>
    <div class="row" >
        <div class="col-md-12">
            <ul class="breadcrumb">
                <li><a href=" ">Home</a></li>
                <li><router-link :to="{name: 'indexBankWarung'}">Bank Warung</router-link></li>
                <li class="active">Edit Bank Warung</li>
            </ul>
            <div class="card">
             <div class="card-header card-header-icon" data-background-color="purple">
                 <i class="material-icons">payment</i>
             </div>
             <div class="card-content">
               <h4 class="card-title"> Bank Warung </h4>
               <div>

                <form v-on:submit.prevent="saveForm()" class="form-horizontal"> 
                    <div class="form-group">
                        <label for="name" class="col-md-2 control-label">Nama Bank</label>
                        <div class="col-md-4">
                            <input class="form-control" required autocomplete="off" placeholder="Nama Bank" type="text" v-model="bankWarung.nama_bank"  autofocus="" name="nama_bank">
                            <span v-if="errors.nama_bank" class="label label-danger">{{ errors.nama_bank[0] }}</span>
                            
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="no_telp" class="col-md-2 control-label">A.N Bank</label>
                        <div class="col-md-4">
                            <input class="form-control" required autocomplete="off" placeholder="A.N Bank" type="text" name="atas_nama" v-model="bankWarung.atas_nama">
                            <span v-if="errors.atas_nama" class="label label-danger">{{ errors.atas_nama[0] }}</span>
                        </div>
                    </div>   
                    <div class="form-group">
                        <label for="no_rek" class="col-md-2 control-label">No Rek</label>
                        <div class="col-md-4">
                            <input class="form-control" required autocomplete="off" placeholder="No Rekening" type="text" name="no_rek" v-model="bankWarung.no_rek">
                            <span v-if="errors.no_rek" class="label label-danger">{{ errors.no_rek[0] }}</span>
                        </div>
                    </div>
                <div class="form-group">
                    <div class="col-md-4 col-md-offset-2">
                        <button class="btn btn-primary" id="btnSimpanBank" type="submit"><i class="material-icons">send</i> Submit</button>
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
        app.bankWarungId = id;

        axios.get(app.url+'/' + id)
        .then(function (resp) {
            app.bankWarung = resp.data;
        })
        .catch(function () {
            alert("Could not load your bank")
        });
    },
    data: function () {
        return {
            bankWarungId: null,
            bankWarung: {
                nama_bank: '',
                atas_nama: '',
                no_rek: '',
            },
            url : window.location.origin + (window.location.pathname).replace("dashboard", "bank-warung"),
            errors: []
        }
    },
    methods: {
        saveForm() {
            var app = this;
            var newBank = app.bankWarung;
            axios.patch(app.url+'/' + app.bankWarungId, newBank)
            .then(function (resp) {
                app.alert();
                app.$router.replace('/bank-warung/');
            })
            .catch(function (resp) {
                console.log(resp);
                app.errors = resp.response.data.errors;
                alert("Could not create your bank");
            });
        }
        ,
        alert() {
          this.$swal({
              title: "Berhasil Mengubah Bank!",
              icon: "success",
          });
      }
  }
}
</script>