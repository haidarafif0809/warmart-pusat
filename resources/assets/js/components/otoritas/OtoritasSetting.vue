<style scoped>
.pencarian {
  color: red; 
  float: right;
}
</style>
<template>

  <div class="row" >
    <div class="col-md-12">

      <ul class="breadcrumb">
       <li><router-link :to="{name: 'indexDashboard'}">Home</router-link></li>
       <li style="color: purple">Master Data</li>
       <li style="color: purple"><router-link :to="{name: 'indexOtoritas'}">Otoritas</router-link></li>
       <li class="active">Setting Otoritas {{nama_otoritas}}</li>
     </ul>

     <div class="card">
      <div class="card-header card-header-icon" data-background-color="purple">
        <i class="material-icons">settings</i>
      </div>

      <div class="card-content">
        <h4 class="card-title">Setting Otoritas <b>{{nama_otoritas}}</b>
        </h4>

        <!-- OTORITAS laporan -->
        <b v-if="seen">Pilih Semua </b>
        <div class="checkbox" v-if="seen">
          <label>
            <input type="checkbox" name="setting_user" v-model="pilih_semua" v-bind:value="1" v-on:change="pilihSemua"> Pilih Semua
          </label>
        </div>            
        <!--END OTORITAS laporan -->
        
        <div class="row" v-if="seen">
          <div class="col-sm-2">
            <!-- OTORITAS laporan -->
            <b>Laporan</b>
            <div class="checkbox" v-for="permission_laporans, index in permission_laporan">
              <label>
                <input type="checkbox" name="setting_user" v-bind:value="permission_laporans.id" v-model="setting_otoritas.laporan"> {{permission_laporans.display_name}}
              </label>
            </div>            
            <!--END OTORITAS laporan -->
          </div>

          <div class="col-sm-2"> 
            <!-- kas_masuk -->
            <b>Kas Masuk</b>
            <div class="checkbox" v-for="permissions_kas_masuk, index in permission_kas_masuk">
              <label>
                <input type="checkbox" name="setting_kas_masuk" v-bind:value="permissions_kas_masuk.id" v-model="setting_otoritas.kas_masuk"> {{permissions_kas_masuk.display_name}}
              </label>
            </div>
            <!--end kas_masuk -->
            <!-- pesanan -->
            <b>Pesanan</b>
            <div class="checkbox" v-for="permissions_pesanan, index in permission_pesanan">
              <label>
                <input type="checkbox" name="setting_pesanan" v-bind:value="permissions_pesanan.id" v-model="setting_otoritas.pesanan"> {{permissions_pesanan.display_name}}
              </label>
            </div>
            <!--end Pesanan -->

            <!-- Kelompok Produk  -->
            <b>Kelompok Produk</b>
            <div class="checkbox" v-for="permission_kelompok_produk, index in permission_kelompok_produk">
              <label>
                <input type="checkbox" name="setting_kelompok_produk" v-bind:value="permission_kelompok_produk.id" v-model="setting_otoritas.kelompok_produk"> {{permission_kelompok_produk.display_name}}
              </label>
            </div>
            <!-- Kelompok Produk  -->
          </div>          

          <div class="col-sm-2"> 
            <!-- Bank  -->
            <b>Bank</b>
            <div class="checkbox" v-for="permission_bank, index in permission_bank">
              <label>
                <input type="checkbox" name="setting_bank" v-bind:value="permission_bank.id" v-model="setting_otoritas.bank"> {{permission_bank.display_name}}
              </label>
            </div>
            <!-- Bank  -->
            <!-- kas_keluar  -->
            <b>Kas Keluar</b>
            <div class="checkbox" v-for="permission_kas_keluar, index in permission_kas_keluar">
              <label>
                <input type="checkbox" name="setting_kas_keluar" v-bind:value="permission_kas_keluar.id" v-model="setting_otoritas.kas_keluar"> {{permission_kas_keluar.display_name}}
              </label>
            </div>
            <!-- kas_keluar  -->
            <!-- pembayaran_piutang  -->
            <b>Pembayaran Piutang</b>
            <div class="checkbox" v-for="permission_pembayaran_piutang, index in permission_pembayaran_piutang">
              <label>
                <input type="checkbox" name="setting_pembayaran_piutang" v-bind:value="permission_pembayaran_piutang.id" v-model="setting_otoritas.pembayaran_piutang"> {{permission_pembayaran_piutang.display_name}}
              </label>
            </div>
            <!-- pembayaran_piutang  -->
          </div>

          <div class="col-sm-2"> 
            <!-- Customer  -->
            <b>Customer</b>
            <div class="checkbox" v-for="permission_customer, index in permission_customer">
              <label>
                <input type="checkbox" name="setting_customer" v-bind:value="permission_customer.id" v-model="setting_otoritas.customer"> {{permission_customer.display_name}}
              </label>
            </div>
            <!-- Customer  -->
            <!-- kas_mutasi  -->
            <b>Kas Mutasi</b>
            <div class="checkbox" v-for="permission_kas_mutasi, index in permission_kas_mutasi">
              <label>
                <input type="checkbox" name="setting_kas_mutasi" v-bind:value="permission_kas_mutasi.id" v-model="setting_otoritas.kas_mutasi"> {{permission_kas_mutasi.display_name}}
              </label>
            </div>
            <!-- kas_mutasi  -->
            <!-- pembayaran_hutang  -->
            <b>Pembayaran Hutang</b>
            <div class="checkbox" v-for="permission_pembayaran_hutang, index in permission_pembayaran_hutang">
              <label>
                <input type="checkbox" name="setting_pembayaran_hutang" v-bind:value="permission_pembayaran_hutang.id" v-model="setting_otoritas.pembayaran_hutang"> {{permission_pembayaran_hutang.display_name}}
              </label>
            </div>
            <!-- pembayaran_hutang  -->
          </div>

          <div class="col-sm-2"> 
            <!-- Item Masuk  -->
            <b>Item Masuk</b>
            <div class="checkbox" v-for="permission_item_masuk, index in permission_item_masuk">
              <label>
                <input type="checkbox" name="setting_item_masuk" v-bind:value="permission_item_masuk.id" v-model="setting_otoritas.item_masuk"> {{permission_item_masuk.display_name}}
              </label>
            </div>
            <!-- Item Masuk  -->
            <!-- supplier -->
            <b>Supplier</b>
            <div class="checkbox" v-for="permissions_supplier, index in permission_supplier">
              <label>
                <input type="checkbox" name="setting_supplier" v-bind:value="permissions_supplier.id" v-model="setting_otoritas.supplier"> {{permissions_supplier.display_name}}
              </label>
            </div>
            <!--end supplier -->

            <!-- pembelian  -->
            <b>Pembelian</b>
            <div class="checkbox" v-for="permission_pembelian, index in permission_pembelian">
              <label>
                <input type="checkbox" name="setting_pembelian" v-bind:value="permission_pembelian.id" v-model="setting_otoritas.pembelian"> {{permission_pembelian.display_name}}
              </label>
            </div>
            <!-- Pembelian  -->
            <!-- Master Data -->
            <b>Master Data</b>
            <div class="checkbox" v-for="permissions_master_data, index in permission_master_data">
              <label>
                <input type="checkbox" name="setting_otoritas" v-bind:value="permissions_master_data.id" v-model="setting_otoritas.master_data"> {{permissions_master_data.display_name}}
              </label>
            </div>
            <!-- Master Data -->

          </div>

          <div class="col-sm-2"> 
            <!-- Item Keluar  -->
            <b>Item Keluar</b>
            <div class="checkbox" v-for="permission_item_keluar, index in permission_item_keluar">
              <label>
                <input type="checkbox" name="setting_item_keluar" v-bind:value="permission_item_keluar.id" v-model="setting_otoritas.item_keluar"> {{permission_item_keluar.display_name}}
              </label>
            </div>
            <!-- Item Keluar  -->
            <!-- Penjualan  -->
            <b>Penjualan</b>
            <div class="checkbox" v-for="permission_penjualan, index in permission_penjualan">
              <label>
                <input type="checkbox" name="setting_penjualan" v-bind:value="permission_penjualan.id" v-model="setting_otoritas.penjualan"> {{permission_penjualan.display_name}}
              </label>
            </div>
            <!-- Penjualan  -->
            <!-- Kategori Kas  -->
            <b>Kategori Kas</b>
            <div class="checkbox" v-for="permission_kategori_kas, index in permission_kategori_kas">
              <label>
                <input type="checkbox" name="setting_kategori_kas" v-bind:value="permission_kategori_kas.id" v-model="setting_otoritas.kategori_kas"> {{permission_kategori_kas.display_name}}
              </label>
            </div>
            <!-- Kategori Kas  -->
            <!-- laporan_persediaan -->
            <b>Laporan Persediaan</b>
            <div class="checkbox" v-for="permissions_laporan_persediaan, index in permission_laporan_persediaan">
              <label>
                <input type="checkbox" name="setting_laporan_persediaan" v-bind:value="permissions_laporan_persediaan.id" v-model="setting_otoritas.laporan_persediaan"> {{permissions_laporan_persediaan.display_name}}
              </label>
            </div>
            <!--end laporan persediaan -->
          </div>

        </div>

        <div class="row" v-if="seen">
          <div class="col-sm-2">
            <!-- produk  -->
            <b>Produk</b>
            <div class="checkbox" v-for="permission_produk, index in permission_produk">
              <label>
                <input type="checkbox" name="setting_produk" v-bind:value="permission_produk.id" v-model="setting_otoritas.produk"> {{permission_produk.display_name}}
              </label>
            </div>
            <!-- produk  -->

            <!-- setting  -->
            <b>Setting</b>
            <div class="checkbox" v-for="permission_setting, index in permission_setting">
              <label>
                <input type="checkbox" name="setting_setting" v-bind:value="permission_setting.id" v-model="setting_otoritas.setting"> {{permission_setting.display_name}}
              </label>
            </div>
            <!-- setting  -->
          </div>
          <div class="col-sm-2">            
            <!-- satuan  -->
            <b>Satuan</b>
            <div class="checkbox" v-for="permission_satuan, index in permission_satuan">
              <label>
                <input type="checkbox" name="setting_satuan" v-bind:value="permission_satuan.id" v-model="setting_otoritas.satuan"> {{permission_satuan.display_name}}
              </label>
            </div>
            <!-- satuan  -->
          </div>
          <div class="col-sm-2">     

            <!-- OTORITAS KAS -->
            <b>Kas</b>
            <div class="checkbox" v-for="permission_kass, index in permission_kas">
              <label>
                <input type="checkbox" name="setting_kas" v-bind:value="permission_kass.id" v-model="setting_otoritas.kas"> {{permission_kass.display_name}}
              </label>
            </div>            
            <!--END OTORITAS KAS -->
          </div>    

          <div class="col-sm-2">            
            <!-- otoritas -->
            <b>Otoritas</b>
            <div class="checkbox" v-for="permissions_otoritas, index in permission_otoritas">
              <label>
                <input type="checkbox" name="setting_otoritas" v-bind:value="permissions_otoritas.id" v-model="setting_otoritas.otoritas"> {{permissions_otoritas.display_name}}
              </label>
            </div>
            <!--end otoritas -->
          </div>

          <div class="col-sm-2">
            <!-- Stok Opname  -->
            <b>Stok Opname</b>
            <div class="checkbox" v-for="permission_stok_opname, index in permission_stok_opname">
              <label>
                <input type="checkbox" name="setting_stok_opname" v-bind:value="permission_stok_opname.id" v-model="setting_otoritas.stok_opname"> {{permission_stok_opname.display_name}}
              </label>
            </div>
            <!-- Stok Opname  -->

          </div>

          <div class="col-sm-2">
            <!-- OTORITAS USER -->
            <b>User</b>
            <div class="checkbox" v-for="permission_users, index in permission_user">
              <label>
                <input type="checkbox" name="setting_user" v-bind:value="permission_users.id" v-model="setting_otoritas.user"> {{permission_users.display_name}}
              </label>
            </div>            
            <!--END OTORITAS USER -->
          </div>
        </div>


        <center>     <vue-simple-spinner v-if="loading"></vue-simple-spinner></center>
        <button v-if="seen" class="btn btn-primary" id="btnSimpanOtoritas" type="submit" v-on:click="submitOtoritas()"><i class="material-icons">send</i> Simpan</button>

      </div>
    </div>

  </div><!--/END-COL-SM-10-->
