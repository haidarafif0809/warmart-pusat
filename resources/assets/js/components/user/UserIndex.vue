<template>


<div class="row" >
		
		<div class="col-md-12">
			<ul class="breadcrumb">
				<li><a href="#">Home</a></li>
				<li class="active">User</li>
			</ul>
 
			
			   <div class="card">
			   	   <div class="card-header card-header-icon" data-background-color="purple">
                       <i class="material-icons">dns</i>
                                </div>
                      <div class="card-content">
                         <h4 class="card-title"> User </h4>
	
                       <div class="toolbar">
                    
                        <p> <router-link :to="{name: 'createUser'}" class="btn btn-primary">Tambah User</router-link></p>
                         	
                         </div>
     		<div class=" table-responsive ">
            	<div  align="right">
              	pencarian
             	 <input type="text" name="pencarian" v-model="pencarian" placeholder="Kolom Pencarian" >
            	</div>

            		<table class="table table-striped table-hover ">
		                    <thead class="text-primary">
		                    <tr>
		 						                     
		                        <th>Nama</th>
		                        <th>No. Telpon</th>
		                        <th>Username</th>
		                        <th>Alamat</th>
		                        <th>Otoritas</th>
								<th>Aksi</th>
		                    </tr>
		                    </thead>
		                    <tbody v-if="users.length" class="data-ada">
		                    <tr v-for="user, index in users" >
		                        <td>{{ user.name }}</td>
		                         <td>{{ user.no_telp }}</td>
		                          <td>{{ user.email }}</td>
		                           <td>{{ user.alamat }}</td>
		                            <td>{{ user.role }}</td>
		                        <td> 
		                           <router-link :to="{name: 'editUser', params: {id: user.id}}" class="btn btn-xs btn-default" v-bind:id="'edit-' + user.id" >
                                Edit 
                           			 </router-link> <a href="#"
                               class="btn btn-xs btn-danger" v-bind:id="'delete-' + user.id"
                               v-on:click="deleteEntry(user.id, index,user.nama_user)">
                                Delete
                            </a></td>
		          
		                
		                    </tr>
                      </tbody>
                      <tbody class="data-tidak-ada" v-else>
                                  <tr ><td colspan="4"  class="text-center">Tidak Ada Data</td></tr>
                      </tbody>
		          </table>

		                       <vue-simple-spinner v-if="loading"></vue-simple-spinner>
                 
            <div align="right"><pagination :data="usersData" v-on:pagination-change-page="getResults"></pagination></div>

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
                users: [],
                usersData: {},
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
                    app.users = resp.data.data;
                    app.usersData = resp.data;
                    app.loading = false;
                })
                .catch(function (resp) {
                    console.log(resp);
                    app.loading = false;
                    alert("Could not load users");
                });
       },
      getHasilPencarian(page){
        var app = this;
        if (typeof page === 'undefined') {
          page = 1;
        }
        axios.get(app.url+'/pencarian?search='+app.pencarian+'&page='+page)
                .then(function (resp) {
                    app.users = resp.data.data;
                    app.usersData = resp.data;
                })
                .catch(function (resp) {
                    console.log(resp);
                    alert("Could not load satuans");
                });
      },
		  alert(pesan) {
              this.$swal({
                  title: "Berhasil Menghapus User!",
                  text: pesan,
                  icon: "success",
                });
            },
            deleteEntry(id, index,name){
		     if (confirm("Yakin Ingin Menghapus User "+name+" ?")) {
                    var app = this;
                    axios.delete(app.url+'/' + id)
                        .then(function (resp) {
                          app.getResults();
							app.alert("Berhasil Menghapus User "+name)
						})
                        .catch(function (resp) {
                            alert("Could not delete company");
                        });
                }
            }
         }
	  }
</script>