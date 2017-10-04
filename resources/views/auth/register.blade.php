@extends('layouts.app_login')

@section('content')
  <form method="POST" action="{{ url('/register') }}">
                             {{ csrf_field() }}
                                <div class="card card-login ">
                                    <div class="card-header text-center" data-background-color="blue">
                                        <h4 class="card-title">Registrasi Komunitas</h4>
                                 
                                    </div>
                             
                                   
                                   
                                    <div class="card-content">
                                        <div class="input-group ">
                                            <span class="input-group-addon">
                                                <i class="material-icons">person</i>
                                            </span>
                                            <div class="form-group label-floating {{ $errors->has('name') ? ' has-error' : '' }}">
                                                <label class="control-label">Nama</label>
                                                    {!! Form::text('name', null, ['class'=>'form-control','required','autocomplete'=>'off']) !!}
                                                    {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
                                                
                                            </div>
                                        </div> 
                                        
                                        <div class="input-group ">
                                            <span class="input-group-addon">
                                                <i class="material-icons">phone</i>
                                            </span>
                                            <div class="form-group label-floating {{ $errors->has('email') ? ' has-error' : '' }}">
                                                <label class="control-label">No Telpon</label>
                                                    {!! Form::number('email', null, ['class'=>'form-control','required','autocomplete'=>'off']) !!}
                                                    {!! $errors->first('email', '<p class="help-block">:message</p>') !!}

                                                
                                            </div>
                                        </div>

                                        <div class="input-group ">
                                            <span class="input-group-addon">
                                                <i class="material-icons">email</i>
                                            </span>
                                            <div class="form-group label-floating {{ $errors->has('no_telp') ? ' has-error' : '' }}">
                                                <label class="control-label">Email</label> 
                                                    {!! Form::email('no_telp', null, ['class'=>'form-control','required','autocomplete'=>'off']) !!}
                                                    {!! $errors->first('no_telp', '<p class="help-block">:message</p>') !!}

                                                
                                            </div>
                                        </div>

                                        <div class="input-group ">
                                            <span class="input-group-addon">
                                                <i class="material-icons">home</i>
                                            </span>
                                            <div class="form-group label-floating {{ $errors->has('alamat') ? ' has-error' : '' }}">
                                                <label class="control-label">Alamat</label>
                                                    {!! Form::text('alamat', null, ['class'=>'form-control','required','autocomplete'=>'off']) !!}
                                                    {!! $errors->first('alamat', '<p class="help-block">:message</p>') !!}

                                                
                                            </div>
                                        </div>


                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="material-icons">lock_outline</i>
                                            </span>
                                            <div class="form-group label-floating {{ $errors->has('password') ? ' has-error' : '' }}">
                                                <label class="control-label">Password</label>
                                                <input type="password" class="form-control" name="password" >

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
                                                <label class="control-label">Konfirmasi Password</label>
                                                <input type="password" id="password-confirm" class="form-control" name="password_confirmation" >

                                             
                                            </div>
                                        </div>
                                    </div>
                                          {!! Form::hidden('id_register', 2, ['class'=>'form-control','autocomplete'=>'off']) !!}
                                    <div class="footer text-center">
                                        <button type="submit" class="btn btn-rose btn-simple btn-wd btn-lg">Registrasi Komunitas</button>
                                    </div>
                                </div>
                            </form>
@endsection
