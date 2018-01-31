<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SettingFooter extends Model
{
    public $fillable = ['warung_id', 'header_warung', 'support_link', 'about_link', 'about_us', 'no_telp', 'alamat', 'email', 'whatsapp', 'facebook', 'twitter', 'instagram', 'google_plus'];
}
