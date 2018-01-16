<style scoped>
    .modal {
    overflow-y:auto;
}
.pencarian {
  color: red; 
  float: right;
}
.form-pembelian{
    width: 100%;
    padding: 12px 20px;
    margin: 8px 0;
    display: inline-block;
    border: 3px solid #555;
    border-radius: 4px;
    box-sizing: border-box;
    font-size: 30px;
}
.form-subtotal{
    width: 100%;
    margin: 8px 0;
    display: inline-block;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}
.card-produk{
    background-color:#82B1FF;
}
  .btn-icon{
    border-radius: 1px solid;
    padding: 10px 10px;
  }
</style><template>
	<div class="row">
		<div class="col-md-12">
			<ul class="breadcrumb">

				<li><router-link :to="{name: 'indexDashboard'}">Home</router-link></li>
				<li style="color: purple">Persediaan</li>
				<li><router-link :to="{name: 'indexPembayaranHutang'}">Pembayaran Hutang</router-link></li>
				<li class="active">Tambah Pembayaran Hutang</li>

			</ul>
    <div class="card" style="margin-bottom: 1px; margin-top: 1px;" ><!-- CARD --> 
          <div class="card-content"> 
            <h4 class="card-title" style="margin-bottom: 1px; margin-top: 1px;"> Pembayaran Hutang </h4> 
            <div class="row" style="margin-bottom: 1px; margin-top: 1px;"> 

              <div class="col-md-3">
                <div class="card card-produk" style="margin-bottom: 1px; margin-top: 1px;">
                  <div class="form-group" style="margin-right: 10px; margin-left: 10px;">
                   
                     <selectize-component v-model="inputTbsPembayaranHutang.suplier" :settings="placeholder_suplier"  id="suplier" name="suplier" ref='suplier'> 
                      <option v-for="supliers, index in suplier" v-bind:value="supliers.id">{{ supliers.nama_suplier }}</option>
                     </selectize-component>

                      </div><!--/COL MD  3 --> 
                      <span v-if="errors.suplier" id="produk_error" class="label label-danger">{{ errors.suplier[0] }}</span>
            </div>
          </div>
          </div>

    <div class="row">
        <div class="col-md-9">
          <div class=" table-responsive ">
               <div class="pencarian">
                <input type="text" name="pencarian" v-model="pencarian" placeholder="Pencarian" class="form-control pencarian" autocomplete="">
            </div>

            <table class="table table-striped table-hover" v-if="seen">
              <thead class="text-primary">
                <tr>
                  <th >No Faktur Beli</th>
                  <th>Suplier</th>
                  <th>Jatuh Tempo</th>
                  <th  style="text-align:right;">Hutang</th>
                  <th  style="text-align:right;">Potongan</th>
                  <th  style="text-align:right;">Pembayaran</th>
                  <th  style="text-align:right;">Hapus</th>
                </tr>
              </thead>
              <tbody v-if="tbs_pembayaran_hutang.length > 0 && loading == false"  class="data-ada">
                <tr v-for="tbs_pembayaran_hutangs, index in tbs_pembayaran_hutang" >

                  <td>{{ tbs_pembayaran_hutangs.no }}</td>
                   <td>{{ tbs_pembayaran_hutangs.suplier }}</td>
                    <td>{{ tbs_pembayaran_hutangs.jatuh_tempo }}</td>
                    <td style="text-align:right;">{{ tbs_pembayaran_hutangs.hutang }}</td>
                    <td style="text-align:right;">{{ tbs_pembayaran_hutangs.potongan }}</td>
                    <td style="text-align:right;">>{{ tbs_pembayaran_hutangs.jumlah_bayar }}</td>
                    <td style="text-align:right;"> 
                    <a href="#create-pembelian" class="btn btn-xs btn-danger" v-bind:id="'delete-' + tbs_pembayaran_hutangs.id_tbs_pembayaran_hutangs" >Delete</a>
                  </td>
                </tr>
              </tbody>          
              <tbody class="data-tidak-ada"  v-else-if="tbs_pembayaran_hutang.length == 0 && loading == false" >
                <tr ><td colspan="7"  class="text-center">Tidak Ada Data</td></tr>
              </tbody>
            </table>  

            <vue-simple-spinner v-if="loading"></vue-simple-spinner>

            <div align="right"><pagination :data="tbsPembayaranHutangData" v-on:pagination-change-page="getResults" :limit="4"></pagination></div>

          </div>

          <p style="color: red; font-style: italic;">*Note : Klik Kolom Subtotal Pembayaran,Potongan  Untuk Mengubah Nilai.</p> 
      </div><!-- COL SM 8 --> 

      <div class="col-md-3"><!-- COL SM 4 --> 
        <div class="card card-stats"><!-- CARD --> 
            <div class="card-header" data-background-color="blue">
                <i class="material-icons">shopping_cart</i>
            </div>
          <div class="card-content"> 
              <p class="category"><h4>Total Pembayaran</h4></p>
              <h3 class="card-title"><b><money class="form-subtotal" style="text-align:right;" v-model="inputPembayaranHutang.subtotal" readonly v-bind="separator" ></money></b></h3>

              <div class="row">
                <div class="col-md-10 col-xs-10" > 
                      <h4>Kas </h4> 
                      <selectize-component style="text-align:left;" v-model="inputPembayaranHutang.cara_bayar" :settings="placeholder_cara_bayar"  id="cara_bayar" name="cara_bayar" ref='cara_bayar'> 
                      <option v-for="cara_bayars, index in cara_bayar" v-bind:value="cara_bayars.id" >{{ cara_bayars.nama_kas }}</option>
                      </selectize-component>        
                </div> 
              <div class="col-md-1 col-xs-1" style="padding-left:0px;padding-top:43;">
                <div class="row" style="margin-top:-10px">
                <button class="btn btn-primary btn-icon waves-effect waves-light" v-on:click="tambahModalKas()" type="button"> <i class="material-icons" >add</i> </button>
                </div>
             </div>
              </div> 
              
          </div> 
          
          <div class="card-footer">
                     <div class="row"> 
                      <div class="col-md-6 col-xs-6"> 
                        <button type="button btn-lg"  class="btn btn-success" id="bayar" v-on:click="selesaiPembelian()" v-shortkey.push="['f2']" @shortkey="selesaiPembelian()" ><font style="font-size:20px;">Bayar(F2)</font></button>
                      </div>
                      <div class="col-md-6 col-xs-6"> 
                        <button type="submit btn-lg"  class="btn btn-danger" id="btnBatal" v-on:click="batalPembelian()" v-shortkey.push="['f3']" @shortkey="batalPembelian()" ><font style="font-size:20px;">Batal(F3)</font>  </button>
                    </div>
                </div>
            </div>
        </div>             
      </div><!-- COL SM 4 --> 
    </div><!-- ROW --> 
  </div>
