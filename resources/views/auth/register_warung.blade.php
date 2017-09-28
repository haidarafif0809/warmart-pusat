@extends('layouts.app_login')

@section('content')
  <form method="POST" action="{{ url('/register-warung') }}">
                             {{ csrf_field() }}
                                <div class="card card-login ">
                                    <div class="card-header text-center" data-background-color="blue">
                                        <h4 class="card-title">Registrasi Warung</h4>
                                 
                                    </div>
                              
                                    <div class="footer text-center">
                                        <button type="submit" class="btn btn-rose btn-simple btn-wd btn-lg">Registrasi Warung</button>
                                    </div>
                                </div>
                            </form>
@endsection
