<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;
use Laratrust\Traits\LaratrustUserTrait;
use Yajra\Auditable\AuditableTrait;

class User extends Authenticatable
{
    use Notifiable;
    use LaratrustUserTrait;
    use AuditableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    // protected $casts = [
    //     'is_verified' => 'boolean',
    // ];
    protected $fillable = [
        'name', 'email', 'password', 'alamat', 'status_konfirmasi', 'tipe_user', 'wilayah', 'komunitas', 'no_telp', 'tgl_lahir', 'nama_bank', 'no_rekening', 'an_rekening', 'id_warung', 'kode_verifikasi',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function role()
    {
        return $this->hasOne('App\Otoritas', 'user_id', 'id');
    }
    // Specify Slack Webhook URL to route notifications to
    public function routeNotificationForSlack()
    {
        return 'https://hooks.slack.com/services/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';
    }

    public function sendVerification()
    {
        $user                     = $this;
        $token                    = str_random(40);
        $user->verification_token = $token;
        $user->save();
        Mail::send('auth.emails.verification', compact('user', 'token'), function ($message) use ($user) {
            $message->to($user->email, $user->name)->subject('Konfirmasi Registrasi Akun Topos Anda');
        });
    }

    public function verifyEmail()
    {
        $this->status_konfirmasi  = 1;
        $this->verification_token = null;
        $this->save();
    }

}
