<?php

use Illuminate\Database\Seeder;
use App\Role;
use App\User;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       // Membuat role admin
	    $adminRole = new Role();
	    $adminRole->name = "admin";
	    $adminRole->display_name = "Admin";
	    $adminRole->save();
	    
	    // Membuat role member
	    $memberRole = new Role();
	    $memberRole->name = "member";
	    $memberRole->display_name = "Member";
	    $memberRole->save();
	    
	    // Membuat role customer
	    $customerRole = new Role();
	    $customerRole->name = "customer";
	    $customerRole->display_name = "Customer";
	    $customerRole->save();
	    
	    // Membuat role warung
	    $warungRole = new Role();
	    $warungRole->name = "warung";
	    $warungRole->display_name = "Warung";
	    $warungRole->save();

	    // Membuat sample admin
	    $admin = new User();
	    $admin->name = 'Admin Larapus';
	    $admin->email = 'admin@gmail.com';
	    $admin->alamat = "-";
	    $admin->password = bcrypt('rahasia');
	    $admin->status_konfirmasi = "1";
	 
	    $admin->wilayah = '101';
	    $admin->link_afiliasi = 'andaglos.com/aff/1';
	    $admin->no_telp = "087345365743";
	    $admin->nama_bank = 'BNI';
	    $admin->no_rekening = '044353534';
	    $admin->an_rekening = 'fahrizal';
	    $admin->tipe_user = '1';
	    $admin->tgl_lahir = '2000-10-10';

	    $admin->save();
	    $admin->attachRole($adminRole);

	    // Membuat sample member
	    $member = new User();
	    $member->name = "Sample Member";
	    $member->email = 'member@gmail.com';
	    $member->alamat = "-";
		$member->status_konfirmasi = "1";

	    $member->wilayah = '103';
	    $member->link_afiliasi = 'andaglos.com/aff/1';
	    $member->no_telp = "087345365743";
	    $member->nama_bank = 'BNI';
	    $member->no_rekening = '044353534';
	    $member->an_rekening = 'fahrizal';
	    $member->tipe_user = '2';
	    $member->tgl_lahir = '2000-10-10';
	    
	    $member->password = bcrypt('rahasia');
	    $member->save();
	    $member->attachRole($memberRole);

	    // Membuat Sample Customer
	    $customer = new User();
	    $customer->name = "Sample Customer";
	    $customer->email = 'customer@gmail.com';
	    $customer->alamat = "Jl. Kedaton";
	    $customer->wilayah = '1';
	    $customer->warung = '2';
	    $customer->no_telp = "085345330858";
	    $customer->tipe_user = '3';
	    $customer->tgl_lahir = '1999-02-20';
		$customer->status_konfirmasi = "1";

	    $customer->link_afiliasi = '-';
	    $customer->nama_bank = '-';
	    $customer->no_rekening = '-';
	    $customer->an_rekening = '-';
	    
	    $customer->password = bcrypt('rahasia');
	    $customer->save();
	    $customer->attachRole($customerRole);
    }
}
