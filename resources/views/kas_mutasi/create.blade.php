@extends('layouts.app') 
 
@section('content') 
 
  <div class="row"> 
    <div class="col-md-12"> 
      <ul class="breadcrumb"> 
        <li><a href="{{ url('/home') }} ">Home</a></li> 
        <li><a href="{{ url('/kas_mutasi') }}">Kas Mutasi</a></li> 
        <li class="active">Tambah Kas Mutasi</li> 
      </ul> 
        <div class="card"> 
              <div class="card-header card-header-icon" data-background-color="purple"> 
                       <i class="material-icons">compare_arrows</i> 
                   </div> 
                      <div class="card-content"> 
                         <h4 class="card-title"> Kas Mutasi </h4> 
                       
          {!! Form::open(['url' => route('kas_mutasi.store'),'method' => 'post', 'class'=>'form-horizontal', 'id'=> 'form_kas_mutasi']) !!} 
            @include('kas_mutasi._form') 
          {!! Form::close() !!} 
        </div> 
      </div> 
    </div> 
  </div> 
 
@endsection 
 
@section('scripts') 
<script type="text/javascript"> 
 
    $(document).ready(function(){ 
 
      $(document).on('change','#dari_kas', function(){ 
 
        var kas = $(this).val();         
        var ke_kas = $("#ke_kas").val(); 
 
 
          if (kas == ke_kas) { 
 
            alert("Dari Kas dan Ke Kas Tidak Bisa Sama!"); 
            document.getElementById('ke_kas').selectize.setValue(''); 
 
          }else{ 
 
            $.post('{{ route('cek_total_kas') }}',{'_token': $('meta[name=csrf-token]').attr('content'),kas:kas}, function(data){ 
            $("#sisa_kas").val(data); 
 
          }); 
        } 
             
      }) 
 
      $(document).on('change','#ke_kas', function(){ 
 
        var ke_kas = $(this).val(); 
        var dari_kas = $('#dari_kas').val(); 
 
        if (dari_kas == ke_kas) { 
 
          alert("Dari Kas dan Ke Kas Tidak Bisa Sama!"); 
          document.getElementById('ke_kas').selectize.setValue(''); 
        } 
 
         
             
      }) 
 
 
      $(document).on('click','#submit_kas', function(){ 
 
        var jumlah = $("#jumlah").val(); 
        var sisa_kas = $('#sisa_kas').val(); 
 
        if (sisa_kas == '') { 
          sisa_kas = 0; 
        } 
 
        var hitung_kas = parseInt(sisa_kas,10) - parseInt(jumlah,10); 
 
        if (hitung_kas < 0) { 

            $("#form_kas_mutasi").submit(function(){
            return false;
            });
            alert("Total Kas Tidak Mencukupi!"); 
            $("#jumlah").val('');

          }else{

            document.getElementById("form_kas_mutasi").submit();

          }
             
      }) 
 
     
    }); 
             
 
</script> 
@endsection 