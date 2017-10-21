@extends('layouts.app_login')


@section('content')

@include('layouts._flash_login')
       @if ($errors->has('no_telp'))
                            <div class="alert alert-danger alert-with-icon">
        <i class="material-icons" data-notify="icon" >error_outline</i>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">x</button>
        <span data-notify="message"> <b>Failed:</b> {{ $errors->first('no_telp') }}</span>
    </div>
     @endif


                    <form method="POST" action="{{ url('/login') }}">
                             {{ csrf_field() }}
                                <div class="card card-login ">
                                    <div class="card-header text-center" data-background-color="blue">
                                        <h4 class="card-title">Login</h4>
                                 
                                    </div>
                             
                                   
                                   
                                    <div class="card-content">
                                        <div class="input-group ">
                                            <span class="input-group-addon">
                                                <i class="material-icons">phone</i>
                                            </span>
                                            <div class="form-group label-floating {{ $errors->has('no_telp') ? ' has-error' : '' }}"> 
                                                <input type="number" value="{{ old('no_telp') }}" class="form-control" name="no_telp" placeholder="Nomor Telpon" autocomplete="off">

                                                
                                            </div>
                                        </div>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="material-icons">lock_outline</i>
                                            </span>
                                            <div class="form-group label-floating {{ $errors->has('password') ? ' has-error' : '' }}"> 
                                                <input type="password" class="form-control" name="password" placeholder="Password">

                                                  @if ($errors->has('password'))
                                                        <span class="help-block">
                                                            <strong>{{ $errors->first('password') }}</strong>
                                                        </span>
                                                 @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="footer text-center">
                                    <center>    
                                        <a style="padding-right: 40%;font-size: 90%"  href="{{ url('/lupa-password/') }}">Lupa Password</a> 
                                    </center>
                                        <button type="submit" id="login" class="btn btn-rose btn-simple btn-wd btn-lg">Login</button>
                                    </div>
                                    <center>     
                                       <p>Belum Daftar? <a class="swal-pendaftaran" href="#">Daftar Sekarang</a> </p>
                                    </center>
                                    
                                </div>
                            </form>
               
@endsection 

@section('scripts') 

<script type="text/javascript">
$('.swal-pendaftaran').click(function(){
    swal({
        title: 'Daftar Sebagai?',
        html:
            '<li class="" style="list-style-type:none"><a href="{{ url('/register-customer') }}"  class="btn btn-info"><i class="material-icons">person_add</i> Pelanggan</a></li><li class="" style="list-style-type:none"><a href="{{ url('/register') }}"  class="btn btn-success"><i class="material-icons">people</i> Komunitas</a></li><li class=""  style="list-style-type:none"><a href="{{ url('/register-warung') }}"  class="btn btn-warning"><i class="material-icons">store</i> Warung</a></li> ',
        showConfirmButton :  false,
    });
});
</script>

@endsection
