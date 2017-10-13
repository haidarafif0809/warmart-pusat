<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransaksiKas extends Model
{
    //

        protected $fillable = ['no_faktur','jenis_transaksi','tipe_transaksi','jumlah_masuk' ,'jumlah_keluar' ,'kas' ];

}
