<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KategoriHarga extends Model
{
    //Kolom mana saja yg boleh diisi / update melalui method create atau update
    protected $fillable = ['nama_kategori_harga'];
}