</div><!--/END ROW-->

</template>

<script>
export default {
  data: function () {
    return {
     permission_user: [],
     permission_otoritas: [],
     permission_master_data: [],
     permission_bank: [],
     permission_customer: [],
     permission_master_data: [],
     permission_item_masuk: [],
     permission_item_keluar: [],
     permission_kas: [],
     permission_kas_masuk: [],
     permission_kas_keluar: [],
     permission_kas_mutasi: [],
     permission_kategori_kas: [],
     permission_kelompok_produk: [],
     permission_laporan: [],
     permission_laporan_persediaan: [],
     permission_pembayaran_piutang: [],
     permission_pembayaran_hutang: [],
     permission_pembelian: [],
     permission_penjualan: [],
     permission_pesanan: [],
     permission_produk: [],
     permission_satuan: [],
     permission_setting: [],
     permission_stok_opname: [],
     permission_supplier: [],
     setting_otoritas : {
      user : [],
      otoritas : [],
      bank : [],
      customer : [],
      master_data : [],
      item_masuk : [],
      item_keluar : [],
      kas : [],
      kas_masuk : [],
      kas_keluar : [],
      kas_mutasi : [],
      kategori_kas : [],
      kelompok_produk : [],
      laporan : [],
      laporan_persediaan : [],
      pembayaran_piutang : [],
      pembayaran_hutang : [],
      pembelian : [],
      penjualan : [],
      pesanan : [],
      produk : [],
      satuan : [],
      setting : [],
      stok_opname : [],
      supplier : [],
    },
    nama_otoritas : '',
    pilih_semua : false,
    url : window.location.origin+(window.location.pathname).replace("dashboard", "otoritas"),
    pencarian: '',
    loading: true,
    seen: false
  }
},
mounted() {
  var app = this;
  app.getResults();
},
watch: {
  pencarian: function (newQuestion) {
    this.getHasilPencarian()  
  }
},
methods: {
  getResults() {
    var app = this;
    var id = app.$route.params.id;
    axios.get(app.url+'/'+id+'/edit')
    .then(function (resp) {

      app.permission_user = resp.data.permission_user
      app.permission_otoritas = resp.data.permission_otoritas
      app.permission_master_data = resp.data.permission_master_data
      app.permission_bank = resp.data.permission_bank
      app.permission_customer = resp.data.permission_customer
      app.permission_master_data = resp.data.permission_master_data
      app.permission_item_masuk = resp.data.permission_item_masuk
      app.permission_item_keluar = resp.data.permission_item_keluar
      app.permission_kas = resp.data.permission_kas
      app.permission_kas_masuk = resp.data.permission_kas_masuk
      app.permission_kas_keluar = resp.data.permission_kas_keluar
      app.permission_kas_mutasi = resp.data.permission_kas_mutasi
      app.permission_kategori_kas = resp.data.permission_kategori_kas
      app.permission_kelompok_produk = resp.data.permission_kelompok_produk
      app.permission_laporan = resp.data.permission_laporan
      app.permission_laporan_persediaan = resp.data.permission_laporan_persediaan
      app.permission_pembayaran_piutang = resp.data.permission_pembayaran_piutang
      app.permission_pembayaran_hutang = resp.data.permission_pembayaran_hutang
      app.permission_pembelian = resp.data.permission_pembelian
      app.permission_penjualan = resp.data.permission_penjualan
      app.permission_pesanan = resp.data.permission_pesanan
      app.permission_produk = resp.data.permission_produk
      app.permission_satuan = resp.data.permission_satuan
      app.permission_setting = resp.data.permission_setting
      app.permission_stok_opname = resp.data.permission_stok_opname
      app.permission_supplier = resp.data.permission_supplier


      app.nama_otoritas = resp.data.otoritas.display_name

      app.setting_otoritas.user = resp.data.data_permission_user
      app.setting_otoritas.otoritas = resp.data.data_permission_otoritas
      app.setting_otoritas.bank = resp.data.data_permission_bank
      app.setting_otoritas.customer = resp.data.data_permission_customer
      app.setting_otoritas.master_data = resp.data.data_permission_master_data
      app.setting_otoritas.item_masuk = resp.data.data_permission_item_masuk
      app.setting_otoritas.item_keluar = resp.data.data_permission_item_keluar
      app.setting_otoritas.kas = resp.data.data_permission_kas
      app.setting_otoritas.kas_masuk = resp.data.data_permission_kas_masuk
      app.setting_otoritas.kas_keluar = resp.data.data_permission_kas_keluar
      app.setting_otoritas.kas_mutasi = resp.data.data_permission_kas_mutasi
      app.setting_otoritas.kategori_kas = resp.data.data_permission_kategori_kas
      app.setting_otoritas.kelompok_produk = resp.data.data_permission_kelompok_produk
      app.setting_otoritas.laporan = resp.data.data_permission_laporan
      app.setting_otoritas.laporan_persediaan = resp.data.data_permission_laporan_persediaan
      app.setting_otoritas.pembayaran_piutang = resp.data.data_permission_pembayaran_piutang
      app.setting_otoritas.pembayaran_hutang = resp.data.data_permission_pembayaran_hutang
      app.setting_otoritas.pembelian = resp.data.data_permission_pembelian
      app.setting_otoritas.penjualan = resp.data.data_permission_penjualan
      app.setting_otoritas.pesanan = resp.data.data_permission_pesanan
      app.setting_otoritas.produk = resp.data.data_permission_produk
      app.setting_otoritas.satuan = resp.data.data_permission_satuan
      app.setting_otoritas.setting = resp.data.data_permission_setting
      app.setting_otoritas.stok_opname = resp.data.data_permission_stok_opname
      app.setting_otoritas.supplier = resp.data.data_permission_supplier

      app.loading = false;
      app.seen = true;
    })
    .catch(function (resp) {
      console.log(resp);
      app.loading = false;
      app.seen = true;
      alert("Tidak Bisa Memuat Otoritas");
    });
  },
  submitOtoritas(){
    var app = this
    app.$swal({
      text: "Anda Yakin Ingin Menyimpan Otoritas Ini ?",
      buttons: {
        cancel: true,
        confirm: "OK"                   
      },

    }).then((value) => {

      if (!value) throw null;

      this.prosesSimpanOtoritas();

    });
  },
  prosesSimpanOtoritas(){
    var app = this
    app.loading = true
    var id = app.$route.params.id
    axios.put(app.url+"/permission/"+id,app.setting_otoritas)
    .then(function (resp) {
      app.alert("Menyimpan Otoritas "+app.nama_otoritas)
      app.loading = false
      app.$router.replace('/otoritas')
    })
    .catch(function (resp) {
      app.loading = false
      alert("Tidak Bisa Menyimpan Otoritas");
    });
  },
  pilihSemua(){

    var app = this
    var pilih_semua = app.pilih_semua
    app.removeValue()
    if (pilih_semua == true) {

        // OTORITAS LAPORAN
        $.each(app.permission_laporan, function (i, item) { 
          app.setting_otoritas.laporan.push(app.permission_laporan[i].id)     
        });

        // OTORITAS USER
        $.each(app.permission_user, function (i, item) {
          app.setting_otoritas.user.push(app.permission_user[i].id)
        });

        // OTORITAS OTORITAS
        $.each(app.permission_otoritas, function (i, item) {
          app.setting_otoritas.otoritas.push(app.permission_otoritas[i].id)
        });

        // OTORITAS BANK
        $.each(app.permission_bank, function (i, item) { 
          app.setting_otoritas.bank.push(app.permission_bank[i].id)     
        });

        // OTORITAS CUSTOMER
        $.each(app.permission_customer, function (i, item) { 
          app.setting_otoritas.customer.push(app.permission_customer[i].id)     
        });

        // OTORITAS MASTER DATA
        $.each(app.permission_master_data, function (i, item) { 
          app.setting_otoritas.master_data.push(app.permission_master_data[i].id)     
        });

        // OTORITAS ITEM MASUK
        $.each(app.permission_item_masuk, function (i, item) { 
          app.setting_otoritas.item_masuk.push(app.permission_item_masuk[i].id)     
        });

        // OTORITAS ITEM KELUAR
        $.each(app.permission_item_keluar, function (i, item) { 
          app.setting_otoritas.item_keluar.push(app.permission_item_keluar[i].id)     
        });

        // OTORITAS KAS
        $.each(app.permission_kas, function (i, item) { 
          app.setting_otoritas.kas.push(app.permission_kas[i].id)     
        });

        // OTORITAS KAS MASUK
        $.each(app.permission_kas_masuk, function (i, item) { 
          app.setting_otoritas.kas_masuk.push(app.permission_kas_masuk[i].id)     
        });

        // OTORITAS KAS KELUAR
        $.each(app.permission_kas_keluar, function (i, item) { 
          app.setting_otoritas.kas_keluar.push(app.permission_kas_keluar[i].id)     
        });

        // OTORITAS KAS MUTASI
        $.each(app.permission_kas_mutasi, function (i, item) { 
          app.setting_otoritas.kas_mutasi.push(app.permission_kas_mutasi[i].id)     
        });

        // OTORITAS KATEGORI KAS
        $.each(app.permission_kategori_kas, function (i, item) { 
          app.setting_otoritas.kategori_kas.push(app.permission_kategori_kas[i].id)     
        });

        // OTORITAS KELOMPOK PRODUK
        $.each(app.permission_kelompok_produk, function (i, item) { 
          app.setting_otoritas.kelompok_produk.push(app.permission_kelompok_produk[i].id)     
        });

        // OTORITAS LAPORAN PERSEDIAAN
        $.each(app.permission_laporan_persediaan, function (i, item) { 
          app.setting_otoritas.laporan_persediaan.push(app.permission_laporan_persediaan[i].id)     
        });

        // OTORITAS PEMBAYARAN PIUTANG
        $.each(app.permission_pembayaran_piutang, function (i, item) { 
          app.setting_otoritas.pembayaran_piutang.push(app.permission_pembayaran_piutang[i].id)     
        });

        // OTORITAS PEMBAYARAN HUTANG
        $.each(app.permission_pembayaran_hutang, function (i, item) { 
          app.setting_otoritas.pembayaran_hutang.push(app.permission_pembayaran_hutang[i].id)     
        });

        // OTORITAS PEMBELIAN
        $.each(app.permission_pembelian, function (i, item) { 
          app.setting_otoritas.pembelian.push(app.permission_pembelian[i].id)     
        });

        // OTORITAS PENJUALAN
        $.each(app.permission_penjualan, function (i, item) { 
          app.setting_otoritas.penjualan.push(app.permission_penjualan[i].id)     
        });

        // OTORITAS PESANAN
        $.each(app.permission_pesanan, function (i, item) { 
          app.setting_otoritas.pesanan.push(app.permission_pesanan[i].id)     
        });

        // OTORITAS PRODUK
        $.each(app.permission_produk, function (i, item) { 
          app.setting_otoritas.produk.push(app.permission_produk[i].id)     
        });

        // OTORITAS SATUAN
        $.each(app.permission_satuan, function (i, item) { 
          app.setting_otoritas.satuan.push(app.permission_satuan[i].id)     
        });

        // OTORITAS SETTING
        $.each(app.permission_setting, function (i, item) { 
          app.setting_otoritas.setting.push(app.permission_setting[i].id)
        });

        // OTORITAS STOK OPNAME
        $.each(app.permission_stok_opname, function (i, item) { 
          app.setting_otoritas.stok_opname.push(app.permission_stok_opname[i].id)
        });

        // OTORITAS SUPPLIER
        $.each(app.permission_supplier, function (i, item) { 
          app.setting_otoritas.supplier.push(app.permission_supplier[i].id)
        });


      }else{
        // REMOVE VALUE ARRAY SETTING OTORITAS
        app.removeValue()
      }
      console.log(app.setting_otoritas)


    },
    removeValue(){
      var app = this
        // REMOVE VALUE ARRAY SETTING OTORITAS
        app.setting_otoritas.laporan.splice(0)
        app.setting_otoritas.user.splice(0)
        app.setting_otoritas.otoritas.splice(0)
        app.setting_otoritas.bank.splice(0)
        app.setting_otoritas.customer.splice(0)
        app.setting_otoritas.master_data.splice(0)
        app.setting_otoritas.item_masuk.splice(0)
        app.setting_otoritas.item_keluar.splice(0)
        app.setting_otoritas.kas.splice(0)
        app.setting_otoritas.kas_masuk.splice(0)
        app.setting_otoritas.kas_keluar.splice(0)
        app.setting_otoritas.kas_mutasi.splice(0)
        app.setting_otoritas.kategori_kas.splice(0)
        app.setting_otoritas.kelompok_produk.splice(0)
        app.setting_otoritas.laporan_persediaan.splice(0)
        app.setting_otoritas.pembayaran_piutang.splice(0)
        app.setting_otoritas.pembayaran_hutang.splice(0)
        app.setting_otoritas.pembelian.splice(0)
        app.setting_otoritas.penjualan.splice(0)
        app.setting_otoritas.pesanan.splice(0)
        app.setting_otoritas.produk.splice(0)
        app.setting_otoritas.satuan.splice(0)
        app.setting_otoritas.setting.splice(0)
        app.setting_otoritas.stok_opname.splice(0)
        app.setting_otoritas.supplier.splice(0)
      },
      alert(pesan) { 
        this.$swal({ 
          title: "Berhasil!", 
          text: pesan, 
          icon: "success", 
          buttons: false,
          timer: 1000,
        }); 
      }, 
    }
  }
  </script>
