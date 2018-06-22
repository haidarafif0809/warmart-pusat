@if (session()->has('flash_notification.message'))
<div class="alert alert-{!! session()->get('flash_notification.alert') !!} alert-with-icon">
	<i class="material-icons" data-notify="icon" >{!! session()->get('flash_notification.icon') !!}</i>
	
	<span data-notify="message"> <b> {!! session()->get('flash_notification.judul') !!}:</b> {!! session()->get('flash_notification.message') !!}</span>
</div> 
@endif