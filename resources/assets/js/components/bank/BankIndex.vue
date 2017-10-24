<template>
<div class="row" >
		
		<div class="col-md-12">
			<ul class="breadcrumb">
				<li><a href="#">Home</a></li>
				<li class="active">Bank</li>
			</ul>
 
			
			   <div class="card">
			   	   <div class="card-header card-header-icon" data-background-color="purple">
                       <i class="material-icons">payment</i>
                                </div>
                      <div class="card-content">
                         <h4 class="card-title"> Bank </h4>
	
                       <div class="toolbar">
                    

                         	<router-link :to="{name: 'createBank'}" class="btn btn-primary">Tambah Bank</router-link>
                         </div>
             
					<div class=" table-responsive material-datatables">
						<table class="table table-striped table-hover ">
		                    <thead class="text-primary">
		                    <tr>
		                      
		                        <th>Nama Bank</th>
		                        <th>A.N Bank</th>
		                        <th>No Rekening</th>
		                        <th>Aksi</th>
		             
		                    </tr>
		                    </thead>
		                    <tbody>
		                    <tr v-for="bank, index in banks">
		                    
		                        <td>{{ bank.nama_bank }}</td>
		                        <td>{{ bank.atas_nama }}</td>
		                        <td>{{ bank.no_rek }}</td>
		                        <td> 
		                           <router-link :to="{name: 'editBank', params: {id: bank.id}}" class="btn btn-xs btn-default">
                                Edit
                           			 </router-link> <a href="#"
                               class="btn btn-xs btn-danger"
                               v-on:click="deleteEntry(bank.id, index,bank.nama_bank)">
                                Delete
                            </a></td>
		          
		                
		                    </tr>
		                    </tbody>
		                </table>
                 

					</div>
             <pagination :data="banksData" v-on:pagination-change-page="getResults"></pagination>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
    export default {
        data: function () {
            return {
                banks: [],
                banksData: {},
                url : window.location.origin+window.location.pathname
            }
        },
        mounted() {
            var app = this;
            app.getResults();

      },
        created() {
        // Fetch initial results
      
      },
        methods: {

            // Our method to GET results from a Laravel endpoint
      getResults(page) {
          var app = this;
        if (typeof page === 'undefined') {
          page = 1;
        }
         // Using vue-resource as an example
      
            axios.get(app.url+'/view?page='+page)
                .then(function (resp) {
                    app.banks = resp.data.data;
                    app.banksData = resp.data;
                })
                .catch(function (resp) {
                    console.log(resp);
                    alert("Could not load banks");
                });

      },
		  alert(pesan) {
              this.$swal({
                  title: "Berhasil Menghapus Bank!",
                  text: pesan,
                  icon: "success",
                });
            },
	      deleteEntry(id, index,nama_bank) {
                if (confirm("Yakin Ingin Menghapus Bank "+nama_bank+" ?")) {
                    var app = this;
                    axios.delete('http://localhost/warmart/public/bank/' + id)
                        .then(function (resp) {
                            app.banks.splice(index, 1);
							app.alert("Berhasil Menghapus Bank "+nama_bank)
						})
                        .catch(function (resp) {
                            alert("Could not delete company");
                        });
                }
            }
	  }
    }
</script>
