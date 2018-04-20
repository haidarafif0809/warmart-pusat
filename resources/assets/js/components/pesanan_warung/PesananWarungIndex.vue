<style scoped>
.pencarian {
  color: red; 
  float: right;
}
</style>

<template>	
	<div class="row">
		<div class="col-md-12" v-if="dataAgent == 1"><!--AKSES VIA PC-->
        <!-- MODAL PEMESAN -->
        <div class="modal " id="data_pemesan" role="dialog" data-backdrop="">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Data Pemesan</h4>
                        </div>

                        <div class="modal-body">
                            <div class="responsive">
                                <table class="table table-striped table-hover ">
                                    <tbody>
                                        <tr>
                                            <tr><td width="25%"> Nama</td> <td> : </td> <td>  {{detailPesananWarung.nama_pemesan}} </td></tr>
                                            <tr><td width="25%"> Alamat</td> <td> : </td> <td>  {{detailPesananWarung.alamat_pemesan}} </td></tr>
                                            <tr><td width="25%"> No Telp</td> <td> : </td> <td>  {{detailPesananWarung.no_telp_pemesan}} </td></tr>
                                        </tr>
                                    </tbody>
                            </table>
                            </div>
                        </div>
                </div>
            </div>
        </div> <!--END MODAL PEMESAN-->

			<ul class="breadcrumb">
				<li><router-link :to="{name: 'indexDashboard'}">Home</router-link></li>
				<li class="active">Pesanan</li>
			</ul>
			
			<div class="card">
				<div class="card-header card-header-icon" data-background-color="purple">
					<i class="material-icons">archive</i>
				</div>
				<div class="card-content">
					<h4 class="card-title"> Pesanan</h4>

					<div class="toolbar">
                        <router-link class="btn btn-primary" style="padding-bottom:10px"><i class="material-icons">add</i></router-link>

                        <div class="pencarian">
                            <input type="text" name="pencarian" v-model="pencarian" placeholder="Pencarian" class="form-control pencarian" autocomplete="">
                        </div>
					</div>

                    <br><br><br>
					<div class=" table-responsive">
						<table class="table table-striped table-hover ">
							<thead class="text-primary">
								<tr>

                                    <th>No</th>
                                    <th>Pemesan</th>
                                    <th>Status</th>
                                    <th>Waktu</th>
                                    <th>No. Resi</th>
                                    <th>Detail</th>

                                </tr>
                            </thead>
                            <tbody v-if="pesananWarung.length > 0 && loading == false"  class="data-ada">
                                <tr v-for="pesananWarung, index in pesananWarung" >
                                     <td>{{ pesananWarung.pesanan_warung.id }}</td>
                                     <td>{{ pesananWarung.pemesan }}</td>
                                     <td>
                                        <b style="color:red" v-if="pesananWarung.pesanan_warung.konfirmasi_pesanan == 0">Pembayaran Belum Di Konfirmasi</b>
                                        <b style="color:orange" v-else-if="pesananWarung.pesanan_warung.konfirmasi_pesanan == 1">Pembayaran Sudah Konfirmasi</b>
                                        <b style="color:#01573e" v-else-if="pesananWarung.pesanan_warung.konfirmasi_pesanan == 2">Selesai</b>
                                        <b style="color:#01573e" v-else-if="pesananWarung.pesanan_warung.konfirmasi_pesanan == 3">Batal</b>
                                        <b style="color:orange" v-else>Batal Pelanggan</b>
                                     </td>
                                     <td>{{ pesananWarung.pesanan_warung.created_at }}</td>
                                     <td>
                                        <span v-if="pesananWarung.pesanan_warung.kurir != 'cod'">
                                            <span v-if="pesananWarung.pesanan_warung.no_resi == null">
                                                <button type="button" class="btn btn-sm btn-primary"  @click="insertNoResi(pesananWarung.pesanan_warung.id, pesananWarung.pesanan_warung.no_resi)">Masukkan</button>
                                            </span>
                                            <span v-else>
                                                <a :href="url2" @click="insertNoResi(pesananWarung.pesanan_warung.id, pesananWarung.pesanan_warung.no_resi)">{{ pesananWarung.pesanan_warung.no_resi }}</a>
                                            </span>
                                        </span>
                                        <span v-else>
                                            <button class="btn btn-sm btn-default disabled">Tidak Tersedia</button>
                                        </span>
                                     </td>
                                     <td>
                                        <router-link :to="{name: 'detailPesananWarung', params: {id: pesananWarung.pesanan_warung.id}}" class="btn btn-sm btn-default" v-bind:id="'detail-' + pesananWarung.pesanan_warung.id" > Detail Pesanan
                                        </router-link>

                                        <button type="button" class="btn btn-sm btn-primary" id="btnDetail" data-toggle="modal" data-target="#data_pemesan" @click="detailPesanan(pesananWarung.pesanan_warung.id)">Pemesan</button>

                                        <a v-if="pesananWarung.pesanan_warung.konfirmasi_pesanan == 2" target="blank" class="btn btn-warning btn-xs" v-bind:href="'pesanan-warung/cetak-kecil-penjualan/'+pesananWarung.pesanan_warung.id">Cetak Ulang</a>
                                        <a v-else target="blank" class="btn btn-warning btn-xs" v-bind:href="'pesanan-warung/cetak-kecil-pesanan/'+pesananWarung.pesanan_warung.id">Cetak Ulang</a>

                                    </td>
                                </tr>
                            </tbody>					
                            <tbody class="data-tidak-ada" v-else-if="pesananWarung.length == 0 && loading == false">
                                <tr ><td colspan="7"  class="text-center">Tidak Ada Data</td></tr>
                            </tbody>
                    </table>	

                 <vue-simple-spinner v-if="loading"></vue-simple-spinner>

                 <div align="right"><pagination :data="pesananWarungData" v-on:pagination-change-page="getResults" :limit="4"></pagination></div>

                    </div>

                </div>
            </div>
        </div><!--AKSES VIA PC-->

        <div class="col-md-12" style="padding-left:25px; padding-right:25px" v-else><!--AKSES VIA MOBILE-->
            <ul class="breadcrumb" style="margin-bottom: 0px;">
                <li><router-link :to="{name: 'indexDashboard'}">Home</router-link></li>
                <li class="active">Pesanan</li>
            </ul>

            <div class="card" style="margin-bottom: 5px; margin-top: 1px;">
                <div class="card-content" style="padding: 5px 0px">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">search</i>
                        </span>
                        <input type="text" name="pencarian" v-model="pencarian" placeholder="Pencarian" class="form-control" autocomplete="" style="color: red">
                    </div>
                </div>
            </div>
            

            <div class="card" style="margin-bottom: 5px; margin-top: 1px;" v-for="pesananWarung, index in pesananWarung">
                <div class="card-content" style="padding: 5px 0px">
                        <div class="col-md-12">
                            <b class="card-title" style="font-size:medium">Pesanan : #{{pesananWarung.pesanan_warung.id}}</b>
                        </div><hr style="margin-bottom: 0px;margin-top: 1px">
                        <div class="col-md-12 table-responsive" style="margin-bottom: 0px;">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <tr><td width="25%"> Pemesan</td> <td> : </td> <td>  {{pesananWarung.pesanan_warung.nama_pemesan}} </td></tr>
                                        <tr><td width="25%"> No. Telp</td> <td> : </td> <td>  {{pesananWarung.pesanan_warung.no_telp_pemesan}} </td></tr>
                                        <tr><td width="25%"> Status</td> <td> : </td>
                                            <td>
                                                <b style="color:red" v-if="pesananWarung.pesanan_warung.konfirmasi_pesanan == 0">Pembayaran Belum Di Konfirmasi</b>
                                                <b style="color:orange" v-else-if="pesananWarung.pesanan_warung.konfirmasi_pesanan == 1">Pembayaran Sudah Konfirmasi</b>
                                                <b style="color:#01573e" v-else-if="pesananWarung.pesanan_warung.konfirmasi_pesanan == 2">Selesai</b>
                                                <b style="color:#01573e" v-else-if="pesananWarung.pesanan_warung.konfirmasi_pesanan == 3">Batal</b>
                                                <b style="color:orange" v-else>Batal Pelanggan</b>
                                            </td>
                                        </tr>
                                        <tr><td width="25%"> Waktu</td> <td> : </td> <td> {{pesananWarung.pesanan_warung.created_at}} </td></tr>
                                    </tr>
                                </tbody>
                            </table>

                            <router-link :to="{name: 'detailPesananWarung', params: {id: pesananWarung.pesanan_warung.id}}" class="btn btn-block" v-bind:id="'detail-' + pesananWarung.pesanan_warung.id" style="background-color: #01573e; margin: 1px 1px" > Detail Pesanan
                            </router-link>
                        </div>
                    </div>
            </div><!-- end-main-raised -->

            <vue-simple-spinner v-if="loading"></vue-simple-spinner>
            <div align="right"><pagination :data="pesananWarungData" v-on:pagination-change-page="getResults" :limit="4"></pagination></div>
        </div><!--AKSES VIA MOBILE-->
    </div>
