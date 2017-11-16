<template>

	<div class="row">
		<div class="col-md-12">
			<ul class="breadcrumb">
				<li><a href="#">Home</a></li>
				<li class="active">Error Log</li>
			</ul>

			<div class="card">
				<div class="card-header card-header-icon" data-background-color="purple">
	                <i class="material-icons">error</i>
	            </div>
	            <div class="card-content">
	            	<h4 class="card-title"> Error Log </h4>

	            	<div class=" table-responsive ">
			            <div  align="right">
			               Pencarian
			               <input type="text" name="pencarian" v-model="pencarian" placeholder="Kolom Pencarian" >
			            </div>

			            <table class="table table-striped table-hover">
			              <thead class="text-primary">
			                <tr>
			                  <th>Id</th>
			                  <th>Kode</th>
			                  <th>Method</th>
			                  <th>Route</th>
			                  <th>Pesan Error</th>
			                  <th>Waktu</th>
			                </tr>
			              </thead>

			              <tbody v-if="errors.length > 0 && loading== false" class="data-ada">
			                <tr v-for="error, index in errors">
			                  <td>{{ error.error.id }}</td>
			                  <td>{{ error.error.code }}</td>
			                  <td>{{ error.method }}</td>
			                  <td>{{ error.route }}</td>
			                  <td>{{ error.error.message }}</td>
			                  <td>{{ error.error.created_at }}</td>
			                </tr>
			              </tbody>
			              <!--JIKA DATA CUSTOMER KOSONG-->
			              <tbody class="data-tidak-ada" v-else-if="errors.length == 0 && loading== false">
			                <tr>
			                  <td colspan="4"  class="text-center">Tidak Ada Data</td>
			                </tr>
			              </tbody>
			            </table>

			            <!--LOADING-->
			            <vue-simple-spinner v-if="loading"></vue-simple-spinner>
			            <!--PAGINATION TABLE-->
			            <div align="right"><pagination :data="errorsData" v-on:pagination-change-page="getResults"></pagination></div>

			        </div><!-- /END RESPONSIVE-->
				</div>
			</div>
		</div>
	</div>

</template>

<script>
	export default {
		data: function () {
    		return {
		      errors: [],
		      errorsData: {
		      },
		      url : window.location.origin+(window.location.pathname).replace("dashboard", "error"),
		      pencarian: '',
		      contoh : '',
		      loading: true
		    }
		},
		mounted() {
			var app = this;
			app.getResults();	            
		},
		watch: {
	        pencarian: function (newQuestion) {
	          this.getHasilPencarian()  
	        }
    	},
    	methods: {
    		getResults(page) {
	        var app = this;
	        if (typeof page === 'undefined') {
	        	page = 1;
	        }
	        axios.get(app.url+'/view?page='+page)
	        .then(function (resp) {
	            app.errors = resp.data.data;
	            app.errorsData = resp.data;
	            app.loading = false;
        	})
        	.catch(function (resp) {
	            console.log(resp);
	            app.loading = false;
	            alert("Could not load errors");
	        });
        },
        getHasilPencarian(page){
          var app = this;
          if (typeof page === 'undefined') {
            page = 1;
          }
          axios.get(app.url+'/pencarian?search='+app.pencarian+'&page='+page)
          .then(function (resp) {
            app.errors = resp.data.data;
            app.errorsData = resp.data;
          })
          .catch(function (resp) {
            console.log(resp);
            alert("Could not load errors");
          });
        }
      }
    }
</script>