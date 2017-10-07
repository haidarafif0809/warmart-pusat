@extends('layouts.app_login')


@section('content')

@include('layouts._flash_login') 

                        {!! Form::model($user->id,['url' => route('user.proses_kirim_kode_verifikasi', $nomor_hp), 'method' => 'put', 'files'=>'true','class'=>'form-horizontal']) !!}
                             {{ csrf_field() }}
                                <div class="card card-login ">
                                    <div class="card-header text-center" data-background-color="blue">
                                        <h4 class="card-title">Masukan Kode Verifikasi</h4>
                                 
                                    </div>
                              
                                    <div class="card-content">
                                        <div class="input-group ">
                                            <span class="input-group-addon">
                                                <i class="material-icons">phone</i>
                                            </span>
                                            <div class="form-group label-floating {{ $errors->has('no_telp') ? ' has-error' : '' }}">
                                                <label class="control-label">Nomor Telpon</label>
                                                <input type="text" value="{{ $nomor_hp }}" class="form-control" name="no_telp" readonly="">

                                                
                                            </div>
                                        </div>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="material-icons">lock_outline</i>
                                            </span>
                                            <div class="form-group label-floating {{ $errors->has('kode_verifikasi') ? ' has-error' : '' }}">
                                                <label class="control-label">Kode Verifikasi</label>
                                                <input type="text" class="form-control" name="kode_verifikasi" maxlength="4" required="" autocomplete="off">

                                                  @if ($errors->has('kode_verifikasi'))
                                                        <span class="help-block">
                                                            <strong>{{ $errors->first('kode_verifikasi') }}</strong>
                                                        </span>
                                                 @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="footer text-center">
                                        <button type="submit" class="btn btn-rose btn-simple btn-wd btn-lg">Verifikasi</button>
                                        <h6><a href="{{ url('/kirim-ulang-kode-verifikasi/'.$user->id.'') }}">Kirim Ulang Kode Verifikasi</a></h6>
                                    </div>
                                </div>
                    {!! Form::close() !!}

               
@endsection
