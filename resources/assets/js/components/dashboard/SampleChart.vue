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
				}
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
			barChart(){

				console.log("12345")
				var app = this;
				app.renderChart({
					labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
					datasets: [
					{
						label: 'Satu',
						backgroundColor: '#FC2525',
						data: [40, 39, 10, 40, 39, 100, 40]
					},{
						label: 'Dua',
						backgroundColor: '#05CBE1',
						data: [60, 55, 32, 10, 52, 24, 53]
					},{
						label: 'Tiga',
						backgroundColor: '#65499c',
						data: [30, 25, 15, 8, 72, 52, 63]
					},{
						label: 'Empat',
						backgroundColor: '#ff6090',
						data: [90, 65, 32, 20, 12, 74, 45]
					},{
						label: 'Lima',
						backgroundColor: '#00701a',
						data: [25, 77, 24, 20, 52, 85, 87]
					}
					]
				},
				{
					responsive: true, maintainAspectRatio: false
				})
			}
		}
	}
</script>