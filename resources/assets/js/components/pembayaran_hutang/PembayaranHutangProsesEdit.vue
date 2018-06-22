<template>
	<div class="row">
		<div class="col-md-12">
			<ul class="breadcrumb" style="margin-bottom: 1px; margin-top: 1px;">
				<li><router-link :to="{name: 'indexDashboard'}">Home</router-link></li>
				<li><router-link :to="{name: 'indexPembayaranHutang'}">Pembayaran Hutang</router-link></li>
				<li class="active">Proses Edit Pembayaran Hutang</li>
			</ul>
		</div>
	</div>
</template>

<script>
	export default {
		data: function () {
			return {
				url : window.location.origin+(window.location.pathname).replace("dashboard", "pembayaran-hutang")
			}
		},
		mounted() {   
			var app = this;
			app.insertEditTbsPembayaranHutang();
		},
		methods: {
			insertEditTbsPembayaranHutang() {
				var app = this; 			
				var id = app.$route.params.id;
				axios.get(app.url+'/'+id+'/edit')
				.then(function (resp) {
					app.$router.replace('/edit-pembayaran-hutang/'+id);
				})
				.catch(function (resp) {
					console.log(resp);
					alert("Tidak Dapat Memuat Edit Pembayaran Hutang");
					app.$router.replace('/pembayaran-hutang');
				});
			}
		}
	}
</script>