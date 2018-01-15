@extends('layouts.app_login')

@section('content')

<style type="text/css">
.form-total{
    font-size: 20px;
    font-weight: 1000;
}
</style>
@if ($errors->has('no_telp'))
<div class="alert alert-danger alert-with-icon">
    <i class="material-icons" data-notify="icon">
        error_outline
    </i>
    <button aria-label="Close" class="close" data-dismiss="alert" type="button">
        x
    </button>
    <span data-notify="message">
        <b>
            Failed:
        </b>
        Mohon Maaf Nomor Telpon Yang Anda Input Sudah Terdaftar
    </span>
</div>
@endif
{!! Form::open(['url' => url('/proses-daftar-topos'),'method' => 'post', 'class'=>'form-horizontal','id'=>'form_daftar_topos']) !!}
{{ csrf_field() }}
<div class="card card-login ">
    <div class="card-header text-center" data-background-color="blue">
        <h4 class="card-title">
            Pendaftaran Topos
        </h4>
    </div>
    <div class="card-content">

        <div class="input-group ">
            <span class="input-group-addon">
                <i class="material-icons">
                    store
                </i>
            </span>
            <div class="form-group label-floating {{ $errors->has('nama_warung') ? ' has-error' : '' }}">

                {!! Form::text('nama_warung', null, ['class'=>'form-control','required','autocomplete'=>'off','placeholder'=>'Nama Toko','id'=>'nama_warung']) !!}

                {!! $errors->first('nama_warung', '
                <p class="label label-danger">
                    :message
                </p>
                ') !!}
            </div>
        </div>
        <div class="input-group ">
            <span class="input-group-addon">
                <i class="material-icons">
                    person
                </i>
            </span>
            <div class="form-group label-floating {{ $errors->has('name') ? ' has-error' : '' }}">
                {!! Form::text('name', null, ['class'=>'form-control','required','autocomplete'=>'off','placeholder'=>'Nama Pemilik','id'=>'name']) !!}
                {!! $errors->first('name', '
                <p class="label label-danger">
                    :message
                </p>
                ') !!}
            </div>
        </div>
        <div class="input-group ">
            <span class="input-group-addon">
                <i class="material-icons">
                    local_phone
                </i>
            </span>
            <div class="form-group label-floating">
                {!! Form::tel('no_telp', null, ['class'=>'form-control','required','autocomplete'=>'off','placeholder'=>'Nomor Telpon','id'=>'no_telp']) !!}
            </div>
        </div>
        <div class="input-group ">
            <span class="input-group-addon">
                <i class="material-icons">
                    home
                </i>
            </span>
            <div class="form-group label-floating {{ $errors->has('alamat') ? ' has-error' : '' }}">
                {!! Form::text('alamat', null, ['class'=>'form-control','required','autocomplete'=>'off','placeholder'=>'Alamat','id'=>'alamat']) !!}
                {!! $errors->first('alamat', '
                <p class="label label-danger">
                    :message
                </p>
                ') !!}
            </div>
        </div>

        <div class="input-group ">
            <span class="input-group-addon">
                <i class="material-icons">
                    alarm
                </i>
            </span>
            <div class="row">
                <div class="col-md-7">
                    <div class="form-group label-floating ">
                        {!! Form::select('lama_berlangganan', [
                        '1'=>'1 Bulan',
                        '2'=>'6 Bulan',
                        '3'=>'12 Bulan'
                        ],null, ['class'=>'js-selectize-reguler', 'placeholder' => 'Berlangganan','id' => 'lama_berlangganan']) !!}
                        {!! $errors->first('lama_berlangganan', '
                        <p class="label label-danger">
                            :message
                        </p>
                        ') !!}
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="form-group label-floating {{ $errors->has('harga') ? ' has-error' : '' }}">
                        {!! Form::text('harga', null, ['class'=>'form-control','autocomplete'=>'off','readonly','id' => 'harga']) !!}
                        {!! $errors->first('harga', '
                        <p class="label label-danger">
                            :message
                        </p>
                        ') !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="input-group ">
            <span class="input-group-addon">
                <i class="material-icons">
                    date_range
                </i>
            </span>
            <div class="form-group label-floating {{ $errors->has('berlaku_hingga') ? ' has-error' : '' }}">
                {!! Form::text('berlaku_hingga', null, ['class'=>'form-control','autocomplete'=>'off','placeholder'=>'Berlaku Hingga','readonly','id' => 'berlaku_hingga']) !!}
                {!! $errors->first('berlaku_hingga', '
                <p class="label label-danger">
                    :message
                </p>
                ') !!}
            </div>
        </div>

        <div class="input-group ">
            <span class="input-group-addon">
              <b>Rp.</b>
          </span>
          <div class="form-group label-floating {{ $errors->has('total') ? ' has-error' : '' }}">
            {!! Form::text('total', null, ['class'=>'form-control form-total','autocomplete'=>'off','placeholder'=>'Total Yang Harus di Bayar','readonly','id' => 'total' ]) !!}
            {!! $errors->first('total', '
            <p class="label label-danger">
                :message
            </p>
            ') !!}
        </div>
    </div>

    <div class="input-group ">
        <span class="input-group-addon">
            <i class="material-icons">
                account_balance
            </i>
        </span>
        <div class="form-group label-floating ">
            {!! Form::select('tujuan_transfer', [''=>'']+App\Bank::select([DB::raw('CONCAT(id, "|", no_rek,"|",atas_nama) AS id'),'nama_bank AS nama_bank'])->pluck('nama_bank','id')->all(),null, ['class'=>'js-selectize-reguler', 'placeholder' => 'Tujuan Transfer','id' => 'tujuan_transfer']) !!}
            {!! $errors->first('tujuan_transfer', '
            <p class="label label-danger">
                :message
            </p>
            ') !!}
        </div>
    </div>

    <div class="input-group ">
        <span class="input-group-addon">
            <i class="material-icons">
                payment
            </i>
        </span>
        <div class="form-group label-floating {{ $errors->has('no_rek_transfer') ? ' has-error' : '' }}">
            {!! Form::text('no_rek_transfer', null, ['class'=>'form-control','autocomplete'=>'off','placeholder'=>'No. Rekening','readonly','id'=>'no_rek_transfer']) !!}
            {!! $errors->first('no_rek_transfer', '
            <p class="label label-danger">
                :message
            </p>
            ') !!}
        </div>
    </div>

    <div class="input-group ">
        <span class="input-group-addon">
            <i class="material-icons">
                person
            </i>
        </span>
        <div class="form-group label-floating {{ $errors->has('atas_nama') ? ' has-error' : '' }}">
            {!! Form::text('atas_nama', null, ['class'=>'form-control','autocomplete'=>'off','placeholder'=>'Atas Nama','readonly','id'=>'atas_nama']) !!}
            {!! $errors->first('atas_nama', '
            <p class="label label-danger">
                :message
            </p>
            ') !!}
        </div>
    </div>
</div>

<div class="footer text-center">
    <button class="btn btn-rose btn-simple btn-wd btn-lg" id="tombol_daftar" type="submit">
        Daftar
    </button>
</div>
</div>
{!! Form::close() !!}
@endsection
@section('scripts')
<script type="text/javascript">
$('select').selectize({
   sortField: 'text'
});
Number.prototype.format = function(n, x, s, c) {
    var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\D' : '$') + ')',
    num = this.toFixed(Math.max(0, ~~n));

    return (c ? num.replace('.', c) : num).replace(new RegExp(re, 'g'), '$&' + (s || ','));
};

</script>

<script type="text/javascript">
$(document).ready(function(){

    var lama_berlangganan = "{{$id}}";  
    document.getElementById('lama_berlangganan').selectize.setValue(lama_berlangganan);
    berlakuHingga();
    
    function berlakuHingga(){
      var now = new Date(); 
      var nowDay= now.getDate();
      var nowMonth = now.getMonth();
      var nowYear= now.getFullYear();

      var monthNames = [
      "Januari", "Februari", "Maret",
      "April", "Mei", "Juni", "Juli",
      "Agustus", "September", "Oktober",
      "November", "Desember"
      ];
      var total = $("#total").val();
      var lama_berlangganan = $("#lama_berlangganan").val();
      var harga = $("#harga").val();
      var berlaku_hingga = $("#berlaku_hingga").val();

      if (total > 0) {                 
        total = 0;
    }
    if (total == '') {
        total = 0
    }
    if (lama_berlangganan == 1) {
        var satu_bulan = monthNames[nowMonth + 1];
        var harga_perbulan = 500000;
        harga = '500.000/Bulan';
        berlaku_hingga = nowDay + ' ' + satu_bulan + ' ' + nowYear;
        total = parseInt(harga_perbulan) * parseInt(1);

    }else if (lama_berlangganan == 2) {
        var enam_bulan = monthNames[nowMonth + 6];
        var harga_perbulan = 300000;
        harga = '300.000/Bulan';
        berlaku_hingga = nowDay + ' ' + enam_bulan + ' ' + nowYear;
        total = parseInt(harga_perbulan) * parseInt(6);

    }else if (lama_berlangganan == 3) {

        var duabelas_bulan = nowYear + 1;
        var harga_perbulan = 200000;
        harga = '200.000/Bulan';
        berlaku_hingga = nowDay + ' ' + monthNames[nowMonth] + ' ' + duabelas_bulan;
        total = parseInt(harga_perbulan) * parseInt(12);
    }

    $("#total").val(total.format(0, 3, '.', ','));
    $("#berlaku_hingga").val(berlaku_hingga);
    $("#harga").val(harga);
}

$(document).on('change','#lama_berlangganan', function(){
    berlakuHingga();
});


$(document).on('change','#tujuan_transfer', function(){

    var bank_transfer = $(this).val();
    var bank_transfer = bank_transfer.split("|");      

    $("#no_rek_transfer").val(bank_transfer[1]);
    $("#atas_nama").val(bank_transfer[2]);
});


}); 
</script>
@endsection
