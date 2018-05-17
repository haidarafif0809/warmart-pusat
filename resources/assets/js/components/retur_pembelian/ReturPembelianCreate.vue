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
.table>thead>tr>th {
    border-bottom-width: 1px;
    font-size: 1em;
    font-weight: 300;
}

</style>

<template>
    <div class="row">
        <div class="col-md-12">

          <ul class="breadcrumb"> 
            <li><router-link :to="{name: 'indexDashboard'}">Home</router-link></li> 
            <li><router-link :to="{name: 'indexReturPembelian'}">Retur Pembelian</router-link></li> 
            <li class="active">Tambah Retur Pembelian</li> 
        </ul> 

        <div class="card" style="margin-bottom: 1px; margin-top: 1px;" >
            <div class="card-content">
                <h4 class="card-title" style="margin-bottom: 1px; margin-top: 1px;"> Retur Pembelian </h4>

                <div class="row" style="margin-bottom: 1px; margin-top: 1px;">
                    <div class="col-md-3">
                        <div class="card card-produk" style="margin-bottom: 1px; margin-top: 1px;">
                            <div class="form-group" style="margin-right: 10px; margin-left: 10px;">
                                <selectize-component v-model="inputTbsRetur.supplier" :settings="placeholder_supplier" id="produk" ref='produk'>
                                    <option v-for="suplier, index in supliers" v-bind:value="suplier.id">
                                        {{suplier.nama_suplier}}
                                    </option>
                                </selectize-component>
                            </div>
                            <span v-if="errors.supplier" id="produk_error" class="label label-danger">
                                {{ errors.supplier[0] }}
                            </span>
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
                                        <th >Faktur Pembelian</th>
                                        <th >Produk</th>
                                        <th style="text-align:right;">Jumlah Produk</th>
                                        <th style="text-align:right;">Jumlah Retur</th>
                                        <th style="text-align:center;">Satuan</th>
                                        <th style="text-align:right;">Harga</th>
                                        <th style="text-align:right;">Potongan</th>
                                        <th style="text-align:right;">Pajak</th>
                                        <th style="text-align:right;">Subtotal</th>
                                        <th style="text-align:right;">Hapus</th>
                                    </tr>
                                </thead>
                                <tbody v-if="tbsReturPembelians.length > 0 && loading == false"  class="data-ada">
                                    <tr v-for="tbs_retur_pembelian, index in tbsReturPembelians" >
                                        <td>{{ tbs_retur_pembelian.kode_produk }} - {{ tbs_retur_pembelian.nama_produk }}</td>
                                        <td>{{ tbs_retur_pembelian.kode_produk }} - {{ tbs_retur_pembelian.nama_produk }}</td>
                                        <td>{{ tbs_retur_pembelian.kode_produk }} - {{ tbs_retur_pembelian.nama_produk }}</td>
                                        <td>{{ tbs_retur_pembelian.kode_produk }} - {{ tbs_retur_pembelian.nama_produk }}</td>
                                        <td>{{ tbs_retur_pembelian.kode_produk }} - {{ tbs_retur_pembelian.nama_produk }}</td>
                                        <td>{{ tbs_retur_pembelian.kode_produk }} - {{ tbs_retur_pembelian.nama_produk }}</td>
                                        <td>{{ tbs_retur_pembelian.kode_produk }} - {{ tbs_retur_pembelian.nama_produk }}</td>
                                        <td>{{ tbs_retur_pembelian.kode_produk }} - {{ tbs_retur_pembelian.nama_produk }}</td>
                                        <td>{{ tbs_retur_pembelian.kode_produk }} - {{ tbs_retur_pembelian.nama_produk }}</td>
                                        <td style="text-align:right;">
                                            <a href="#create-pembelian" class="btn btn-xs btn-danger" v-bind:id="'delete-' + tbs_retur_pembelian.id_tbs_retur_pembelian" v-on:click="deleteEntry(tbs_retur_pembelian.id_tbs_retur_pembelian, index,tbs_retur_pembelian.nama_produk,tbs_retur_pembelian.subtotal_tbs)">
                                                Delete
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>          
                                <tbody class="data-tidak-ada"  v-else-if="tbsReturPembelians.length == 0 && loading == false">
                                    <tr ><td colspan="10"  class="text-center">Tidak Ada Data</td></tr>
                                </tbody>
                            </table>

                            <vue-simple-spinner v-if="loading"></vue-simple-spinner>

                            <div align="right">
                                <pagination :data="tbsReturPembelianDatas" v-on:pagination-change-page="getTbs" :limit="4">
                                </pagination>
                            </div>
                        </div>                     
                    </div>

                    <div class="col-md-3">
                        <div class="card card-stats">
                            <div class="card-header" data-background-color="blue">
                                <i class="material-icons">shopping_cart</i>
                            </div>
                            <div class="card-content">

                            </div>

                            <div class="card-footer">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
</template>

<script>
    import { mapState } from 'vuex';
    export default {
        data: function () {
            return {
                errors: [],
                tbsReturPembelians: [],
                tbsReturPembelianDatas : {},
                url : window.location.origin+(window.location.pathname).replace("dashboard", "retur-pembelian"),        
                pencarian: '',
                loading: true,
                seen : false,
                placeholder_supplier:{
                    placeholder: '--PILIH SUPPLIER--',
                    sortField: 'text',
                    openOnFocus : true,
                },
                inputTbsRetur: {
                    supplier: ''
                },
                inputPembayaranRetur: {
                    keterangan: ''
                }
            }
        },
        computed : mapState ({    
            supliers(){
                return this.$store.state.supplier
            }
        }),
        mounted() {
            var app = this;
            app.$store.dispatch('LOAD_SUPPLIER_LIST');
            app.getTbs();
        },
        methods: {
            getTbs(page) {
                var app = this; 
                if (typeof page === 'undefined') {
                    page = 1;
                }

                axios.get(app.url+'/view-tbs?page='+page)
                .then( (resp) => {
                    console.log(resp.data)
                    app.tbsReturPembelians = resp.data.data
                    app.tbsReturPembelianDatas = resp.data
                    app.loading = false
                    app.seen = true
                })
                .catch( (err) => {
                    console.log(err);
                    app.loading = false;
                    app.seen = true;
                    alert("Tidak Dapat Memuat Retur Pembelian");
                })
            }
        }
    }
</script>