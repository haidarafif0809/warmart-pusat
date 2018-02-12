<script>

	import {Bar} from 'vue-chartjs'

	export default {
		extends: Bar,
		data: function () {
			return {
				filter: {
					dari_tanggal: '',
					sampai_tanggal: new Date(),
					kelipatan: '',
				},
				separator: {
					decimal: ',',
					thousands: '.',
					prefix: '',
					suffix: '',
					precision: 2,
					masked: false /* doesn't work with directive */
				},
				url: window.location.origin + (window.location.pathname).replace("dashboard", "laporan-bucket-size"),
			}
		},
		mounted () {
			var app = this;		
			var awal_tanggal = new Date();
			awal_tanggal.setDate(1);

			app.filter.dari_tanggal = awal_tanggal;
			app.barChart();
		},
		methods: {	
			barChart(){var app = this;
				axios.get(app.url+'/view')
				.then(function (resp) {
					console.log(resp.data)

					app.renderChart(
					{
						labels: resp.data.kelipatan,
						datasets: [{
							label: 'Satu',
							backgroundColor: '#FC2525',
							data: resp.data.datasets.total_faktur
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