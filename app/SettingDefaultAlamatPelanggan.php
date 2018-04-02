<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laratrust\Traits\LaratrustUserTrait;
use Spatie\Activitylog\Traits\LogsActivity;

class SettingDefaultAlamatPelanggan extends Model
{
	use LogsActivity;

	protected $table = 'setting_default_alamat_pelanggans';

	protected $fillable = ['id','provinsi','kabupaten','status'];
}
