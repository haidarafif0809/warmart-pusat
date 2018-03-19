
<template>
	<div class="row">
		<div class="col-md-12">
			<ul class="breadcrumb">
				<li><router-link :to="{name: 'indexDashboard'}">Home</router-link></li>
				<li class="active">Laporan Bucket Size</li>
			</ul>
			<div class="card">
				<div class="card-header card-header-icon" data-background-color="purple">
					<i class="material-icons">insert_chart</i>
				</div>

				<div class="card-content">
					<h4 class="card-title"> Laporan Bucket Size </h4>


              <ul class="nav nav-pills nav-pills-rose" role="tablist" style="margin-top:5px;">
                  <!--
                    color-classes: "nav-pills-primary", "nav-pills-info", "nav-pills-success", "nav-pills-warning","nav-pills-danger"
                  -->
                  <li class="active">
                    <a href="#laporan_grafik" role="tab" data-toggle="tab" style="margin-left:10px;">
                      Laporan Grafik
                    </a>
                  </li>
                  <li>
                    <a href="#laporan_data" role="tab" data-toggle="tab" style="margin-right:10px; ">
                      Laporan Data
                    </a>
                  </li>
                </ul>

               <div class="tab-content tab-space" style="margin-top:5px;margin-bottom:5px;">
                  <div class="tab-pane active" id="laporan_grafik"  style="margin-top:5px;margin-bottom:5px;">

					<div class="row">
						<div class="form-group col-md-2">
							<datepicker :input-class="'form-control'" placeholder="Dari Tanggal" v-model="filter.dari_tanggal" name="dari_tanggal" v-bind:id="'dari_tanggal'"></datepicker>				
						</div>
						<div class="form-group col-md-2">
							<datepicker :input-class="'form-control'" placeholder="Sampai Tanggal" v-model="filter.sampai_tanggal" name="sampai_tanggal" v-bind:id="'sampai_tanggal'"></datepicker>
						</div>
						<div class="form-group col-md-2">
							<money style="text-align:right" class="form-control" v-model="filter.kelipatan" v-bind="separator" v-shortkey.focus="['f7']"></money>
						</div>
						<div class="form-group col-md-2">
							<selectize-component v-model="filter.jenis_penjualan" :settings="placeholder_penjualan" id="jenis_penjualan" ref="jenis_penjualan"> 
								<option v-bind:value="0" > Penjualan POS </option>
								<option v-bind:value="1" > Penjualan Online </option>
							</selectize-component>
							<input class="form-control" type="hidden"  v-model="filter.jenis_penjualan"  name="jenis_penjualan" id="jenis_penjualan"  v-shortkey="['f1']" @shortkey="pilihJenisLaporan()">
						</div>

						<div class="form-group col-md-2">
							<button class="btn btn-primary" id="btnSubmit" type="submit" style="margin: 0px 0px;" @click="submitLaporan()"><i class="material-icons">search</i> Cari</button>
						</div>
					</div>

				</div>
				 <div class="tab-pane" id="laporan_data"  style="margin-top:5px;margin-bottom:5px;">
				 	<div class="row">
						<div class="form-group col-md-2">
							<datepicker :input-class="'form-control'" placeholder="Dari Tanggal" v-model="filter.dari_tanggal" name="dari_tanggal" v-bind:id="'dari_tanggal'"></datepicker>				
						</div>
						<div class="form-group col-md-2">
							<datepicker :input-class="'form-control'" placeholder="Sampai Tanggal" v-model="filter.sampai_tanggal" name="sampai_tanggal" v-bind:id="'sampai_tanggal'"></datepicker>
						</div>
						<div class="form-group col-md-2">
							<money style="text-align:right" class="form-control" v-model="filter.kelipatan" v-bind="separator" v-shortkey.focus="['f7']"></money>
						</div>
						<div class="form-group col-md-2">
							<selectize-component v-model="filter.jenis_penjualan" :settings="placeholder_penjualan" id="jenis_penjualan" ref="jenis_penjualan"> 
								<option v-bind:value="0" > Penjualan POS </option>
								<option v-bind:value="1" > Penjualan Online </option>
							</selectize-component>
							<input class="form-control" type="hidden"  v-model="filter.jenis_penjualan"  name="jenis_penjualan" id="jenis_penjualan"  v-shortkey="['f1']" @shortkey="pilihJenisLaporan()">
						</div>

						<div class="form-group col-md-2">
							<button class="btn btn-primary" id="btnSubmit" type="submit" style="margin: 0px 0px;" @click="submitLaporanData()"><i class="material-icons">search</i> Cari</button>
						</div>
					</div>

					 <div class="card-content">
                       <b><h4 class="card-title">{{judul}}</h4></b>
                       <hr>
                            <div class=" table-responsive">

                            <table class="table table-striped table-hover">
                              <thead class="text-primary">
                                <tr>
                                  <th>Kelipatan</th>
                                  <th style="text-align:right">Jumlah Terjual</th>
	                              <th></th>
	                              <th></th>
	                              <th></th>
                                </tr>
                              </thead>
                            <tbody v-if="laporanBucketSize.length > 0 && loading == false"  class="data-ada">
                               <tr v-for="laporanBucketSizes, index in laporanBucketSize" >
                                <td >{{ laporanBucketSizes.kelipatan  }}</td>
                                <td align="right">{{ laporanBucketSizes.total_faktur | pemisahTitik }} </td>
                                <td></td>
                                <td></td>
                                <td></td>
                              </tr>
                            </tbody>          
                            <tbody class="data-tidak-ada" v-else-if="laporanBucketSize.length == 0 && loading == false">
                              <tr ><td colspan="3"  class="text-center">Tidak Ada Data</td></tr>
                            </tbody>
                          </table>
                          <vue-simple-spinner v-if="loading"></vue-simple-spinner>
                        
                        </div><!--RESPONSIVE-->
                      </div>
                      
                     <a href="#" class='btn btn-warning' id="btnExcel" target='blank' :style="'display: none'"><i class="material-icons">file_download</i> Download Excel</a> 


				 </div>
				</div><!--class="tab-content tab-space"-->

				</div>
			</div>
		</div>
	</div>


