<script>
	import {Bar} from 'vue-chartjs'

	export default {
		extends: Bar,
		data: function () {
			return {
				filter: {
					tanggal : ''
				},
				separator: {
					decimal: ',',
					thousands: '.',
					prefix: '',
					suffix: '',
					precision: 2,
					masked: false /* doesn't work with directive */
				},
				url: window.location.origin + (window.location.pathname).replace("dashboard", "grafik-jam-transaksi-penjualan"),
			}
		},
		mounted () {
			var app = this;
			var tanggal = app.$route.params.tanggal;
			app.filter.tanggal = tanggal;
			app.barChart();
		},
		methods: {	
			barChart(){
				var app = this;
				var filter = app.filter;
				axios.get(app.url+'/view/'+filter.tanggal)
				.then(function (resp) {
					app.renderChart(
					{
						labels: resp.data.waktu_jual,
						datasets: [{
							label: 'Laporan Jam Transaksi Penjualan',
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