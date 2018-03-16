<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $this->call(UsersSeeder::class);
        $this->call(BankSeeder::class);
        $this->call(KelurahanSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(KasSeeder::class);
        $this->call(BarangSeeder::class);
        $this->call(SatuanSeeder::class);
        $this->call(KategoriBarangSeeder::class);
        $this->call(KategoriTransaksiSeeder::class);
        $this->call(SettingAplikasiSeeder::class);
        $this->call(SuplierSeeder::class);
        $this->call(KasMasukSeeder::class);
        $this->call(SettingFooterSeeder::class);
        $this->call(SettingVerifikasiSeeder::class);
        $this->call(SettingTransferBankSeeder::class);
        $this->call(SettingJasaPengirimanSeeder::class);
    }
}
