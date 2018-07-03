window.Vue = require('vue');
import Vue from 'vue'

Vue.component('form-tambah-pelanggan', {
	props: ['data','errors'],
	template: `<span>    
                    <div class="form-group">
                        <input class="form-control" reeuired autocomplete="off" placeholder="Nama Pelanggan" type="text" name="name"
                        v-bind:value="data.name" v-on:input="data.name = $event.target.value" autofocus="">
                        <span v-if="errors.name" id="name_error" class="label label-danger">{{ errors.name[0] }}</span>
                    </div>

                    <div class="form-group">
                        <input class="form-control" required autocomplete="off" placeholder="No. Telpon" type="number" v-bind:value="data.no_telp"
                        v-on:input="data.no_telp = $event.target.value" name="no_telp"  autofocus="">
                        <span v-if="errors.no_telp" id="no_telp_error" class="label label-danger">{{ errors.no_telp[0] }}</span>
                    </div>

                    <div class="form-group">
                        <input class="form-control" required autocomplete="off" placeholder="Password" type="password" v-bind:value="data.password"
                        v-on:input="data.password = $event.target.value" name="password"  autofocus="">
                        <span v-if="errors.password" id="password_error" class="label label-danger">{{ errors.password[0] }}</span>
                    </div>

                    <div class="form-group">
                        <input class="form-control" autocomplete="off" placeholder="Kode Customer(Jika ada)" type="text" 
                        v-bind:value="data.kode_customer" v-on:input="data.kode_customer = $event.target.value"
                        name="kode_customer"  autofocus="">
                        <span v-if="errors.kode_customer" id="kode_customer_error" class="label label-danger">{{ errors.kode_customer[0] }}</span>
                    </div>

                    <div class="form-group">
                        <input class="form-control" required autocomplete="off" placeholder="Email" type="email" v-bind:value="data.email" 
                        v-on:input="data.email = $event.target.value" name="email"  autofocus="">
                        <span v-if="errors.email" id="email_error" class="label label-danger">{{ errors.email[0] }}</span>
                    </div>

                    <div class="form-group">
                        <input class="form-control" required autocomplete="off" placeholder="Alamat" type="text" v-bind:value="data.alamat" 
                        v-on:input="data.alamat = $event.target.value" name="alamat"  autofocus="">
                        <span v-if="errors.alamat" id="alamat_error" class="label label-danger">{{ errors.alamat[0] }}</span>
                    </div>

               </span>
               `,
})

export default Vue
