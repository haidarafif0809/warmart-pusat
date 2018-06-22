@extends('layouts.app_login')


@section('content')

@include('layouts._flash_daftar_topos') 


@if($pendaftar_topos->status_pembayaran == 0)
{!! Form::model($pendaftar_topos->id,['url' => route('pendaftar_topos.proses_kirim_bukti_pembayaran',$pendaftar_topos->id), 'method' => 'put', 'files'=>'true','class'=>'form-horizontal']) !!}
{{ csrf_field() }}
<div class="card card-login ">
    <div class="card-header text-center" data-background-color="blue">
        <h5 class="card-title">Lakukan Konfirmasi Pembayaran dengan Meng-Upload Foto Bukti Transfer</h5>

    </div>

    <div class="card-content">
        <div class="input-group ">
            <span class="input-group-addon">
                <i class="material-icons">file_upload</i>
            </span>
            <div class="form-group label-floating {{ $errors->has('foto') ? ' has-error' : '' }}"> 
               <input type="file" id="foto" name="foto" class="form-control">
               <input type="text" readonly="" class="form-control" placeholder="Upload Foto Bukti Transfer disini ...">
               {!! $errors->first('foto', '
               <p class="label label-danger">
                :message
            </p>
            ') !!}

        </div>
    </div>
    <div class="input-group">
        <span class="input-group-addon">
            <i class="material-icons">list</i>
        </span>
        <div class="form-group label-floating {{ $errors->has('keterangan') ? ' has-error' : '' }}"> 
            <input type="text" class="form-control" name="keterangan" autocomplete="off" placeholder="Keterangan">

        </div>
    </div>
</div>
<div class="footer text-center">
    <button type="submit" class="btn btn-rose btn-simple btn-wd btn-lg">Kirim Bukti Transfer</button>
</div>
</div>
{!! Form::close() !!}
@endif
@endsection
