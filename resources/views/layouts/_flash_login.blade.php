@if (session()->has('flash_notification.message'))
                    <div class="alert alert-danger alert-with-icon">
        <i class="material-icons" data-notify="icon" >error_outline</i>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">x</button>
        <span data-notify="message"> <b>Failed:</b> {!! session()->get('flash_notification.message') !!}</span>
    </div> 
@endif