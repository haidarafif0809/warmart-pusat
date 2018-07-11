<?php

use Illuminate\Database\Seeder;
use App\Permission;
use App\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // MASTER DATA          
        $lihat_master_data = Permission::create(['name'=> 'lihat_master_data','display_name' => 'Lihat Master Data','grup'=>'master_data']);
        //USER
        $tambah_user = Permission::create(['name'=> 'tambah_user','display_name' => 'Tambah User','grup'=>'user']);
        $edit_user = Permission::create(['name'=> 'edit_user','display_name' => 'Edit User','grup'=>'user']);
        $hapus_user = Permission::create(['name'=> 'hapus_user','display_name' => 'Hapus User','grup'=>'user']);
        $lihat_user = Permission::create(['name'=> 'lihat_user','display_name' => 'Lihat User','grup'=>'user']);
        $konfirmasi_user = Permission::create(['name'=> 'konfirmasi_user','display_name' => 'Konfirmasi User','grup'=>'user']);
        $reset_password_user = Permission::create(['name'=> 'reset_password_user','display_name' => 'Reset Password User','grup'=>'user']);
        //OTORITAS
        $tambah_otoritas = Permission::create(['name'=> 'tambah_otoritas','display_name' => 'Tambah Otoritas','grup'=>'otoritas']);
        $edit_otoritas = Permission::create(['name'=> 'edit_otoritas','display_name' => 'Edit Otoritas','grup'=>'otoritas']);
        $hapus_otoritas = Permission::create(['name'=> 'hapus_otoritas','display_name' => 'Hapus Otoritas','grup'=>'otoritas']);
        $lihat_otoritas = Permission::create(['name'=> 'lihat_otoritas','display_name' => 'Lihat Otoritas','grup'=>'otoritas']);
        $permission_otoritas = Permission::create(['name'=> 'permission_otoritas','display_name' => 'Permission Otoritas','grup'=>'otoritas']);
        //BANK
        $tambah_bank = Permission::create(['name'=> 'tambah_bank','display_name' => 'Tambah Bank','grup'=>'bank']);
        $edit_bank = Permission::create(['name'=> 'edit_bank','display_name' => 'Edit Bank','grup'=>'bank']);
        $hapus_bank = Permission::create(['name'=> 'hapus_bank','display_name' => 'Hapus Bank','grup'=>'bank']);
        $lihat_bank = Permission::create(['name'=> 'lihat_bank','display_name' => 'Lihat Bank','grup'=>'bank']);
        //CUSTOMER
        $tambah_customer = Permission::create(['name'=> 'tambah_customer','display_name' => 'Tambah Customer','grup'=>'customer']);
        $edit_customer = Permission::create(['name'=> 'edit_customer','display_name' => 'Edit Customer','grup'=>'customer']);
        $hapus_customer = Permission::create(['name'=> 'hapus_customer','display_name' => 'Hapus Customer','grup'=>'customer']);
        $lihat_customer = Permission::create(['name'=> 'lihat_customer','display_name' => 'Lihat Customer','grup'=>'customer']);
        //KOMUNITAS
        $tambah_komunitas = Permission::create(['name'=> 'tambah_komunitas','display_name' => 'Tambah Komunitas','grup'=>'komunitas']);
        $edit_komunitas = Permission::create(['name'=> 'edit_komunitas','display_name' => 'Edit Komunitas','grup'=>'komunitas']);
        $hapus_komunitas = Permission::create(['name'=> 'hapus_komunitas','display_name' => 'Hapus Komunitas','grup'=>'komunitas']);
        $lihat_komunitas = Permission::create(['name'=> 'lihat_komunitas','display_name' => 'Lihat Komunitas','grup'=>'komunitas']);
        //WARUNG
        $tambah_warung = Permission::create(['name'=> 'tambah_warung','display_name' => 'Tambah Warung','grup'=>'warung']);
        $edit_warung = Permission::create(['name'=> 'edit_warung','display_name' => 'Edit Warung','grup'=>'warung']);
        $hapus_warung = Permission::create(['name'=> 'hapus_warung','display_name' => 'Hapus Warung','grup'=>'warung']);
        $lihat_warung = Permission::create(['name'=> 'lihat_warung','display_name' => 'Lihat Warung','grup'=>'warung']);


         $role = Role::find(1);
          $role->attachPermissions([$lihat_master_data, 
                $tambah_user,
                $edit_user,
                $hapus_user,
                $lihat_user,
                $konfirmasi_user,
                $reset_password_user,
                $tambah_otoritas,
                $edit_otoritas,
                $hapus_otoritas, 
                $lihat_otoritas,
                $permission_otoritas,
                $tambah_bank,
                $edit_bank,
                $hapus_bank, 
                $lihat_bank,
                $tambah_customer,
                $edit_customer,
                $hapus_customer, 
                $lihat_customer, 
                $tambah_komunitas,
                $edit_komunitas,
                $hapus_komunitas, 
                $lihat_komunitas,
                $tambah_warung,
                $edit_warung,
                $hapus_warung, 
                $lihat_warung,]);


    } 
}
