<?php

namespace App\Observers;

use App\BankWarung;
use App\Kas;
use App\SettingTransferBank;
use Auth;

class BankWarungObserver
{
    public function created(BankWarung $BankWarung)
    {
        $bank_transfer = SettingTransferBank::select('nama_bank')->where('id', $BankWarung->nama_bank)->first();

        $kas = Kas::create([
            'kode_kas'    => strtoupper($bank_transfer->nama_bank) . "" . $BankWarung->id,
            'nama_kas'    => strtoupper($BankWarung->nama_tampil),
            'status_kas'  => 1,
            'default_kas' => 0,
            'id_bank' => $BankWarung->id,
            'warung_id'   => Auth::user()->id_warung,
            ]);

        return true;

    } // OBERVERS CREATING

    //HAPUS ITEM KELUAR
    public function deleting(BankWarung $BankWarung)
    {
        $bank_transfer = SettingTransferBank::select('nama_bank')->where('id', $BankWarung->nama_bank)->first();
        $kode_kas      = $bank_transfer->nama_bank . "" . $BankWarung->id;
        $data_kas      = Kas::where('kode_kas', $kode_kas)->delete();
        return true;
    } // OBERVERS DELETING
}
