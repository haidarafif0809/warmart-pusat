<?php

use Illuminate\Database\Seeder;
use App\Permission;
use App\Role;

class PermissionLaporanPiutangBeredarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $piutang_beredar = Permission::create(['name'=> 'piutang_beredar','display_name' => 'Piutang Beredar','grup'=>'laporan']);
    }
}
