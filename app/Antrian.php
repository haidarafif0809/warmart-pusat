<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Antrian extends Model
{
	protected $fillable = ['id','pelanggan_id','no_antrian','warung_id'];
}