</template>


<script>
	export default {
		data: function () {
			return {
			laporanBucketSize: [],
	        laporanBucketSizeOnline: [],
				filter: {
					dari_tanggal: '',
					sampai_tanggal: new Date(),
					kelipatan: 100000,
					jenis_penjualan: '0',
				},
				separator: {
					decimal: ',',
					thousands: '.',
					prefix: '',
					suffix: '',
					precision: 2,
					masked: false /* doesn't work with directive */
				},
				placeholder_penjualan: {
					placeholder: '--JENIS PENJUALAN--'
				},
				url: window.location.origin + (window.location.pathname).replace("dashboard", "laporan-bucket-size"),
				urlDownloadExcel : window.location.origin+(window.location.pathname).replace("dashboard", "laporan-bucket-size/download-excel-pos"), 
         		 urlDownloadExcelOnline : window.location.origin+(window.location.pathname).replace("dashboard", "laporan-bucket-size/download-excel-online"), 
				pencarian: '',
				loading: false,
				judul : '',
			}
		},
		mounted () {
			var app = this;		
			var awal_tanggal = new Date();
			awal_tanggal.setDate(1);

			app.filter.dari_tanggal = awal_tanggal;
		},
		filters: {
		      pemisahTitik: function (value) {
		        var angka = [value];
		        var numberFormat = new Intl.NumberFormat('es-ES');
		        var formatted = angka.map(numberFormat.format);
		        return formatted.join('; ');
     	 }
     	},
		methods: {
			dariTanggal(filter){
				var dari_tanggal = "" + filter.dari_tanggal.getFullYear() +'-'+ ((filter.dari_tanggal.getMonth() + 1) > 9 ? '' : '0') + (filter.dari_tanggal.getMonth() + 1) +'-'+ (filter.dari_tanggal.getDate() > 9 ? '' : '0') + filter.dari_tanggal.getDate();

				return dari_tanggal;
			},
			sampaiTanggal(filter){
				var sampai_tanggal = "" + filter.sampai_tanggal.getFullYear() +'-'+ ((filter.sampai_tanggal.getMonth() + 1) > 9 ? '' : '0') + (filter.sampai_tanggal.getMonth() + 1) +'-'+ (filter.sampai_tanggal.getDate() > 9 ? '' : '0') + filter.sampai_tanggal.getDate();

				return sampai_tanggal;
			},
			submitLaporan(){
				var app = this;
				var filter = app.filter;

				var dari_tanggal = app.dariTanggal(filter);
				var sampai_tanggal = app.sampaiTanggal(filter);

				if (filter.jenis_penjualan == "") {
					app.alertGagal('Silakan Pilih Jenis Penjualan Terlebih Dahulu');					
				}else{
					if (filter.jenis_penjualan == 0) {
						app.$router.replace('/laporan-bucket-size/view/'+dari_tanggal+'/'+sampai_tanggal+'/'+app.filter.kelipatan);
					}else{
						app.$router.replace('/laporan-bucket-size-online/view/'+dari_tanggal+'/'+sampai_tanggal+'/'+app.filter.kelipatan);
					}					
				}
			},
			submitLaporanData(){
	                var app = this;
	                if (app.filter.jenis_penjualan == '0') {
	                	app.prosesLaporanBucketSizeData();
	                	app.showButton();
	                }
	                else{
	                	app.prosesLaporanBucketSizeOnlineData();
	                	app.showButton();
	                }   
	         },
	         prosesLaporanBucketSizeData(){
	           var app = this; 
	              var newFilter = app.filter;
	              app.loading = true,
	              axios.post(app.url+'/view-pos-data', newFilter)
	              .then(function (resp) {
	                app.laporanBucketSize = resp.data;
	                app.judul = "Laporan Penjualan POS";
	                app.loading = false
	                console.log(resp);
	              })
	              .catch(function (resp) {
	                // console.log(resp);
	                alert("Tidak Dapat Memuat Laporan Bucket Size");
	              });
	         },
	         prosesLaporanBucketSizeOnlineData(){
	           var app = this; 
	              var newFilter = app.filter;
	              app.loading = true,
	              axios.post(app.url+'/view-online-data', newFilter)
	              .then(function (resp) {
	                app.laporanBucketSize = resp.data;
	                app.judul = "Laporan Penjualan Online";
	                app.loading = false
	                console.log(resp);
	              })
	              .catch(function (resp) {
	                // console.log(resp);
	                alert("Tidak Dapat Memuat Laporan Bucket Size");
	              });
	         },
			alertGagal(pesan) {
				this.$swal({
					title: "Peringatan!",
					text: pesan,
					icon: "warning",
				});
			},
			showButton() { 
            var app = this; 
            var filter = app.filter; 
 
            var date_dari_tanggal = filter.dari_tanggal; 
            var date_sampai_tanggal = filter.sampai_tanggal; 
            var dari_tanggal = app.dariTanggal(filter); 
            var sampai_tanggal = app.sampaiTanggal(filter); 
 
            $("#btnExcel").show(); 
            if (app.filter.jenis_penjualan == '0') { 
             $("#btnExcel").attr('href', app.urlDownloadExcel+'/'+dari_tanggal+'/'+sampai_tanggal+'/'+filter.kelipatan); 
            }else{ 
              $("#btnExcel").attr('href', app.urlDownloadExcelOnline+'/'+dari_tanggal+'/'+sampai_tanggal+'/'+filter.kelipatan); 
            } 
          }, 
		}
	}
</script>