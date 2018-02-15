<script>
	import {Line} from 'vue-chartjs'

	export default {
		extends: Line,
		data: function () {
			return {
				filter: {
					dari_tanggal: '',
					sampai_tanggal: '',
				},
				url: window.location.origin + (window.location.pathname).replace("dashboard", "laporan-penjualan-harian"),
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
						labels: resp.data.tanggal,
						datasets: [{
							label: 'LAPORAN HARIAN PENJUALAN POS '+ app.tanggal(filter.dari_tanggal) +" - "+ app.tanggal(filter.sampai_tanggal),
							backgroundColor: '#ed79a1',
							data: resp.data.total,
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