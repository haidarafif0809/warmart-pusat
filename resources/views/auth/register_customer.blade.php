@extends('layouts.app_login')

@section('content')
       @if ($errors->has('no_telp'))
                            <div class="alert alert-danger alert-with-icon">
        <i class="material-icons" data-notify="icon" >error_outline</i>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">x</button>
        <span data-notify="message"> <b>Failed:</b> Mohon Maaf Nomor Telfon Yang Anda Input Sudah Terdaftar</span>
        </div>
     @endif
    {!! Form::open(['url' => url('/register'),'method' => 'post', 'class'=>'form-horizontal']) !!}
                             {{ csrf_field() }}
                                <div class="card card-login ">
                                    <div class="card-header text-center" data-background-color="blue">
                                        <h4 class="card-title">Registrasi Pelanggan</h4>
                                 
                                    </div>
                              
                                    <div class="card-content">
                                        <div class="input-group ">
                                            <span class="input-group-addon">
                                                <i class="material-icons">person</i>
                                            </span>
                                            <div class="form-group label-floating {{ $errors->has('name') ? ' has-error' : '' }}"> 
                                                    {!! Form::text('name', null, ['class'=>'form-control','required','autocomplete'=>'off','placeholder'=>'Nama']) !!}
                                                    {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
                                                
                                            </div>
                                        </div> 


                                        <div class="input-group ">
                                            <span class="input-group-addon">
                                                <i class="material-icons">local_phone</i>
                                            </span>
                                            <div class="form-group label-floating"> 
                                                    {!! Form::text('no_telp', null, ['class'=>'form-control','required','autocomplete'=>'off','placeholder'=>'Nomor Telpon']) !!} 

                                                
                                            </div>
                                        </div>
                                        
                                        <div class="input-group ">
                                            <span class="input-group-addon">
                                                <i class="material-icons">email</i>
                                            </span>
                                            <div class="form-group label-floating {{ $errors->has('email') ? ' has-error' : '' }}"> 
                                                    {!! Form::email('email', null, ['class'=>'form-control','autocomplete'=>'off','placeholder'=>'Email']) !!}
                                                    {!! $errors->first('email', '<p class="help-block">:message</p>') !!}

                                                
                                            </div>
                                        </div>

                                        <div class="input-group ">
                                            <span class="input-group-addon">
                                                <i class="material-icons">home</i>
                                            </span>
                                            <div class="form-group label-floating {{ $errors->has('alamat') ? ' has-error' : '' }}"> 
                                                    {!! Form::text('alamat', null, ['class'=>'form-control','required','autocomplete'=>'off','placeholder'=>'Alamat']) !!}
                                                    {!! $errors->first('alamat', '<p class="help-block">:message</p>') !!}

                                                
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
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="material-icons">lock_outline</i>
                                            </span>
                                            <div class="form-group label-floating"> 
                                                <input type="password" id="password-confirm" class="form-control" name="password_confirmation" placeholder="Konfirmasi Password">

                                             
                                            </div>
                                        </div>

                                        <div class="input-group">
                                                        <div class="checkbox" style="padding:10;font-size:12px;" >
                                                        <label>
                                                              <input type="checkbox" name="ceklis_syarat_warung" id="ceklis_syarat_warung" value="0" data_toogle = "0"> 
                                                        </label>
                                                       <b>* Anda memahami & menyetujui <a href="{{ url('/syarat-ketentuan') }}" target="_blank" ><u>Syarat & Ketentuan</u></a></b>
                                                    </div>
                                        </div>
                         


                                          {!! Form::hidden('id_register', 1, ['class'=>'form-control','autocomplete'=>'off']) !!}

                                          @if(isset($komunitas_id))
                                          {!! Form::hidden('komunitas_id', $komunitas_id, ['class'=>'form-control','autocomplete'=>'off']) !!}
                                          @endif
                                    </div>



                                    <div class="footer text-center">
                                        <button type="submit" class="btn btn-rose btn-simple btn-wd btn-lg" disabled id="tombol_regist">Registrasi Pelanggan</button>
                                    </div>
                                    <center>     
                                       <p>Sudah Daftar? <a href="{{ url('/login') }}">Masuk Sekarang</a> </p>
                                    </center>
                                </div>
    {!! Form::close() !!}
@endsection

