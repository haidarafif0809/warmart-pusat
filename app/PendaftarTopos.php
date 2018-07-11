<?php

namespace App;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Yajra\Auditable\AuditableTrait;

class PendaftarTopos extends Model
{
	use Notifiable;
	use AuditableTrait;
	protected $fillable = ['name', 'no_telp', 'email', 'alamat', 'lama_berlangganan', 'berlaku_hingga', 'jenis_pembayaran', 'total', 'bank_id', 'no_rekening', 'atas_nama', 'warung_id','nama_subdomain','foto','keterangan','status_pembayaran'];

	public function bank()
	{
		return $this->belongsTo('App\Bank', 'bank_id', 'id');
	}
	public function user_warung()
	{
		return $this->belongsTo('App\UserWarung', 'warung_id', 'id_warung');
	}

	           // Specify Slack Webhook URL to route notifications to 
	public function routeNotificationForSlack() {
		return 'https://hooks.slack.com/services/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';
	}
}
