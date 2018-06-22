window.Vue = require('vue');
import Vue from 'vue'

Vue.component('form-tambah-pelanggan', {
	props: ['value'],
	template: `<span>    
                    <div class="form-group">
                        <input class="form-control" reeuired autocomplete="off" placeholder="Nama Pelanggan" type="text" name="name"
                        v-bind:value="value.name" v-on:input="$emit('input', $event.target.value.name)" autofocus="">
                    </div>

                    <div class="form-group">
                        <input class="form-control" required autocomplete="off" placeholder="No. Telpon" type="number" v-bind-value="value.no_telp"
                        v-on:input="$emit('input', $event.target.value.no_telp)" name="no_telp"  autofocus="">
                    </div>

                    <div class="form-group">
                        <input class="form-control" required autocomplete="off" placeholder="Password" type="password" v-bind-value="value.password"
                        v-on:input="$emit('input', $event.target.value.password)" name="password"  autofocus="">
                    </div>

                    <div class="form-group">
                        <input class="form-control" autocomplete="off" placeholder="Kode Customer(Jika ada)" type="text" 
                        v-bind-value="value.kode_customer" v-on:input="$emit('input', $event.target.value.kode_customer)"
                        name="kode_customer"  autofocus="">
                    </div>

                    <div class="form-group">
                        <input class="form-control" required autocomplete="off" placeholder="Email" type="email" v-bind-value="value.email" 
                        v-on:input="$emit('input', $event.target.value.email)" name="email"  autofocus="">
                    </div>

                    <div class="form-group">
                        <input class="form-control" required autocomplete="off" placeholder="Alamat" type="text" v-bind-value="value.alamat" 
                        v-on:input="$emit('input', $event.target.value.alamat)" name="alamat"  autofocus="">
                    </div>

                    <div class="form-group">
                        <datepicker :input-class="'form-control'" placeholder="Tanggal Lahir" v-bind-value="value.tgl_lahir" 
                        v-on:input="$emit('input', $event.target.value.tgl_lahir)" name="uniquename" v-bind:id="'tanggal_lahir'"></datepicker>
                    </div>

               </span>
               `,
})

export default Vue
