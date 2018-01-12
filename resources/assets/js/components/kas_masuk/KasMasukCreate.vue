<template>
	
	<div class="row" >
        <div class="col-md-12">
            <ul class="breadcrumb">
                <li><a href=" ">Home</a></li>
                <li><router-link :to="{name: 'indexKasMasuk'}">Kas Masuk</router-link></li>
                <li class="active">Tambah Kas Masuk</li>
            </ul>

            <div class="card">
	            <div class="card-header card-header-icon" data-background-color="purple">
	                 <i class="material-icons">person_add</i>
	            </div>
	            
	            <div class="card-content">
	               	<h4 class="card-title"> Kas Masuk </h4>
	               	<div>
		               	<form v-on:submit.prevent="saveForm()" class="form-horizontal"> 
		                    <div class="form-group">
		                        <label for="kas" class="col-md-2 control-label">Kas</label>
		                        <div class="col-md-4">
		                            <selectize-component v-model="kasmasuk.kas" :settings="setKas" id="kas"  name="kas"> 
	                                    <option v-for="data_kas, index in kas" v-bind:value="data_kas.id">{{ data_kas.nama_kas }}</option>
	                                </selectize-component>
		                        </div>
		                    </div>

		                    <div class="form-group">
		                        <label for="kategori" class="col-md-2 control-label">Kategori</label>
		                        <div class="col-md-4">
		                             <selectize-component v-model="kasmasuk.kategori" :settings="setKategori" id="kategori_transaksi" name="kategori_transaksi">
	                                    <option v-for="data_kategori, index in kategori" v-bind:value="data_kategori.id">{{ data_kategori.nama_kategori_transaksi }}</option>
	                                </selectize-component>
		                        </div>
		                    </div>

		                    <div class="form-group">
		                        <label for="jumlah" class="col-md-2 control-label">Jumlah </label>
		                        <div class="col-md-4">
		                        	<money class="form-control" required autocomplete="off" v-model="kasmasuk.jumlah" v-bind="separator" id="jumlah" name="jumlah"></money>
		                            <span v-if="errors.jumlah" id="jumlah_error" class="label label-danger">{{ errors.jumlah[0] }}</span>
		                        </div>
		                    </div>

		                    <div class="form-group">
		                        <label for="keterangan" class="col-md-2 control-label">Keterangan</label>
		                        <div class="col-md-4">
		                            <input class="form-control" autocomplete="off" placeholder="Keterangan" type="text" v-model="kasmasuk.keterangan" id="jumlah" name="keterangan"  autofocus="">
		                            <span v-if="errors.keterangan" id="keterangan_error" class="label label-danger">{{ errors.keterangan[0] }}</span>
		                        </div>
		                    </div>


		                    <div class="form-group">
		                        <div class="col-md-4 col-md-offset-2">
		                            <button class="btn btn-primary" id="btnSimpanKasMasuk" type="submit"><i class="material-icons">send</i> Submit</button>
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
    data: function () {
        return {
            errors: [],
            kas : [],
            kategori :[],
            url : window.location.origin+(window.location.pathname).replace("dashboard", "kas-masuk"),
            url_create : window.location.origin+(window.location.pathname).replace("dashboard", "kas_masuk"),
            kasmasuk: {
                kas: '',
                kategori: '',
                jumlah: '',
                keterangan: '',
            },
            message : '',
            setKas: {
                placeholder: '--PILIH KAS--'
            },
           setKategori: {
                placeholder: '--PILIH KATEGORI--'
            },
            separator: {
              decimal: ',',
              thousands: '.',
              prefix: '',
              suffix: '',
              precision: 2,
              masked: false /* doesn't work with directive */
          }
        }
        
    },
    mounted() {
        var app = this;
        app.dataKas();
        app.dataKategori();
    },
    methods: {
        saveForm() {
            var app = this;
            var newkasmasuk = app.kasmasuk;
            axios.post(app.url_create, newkasmasuk)
            .then(function (resp) {
                app.message = 'Berhasil Menambah Kas Masuk';
                app.alert(app.message);
                app.kasmasuk.kas = ''
                app.kasmasuk.kategori = ''
                app.kasmasuk.jumlah = ''
                app.kasmasuk.keterangan = ''
                app.errors = '';
                app.$router.replace('/kas-masuk');

            })
            .catch(function (resp) {
                app.success = false;
                app.errors = resp.response.data.errors;
            });
        },
        alert(pesan) {
          this.$swal({
              title: "Sukses!",
              text: pesan,
              icon: "success",
          });
      	},
      	dataKas() {
	      	var app = this;
	      	axios.get(app.url+'/pilih-kas').then(function (resp) {
	            app.kas = resp.data;
	        })
	        .catch(function (resp) {
	        	alert("Tidak Bisa Memuat Kas");
	        });
	    },
	    dataKategori() {
	      	var app = this;
	      	axios.get(app.url+'/pilih-kategori').then(function (resp) {
	            app.kategori = resp.data;
	        })
	        .catch(function (resp) {
	        	alert("Tidak Bisa Memuat Kategori");
	        });
	    },
  	}
}
</script>