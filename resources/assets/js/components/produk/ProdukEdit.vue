<template>
    <div class="row">
        <div class="col-md-12">
            <ul class="breadcrumb">
                <li><router-link :to="{name: 'indexDashboard'}">Home</router-link></li>
                <li><router-link :to="{name: 'indexProduk'}">Produk</router-link></li>
                <li class="active">Edit Produk</li>
            </ul>
            <div class="card">

                <div class="card-header card-header-icon" data-background-color="purple">
                    <i class="material-icons">dns</i>
                </div>

                <div class="card-content">
                    <h4 class="card-title">Edit Produk </h4>
                    <form v-on:submit.prevent="saveForm()" class="form-horizontal"> 
                        <div class="form-group">
                            <label for="kode_barcode" class="col-md-2 control-label">Kode Barcode</label>
                            <div class="col-md-4">
                                <input class="form-control" autocomplete="off" placeholder="Kode Barcode" v-model="produk.kode_barcode" type="text" name="kode_barcode" id="kode_barcode"  autofocus="">
                                <span v-if="errors.kode_barcode" id="kode_barcode_error" class="label label-danger">{{ errors.kode_barcode[0] }}</span>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="kode_barang" class="col-md-2 control-label">Kode Produk</label>
                            <div class="col-md-4">
                                <input class="form-control" autocomplete="off" placeholder="Kode Produk" v-model="produk.kode_barang" type="text" name="kode_barang" id="kode_barang"  autofocus="">
                                <span v-if="errors.kode_barang" id="kode_barang_error" class="label label-danger">{{ errors.kode_barang[0] }}</span>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="nama_barang" class="col-md-2 control-label">Nama Produk</label>
                            <div class="col-md-4">
                                <input class="form-control" autocomplete="off" placeholder="Nama Produk" v-model="produk.nama_barang" type="text" name="nama_barang" id="nama_barang"  autofocus="">
                                <span v-if="errors.nama_barang" id="nama_barang_error" class="label label-danger">{{ errors.nama_barang[0] }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="kategori_barang_id" class="col-md-2 control-label">Kategori Produk</label>
                            <div class="col-md-4">
                                <selectize-component v-model="produk.kategori_barang_id" :settings="placeholder_kategori" id="pilih_kategori_barang_id"> 
                                    <option v-for="kategoris, index in kategori_barang_id" v-bind:value="kategoris.id" >{{ kategoris.nama_kategori_barang }}</option>
                                </selectize-component>
                                <span v-if="errors.kategori_barang_id" id="kategori_barang_id_error" class="label label-danger">{{ errors.kategori_barang_id[0] }}</span>
                            </div> 
                        </div>

                        <div class="form-group">
                            <label for="satuan_id" class="col-md-2 control-label">Satuan Produk</label>
                            <div class="col-md-4">
                                <selectize-component v-model="produk.satuan_id" :settings="placeholder_satuan" id="pilih_satuan_id"> 
                                    <option v-for="satuans, index in satuan_id" v-bind:value="satuans.id" >{{ satuans.nama_satuan }}</option>
                                </selectize-component>
                                <span v-if="errors.satuan_id" id="satuan_id_error" class="label label-danger">{{ errors.satuan_id[0] }}</span>
                            </div> 
                        </div>
                        
                        <div class="form-group">
                            <label for="harga_beli" class="col-md-2 control-label">Harga Beli</label>
                            <div class="col-md-4">
                                <input class="form-control" autocomplete="off" placeholder="Harga Beli" v-model="produk.harga_beli" type="text" name="harga_beli" id="harga_beli"  autofocus="">
                                <span v-if="errors.harga_beli" id="harga_beli_error" class="label label-danger">{{ errors.harga_beli[0] }}</span>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="harga_jual" class="col-md-2 control-label">Harga Jual</label>
                            <div class="col-md-4">
                                <input class="form-control" autocomplete="off" placeholder="Harga Jual" v-model="produk.harga_jual" type="text" name="harga_jual" id="harga_jual"  autofocus="">
                                <span v-if="errors.harga_jual" id="harga_jual_error" class="label label-danger">{{ errors.harga_jual[0] }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="hitung_stok" class="col-md-2 control-label">Hitung Stok</label>
                               <div class="togglebutton col-md-4">
                                <label>
                                    <input type="checkbox" v-model="produk.hitung_stok" name="hitung_stok" id="hitung_stok" value="1">
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="status_aktif" class="col-md-2 control-label">Bisa Dijual</label>
                               <div class="togglebutton col-md-4">
                                <label>
                                    <input type="checkbox" v-model="produk.status_aktif" name="status_aktif" id="status_aktif" value="1">
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="foto" class="col-md-2 control-label">Foto Produk</label>
                            <div class="col-md-4">
                                <div class="fileinput fileinput-new text-center" data-provides="fileinput">
                                    <div class="fileinput-new thumbnail">
                                        <img v-if="produk.foto != ''" :src="url_picture+'/'+produk.foto" /> 
                                        <img :src="url_origin+'/assets/img/image_placeholder.jpg'" alt="Foto Akan Tampil Disini" v-else>
                                    </div>
                                    <div class="fileinput-preview fileinput-exists thumbnail"></div>
                                    <div>
                                        <span class="btn btn-rose btn-round btn-file">
                                            <span class="fileinput-new">Ambil Foto</span>
                                            <span class="fileinput-exists">Ubah</span>
                                            <input class="form-control" type="file" name="foto" id="foto">
                                        </span>
                                        <a href="#pablo" class="btn btn-danger btn-round fileinput-exists" data-dismiss="fileinput"><i class="fa fa-times"></i> Batal</a>
                                    </div>
                                    <span v-if="errors.foto" id="foto_error" class="label label-danger">{{ errors.foto[0] }}</span>
                                    <a style="color: red;">Size Foto (Ukuran Max : 3MB)</a>
                                </div>
                            </div>
                        </div> 
                        
                        <div class="form-group">
                            <div class="col-md-4 col-md-offset-2">
                                <button class="btn btn-primary" id="btnSimpanProduk" type="submit"><i class="material-icons">send</i> Submit</button>
                            </div>
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
            alert("Tidak Dapat Memuat Produk")
        });
        app.dataKategori();
        app.dataSatuan();
    },  
    data: function () {
        return {

            produkId: null,
            errors: [],
            kategori_barang_id: [],
            satuan_id: [],
            url : window.location.origin+(window.location.pathname).replace("dashboard", "produk"),
            url_picture : window.location.origin+(window.location.pathname).replace("dashboard", "foto_produk"),
            url_origin : window.location.origin+(window.location.pathname).replace("dashboard", ""),
            produk: {
                foto : '',
                kode_barcode : '',
                kode_barang : '',
                nama_barang : '',
                kategori_barang_id : '',
                satuan_id : '',
                harga_beli : '',
                harga_jual : '',
                hitung_stok : 'true',
                status_aktif : 'true'
            },
            message : '',
            placeholder_kategori: {
                placeholder: '--PILIH KATEGORI--'
            }, 
            placeholder_satuan: {
                placeholder: '--PILIH SATUAN--'
            }, 
        }
    },
    methods: {        
        saveForm() {
            var app = this;
            if (document.getElementById('foto').files[0] != undefined) {
                var newProduk = app.inputData();
            }else{
                var newProduk = app.produk;
            }
            app.loading();

            axios.patch(app.url+'/' + app.produkId, newProduk)
            .then(function (resp) {
                app.message = 'Berhasil Mengubah Produk '+app.produk.nama_barang;
                app.alert(app.message);
                app.kosongkanData();
                app.$router.replace('/produk/');
                app.$swal.close();
            })
            .catch(function (resp) {
                app.errors = resp.response.data.errors;
                app.$swal.close();
            });
        },
        alert(pesan) {
            this.$swal({
                title: "Berhasil !",
                text: pesan,
                icon: "success",
            });
        },
        dataKategori() {
            var app = this;
            axios.get(app.url+'/pilih-kategori').then(function (resp) {
                app.kategori_barang_id = resp.data;
            })
            .catch(function (resp) {
                alert("Tidak Bisa Memuat Kategori");
            });
        },
        dataSatuan() {
            var app = this;
            axios.get(app.url+'/pilih-satuan').then(function (resp) {
                app.satuan_id = resp.data;
            })
            .catch(function (resp) {
                alert("Tidak Bisa Memuat Satuan");
            });
        },
        loading(){
            this.$swal({
                title: "Sedang Memproses Data ...",
                text: "Harap Tunggu!",
                icon: "info",
                buttons:  false,
                closeOnClickOutside: false,
                closeOnEsc: false

            });
        },
        inputData(){
            var app = this;

            let newProduk = new FormData();
            if (document.getElementById('foto').files[0] != undefined) {
                newProduk.append('foto', document.getElementById('foto').files[0]);
            }           
            newProduk.append('kode_barcode', app.produk.kode_barcode);
            newProduk.append('kode_barcode', app.produk.kode_barcode);
            newProduk.append('nama_barang', app.produk.nama_barang);
            newProduk.append('kategori_barang_id', app.produk.kategori_barang_id);
            newProduk.append('satuan_id', app.produk.satuan_id);
            newProduk.append('harga_beli', app.produk.harga_beli);
            newProduk.append('harga_jual', app.produk.harga_jual);
            newProduk.append('hitung_stok', app.produk.hitung_stok);
            newProduk.append('status_aktif', app.produk.status_aktif);

            return newProduk;
        },
        kosongkanData(){
            var app = this;

                app.produk.foto = '';
                app.produk.kode_barcode = '';
                app.produk.kode_barang = '';
                app.produk.nama_barang = '';
                app.produk.kategori_barang_id = '';
                app.produk.satuan_id = '';
                app.produk.harga_beli = '';
                app.produk.harga_jual = '';
                app.produk.hitung_stok = 'true';
                app.produk.status_aktif = 'true';
                app.errors = '';
        }
    }
}
</script>