</div>

    </div> 
</div> 
</template>
</template>


<script>
export default {
	data: function () {
		return {
			errors: [],
			suplier: [],
            cara_bayar:[],
			tbs_pembayaran_hutang: [],
			tbspPembayaranHutangData : {},
			url : window.location.origin+(window.location.pathname).replace("dashboard", "pembayaran-hutang"),
            url_kas : window.location.origin+(window.location.pathname).replace("dashboard", "penjualan"),
			placeholder_suplier: {
				placeholder: '--PILIH SUPLIER--'
			},
            placeholder_cara_bayar:{
                placeholder: '--PILIH KAS--'
            },
            inputTbsPembayaranHutang:{
              suplier : '',
            },
            inputPembayaranHutang:{
                subtotal: 0,
                cara_bayar: '',
            },
			pencarian: '',
			loading: true,
			seen : false
		}
	},
	mounted() {
		var app = this;
		app.dataSuplier();
        app.dataCaraBayar();
		app.getResults();
	},
	watch: {
        // whenever question changes, this function will run
        pencarian: function (newQuestion) {
        	this.getHasilPencarian();
        	this.loading = true;  
        },
        'inputTbsPembayaranHutang.suplier': function (newQuestion) {
        	this.pilihProduk();  
        },

    },
    methods: {
    	getResults(page) {
    		var app = this;	
    		if (typeof page === 'undefined') {
    			page = 1;
    		}
    		axios.get(app.url+'/view-tbs-pembayaran-hutang?page='+page)
    		.then(function (resp) {
    			app.tbs_pembayaran_hutang = resp.data.data;
    			app.tbsPembayaranHutangData = resp.data;
    			app.loading = false;
    			app.seen = true;
    		})
    		.catch(function (resp) {
    			console.log(resp);
    			app.loading = false;
    			app.seen = true;
    			alert("Tidak Dapat Memuat Pembayaran Hutang");
    		});
    	},
    	getHasilPencarian(page){
    		var app = this;
    		if (typeof page === 'undefined') {
    			page = 1;
    		}
    		axios.get(app.url+'/pencarian-tbs-item-masuk?search='+app.pencarian+'&page='+page)
    		.then(function (resp) {
    			app.tbs_pembayaran_hutang = resp.data.data;
    			app.tbsPembayaranHutangData = resp.data;
    			app.loading = false;
    			app.seen = true;
    		})
    		.catch(function (resp) {
    			console.log(resp);
    			alert("Tidak Dapat Memuat Item Masuk");
    		});
    	},    
    	dataSuplier() {
    		var app = this;
    		axios.get(app.url+'/pilih-suplier').then(function (resp) {
    			app.suplier = resp.data;
    		})
    		.catch(function (resp) {
    			alert("Tidak Bisa Memuat Produk");
    		});
    	},	
        dataCaraBayar() {
            var app = this;
            axios.get(app.url_kas+'/pilih-kas').then(function (resp) {
                    app.cara_bayar = resp.data;
                    $.each(resp.data, function (i, item) {
                        if (resp.data[i].default_kas == 1) {
                            app.inputPembayaranHutang.cara_bayar  = resp.data[i].id 
                        }
                    });
            })
            .catch(function (resp) {
                alert("Tidak Bisa Memuat Kas");
            });
        },//END FUNGSI UNTUK SELECTIZE CARABAYAR    
    	alert(pesan) {
    		this.$swal({
    			title: "Berhasil ",
    			text: pesan,
    			icon: "success",
    		});
    	},
    	deleteEntry(id, index,nama_produk) {
    		var app = this;
    		app.$swal({
    			text: "Anda Yakin Ingin Menghapus Produk "+nama_produk+ " ?",
    			buttons: true,
    			dangerMode: true,
    		})
    		.then((willDelete) => {
    			if (willDelete) {
    				this.prosesDelete(id,nama_produk);
    			} else {
    				app.$swal.close();
    			}
    		});

    	},
    	prosesDelete(id,nama_produk){
    		var app = this;
    		app.loading = true;
    		axios.post(app.url+'/proses-hapus-tbs-item-masuk/'+id)
    		.then(function (resp) {
    			app.getResults();
    			app.alert("Menghapus Produk "+nama_produk);
    			app.loading = false;
    			app.inputTbsPembayaranHutang.id_tbs = ''
    		})
    		.catch(function (resp) {

    			app.loading = false;
    			alert("Tidak dapat Menghapus Produk "+nama_produk);
    		});
    	},
    	pilihProduk() {
    		if (this.inputTbsPembayaranHutang.suplier == '') {
    			this.$swal({
    				text: "Silakan Pilih Supplier Telebih dahulu!",
    			});
    		}else{

    			var app = this;
    			var produk = app.inputTbsPembayaranHutang.produk.split("|");
    			var nama_produk = produk[1];
    			this.isiJumlahProduk(nama_produk);
    		}
    	},
    	isiJumlahProduk(nama_produk){
    		var app = this;
    		app.$swal({
    			title: nama_produk,
    			content: {
    				element: "input",
    				attributes: {
    					placeholder: "Jumlah Produk",
    					type: "number",
    				},
    			},
    			buttons: {
    				cancel: true,
    				confirm: "Submit"    				
    			}


    		}).then((value) => {
    			if (!value) throw null;
    			this.submitProdukPembayaranHutang(value);
    		});
    	},
    	submitProdukPembayaranHutang(value){

    		if (value == 0) {

    			this.$swal({
    				text: "Jumlah Produk Tidak Boleh Nol!",
    			});

    		}else{

    			var app = this;
    			var produk = app.inputTbsPembayaranHutang.produk.split("|");
    			var nama_produk = produk[1];

    			app.inputTbsPembayaranHutang.jumlah_produk = value;
    			var newinputTbsPembayaranHutang = app.inputTbsPembayaranHutang;
    			app.loading = true;
    			axios.post(app.url+'/proses-tambah-tbs-item-masuk', newinputTbsPembayaranHutang)
    			.then(function (resp) {

    				if (resp.data == 0) {

    					app.alertTbs("Produk "+nama_produk+" Sudah Ada, Silakan Pilih Produk Lain!");
    					app.loading = false;

    				}else{

    					app.getResults();
    					app.alert("Menambahkan Produk "+nama_produk);
    					app.loading = false;
    					app.inputTbsPembayaranHutang.jumlah_produk = ''

    				}
    				
    			})
    			.catch(function (resp) {    				
    				app.loading = false;
    				alert("Tidak dapat Menambahkan Produk");
    			});
    		}
    	},
    	alertTbs(pesan) {
    		this.$swal({
    			text: pesan,
    			icon: "warning",
    		});
    	}
    }
}
</script>