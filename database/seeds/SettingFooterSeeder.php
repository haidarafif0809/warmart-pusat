<?php

use App\SettingFooter;
use Illuminate\Database\Seeder;

class SettingFooterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SettingFooter::create([
            "judul_warung" => 'Toko Online dan Pos',
            "support_link" => 'https://andaglos.id/support/',
            "about_link"   => 'https://andaglos.id/topos/',
            "about_us"     => 'Tentang Toko Anda...',
            "no_telp"      => '07218050299',
            "alamat"       => 'Jl. Pramuka, Sentra Bisnis Terminal Kemiling Blok R3 No.7 Bandar Lampung',
            "email"        => 'solusibisnis@andaglos.id',
            "whatsapp"     => '0811728549',
            "facebook"     => 'https://facebook.com/andaglos',
            "twitter"      => 'https://www.twitter.com/andaglos',
            "instagram"    => 'https://www.instagram.com/andaglos',
            "google_plus"  => 'https://plus.google.com/u/0/102529791461131425545',
        ]);
    }
}
