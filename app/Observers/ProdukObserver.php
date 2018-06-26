<?php

namespace App\Observers;

use App\Barang;
use App\SatuanKonversi;

class ProdukObserver
{
    public function deleting(Barang $Barang)
    {
        SatuanKonversi::where('id_produk', $Barang->id)->delete();
        return true;
    }

}
