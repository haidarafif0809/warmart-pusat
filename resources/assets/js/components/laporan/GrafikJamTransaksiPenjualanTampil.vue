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
				axios.get(app.url+'/view/'+filter.dari_tanggal)
				.then(function (resp) {
					app.renderChart(
					{
						labels: "Grafik Jam Transaksi Penjualan" resp.data.tanggal,
						datasets: [{
							label: '',
							backgroundColor: resp.data.color,
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