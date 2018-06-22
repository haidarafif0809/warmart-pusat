<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SettingFooter extends Model
{

    public $fillable = ['warung_id', 'judul_warung', 'support_link', 'about_link', 'about_us', 'no_telp', 'alamat', 'email', 'whatsapp', 'facebook', 'twitter', 'instagram', 'google_plus', 'play_store'];

    public static function defaultData()
    {

        $object = new \stdClass();

        $object->judul_warung = 'Toko Online dan Pos';
        $object->support_link = 'https://andaglos.id/support/';
        $object->about_link   = 'https://andaglos.id/topos/';
        $object->about_us     = 'Tentang Toko Anda...';
        $object->no_telp      = '07218050299';
        $object->alamat       = 'Jl. Pramuka, Sentra Bisnis Terminal Kemiling Blok R3 No.7 Bandar Lampung';
        $object->email        = 'solusibisnis@andaglos.id';
        $object->whatsapp     = '0811728549';
        $object->facebook     = 'https://facebook.com/andaglos';
        $object->twitter      = 'https://www.twitter.com/andaglos';
        $object->instagram    = 'https://www.instagram.com/andaglos';
        $object->google_plus  = 'https://plus.google.com/u/0/102529791461131425545';
        $object->play_store   = 'https://play.google.com/store/apps/details?id=com.topos.haidar.aplikasitopos';

        return $object;

    }
}
