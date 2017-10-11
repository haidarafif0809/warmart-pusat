<?php

namespace App\Observers;

use App\Warung;
use App\User;
use App\BankWarung;
use Session;

class WarungObserver
{
    public function deleting(Warung $Warung)
    {       

        BankWarung::where('warung_id', $Warung->id)->delete();
        User::where('id_warung', $Warung->id)->delete();
        return true;

    }

}