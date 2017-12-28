<template>
	
	<div class="row" >
        <div class="col-md-12">
            <ul class="breadcrumb">
                <li><a href=" ">Home</a></li>
                <li><router-link :to="{name: 'indexKasMutasi'}">Kas Mutasi</router-link></li>
                <li class="active">Tambah Kas Mutasi</li>
            </ul>

            <div class="card">
	            <div class="card-header card-header-icon" data-background-color="purple">
	                 <i class="material-icons">compare_arrows</i>
	            </div>
	            
	            <div class="card-content">
	               	<h4 class="card-title"> Kas Mutasi </h4>
	               	<div>
		               	<form v-on:submit.prevent="saveForm()" class="form-horizontal"> 
		                    <div class="form-group">
		                        <label for="dari_kas" class="col-md-2 control-label">Dari Kas</label>
		                        <div class="col-md-4">
		                            <selectize-component v-model="kasMutasi.dari_kas" :settings="placeholderKas" id="dari_kas"  name="dari_kas"> 
	                                    <option v-for="data_kas, index in kas" v-bind:value="data_kas.id">{{ data_kas.nama_kas }}</option>
	                                </selectize-component>
	                                <span v-if="errors.dari_kas" id="dari_kas_error" class="label label-danger">{{ errors.dari_kas[0] }}</span>
		                        </div>
		                    </div>

		                    <div class="form-group">
		                        <label for="ke_kas" class="col-md-2 control-label">Ke Kas</label>
		                        <div class="col-md-4">
		                            <selectize-component v-model="kasMutasi.ke_kas" :settings="placeholderKas" id="ke_kas"  name="ke_kas"> 
	                                    <option v-for="data_kas, index in kas" v-bind:value="data_kas.id">{{ data_kas.nama_kas }}</option>
	                                </selectize-component>
	                                <span v-if="errors.ke_kas" id="ke_kas_error" class="label label-danger">{{ errors.ke_kas[0] }}</span>
		                        </div>
		                    </div>

		                    <div class="form-group">
		                        <label for="jumlah" class="col-md-2 control-label">Jumlah </label>
		                        <div class="col-md-4">
		                            <money class="form-control" ref="jumlah" required autocomplete="off" v-model="kasMutasi.jumlah" v-bind="separator" id="jumlah" name="jumlah"></money>
		                            <span v-if="errors.jumlah" id="jumlah_error" class="label label-danger">{{ errors.jumlah[0] }}</span>
		                        </div>
		                    </div>

		                    <div class="form-group">
		                        <label for="keterangan" class="col-md-2 control-label">Keterangan</label>
		                        <div class="col-md-4">
		                            <input class="form-control" autocomplete="off" placeholder="Keterangan" type="text" v-model="kasMutasi.keterangan" id="jumlah" name="keterangan"  autofocus="">
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
            url : window.location.origin+(window.location.pathname).replace("dashboard", "kas-mutasi"),
            kasMutasi: {
                dari_kas: '',
                ke_kas: '',
                jumlah: '',
                keterangan: ''
            },
            message : '',
            placeholderKas: {
                placeholder: '--PILIH KAS--'
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
    },
    methods: {
        saveForm() {
            var app = this;
            var newKasMutasi = app.kasMutasi;
            
            if (app.kasMutasi.dari_kas != app.kasMutasi.ke_kas) {
	            axios.post(app.url, newKasMutasi)
	            .then(function (resp) {	            	
	            		var respons = resp.data;
		            	if (resp.data < 0) {
		            		app.kasTidakCukup(respons);
		            	}
		            	else{
		            		app.inputKasMutasi();
		            	}	            	
	            })
	            .catch(function (resp) {
	            	console.log(resp)
	                app.success = false;
	                app.errors = resp.response.data.errors;
	            });

            }else{
            	app.alertKasSama();
            }
        },
        alert(pesan) {
          this.$swal({
              title: "Sukses!",
              text: pesan,
              icon: "success",
          });
      	},
        alertGagal(pesan) {
          this.$swal({
              title: "Peringatan!",
              text: pesan,
              icon: "warning",
          });
      	},
        alertKasSama() {
          this.$swal({
              title: "Peringatan!",
              text: "Kas Tidak Boleh Sama!",
              icon: "warning",
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
	    kasTidakCukup(respons){
	    	var app = this;
	    	app.message = 'Kas Tidak Mencukupi. Sisa Kas = '+new Intl.NumberFormat().format(respons);
	    	app.alertGagal(app.message);
			app.$router.replace('/create-kas-mutasi');
	    },
	    inputKasMutasi(){
	    	var app = this;
	    	app.message = 'Berhasil Menambah Kas Mutasi';
	    	app.alert(app.message);
	    	app.kasMutasi.kas = ''
	    	app.kasMutasi.kategori = ''
	    	app.kasMutasi.jumlah = ''
	    	app.kasMutasi.keterangan = ''
	    	app.errors = '';
	    	app.$router.replace('/kas-mutasi');
	    }
  	}
}
</script>