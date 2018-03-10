<script>
	import {Bar} from 'vue-chartjs'

	export default {
		extends: Bar,
		data: function () {
			return {
				filter: {
					dari_tanggal: '',
					sampai_tanggal: '',
					tampil_terbaik: '',
				},
				url: window.location.origin + (window.location.pathname).replace("dashboard", "laporan-penjualan-terbaik"),
			}
		},
		mounted () {
			var app = this;
			var dari_tanggal = app.$route.params.dari_tanggal;
			var sampai_tanggal = app.$route.params.sampai_tanggal;
			var tampil_terbaik = app.$route.params.tampil_terbaik;

			app.filter.dari_tanggal = dari_tanggal;
			app.filter.sampai_tanggal = sampai_tanggal;
			app.filter.tampil_terbaik = tampil_terbaik;

			app.barChart();
		},
		methods: {	
			tanggal(tanggal){
				return moment(String(tanggal)).format('DD/MM/YYYY')
			},
			barChart(){
				var app = this;
				var filter = app.filter;
				axios.get(app.url+'/view/'+filter.dari_tanggal+'/'+filter.sampai_tanggal+'/'+filter.tampil_terbaik)
				.then(function (resp) {
					app.renderChart(
					{
						labels: resp.data.nama_barang,
						datasets: [{
							label: 'Item',
							backgroundColor: resp.data.color,
							data: resp.data.jumlah_produk,
						}]
					},
					{
						responsive: true, maintainAspectRatio: false
					})

				})
			}
		}
	}
</script>