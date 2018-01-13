<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;

class PendaftaranTopos extends Notification
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
        if ($pendaftar_topos->lama_berlangganan == 1) {
            $lama_berlangganan = "1 Bulan";
        }else if ($pendaftar_topos->lama_berlangganan == 2) {
            $lama_berlangganan = "6 Bulan";
        }else if ($pendaftar_topos->lama_berlangganan == 3) {
            $lama_berlangganan = "12 Bulan";
        }
        $content = title_case($pendaftar_topos->name) ." mendaftar topos dengan nomor telp " .$pendaftar_topos->no_telp ." di alamat : ". $pendaftar_topos->alamat." lama berlangganan selama ".$lama_berlangganan;

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
