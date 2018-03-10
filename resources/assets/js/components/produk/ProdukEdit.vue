    <style scoped>

        .btn-icon{
            border-radius: 1px solid;
            padding: 10px 20px;
        }

        .btn-konversi{
            border-radius: 1px solid;
            padding: 10px 10px;
        }

    </style>
    <template>
        <div class="row">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li><router-link :to="{name: 'indexDashboard'}">Home</router-link></li>
                    <li><router-link :to="{name: 'indexProduk'}">Produk</router-link></li>
                    <li class="active">Edit Produk</li>
                </ul>
                <div class="card">


                    <!--MODAL kategori produk BARU -->
                    <div class="modal" id="modal_kategori" role="dialog" data-backdrop="">
                        <div class="modal-dialog">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close"  v-on:click="tutupModal()" v-shortkey.push="['esc']" @shortkey="tutupModal()"> <i class="material-icons">close</i></button>
                                    <h4 class="modal-title">
                                        <div class="alert-icon">
                                            <b>Silahkan Isi Kategori Produk !</b>
                                        </div>
                                    </h4>
                                </div>
                                <div class="modal-body">
                                    <form v-on:submit.prevent="saveFormKategori()" class="form-horizontal">
                                        <div class="form-group">
                                            <label for="nama_kelompok" class="col-md-2 control-label">Nama</label>
                                            <div class="col-md-10">
                                                <input class="form-control" autocomplete="off" placeholder="Nama" v-model="kelompok_produk.nama_kelompok" type="text" name="nama_kelompok" id="nama_kelompok"  autofocus="">
                                                <span v-if="errors.nama_kelompok" id="nama_kelompok_error" class="label label-danger">{{ errors.nama_kelompok[0] }}</span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-4">
                                                <input  class="form-control" autocomplete="off" placeholder="Icon"  v-model="kelompok_produk.icon_kelompok"  type="hidden" name="icon_kelompok" id="icon_kelompok" >
                                                <span v-if="errors.icon_kelompok" id="icon_kelompok_error" class="label label-danger">{{ errors.icon_kelompok[0] }}</span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-4 col-md-offset-2">
                                                <button class="btn btn-primary" id="btnSimpanKategoriTransaksi" type="submit"><i class="material-icons">send</i> Submit</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer"></div> 
                            </div>       
                        </div> 
                    </div> 


                    <!--MODAL Satuan BARU -->
                    <div class="modal" id="modal_satuan" role="dialog" data-backdrop="">
                        <div class="modal-dialog">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close"  v-on:click="tutupModal()" v-shortkey.push="['esc']" @shortkey="tutupModal()"> <i class="material-icons">close</i></button>
                                    <h4 class="modal-title">
                                        <div class="alert-icon">
                                            <b>Silahkan Isi Satuan !</b>
                                        </div>
                                    </h4>
                                </div>
                                <div class="modal-body">
                                    <form v-on:submit.prevent="saveFormSatuan()" class="form-horizontal">
                                        <div class="form-group">
                                            <label for="name" class="col-md-2 control-label">Nama Satuan</label>
                                            <div class="col-md-4">
                                                <input class="form-control" required autocomplete="off" placeholder="Nama Satuan" type="text" v-model="satuan.nama_satuan" name="nama_satuan"  autofocus="">
                                                <span v-if="errors.nama_satuan" id="nama_satuan_error" class="label label-danger">{{ errors.nama_satuan[0] }}</span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-4 col-md-offset-2">
                                                <button class="btn btn-primary" id="btnSimpanKategoriTransaksi" type="submit"><i class="material-icons">send</i> Submit</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer"></div> 
                            </div>       
                        </div> 
                    </div> 


                    <div class="card-header card-header-icon" data-background-color="purple">
                        <i class="material-icons">dns</i>
                    </div>

                    <div class="card-content">
                        <h4 class="card-title">Edit Produk </h4>
                        <form v-on:submit.prevent="saveForm()" class="form-horizontal"> 
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="kode_barcode" class="col-md-2 control-label">Kode Barcode</label>
                                        <div class="col-md-10">
                                            <input class="form-control" ref="kode_barcode" autocomplete="off" placeholder="Kode Barcode  (Jika Ada)" v-model="produk.kode_barcode" type="text" name="kode_barcode" id="kode_barcode"  autofocus="">
                                            <span v-if="errors.kode_barcode" id="kode_barcode_error" class="label label-danger">{{ errors.kode_barcode[0] }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="kode_barang" class="col-md-2 control-label">Kode Produk</label>
                                        <div class="col-md-10">
                                            <input class="form-control" autocomplete="off" placeholder="Kode Produk" v-model="produk.kode_barang" type="text" name="kode_barang" id="kode_barang" >
                                            <span v-if="errors.kode_barang" id="kode_barang_error" class="label label-danger">{{ errors.kode_barang[0] }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="nama_barang" class="col-md-2 control-label">Nama Produk</label>
                                        <div class="col-md-10">
                                            <input class="form-control" autocomplete="off" placeholder="Nama Produk" v-model="produk.nama_barang" type="text" name="nama_barang" id="nama_barang" >
                                            <span v-if="errors.nama_barang" id="nama_barang_error" class="label label-danger">{{ errors.nama_barang[0] }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="kategori_barang_id" class="col-md-2 control-label">Kategori Produk</label>
                                        <div class="col-md-9">
                                            <selectize-component v-model="produk.kategori_barang_id" :settings="placeholder_kategori" id="pilih_kategori_barang_id"> 
                                                <option v-for="kategoris, index in kategori_barang_id" v-bind:value="kategoris.id" >{{ kategoris.nama_kategori_barang }}</option>
                                            </selectize-component>
                                            <span v-if="errors.kategori_barang_id" id="kategori_barang_id_error" class="label label-danger">{{ errors.kategori_barang_id[0] }}</span>
                                        </div> 
                                        <div class="col-md-1" style="padding-left:0px">
                                            <div class="row" style="margin-top:-10px">
                                                <button class="btn btn-primary btn-icon waves-effect waves-light" v-on:click="tambahKategori()" type="button"> <i class="material-icons" >add</i> </button>
                                            </div>
                                        </div> 
                                    </div>

                                    <div class="form-group">
                                        <label for="satuan_id" class="col-md-2 control-label">Satuan Produk</label>
                                        <div class="col-md-7">
                                            <selectize-component v-model="produk.satuan_id" :settings="placeholder_satuan" id="pilih_satuan_id"> 
                                                <option v-for="satuans, index in satuan_id" v-bind:value="satuans.satuan" >{{ satuans.nama_satuan }}</option>
                                            </selectize-component>
                                            <span v-if="errors.satuan_id" id="satuan_id_error" class="label label-danger">{{ errors.satuan_id[0] }}</span>
                                        </div> 
                                        <div class="col-md-1" style="padding-left:0px">
                                            <div class="row" style="margin-top:-10px">
                                                <button class="btn btn-primary btn-icon waves-effect waves-light" v-on:click="tambahSatuan()" type="button"> <i class="material-icons" >add</i> </button>
                                            </div>
                                        </div> 
                                        <div class="col-md-2" style="padding-left:0px">
                                            <div class="row" style="margin-top:-10px">
                                                <button class="btn btn-primary btn-konversi waves-effect waves-light" v-on:click="konversiSatuan()" type="button" id="btnSatuanKonversi" style="display: none"> 
                                                    Konversi Satuan
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group" style="padding-bottom: 0px;">
                                        <ul v-for="(input, index) in inputSatuanKonversi" style="padding-left:0px">
                                            <div class="col-md-2"></div>

                                            <div class="col-md-3">
                                                <selectize-component v-model="input.id_satuan" :settings="placeholder_satuan" id="satuan-konversi" ref="satuan_barang"> 
                                                    <option v-for="satuans, index in satuan_id" v-bind:value="satuans.satuan" >{{ satuans.nama_satuan }}</option>
                                                </selectize-component>
                                            </div>

                                            <div class="col-md-2">
                                                <input class="form-control" autocomplete="off" placeholder="Qty" v-model="input.jumlah_produk" type="text" name="jumlah_konversi" id="jumlah_konversi" ref="jumlah_konversi" autofocus="">
                                            </div>  

                                            <div class="col-md-2">
                                                <input class="form-control" autocomplete="off" placeholder="Satuan Dasar" v-model="input.nama_satuan" type="text" name="satuan_dasar" id="satuan_dasar" ref="satuan_dasar" autofocus="" readonly="">
                                            </div>

                                            <div class="col-md-2">
                                                <money class="form-control" autocomplete="off" placeholder="Harga Jual" v-model="input.harga_jual" type="text" name="harga_jual" id="harga_jual"  ref="harga_jual" autofocus="" v-bind="separator">
                                                </money>
                                            </div>

                                            <div class="col-md-1" style="padding-left:0px">
                                                <div class="row" style="margin-top:-10px">
                                                    <button class="btn btn-primary btn-icon waves-effect waves-light" v-on:click="hapusKonversiSatuan(index)" type="button" id="btnSatuan"> <i class="material-icons" >delete</i> </button>
                                                </div>
                                            </div>

                                        </ul>
                                    </div>

                                    <div class="form-group">
                                        <label for="harga_beli" class="col-md-2 control-label">Harga Beli</label>
                                        <div class="col-md-10">
                                            <money class="form-control" autocomplete="off" placeholder="Harga Beli" v-model="produk.harga_beli" type="text" name="harga_beli" id="harga_beli" v-bind="separator" ></money>
                                            <span v-if="errors.harga_beli" id="harga_beli_error" class="label label-danger">{{ errors.harga_beli[0] }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="harga_jual" class="col-md-2 control-label">Harga Jual</label>
                                        <div class="col-md-10">
                                            <money class="form-control" autocomplete="off" placeholder="Harga Jual" v-model="produk.harga_jual" type="text" name="harga_jual" id="harga_jual" v-bind="separator"></money>
                                            <span v-if="errors.harga_jual" id="harga_jual_error" class="label label-danger">{{ errors.harga_jual[0] }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="harga_jual2" class="col-md-2 control-label">Harga Jual 2</label>
                                        <div class="col-md-10">
                                            <money class="form-control" autocomplete="off" placeholder="Harga Jual 2(Jika Ada)" v-model="produk.harga_jual2" type="text" name="harga_jual2" id="harga_jual2" v-bind="separator"></money>
                                            <span v-if="errors.harga_jual2" id="harga_jual2_error" class="label label-danger">{{ errors.harga_jual2[0] }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="berat" class="col-md-2 control-label">Perkiraan Berat(Jika Barang Dijual Online)</label>
                                        <div class="col-md-6">
                                            <money class="form-control" autocomplete="off" placeholder="Perkiraan Berat(Jika Barang Dijual Online)" v-model="produk.berat" type="text" name="berat" id="berat"  v-bind="separator"></money>
                                            <span v-if="errors.berat" id="berat_error" class="label label-danger">{{ errors.berat[0] }}</span>
                                        </div>
                                        <div class="col-md-4">
                                            <p style="color: grey; font-style: italic;">Satuan(Berat) dalam bentuk Gram.</p>     
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="hitung_stok" class="col-md-2 control-label">Hitung Stok</label>
                                        <div class="togglebutton col-md-10">
                                            <label>
                                                <input type="checkbox" v-model="produk.hitung_stok" name="hitung_stok" id="hitung_stok">
                                                <font v-if="produk.hitung_stok == 1 || produk.hitung_stok == true">Ya</font>
                                                <font v-else>Tidak</font>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="status_aktif" class="col-md-2 control-label">Bisa Dijual</label>
                                        <div class="togglebutton col-md-10">
                                            <label>
                                                <input type="checkbox" v-model="produk.status_aktif" name="status_aktif" id="status_aktif">
                                                <font v-if="produk.status_aktif == 1 || produk.status_aktif == true">Ya</font>
                                                <font v-else>Tidak</font>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="form-group" v-if="data_agent == 0">
                                        <div class="col-md-2"></div>
                                        <div class="col-md-10">
                                            <button type="button" class="btn btn-info btn-xs" id="btnDeskripsi" data-toggle="collapse" data-target="#collDeskripsi"><i class="material-icons">add</i>Deskripsi Produk</button>
                                        </div>                                  
                                        <div class="col-md-12 col-xs-12 collapse" id="collDeskripsi">
                                            <quill-editor v-model="produk.deskripsi_produk"
                                            ref="myQuillEditor"
                                            :options="editorOption">
                                        </quill-editor>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="foto" class="col-md-2 control-label">Foto Produk</label>
                                    <div class="col-md-10">
                                        <div class="fileinput fileinput-new text-center" data-provides="fileinput">
                                            <div class="fileinput-new thumbnail">
                                                <img :src="url_origin+'/assets/img/image_placeholder.jpg'" alt="Foto Akan Tampil Disini" v-if="produk.foto == '' || produk.foto == null">
                                                <img v-else :src="url_picture+'/'+produk.foto" /> 
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
                            </div>
                            <div class="col-md-6" style="height: 10%;" v-if="data_agent == 1">
                                <quill-editor v-model="produk.deskripsi_produk"
                                ref="myQuillEditor"
                                :options="editorOption">
                            </quill-editor>
                        </div>
                    </div>

                    <input class="form-control" autocomplete="off" v-model="produk.id" type="hidden" name="id" id="id" >

                    <div class="form-group">
                        <div class="col-md-10 col-md-offset-1">
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
                app.produk.satuan_id = resp.data.satuan_id+"|"+resp.data.nama_satuan;
                app.toogleChange(resp.data.status_aktif,resp.data.hitung_stok);

            })
            .catch(function () {
                alert("Tidak Dapat Memuat Produk")
            });
            app.dataKategori();
            app.dataSatuan();
            app.dataAgent();
        },  
        data: function () {
            return {

                produkId: null,
                errors: [],
                kategori_barang_id: [],
                satuan_id: [],
                inputSatuanKonversi: [],
                url : window.location.origin+(window.location.pathname).replace("dashboard", "produk"),
                url_picture : window.location.origin+(window.location.pathname).replace("dashboard", "foto_produk"),
                url_origin : window.location.origin+(window.location.pathname).replace("dashboard", ""),
                url_kategori : window.location.origin+(window.location.pathname).replace("dashboard", "kelompok-produk"),
                url_satuan : window.location.origin+(window.location.pathname).replace("dashboard", "satuan"),
                produk: {
                    id: '',
                    foto : '',
                    kode_barcode : '',
                    kode_barang : '',
                    nama_barang : '',
                    kategori_barang_id : '',
                    satuan_id : '',
                    harga_beli : '',
                    harga_jual : '',
                    harga_jual2 : '',
                    berat : '',
                    deskripsi_produk : '',
                    hitung_stok : '',
                    status_aktif : ''
                },
                kelompok_produk: {
                    nama_kelompok : '',
                    icon_kelompok : ''
                },
                satuan: {
                    nama_satuan: '',
                },
                message : '',
                data_agent : '',
                placeholder_kategori: {
                    placeholder: '--PILIH KATEGORI--'
                }, 
                placeholder_satuan: {
                    placeholder: '--PILIH SATUAN--'
                },
                editorOption: {
                },
                separator: {
                    decimal: ',',
                    thousands: '.',
                    prefix: '',
                    suffix: '',
                    precision: 0,
                    masked: false /* doesn't work with directive */
                } 
            }
        },
        watch: {            
            'produk.satuan_id':function(){
                var app = this;
                var satuanKonversi = app.inputSatuanKonversi;
                var data_satuan = app.produk.satuan_id.split("|");

                if (app.produk.satuan_id == '') {
                    $("#btnSatuanKonversi").hide();
                }else{
                    $("#btnSatuanKonversi").show();
                }

                $.each(satuanKonversi, function (i, item) {
                    satuanKonversi[0].nama_satuan = data_satuan[1];
                    satuanKonversi[0].satuan_dasar = data_satuan[0];
                })
            },
        },
        computed: {
            editor() {
                return this.$refs.myQuillEditor.quill
            }
        },
        methods: {        
            saveForm() {
                var app = this;
                var newProduk = app.inputData();
                app.loading();

                axios.post(app.url+'/' + app.produkId, newProduk)
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
                    buttons: false,
                    timer: 1000,
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
            dataAgent() {
                var app = this;
                axios.get(app.url+'/pilih-agent').then(function (resp) {
                    app.data_agent = resp.data;
                })
                .catch(function (resp) {
                    alert("Tidak Bisa Memuat Agent");
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
                newProduk.append('kode_barang', app.produk.kode_barang);
                newProduk.append('nama_barang', app.produk.nama_barang);
                newProduk.append('kategori_barang_id', app.produk.kategori_barang_id);
                newProduk.append('satuan_id', app.produk.satuan_id);
                newProduk.append('harga_beli', app.produk.harga_beli);
                newProduk.append('harga_jual', app.produk.harga_jual);
                newProduk.append('harga_jual2', app.produk.harga_jual2);
                newProduk.append('berat', app.produk.berat);
                newProduk.append('hitung_stok', app.produk.hitung_stok);
                newProduk.append('deskripsi_produk', app.produk.deskripsi_produk);
                newProduk.append('status_aktif', app.produk.status_aktif);

                return newProduk;
            },
            tambahKategori(){
                $("#modal_kategori").show();
                this.$refs.nama_kategori_transaksi.$el.focus(); 
            },
            tambahSatuan(){
                $("#modal_satuan").show();
                this.$refs.nama_satuan.$el.focus(); 
            },
            saveFormKategori() {
                var app = this;
                var newkelompok_produk = app.kelompok_produk;
                axios.post(app.url_kategori, newkelompok_produk)
                .then(function (resp) {
                    app.message = 'Menambah Kelompok Produk '+ app.kelompok_produk.nama_kelompok;
                    app.alert(app.message);
                    app.kelompok_produk.nama_kelompok = ''
                    app.kelompok_produk.icon_kelompok = ''
                    app.errors = '';
                    app.dataKategori();
                    $("#modal_kategori").hide();
                })
                .catch(function (resp) {
                    app.success = false;
                    app.errors = resp.response.data.errors;
                });
            },
            saveFormSatuan() {
                var app = this;
                var newsatuan = app.satuan;
                axios.post(app.url_satuan, newsatuan)
                .then(function (resp) {
                    app.message = 'Berhasil Menambah Satuan '+ app.satuan.nama_satuan;
                    app.alert(app.message);
                    app.satuan.nama_satuan = ''
                    app.errors = '';
                    app.dataSatuan();
                    $("#modal_satuan").hide();
                })
                .catch(function (resp) {
                    app.success = false;
                    app.errors = resp.response.data.errors;
                });
            },
            tutupModal(){
                $("#modal_kategori").hide();  
                $("#modal_satuan").hide();  
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
                app.produk.harga_jual2 = '';
                app.produk.berat = '';
                app.produk.deskripsi_produk = '';
                app.produk.hitung_stok = 'true';
                app.produk.status_aktif = 'true';
                app.errors = '';
            },
            toogleChange(status_aktif,hitung_stok){
                let app = this
                if (status_aktif == 1) {
                    $('#status_aktif').attr("checked", true);
                    console.log(status_aktif);
                }else{
                    $('#status_aktif').attr("checked", false);
                    console.log(status_aktif);
                }
                if (hitung_stok == 1) {
                    $('#hitung_stok').attr("checked", true);
                    console.log(hitung_stok);
                }else{
                    $('#hitung_stok').attr("checked", false);
                    console.log(hitung_stok);
                }
            },
            konversiSatuan() { 
                var app = this;

                if (app.inputSatuanKonversi[0] === undefined) {
                    var data_satuan = app.produk.satuan_id.split("|");
                }else{
                    var length = app.inputSatuanKonversi.length - parseInt(1);
                    var data_satuan = app.inputSatuanKonversi[length].id_satuan.split("|");
                }

                app.inputSatuanKonversi.push({
                    nama_satuan: data_satuan[1],
                    satuan_dasar: data_satuan[0],
                    id_satuan: app.produk.satuan_id,
                    jumlah_produk: '',
                    harga_jual: '',
                })              
            },
            hapusKonversiSatuan(index) {
                console.log(index)
                this.inputSatuanKonversi.splice(index,1)
            }
        }
    }
</script>