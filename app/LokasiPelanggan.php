<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laratrust\Traits\LaratrustUserTrait;
use Spatie\Activitylog\Traits\LogsActivity;

class LokasiPelanggan extends Model
{
      use LogsActivity;

  	  protected $table = 'lokasi_pelanggans';

  	  protected $fillable = ['id','id_pelanggan','provinsi','kabupaten', 'kecamatan', 'kelurahan'];

}