</template>

<script>
export default {
	data: function () {
		return {
			pesananWarung: [],
            detailPesananWarung: {},
            pesananWarungData: {},
            noResiPesanan: {},
			url: window.location.origin+(window.location.pathname).replace("dashboard", "pesanan-warung"),
            url2: window.location.href,
            pencarian: '',
            dataAgent: '',
            loading: true,
            urlTambahNoResi: window.location.origin + (window.location.pathname).replace('dashboard', '/pesanan-warung/tambah-no-resi')
		}
	},
	mounted() {
		var app = this;
		app.getResults();
	},
	watch: {
        // whenever question changes, this function will run
        pencarian: function (newQuestion) {
        	this.getHasilPencarian()  
        }
    },

    methods: {
    	getResults(page) {
    		var app = this;	
    		if (typeof page === 'undefined') {
    			page = 1;
    		}
    		axios.get(app.url+'/view?page='+page)
    		.then(function (resp) {
                console.log(resp.data.data)
    			app.pesananWarung = resp.data.data;
                app.pesananWarungData = resp.data;
                app.dataAgent = resp.data.agent;
    			app.loading = false;
                console.log(resp.data.agent)
    		})
    		.catch(function (resp) {
    			console.log(resp);
    			app.loading = false;
    			alert("Tidak Dapat Memuat Pesanan");
    		});
    	},
        getHasilPencarian(page){
            var app = this;
            if (typeof page === 'undefined') {
                page = 1;
            }
            axios.get(app.url+'/pencarian?search='+app.pencarian+'&page='+page)
            .then(function (resp) {
                app.pesananWarung = resp.data.data;
                app.pesananWarungData = resp.data;
                app.loading = false;
            })
            .catch(function (resp) {
                console.log(resp);
                alert("Tidak Dapat Memuat Pesanan");
            });
        },
        detailPesanan(id){
            var app = this; 

            axios.get(app.url+'/detail-view/'+id)
            .then(function (resp) {
                app.detailPesananWarung = resp.data;
            })
            .catch(function (resp) {
                app.loading = false;
                alert("Tidak Dapat Memuat Detail Pesanan");
            });
        },
        insertNoResi(idPesanan, noResi) {
            let app = this;

            swal({
                title: 'Masukkan Nomor Resi',
                html:
                '<input id="no_resi" placeholder="Nama kategori produk" class="swal2-input">',
                focusConfirm: false,
                showLoaderOnConfirm: false,
                preConfirm: () => {
                    return new Promise((resolve, reject) => {
                        let no_resi = $('#no_resi');

                        setTimeout(() => {

                            if (!no_resi.val().match(/^[a-zA-Z0-9_-\s]*$/)) {
                                swal.showValidationError('Nomor Resi hanya boleh berisi huruf dan angka');
                                no_resi.focus();
                                reject();
                            }
                            if (no_resi.val() == noResi) {
                                swal.showValidationError('Nomor Resi sama dengan yang sudah ada.');
                                no_resi.focus();
                                reject();
                            }
                            resolve(no_resi);
                        }, 5);
                    });
                }
            })
            .then(data => {
                let no_resi = data[0].value;
                let pesan;
                app.noResiPesanan.id_pesanan = idPesanan;
                app.noResiPesanan.no_resi = no_resi.toUpperCase();

                if (!no_resi) {
                    no_resi = null;
                    // pesan = 'Berhasil menghapus Nomor Resi dari Pesanan.';
                    app.noResiPesanan.email = false;

                    axios.post(app.urlTambahNoResi, app.noResiPesanan)
                    .then(function (resp) {
                        swal({
                            title: 'Berhasil!',
                            text: 'Berhasil menghapus Nomor Resi dari Pesanan.',
                            type: 'success',
                            showConfirmButton: false,
                            timer: 2000,
                        });
                        app.getResults();
                    })
                    .catch(function (resp) {
                        console.log(resp)
                        swal("Terjadi kesalahan", "", "warning");
                    });
                }
                else {
                    app.noResiPesanan.email = false;

                    swal({
                        title: 'Update data..',
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                    })
                    swal.showLoading();
                    axios.post(app.urlTambahNoResi, app.noResiPesanan)
                    .then(function (resp) {
                        swal({
                            title: 'Berhasil!',
                            text: 'Berhasil menambahkan "'+ app.noResiPesanan.no_resi +'" sebagai Nomor Resi.',
                            type: 'success',
                            showConfirmButton: false,
                        })
                        .then((resp) => {
                            app.noResiPesanan.email = true;
                            swal({
                                title: 'Mengirim email..',
                                showConfirmButton: false,
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                            })
                            axios.post(app.urlTambahNoResi, app.noResiPesanan)
                            .then((resp) => {
                                console.log('then:', resp);
                                swal.close();
                                setTimeout(() => {
                                    swal({
                                        title: 'Berhasil!',
                                        text: 'Berhasil Email berhasil dikirim.',
                                        type: 'success',
                                        timer: 1800,
                                        showConfirmButton: false
                                    });
                                }, 5);
                            })
                            .catch((resp) => {
                                console.log('catch:', resp);
                                alert('gagal kirim email');
                            });                                

                            setTimeout(() => {
                                swal.showLoading();
                            }, 5);
                        })
                        .catch((resp) => {

                        });
                        setTimeout(() => {
                            swal.clickConfirm();
                        }, 800);
                        app.getResults();                        
                    })
                    .catch(function (resp) {
                        console.log(resp)
                        swal("Terjadi kesalahan", "", "warning");
                    });
                }

            })
            .catch(function (resp) {
                if (resp != 'esc' && resp != 'overlay') {   
                    swal("Ups.. Ada yang tidak beres.", "Penambahan Nomor Resi gagal!", "error");
                }
            });

            let no_resi = $('#no_resi');
            
            if (noResi != null) {   
                no_resi.val(noResi);
            }
            no_resi.focus();

            var button = $(".swal2-confirm");
            $('.swal2-container').keydown(function (event) {

                /*
                | ----------------------------------------------------------------------------
                | Untuk mendeteksi apakah tombol enter ditekan atau tidak, jika ditekan
                | maka trigger event klik untuk tombol konfirmasi pada swal yang muncul
                | karena untuk swal tambah nomor resi kita tidak bisa menekan tombol enter
                | untuk submit form secara bawaan, jadi harus dibuat manual :3
                | ----------------------------------------------------------------------------
                */
                if (event.which == 13) {
                    button.click();
                }
            });
        }
    }
}
</script>