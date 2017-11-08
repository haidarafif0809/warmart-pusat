@extends('layouts.app')
@section('content')
<div class="row">
  <div class="col-md-12">
   <ul class="breadcrumb">
    <li><a href="{{ url('/home') }}">Home</a></li>
    <li class="active">Ubah Password</li>
  </ul>
  
  
  <div class="card">
    <div class="card-header card-header-icon" data-background-color="purple">
     <i class="material-icons">lock_outline</i>
   </div>
   <div class="card-content">
     <h4 class="card-title"> Ubah Password </h4>
     <div class="toolbar">

      {!! Form::model($user, ['url' => route('user.proses_ubah_password', $user->id), 'method' => 'put', 'files'=>'true','class'=>'form-horizontal']) !!}
      
      <div class="col-md-1"></div>
      <div class="input-group col-md-4">
        <span class="input-group-addon">
         <i class="material-icons">lock_outline</i>
       </span>
       <div class="form-group label-floating {{ $errors->has('password') ? ' has-error' : '' }}">
        <label class="control-label">Password Baru</label>
        <input type="password" class="form-control" name="password" >

        <span class="help-block">
          <strong>{{ $errors->first('password_baru') }}</strong>
        </span>
      </div>
    </div> 

    <div class="col-md-1"></div>
    <div class="input-group col-md-4">
     <span class="input-group-addon">
      <i class="material-icons">lock_outline</i>
    </span>
    <div class="form-group label-floating">
     <label class="control-label">Konfirmasi Password</label>
     <input type="password" id="password-confirm" class="form-control" name="password_confirmation" >

     <span class="help-block">
      <strong>{{ $errors->first('konfirmasi_password') }}</strong>
    </span>
    
  </div>
</div>
<br>
<div class="col-md-1"></div>
<div class="col-md-4">
  {!! Form::button('Ubah Password', ['class'=>'btn btn-primary', 'type'=>'submit']) !!}
</div>

{!! Form::close() !!}


</div>

</div>
</div>
</div>
</div>


@endsection