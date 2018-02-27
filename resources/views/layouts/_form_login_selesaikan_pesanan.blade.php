<form action="{{ url('/login') }}" method="POST">
    {{ csrf_field() }}
    <div class="input-group ">
        <span class="input-group-addon">
            <i class="material-icons">
                phone
            </i>
        </span>
        <div class="form-group label-floating ">
            <input autocomplete="off" class="form-control" name="no_telp" placeholder="Nomor Telpon" type="number">
        </input>
    </div>
</div>
<div class="input-group">
    <span class="input-group-addon">
        <i class="material-icons">
            lock_outline
        </i>
    </span>
    <div class="form-group label-floating ">
        <input class="form-control" name="password" placeholder="Password" type="password">
        <input class="form-control" type="hidden" name="session_id" type="text" id="session_id">
        <input  class="form-control" name="status_login" type="hidden" id="status_login" value="1">
        @if ($errors->has('password'))
        <span class="label label-danger">
            <strong>
                {{ $errors->first('password') }}
            </strong>
        </span>
        @endif
    </input>
</div>
</div>



@if($setting_aplikasi->tipe_aplikasi == 0)
<center> <button class="btn btn-round" type="submit" style="background-color: #01573e;width: 600px;">Login</button></center>
@else
<center> <button class="btn btn-round buttonColor" type="submit" style="width: 600px;">Login</button></center>
@endif 

</form>