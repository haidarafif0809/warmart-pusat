@if (session()->has('flash_notification.message'))
                    <div class="alert alert-{!! session()->get('flash_notification.alert') !!} alert-with-icon">
        <i class="material-icons" data-notify="icon" >{!! session()->get('flash_notification.icon') !!}</i>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">x</button>
        <span data-notify="message"> <b> {!! session()->get('flash_notification.judul') !!}:</b> {!! session()->get('flash_notification.message') !!}</span>
    </div> 
@endif