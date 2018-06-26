<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laratrust\Traits\LaratrustUserTrait;
use Spatie\Activitylog\Traits\LogsActivity;

class Customer extends Model
{

  use LogsActivity;
  use Notifiable;
  use LaratrustUserTrait;

  protected $table = 'users';

  protected $fillable = ['id','email','password','name', 'alamat', 'wilayah', 'no_telp','tgl_lahir','tipe_user', 'status_konfirmasi', 'kode_pelanggan','id_warung'];

  protected $hidden = [
    'password', 'remember_token',
  ];



  public function setTglLahirAttribute($tgl_lahir)
  {
      //TANGGAL SQL
    $date= date_create($tgl_lahir);
    $date_format =  date_format($date,"Y-m-d");    
    
    $this->attributes['tgl_lahir'] = $date_format;
  }

  public function getTglLahirAttribute($tgl_lahir) {

    if ($tgl_lahir == NULL) {
      return NULL;
    }
    else {
      return \Carbon\Carbon::createFromFormat('Y-m-d', $tgl_lahir)->format('d-m-Y'); 
    }

    
  }
  
  public function lokasiPelanggan(){
    return $this->hasOne('App\LokasiPelanggan','id_pelanggan','id');
  }


  public function kelurahan(){
    return $this->hasOne('App\Kelurahan','id','wilayah');
  }

   //relasi dengan model warung
  public function warung()
  {
    return $this->hasOne('App\Warung', 'id', 'id_warung');
  }

  public function getKomunitasAttribute() {

    $komunitas = KomunitasCustomer::where('user_id',$this->id); 

    if ($komunitas->count() == 0) {
      return "warmart";
    } 
    else {
      return $komunitas->first()->komunitas->name;
    }
  }

  public function komunitas_customer(){
    return $this->hasOne('App\KomunitasCustomer','user_id','id');
  }
}
