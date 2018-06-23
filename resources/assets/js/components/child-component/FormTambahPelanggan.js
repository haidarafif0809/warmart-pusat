window.Vue = require('vue');
import Vue from 'vue'

Vue.component('form-tambah-pelanggan', {
	props: ['data'],
	template: `<span>    
                    <div class="form-group">
                        <input class="form-control" reeuired autocomplete="off" placeholder="Nama Pelanggan" type="text" name="name"
                        v-bind:value="data.name" v-on:input="data.name = $event.target.value" autofocus="">
                    </div>

                    <div class="form-group">
                        <input class="form-control" required autocomplete="off" placeholder="No. Telpon" type="number" v-bind:value="data.no_telp"
                        v-on:input="data.no_telp = $event.target.value" name="no_telp"  autofocus="">
                    </div>

                    <div class="form-group">
                        <input class="form-control" required autocomplete="off" placeholder="Password" type="password" v-bind:value="data.password"
                        v-on:input="data.password = $event.target.value" name="password"  autofocus="">
                    </div>

                    <div class="form-group">
                        <input class="form-control" autocomplete="off" placeholder="Kode Customer(Jika ada)" type="text" 
                        v-bind:value="data.kode_customer" v-on:input="data.kode_customer = $event.target.value"
                        name="kode_customer"  autofocus="">
                    </div>

                    <div class="form-group">
                        <input class="form-control" required autocomplete="off" placeholder="Email" type="email" v-bind:value="data.email" 
                        v-on:input="data.email = $event.target.value" name="email"  autofocus="">
                    </div>

                    <div class="form-group">
                        <input class="form-control" required autocomplete="off" placeholder="Alamat" type="text" v-bind:value="data.alamat" 
                        v-on:input="data.alamat = $event.target.value" name="alamat"  autofocus="">
                    </div>

               </span>
               `,
})

export default Vue
