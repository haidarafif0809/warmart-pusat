<style scoped>
.paddingChart{
	padding: 200px;
	height: 10px;
}
.modal {
	overflow-y:auto;
}
.pencarian {
	color: red; 
	float: right;
}
.table-pelanggan, .th-pelanggan, .td-pelanggan {
	border: 1px solid black;
	border-collapse: collapse;
}
.th-pelanggan, .td-pelanggan {
	padding: 5px;
	text-align: left;    
}

</style>
<template>
	<div class="row">
		<div class="col-md-12">
			<ul class="breadcrumb">
				<li><router-link :to="{name: 'indexDashboard'}">Home</router-link></li>
				<li class="active">Laporan Bucket Size</li>
			</ul>


			<!-- MODAL ACTION-->

			<div class="modal" id="modal_action" role="dialog" data-backdrop=""> 
				<div class="modal-dialog modal-lg"> 
					<!-- Modal content--> 
					<div class="modal-content"> 
						<div class="modal-header"> 
							<button type="button" class="close" v-on:click="closeModalAction()"> <i class="material-icons">close</i></button> 
							<h4 class="modal-title"> 
								<div class="alert-icon"> 
									<b></b> 
								</div> 
							</h4> 
						</div> 
						<form v-on:submit.prevent="saveForm()" class="form-horizontal"> 
							<div class="modal-body">         

								<div class="row">
									<div class="col-md-3">
										<b>Pelanggan</b><br>
										<span v-if="errors.pelanggan" id="pelanggan_error" class="label label-danger">{{ errors.pelanggan[0] }}</span>
										<div class="checkbox" v-if="pelangganAction.length > 0">
											<label>
												<input type="checkbox" name="pilih_semua" v-model="pilih_semua" v-bind:value="1" v-on:change="pilihSemua"> Pilih Semua
											</label>
										</div>
										<div v-else>
											<br>
											<span id="pelanggan_error" class="label label-danger">Tidak ada pelanggan</span>
										</div>  
										<div class="checkbox" v-for="pelanggans, index in pelangganAction">
											<label>
												<input type="checkbox" v-bind:value="pelanggans.id" v-model="actionBucketSize.pelanggan"> {{pelanggans.name}}
											</label>
										</div> 
									</div>
									<div class="col-md-9">
										<div class="form-group">
											<label>Produk</label> 
											<span v-if="errors.produk" id="produk_error" class="label label-danger">{{ errors.produk[0] }}</span>
											<selectize-component :settings="placeholder_produk" ref='setting_harga_jual' v-model="actionBucketSize.produk"> 
												<option v-for="produks, index in produk" v-bind:value="produks.produk">{{produks.barcode}} || {{produks.kode_produk}} || {{ produks.nama_produk }}</option>
											</selectize-component>
										</div>

										<div class="form-group">
											<label>Kirim Lewat</label>
											<span v-if="errors.kirim_pesan_via" id="kirim_pesan_via_error" class="label label-danger">{{ errors.kirim_pesan_via[0] }}</span>
											<selectize-component :settings="kirim_pesan_via" ref="kirim_pesan_via" v-model="actionBucketSize.kirim_pesan_via">
												<option v-bind:value="1">SMS</option>
												<option v-bind:value="2">E-MAIL</option>
											</selectize-component>
										</div>

										<div class="form-group">
											<label>Pesan Promo</label>
											<span v-if="errors.pesan" id="pesan_error" class="label label-danger">{{ errors.pesan[0] }}</span>
											<textarea class="form-control" placeholder="Silakan Isi Pesan Promo Untuk Pelanggan disini ..." rows="4" v-model="actionBucketSize.pesan"></textarea>
										</div>

										<p style="color: red; font-style: italic;">*Note : Gunakan Shorcut F2 untuk memasukan produk </p>    

										<ul class="timeline timeline-simple">
											<li class="timeline-inverted">
												<div class="timeline-badge info">
													<i class="material-icons">chat</i>
												</div>
												<div class="timeline-panel">
													<div class="timeline-heading">
														<span class="label label-info">Pesan</span>
													</div>
													<div class="timeline-body">
														<p>Hai [nama pelanggan	]..</p>
														<p>{{actionBucketSize.pesan}}</p>
													</div>
													<h6>
														<i class="ti-time"></i>{{chartData.warung}}
													</h6>
												</div>
											</li>
										</ul>
										<button id="kirimPesan" type="submit" class="btn btn-info">Kirim </button>
									</div>

								</div>

							</div>
							<div class="modal-footer">  
								<button type="button" class="btn btn-default btn-sm"  v-on:click="closeModalAction()">Tutup</button>
							</div> 
						</form>
					</div>       
				</div> 
			</div> 
			<!-- / MODAL ACTION --> 

			<!-- MODAL DETAIL PELANGGAN -->

			<div class="modal" id="modal_detail_pelanggan" role="dialog" data-backdrop=""> 
				<div class="modal-dialog"> 
					<!-- Modal content--> 
					<div class="modal-content"> 
						<div class="modal-header"> 
							<button type="button" class="close" v-on:click="closeModal()"> <i class="material-icons">close</i></button> 
							<h4 class="modal-title"> 
								<div class="alert-icon"> 
									<b>{{detailPelanggan.name}}</b> 
								</div> 
							</h4> 
						</div> 
						<form class="form-horizontal" > 
							<div class="modal-body"> 
								<div class="table-responsive">
									<table class="table table-striped table-hover" style="margin-bottom:10px; margin-top:10px; margin-right:10px; margin-left:10px;">

										<tbody style="margin-bottom:10px; margin-top:10px; margin-right:10px; margin-left:10px;">
											<tr>
												<td class="text-primary"><b># No. Telpon </b> </td>
												<td class="text-primary"><b>: {{detailPelanggan.no_telp}} </b> </td>
											</tr>
											<tr>
												<td class="text-primary"><b># Email </b> </td>
												<td class="text-primary"><b>: {{detailPelanggan.email}} </b> </td>
											</tr>
											<tr>
												<td class="text-primary"><b># Tanggal Lahir </b> </td>
												<td class="text-primary"><b>: {{detailPelanggan.tgl_lahir}} </b> </td>
											</tr>
											<tr>
												<td class="text-primary"><b># Alamat </b> </td>
												<td class="text-primary"><b>: {{detailPelanggan.alamat}} </b> </td>
											</tr>
										</tbody>
									</table>  
								</div>
							</div>
							<div class="modal-footer">  
								<button type="button" class="btn btn-default btn-sm"  v-on:click="closeModal()">Tutup</button>
							</div> 
						</form>
					</div>       
				</div> 
			</div> 
			<!-- / MODAL DETAIL PELANGGAN --> 

			<!-- small modal -->																																				
			<div class="modal" id="modalPelanggan" role="dialog" tabindex="-1"  aria-labelledby="myModalLabel" aria-hidden="true" >
				<div class="modal-dialog modal-lg">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close"  v-on:click="closeModalPelanggan()" @shortkey="closeModalPelanggan()"> &times;</button> 
							<h4 class="modal-title"> 
								<div class="alert-icon"> 
									<b>Bucket Size {{bucketSizePelanggan}}</b>
								</div> 
							</h4> 
						</div>
						<div class="modal-body"> 

							<div class="table-responsive">
								<table class="table table-striped table-hover table-responsive">
									<thead class="text-info">
										<tr>
											<th>Pelanggan</th>
											<th class="text-right">Total Belanja</th>
											<th class="text-center">Produk</th>
										</tr>
									</thead>
									<tbody>
										<tr v-for="pelanggan, index in dataPelanggan">
											<td v-if="pelanggan.pelanggan_id == 0">Umum</td>
											<td v-else>
												<a href="#laporan-bucket-size" v-on:click="cekDetailPelanggan(pelanggan.pelanggan)">{{ pelanggan.pelanggan.name  }} </a>
												<!--<p>Nama : {{ pelanggan.pelanggan.name  }}</p>
													<p>No. Telp : {{ pelanggan.pelanggan.no_telp  }}</p> -->
												</td>
												<td align="right">{{ pelanggan.total | pemisahTitik }}</td>
												<td align="center">
											<!-- 		<router-link :to="{name: 'detailPenjualan', params: {id: pelanggan.id}}" class="btn btn-xs btn-info" target="_blank"> 
											Lihat </router-link> -->
											<a href="#laporan-bucket-size" v-on:click="getProdukPos(1, pelanggan.id, pelanggan.potongan)" class="btn btn-xs btn-info" v-if="filter.jenis_penjualan == 0">Lihat</a>
											<a href="#laporan-bucket-size" v-on:click="getProdukOnline(1, pelanggan.id, pelanggan.pesanan_pelanggan.kode_unik_transfer, pelanggan.pesanan_pelanggan.biaya_kirim)" class="btn btn-xs btn-info" v-else>Lihat</a>
										</td>
									</tr>
									<tr>
										<td>
											<font style="font-size:20px;">Total</font>
										</td>
										<td align="right">
											<font style="font-size:20px;">{{dataPelanggan.total | pemisahTitik}}</font>
										</td>
										<td></td>
									</tr>
								</tbody>    
							</table>
						</div><!--RESPONSIVE-->
						<center><button type="button" class="btn btn-xs btn-info" v-on:click="loadMore(dataPelanggan.length)" v-if="loadMoreLength > dataPelanggan.length">Load More... <i class="material-icons">keyboard_arrow_down</i></button></center>
					</div>
					<div class="modal-footer">  
						<button type="button" class="btn btn-primary" v-on:click="action()">Kirim Pesan</button>
						<button type="button" class="btn btn-default btn-sm" v-on:click="closeModalPelanggan()">Tutup</button>
					</div> 

				</div>
			</div>
		</div>
		<!--    end small modal -->



		<!-- small modal -->
		<div class="modal" id="modalDetailPenjualan" role="dialog" tabindex="-1"  aria-labelledby="myModalLabel" aria-hidden="true" >
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close"  v-on:click="closeModalDetailPenjualan()"  @shortkey="closeModalDetailPenjualan()"> &times;</button> 
						<h4 class="modal-title"> 
							<div class="alert-icon"> 
								<b>Detail Penjualan</b>
							</div> 
						</h4> 
					</div>
					<div class="modal-body"> 
						<div class=" table-responsive ">

							<div class="pencarian">
								<input type="text" name="pencarian" v-model="pencarian" placeholder="Pencarian" class="form-control pencarian" autocomplete="">
							</div>

							<table class="table table-striped table-hover" v-if="filter.jenis_penjualan == 0">
								<thead class="text-primary">
									<tr>

										<th>No Transaksi</th>
										<th>Produk</th>
										<th class="text-right">Jumlah</th>
										<th class="text-center">Satuan</th>
										<th class="text-right">Harga</th>
										<th class="text-right">Potongan</th>
										<th class="text-right">Subtotal</th>

									</tr>
								</thead>
								<tbody v-if="detailPenjualan.length"  class="data-ada">
									<tr v-for="detailPenjualan, index in detailPenjualan" >

										<td>{{ detailPenjualan.id_penjualan_pos }}</td>
										<td>{{ detailPenjualan.nama_produk }}</td>
										<td align="right"> {{ detailPenjualan.jumlah_produk | pemisahTitik }}</td>
										<td align="center">{{ detailPenjualan.satuan }}</td>
										<td align="right"> {{ detailPenjualan.harga_produk | pemisahTitik }}</td>
										<td align="right"> {{ detailPenjualan.potongan }}</td>
										<td align="right"> {{ detailPenjualan.subtotal | pemisahTitik }}</td>

									</tr>
									<tr>
										<td colspan="5"></td>
										<td>
											<font style="font-size:20px;">Subtotal</font>
										</td>
										<td align="right">
											<font style="font-size:20px;">{{subtotal | pemisahTitik}}</font>
										</td>
									</tr>
									<tr>
										<td colspan="5"></td>
										<td>
											<font style="font-size:20px;">Disc. Faktur</font>
										</td>
										<td align="right">
											<font style="font-size:20px;">{{potongan | pemisahTitik}}</font>
										</td>
									</tr>
									<tr>
										<td colspan="5"></td>
										<td>
											<font style="font-size:20px;">Total</font>
										</td>
										<td align="right">
											<font style="font-size:20px;">{{parseFloat(subtotal) - parseFloat(potongan) | pemisahTitik}}</font>
										</td>
									</tr>
								</tbody>                    
								<tbody class="data-tidak-ada" v-else>
									<tr ><td colspan="7"  class="text-center">Tidak Ada Data</td></tr>
								</tbody>
							</table>  

							<table class="table table-striped table-hover" v-else>
								<thead class="text-primary">
									<tr>

										<th>No Transaksi</th>
										<th>Produk</th>
										<th class="text-right">Jumlah</th>
										<th class="text-center">Satuan</th>
										<th class="text-right">Harga</th>
										<th class="text-right">Potongan</th>
										<th class="text-right">Subtotal</th>

									</tr>
								</thead>
								<tbody v-if="detailPenjualan.length"  class="data-ada">
									<tr v-for="detailPenjualan, index in detailPenjualan" >

										<td>{{ detailPenjualan.id_penjualan }}</td>
										<td>{{ detailPenjualan.nama_produk }}</td>
										<td align="right"> {{ detailPenjualan.jumlah | pemisahTitik }}</td>
										<td align="center">{{ detailPenjualan.satuan }}</td>
										<td align="right"> {{ detailPenjualan.harga | pemisahTitik }}</td>
										<td align="right"> {{ detailPenjualan.potongan }}</td>
										<td align="right"> {{ detailPenjualan.subtotal | pemisahTitik }}</td>

									</tr>
									
									<tr>
										<td colspan="5"></td>
										<td>
											<font style="font-size:20px;">Subtotal</font>
										</td>
										<td align="right">
											<font style="font-size:20px;">{{subtotal | pemisahTitik}}</font>
										</td>
									</tr>
									<tr>
										<td colspan="5"></td>
										<td>
											<font style="font-size:20px;">Ongkos Kirim</font>
										</td>
										<td align="right">
											<font style="font-size:20px;">{{ongkir | pemisahTitik}}</font>
										</td>
									</tr>
									<tr>
										<td colspan="5"></td>
										<td>
											<font style="font-size:20px;">Kode Unik</font>
										</td>
										<td align="right">
											<font style="font-size:20px;">{{kode_unik}}</font>
										</td>
									</tr>
									<tr>
										<td colspan="5"></td>
										<td>
											<font style="font-size:20px;">Total</font>
										</td>
										<td align="right">
											<font style="font-size:20px;">{{(parseFloat(kode_unik) + parseFloat(ongkir)) + parseFloat(subtotal) | pemisahTitik}}</font>
										</td>
									</tr>

								</tbody>                    
								<tbody class="data-tidak-ada" v-else>
									<tr ><td colspan="7"  class="text-center">Tidak Ada Data</td></tr>
								</tbody>
							</table>    

							<vue-simple-spinner v-if="loading"></vue-simple-spinner>

							<div align="right" v-if="filter.jenis_penjualan == 1"><pagination :data="detailPenjualanData" v-on:pagination-change-page="getProdukPos" :limit="4" ></pagination></div>
							<div align="right" v-else><pagination :data="detailPenjualanData" v-on:pagination-change-page="getProdukOnline" :limit="4"></pagination></div>
						</div>
					</div>
					<div class="modal-footer">  
						<button type="button" class="btn btn-default btn-sm"  v-on:click="closeModalDetailPenjualan()">Tutup</button>
					</div> 

				</div>
			</div>
		</div>
		<!--    end small modal -->


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
                	<a href="#laporan_data" role="tab" data-toggle="tab" style="margin-right:10px; " v-on:click="bucketSizeHide()">
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
            				

            			</div><!--RESPONSIVE-->
            		</div>

            		<a href="#" class='btn btn-warning' id="btnExcel" target='blank' :style="'display: none'"><i class="material-icons">file_download</i> Download Excel</a> 

            		<!--CETAK LAPORAN-->
            		<a href="#" class='btn btn-success' id="btnCetak" target='blank' :style="'display: none'"><i class="material-icons">print</i> Cetak Laporan</a>

            	</div>
            </div><!--class="tab-content tab-space"-->
            <vue-simple-spinner v-if="loading"></vue-simple-spinner>
        </div>
    </div>

    <div class="row" v-if="bucketSize">
    	<div class="col-md-6">

    		<div class="card card-chart">
    			<div class="card-header" data-background-color="blue">
    				<chartist v-if="agent" ratio="ct-double-octave" type="Bar" :data="chartData" :options="chartOptions" :responsiveOptions="responsiveOptions"></chartist>
    				<chartist v-else ratio="ct-square" type="Bar" :data="chartData" :options="chartOptions" :responsiveOptions="responsiveOptions"></chartist>
    			</div>
    			<div class="card-content">
    				<h4 class="card-title" v-if="filter.jenis_penjualan == 0">Bucket Size Penjualan POS</h4>
    				<h4 class="card-title" v-else>Bucket Size Penjualan Online</h4>
    				<p class="category"><b> Periode {{dariTanggal(filter)}} - {{sampaiTanggal(filter)}} </b></p>
    			</div>
    		</div>

    	</div>

    	<div class="col-lg-6 col-md-12">
    		<div class="card">
    			<div class="card-content table-responsive">
    				<table class="table table-striped table-hover">
    					<thead class="text-info">
    						<th>Bucket Size</th>
    						<th>Pelanggan</th>
    						<th class="text-right">Total Faktur</th>
    					</thead>
    					<tbody>
    						<tr v-for="dataPenjualan, index in chartData.data">
    							<td>{{chartData.labels[index]}}</td>
    							<td><button type="button" class="btn btn-xs btn-info" v-on:click="lihatPelanggan(dataPenjualan,chartData.labels[index],index)">
    								Lihat
    							</button></td>
    							<td align="right">{{chartData.series[0][index]}}</td>
    						</tr>
    					</tbody>
    				</table>
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
			kode_unik : 0,
			ongkir : 0,
			potongan : 0,
			subtotal : 0,
			id_penjualan : 0,
			detailPenjualan: [],
			detailPenjualanData : {},
			bucketSizePelanggan : '',
			indexbucketSizePelanggan : 0,
			loadMoreLength : 0,
			agent: true,
			bucketSize: false,
			laporanBucketSize: [],
			laporanBucketSizeOnline: [],
			dataPelanggan : [],
			detailPelanggan : {},
			pelangganAction : [],
			actionBucketSize : {
				pelanggan : [],
				kirim_pesan_via : [],
				produk : '',
				pesan : ''
			},
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
				precision: 0,
				masked: false /* doesn't work with directive */
			},
			placeholder_penjualan: {
				placeholder: '--JENIS PENJUALAN--'
			},
			placeholder_produk: {
				placeholder: '--PILIH PRODUK--'
			},
			kirim_pesan_via:{
				placeholder : '--SILAKAN PILIH--',
				maxItems: 2,
			},
			chartData: {
				data: [],
				labels: [],
				series:[]
			},
			chartOptions: {
				seriesBarDistance: 15,
			},
			responsiveOptions : [{
				seriesBarDistance: 10,
				axisX: {
					offset: 60
				},
				axisY: {
					offset: 80,
					labelInterpolationFnc: function(value) {
						return value + ' CHF'
					},
					scaleMinSpace: 15
				}
			}],
			urlPenjualan : window.location.origin+(window.location.pathname).replace("dashboard", "penjualan"),
			url: window.location.origin + (window.location.pathname).replace("dashboard", "laporan-bucket-size"),
			urlDownloadExcel : window.location.origin+(window.location.pathname).replace("dashboard", "laporan-bucket-size/download-excel-pos"), 
			urlDownloadExcelOnline : window.location.origin+(window.location.pathname).replace("dashboard", "laporan-bucket-size/download-excel-online"), 
			urlCetakPos : window.location.origin+(window.location.pathname).replace("dashboard", "laporan-bucket-size/cetak-pos"), 
			urlCetakOnline : window.location.origin+(window.location.pathname).replace("dashboard", "laporan-bucket-size/cetak-online"), 
			pencarian: '',
			loading: false,
			judul : '',
			pilih_semua : false
		}
	},
	mounted () {
		var app = this;		
		var awal_tanggal = new Date();
		app.$store.dispatch('LOAD_PRODUK_LIST')
		awal_tanggal.setDate(1);
		app.filter.dari_tanggal = awal_tanggal;

	},
	computed : mapState ({    
		produk(){
			return this.$store.state.produk
		}
	}),
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
		bucketSizeHide(){
			this.bucketSize = false;
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
					var url = app.url+'/view-new-bucket-size/'+dari_tanggal+'/'+sampai_tanggal+'/'+app.filter.kelipatan;
				}else{
					var url = app.url+'/view-new-bucket-size-online/'+dari_tanggal+'/'+sampai_tanggal+'/'+app.filter.kelipatan;
				}	
				app.bucketSizePenjualan(dari_tanggal,sampai_tanggal,url);
			}
		},
		bucketSizePenjualan(dari_tanggal,sampai_tanggal,url){
			let app = this;
			app.bucketSize = false;
			app.loading = true;
			axios.get(url)
			.then(function (resp) {
				app.agent = resp.data.agent;
				app.chartData.labels = resp.data.labels;
				app.chartData.series = resp.data.series;
				app.chartData.data = resp.data.data;
				app.chartData.warung = resp.data.warung;
				app.bucketSize = true;
				app.loading = false;
				console.log(app.chartData.data);
			})
			.catch(function (resp) {
				alert("Tidak Dapat Memuat Laporan Bucket Size");
				app.bucketSize = true;
				app.loading = false;
			});
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
		lihatPelanggan(dataPelanggan,labels,index){
			$("#modalPelanggan").show(); 
			let app = this;
			app.indexbucketSizePelanggan = index;
			app.bucketSizePelanggan = labels;
			app.loadMoreLength = dataPelanggan.length;
			app.dataPelanggan = dataPelanggan.slice(0, 10);

			let total = 0
			let pelanggan_id = 0

			app.pelangganAction.splice(0)
			for (var j = 0 ; j < dataPelanggan.length; j++) {

				total += parseFloat(dataPelanggan[j].total)

				if (dataPelanggan[j].pelanggan_id > 0 || dataPelanggan[j].id_pelanggan > 0) {		

					if (pelanggan_id != dataPelanggan[j].pelanggan.id) {

						pelanggan_id = dataPelanggan[j].pelanggan.id

						app.pelangganAction.push({
							id: dataPelanggan[j].pelanggan.id,
							name: dataPelanggan[j].pelanggan.name
						});

					}		

				}

			}
			app.dataPelanggan.total = total
			console.log(dataPelanggan)
			console.log(app.dataPelanggan)
			console.log(app.pelangganAction)
		},
		closeModalPelanggan(){
			$("#modalPelanggan").hide(); 
		},
		loadMore(length){
			let app = this;
			let limit = length + 10;
			let newData = app.chartData.data[app.indexbucketSizePelanggan].slice(length,limit);

			for (var i = 0 ; i < newData.length; i++) {
				app.dataPelanggan.push(newData[i]);
			}
		},
		cekDetailPelanggan(data){
			let app = this;
			app.detailPelanggan = data;
			console.log(app.detailPelanggan);
			$("#modalPelanggan").hide(); 
			$("#modal_detail_pelanggan").show(); 
		},
		closeModal(){
			$("#modal_detail_pelanggan").hide(); 
			$("#modalPelanggan").show(); 
		},
		closeModalAction(){
			$("#modal_action").hide(); 
			$("#modalPelanggan").show(); 
		},
		closeModalDetailPenjualan(){
			$("#modalDetailPenjualan").hide();
			$("#modalPelanggan").show(); 
		},
		getProdukOnline(page,id,kode_unik,ongkir){
			$("#modalPelanggan").hide(); 
			$("#modalDetailPenjualan").show();
			let app = this; 
			app.id_penjualan = id;
			app.kode_unik = kode_unik
			app.ongkir = ongkir

			if (typeof page === 'undefined') {
				page = 1;
			}
			axios.get(app.urlPenjualan+'/view-detail-penjualan-online/'+app.id_penjualan+'?page='+page)
			.then(function (resp) {
				app.responData(resp)
			})
			.catch(function (resp) {
				console.log(resp);
				alert("Tidak Dapat Memuat Detail Penjualan");
			});
		},
		getProdukPos(page,id,potongan) {
			$("#modalPelanggan").hide(); 
			$("#modalDetailPenjualan").show();

			let app = this; 
			app.id_penjualan = id;
			app.potongan = potongan

			if (typeof page === 'undefined') {
				page = 1;
			}
			axios.get(app.urlPenjualan+'/view-detail-penjualan/'+app.id_penjualan+'?page='+page)
			.then(function (resp) {
				app.responData(resp)
			})
			.catch(function (resp) {
				console.log(resp);
				alert("Tidak Dapat Memuat Detail Penjualan");
			});
		},
		responData(resp){
			let app = this
			app.detailPenjualan = resp.data.data
			app.detailPenjualanData = resp.data
			console.log(app.detailPenjualanData)

			var subtotal = 0;
			$.each(app.detailPenjualan, function (i, item) {
				subtotal += parseFloat(app.detailPenjualan[i].subtotal)
			});
			app.subtotal = subtotal
		},
		action(){
			$("#modalPelanggan").hide(); 
			$("#modal_action").show();
		},
		pilihSemua(){
			let app = this
			let pilih_semua = app.pilih_semua
			app.removeValue()
			
			if (pilih_semua == true) {

				$.each(app.pelangganAction, function (i, item) { 
					app.actionBucketSize.pelanggan.push(app.pelangganAction[i].id)
				});

			}else{
				app.removeValue()
			}
			console.log(app.actionBucketSize.pelanggan)
		},
		removeValue(){
			let app = this
			app.actionBucketSize.pelanggan.splice(0)
		},
		saveForm(){
			
			let app = this
			let newActionBucketSize = app.actionBucketSize

			app.loading = true
			$("#kirimPesan").html('Mohon Tunggu, Pesan Sedang dikirim ... <i v-if="loading" class="fa fa-spinner fa-spin"></i>')
			$("#kirimPesan").prop('disabled', true)
			axios.post(app.url+'/kirim-pesan',newActionBucketSize)
			.then(resp => {
				app.loading = false
				app.closeModalAction()
				app.alert("Berhasil Mengirimkan Pesan")
				$("#kirimPesan").html('Kirim')
				$("#kirimPesan").prop('disabled', false)
			})
			.catch(err => {
				console.log(err)
				app.loading = false
				app.errors = err.response.data.errors;
				alert("Maaf, Silakan cek kembali form input anda!")
				$("#kirimPesan").html('Kirim')
				$("#kirimPesan").prop('disabled', false)
			})
			
		},
		alert(pesan) {
			this.$swal({
				title: "Berhasil ",
				text: pesan,
				icon: "success",
				buttons: false,
				timer: 1000,

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
			$("#btnCetak").show(); 

			if (app.filter.jenis_penjualan == '0') { 
				$("#btnExcel").attr('href', app.urlDownloadExcel+'/'+dari_tanggal+'/'+sampai_tanggal+'/'+filter.kelipatan); 
				$("#btnCetak").attr('href', app.urlCetakPos+'/'+dari_tanggal+'/'+sampai_tanggal+'/'+filter.kelipatan); 
			}else{ 
				$("#btnExcel").attr('href', app.urlDownloadExcelOnline+'/'+dari_tanggal+'/'+sampai_tanggal+'/'+filter.kelipatan); 
				$("#btnCetak").attr('href', app.urlCetakOnline+'/'+dari_tanggal+'/'+sampai_tanggal+'/'+filter.kelipatan); 
			} 
		}, 
	}
}
</script>