<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;
use App\Bank;

class PembayaranTopos extends Notification
{
    use Queueable;

    /**
    * Create a new notification instance.
    *
    * @return void
    */
    public function __construct($pendaftar_topos)
    {
        //
        $this->pendaftar_topos = $pendaftar_topos;
    }

    /**
    * Get the notification's delivery channels.
    *
    * @param  mixed  $notifiable
    * @return array
    */
    public function via($notifiable)
    {
        return ['slack'];
    }

    /**
    * Get the mail representation of the notification.
    *
    * @param  mixed  $notifiable
    * @return \Illuminate\Notifications\Messages\MailMessage
    */
    public function toSlack($notifiable)
    {
        $pendaftar_topos = $this->pendaftar_topos;
        $bank = Bank::select('nama_bank')->where('id',$pendaftar_topos->bank_id)->first();

        $content = title_case($pendaftar_topos->name) ." telah mengkonfirmasi Pembayaran Sebesar Rp. " . number_format($pendaftar_topos->total , 0, ',', '.') . " Ke Rekening Bank " . $bank->nama_bank ." atas nama ".$pendaftar_topos->atas_nama;

        $content .=  ". ";

        return (new SlackMessage)
        ->success()
        ->content($content);
    }

/**
* Get the array representation of the notification.
*
* @param  mixed  $notifiable
* @return array
*/
public function toArray($notifiable)
{
 return [
     'title' => 'testing',
     'description' => 'test'
 ];
}
}

?>
