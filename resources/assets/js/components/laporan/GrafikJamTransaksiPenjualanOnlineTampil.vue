<script>
	import {Bar} from 'vue-chartjs'

	export default {
		extends: Bar,
		data: function () {
			return {
				filter: {
					dari_tanggal : '',
					sampai_tanggal: '',
				},
				separator: {
					decimal: ',',
					thousands: '.',
					prefix: '',
					suffix: '',
					precision: 2,
					masked: false /* doesn't work with directive */
				},
				url: window.location.origin + (window.location.pathname).replace("dashboard", "grafik-jam-transaksi-penjualan-online"),
			}
		},
		mounted () {
			var app = this;
			var dari_tanggal = app.$route.params.dari_tanggal;
			var sampai_tanggal = app.$route.params.sampai_tanggal;
			app.filter.dari_tanggal = dari_tanggal;
			app.filter.sampai_tanggal = sampai_tanggal;
			app.barChart();
		},
		methods: {	
			tanggal(tanggal){
				return moment(String(tanggal)).format('DD/MM/YYYY')
			},
			barChart(){
				var app = this;
				var filter = app.filter;
				axios.get(app.url+'/view/'+filter.dari_tanggal+'/'+filter.sampai_tanggal)
				.then(function (resp) {
					app.renderChart(
					{
						labels: resp.data.waktu_jual,
						datasets: [{
							label: 'Laporan Jam Transaksi Penjualan '+ app.tanggal(filter.dari_tanggal) +" - "+ app.tanggal(filter.sampai_tanggal),
							backgroundColor: resp.data.color,
							data: resp.data.hitung,
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