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
                    
                        <p> <router-link :to="{name: 'createBank'}" class="btn btn-primary">Tambah Bank</router-link></p>
                         	
                         </div>
     

					<div class=" table-responsive ">
            <div  align="right">
              pencarian
              <input type="text" name="pencarian" v-model="pencarian" placeholder="Kolom Pencarian" >
            </div>
            
						<table class="table table-striped table-hover ">
		                    <thead class="text-primary">
		                    <tr>
		                      
		                        <th>Nama Bank</th>
		                        <th>A.N Bank</th>
		                        <th>No Rekening</th>
		                        <th>Aksi</th>
		             
		                    </tr>
		                    </thead>
		                    <tbody v-if="banks.length" class="data-ada">
		                    <tr v-for="bank, index in banks" >
		                    
		                        <td>{{ bank.nama_bank }}</td>
		                        <td>{{ bank.atas_nama }}</td>
		                        <td>{{ bank.no_rek }}</td>
		                        <td> 
		                           <router-link :to="{name: 'editBank', params: {id: bank.id}}" class="btn btn-xs btn-default" v-bind:id="'edit-' + bank.id" >
                                Edit 
                           			 </router-link> <a href="#"
                               class="btn btn-xs btn-danger" v-bind:id="'delete-' + bank.id"
                               v-on:click="deleteEntry(bank.id, index,bank.nama_bank)">
                                Delete
                            </a></td>
		          
		                
		                    </tr>
                      </tbody>
                      <tbody class="data-tidak-ada" v-else>
                                  <tr ><td colspan="4"  class="text-center">Tidak Ada Data</td></tr>
                      </tbody>
		                </table>

             <vue-simple-spinner v-if="loading"></vue-simple-spinner>
                 
            <div align="right"><pagination :data="banksData" v-on:pagination-change-page="getResults"></pagination></div>
					</div>
             
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
                url : window.location.origin+window.location.pathname,
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
        // whenever question changes, this function will run
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
                    app.banks = resp.data.data;
                    app.banksData = resp.data;
                    app.loading = false;
                })
                .catch(function (resp) {
                    console.log(resp);
                    app.loading = false;
                    alert("Could not load banks");
                });
       },
      getHasilPencarian(page){
        var app = this;
        if (typeof page === 'undefined') {
          page = 1;
        }
        axios.get(app.url+'/pencarian?search='+app.pencarian+'&page='+page)
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
                    axios.delete(app.url+'/' + id)
                        .then(function (resp) {
                          app.getResults();
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
