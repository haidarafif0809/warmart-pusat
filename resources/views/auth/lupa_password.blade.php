@extends('layouts.app_login')


@section('content')
  
                    <form method="POST" action="{{ url('/proses-lupa-password') }}">
                             {{ csrf_field() }}
                                <div class="card card-login ">
                                    <div class="card-header text-center" data-background-color="blue">
                                        <h4 class="card-title">Lupa Password</h4>
                                 
                                    </div>
                              
                                    <div class="card-content">
                                        <div class="input-group ">
                                            <span class="input-group-addon">
                                                <i class="material-icons">phone</i>
                                            </span>
                                            <div class="form-group label-floating {{ $errors->has('no_telp') ? ' has-error' : '' }}"> 
                                                    {!! Form::number('no_telp', null, ['class'=>'form-control','required','autocomplete'=>'off','placeholder'=>'Nomor Telpon']) !!}
                                                    {!! $errors->first('no_telp', '<p class="help-block">:message</p>') !!} 
                                            </div>
                                        </div>
                                    </div>

                                    <div class="footer text-center">
                                        <button type="submit" class="btn btn-rose btn-simple btn-wd btn-lg">Lupa Password</button> 
                                    </div>
                                </div>
                    </form>
       
@endsection
