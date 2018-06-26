@extends('layouts.app') 
@section('content') 
 
  <div class="row"> 
    <div class="col-md-12"> 
      <ul class="breadcrumb"> 
        <li><a href="{{ url('/home') }}">Home</a></li> 
        <li class="active"> Kas Mutasi </li> 
      </ul> 
  
       
         <div class="card"> 
              <div class="card-header card-header-icon" data-background-color="purple"> 
                       <i class="material-icons">compare_arrows</i> 
                                </div> 
                      <div class="card-content"> 
                         <h4 class="card-title"> Kas Mutasi </h4> 
           
                       <div class="toolbar"> 
                           <p> <a class="btn btn-primary" id="tambah_kas_mutasi" href="{{ route('kas_mutasi.create') }}"><i class="material-icons">add</i> Tambah Kas Mutasi</a> </p> 
                         </div> 
                   
          <div class="table-responsive material-datatables"> 
          {!! $html->table(['class'=>'table table-striped table-no-bordered table-hover','cellspacing' => '0','width' => '100%','style' => 'width:100%']) !!} 
          </div> 
        </div> 
      </div> 
    </div> 
  </div> 
  
@endsection 
 



@section('scripts')
{!! $html->scripts() !!}
<script type="text/javascript">
  // confirm delete
    $(document.body).on('submit', '.js-confirm', function () {
    var $el = $(this)
    var text = $el.data('confirm') ? $el.data('confirm') : 'Anda yakin melakukan tindakan ini\
  ?'
    var c = confirm(text);
    return c;
  }); 
</script>
@endsection

  
