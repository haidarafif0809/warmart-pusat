<style scoped>
  .btn-icon{
    border-radius: 1px solid;
    padding: 10px 10px;
  }
</style>
<template>
	<div class="row" >
        <div class="col-md-12">
            <ul class="breadcrumb">
                <li><a href=" ">Home</a></li>
                <li><router-link :to="{name: 'indexKasMutasi'}">Kas Mutasi</router-link></li>
                <li class="active">Tambah Kas Mutasi</li>
            </ul>


	         <div class="modal" id="modal_tambah_kas" role="dialog" data-backdrop=""> 
                <div class="modal-dialog"> 
                    <!-- Modal content--> 
                    <div class="modal-content"> 
                        <div class="modal-header"> 
                            <button type="button" class="close"  v-on:click="closeModalX()" v-shortkey.push="['esc']" @shortkey="closeModalX()"> &times;</button> 
                            <h4 class="modal-title"> 
                                <div class="alert-icon"> 
                                    <b>Silahkan Isi Kas!</b> 
                                </div> 
                            </h4> 
                        </div> 
                          <div class="modal-body">
                        <form v-on:submit.prevent="saveFormKas()" class="form-horizontal"> 
                          <div class="form-group">
                            <label for="kode_kas" class="col-md-3 control-label">Kode Kas</label>
                            <div class="col-md-9">
                              <input class="form-control" autocomplete="off" placeholder="Kode Kas" v-model="tambahKas.kode_kas" type="text" name="kode_kas" id="kode_kas"  autofocus="">
                              <span v-if="errors.kode_kas" id="kode_kas_error" class="label label-danger">{{ errors.kode_kas[0] }}</span>
                            </div>
                          </div>
                          <div class="form-group">
                            <label for="nama_kas" class="col-md-3 control-label">Nama Kas</label>
                            <div class="col-md-9">
                              <input class="form-control" autocomplete="off" placeholder="Nama Kas" v-model="tambahKas.nama_kas" type="text" name="nama_kas" id="nama_kas"  >
                              <span v-if="errors.nama_kas" id="nama_kas_error" class="label label-danger">{{ errors.nama_kas[0] }}</span>
                            </div>
                          </div>
                          <div class="form-group">
                          <label for="nama_kas" class="col-md-3 control-label">Tampil Transaksi</label>
                               <div class="togglebutton col-md-9">
                                     <label>
                                         <b>No</b>  <input type="checkbox" v-model="tambahKas.status_kas" value="1" name="status_kas" id="status_kas"><b>Yes</b>
                                         </label>
                                          </div>
                                      </div>
                                      <div class="form-group">
                                          <label for="nama_kas" class="col-md-3 control-label">Default Kas</label>
                                              <div class="togglebutton col-md-9">
                                              <label>
                                                  <b>No</b>  <input type="checkbox" @change="defaultKas()" v-model="tambahKas.default_kas" value="1" name="default_kas" id="default_kas"><b>Yes</b>
                                              </label>
                                          </div>
                                  </div>
                              <div class="form-group">
                              <div class="col-md-9 col-md-offset-3">
                                <p style="color: red; font-style: italic;">*Note : Hanya 1 Kas yang dijadikan default.</p>
                              <button class="btn btn-primary" id="btnSimpanKas" type="submit"><i class="material-icons">send</i> Submit</button>
                            </div>
                          </div>
                        </form>
                          </div>
                    <div class="modal-footer">  
                   </div> 
           </div>       
       </div> 
   </div> 
   <!-- / MODAL TOMBOL SELESAI --> 


            <div class="card">
	            <div class="card-header card-header-icon" data-background-color="purple">
	                 <i class="material-icons">compare_arrows</i>
	            </div>
	            
	            <div class="card-content">
	               	<h4 class="card-title"> Kas Mutasi </h4>
	               	<div>
		               	<form  class="form-horizontal"> 
		                    <div class="form-group">
		                        <label for="dari_kas" class="col-md-2 control-label">Dari Kas</label>
		                        <div class="col-md-4 col-xs-10" >
		                            <selectize-component v-model="kasMutasi.dari_kas" :settings="placeholderKas" id="dari_kas"  name="dari_kas"> 
	                                    <option v-for="data_kas, index in kas" v-bind:value="data_kas.id">{{ data_kas.nama_kas }}</option>
	                                </selectize-component>
	                                <span v-if="errors.dari_kas" id="dari_kas_error" class="label label-danger">{{ errors.dari_kas[0] }}</span>
		                        </div>
		                      <div class="col-md-1 col-xs-1" style="padding-left:0px;">
                              <div class="row" style="margin-top:-10px">
                                <button class="btn btn-primary btn-icon waves-effect waves-light" @click="tambahModalKas()" type="button"> <i class="material-icons" style="font-size:14px">add</i> </button>
                              </div>
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
		                            <button class="btn btn-primary" id="btnSimpanKasMasuk"  v-on:click="selesaiTransaksi()"><i class="material-icons">send</i> Submit</button>
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
            urlKas : window.location.origin+(window.location.pathname).replace("dashboard", "kas"),
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
           kasBaru: {
              kode_kas : '',
              nama_kas : '',
              status_kas : 1,
              default_kas : 0
            },
            tambahKas: {
	        kode_kas : '',
	        nama_kas : '',
	        status_kas : 0,
	        default_kas : 0
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
        selesaiTransaksi(){
        this.$swal({
          text: "Anda Yakin Ingin Menyelesaikan Transaksi Ini ?",
          buttons: {
            cancel: true,
            confirm: "OK"                   
          },
        }).then((value) => {
          if (!value) throw null;
          this.saveForm(value);
        });
        },
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
	     tambahModalKas(){
	       $("#modal_tambah_kas").show();
	       this.$refs.kode_kas.$el.focus(); 
	      },
	    kasTidakCukup(respons){
	    	var app = this;
	    	app.message = 'Kas Tidak Mencukupi. Sisa Kas = '+new Intl.NumberFormat().format(respons);
	    	app.alertGagal(app.message);
			app.$router.replace('/create-kas-mutasi');
	    },
	   closeModalX(){
        $("#modal_tambah_kas").hide();  
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
	    },
	   saveFormKas() {
      var app = this;
      var newkas = app.tambahKas;
      axios.post(app.urlKas, newkas)
      .then(function (resp) {
        app.message = 'Menambah Kategori Transaksi '+ app.tambahKas.nama_kas;
        app.alert(app.message);
        app.tambahKas.kode_kas = ''
        app.tambahKas.nama_kas = ''
        app.tambahKas.status_kas = 0
        app.tambahKas.default_kas = 0
        app.errors = '';
         app.dataKas();
        $("#modal_tambah_kas").hide();

      })
      .catch(function (resp) {
        app.success = false;
        app.errors = resp.response.data.errors;
      });
    },
    defaultKas() {
         var app = this;
          var toogle = app.tambahKas.default_kas;

         if (toogle == true) {
          app = this;
      app.$swal({
      title: "Konfirmasi",
      text: "Apakah Anda Yakin Ingin Mengubah Kas Utama ?",
      icon: "warning",
      buttons: true,
      dangerMode: true,
      })
      .then((confirm) => {
        if (confirm) {
        toogle.prop('checked', true);
        } else {
        toogle.prop('checked', false);
      }
      });
         }  
      }
  	}
}
</script>