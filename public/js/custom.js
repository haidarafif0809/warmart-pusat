
$('.js-selectize-reguler').selectize({
 sortField: 'text'
});

$('.js-selectize-multi').selectize({
  sortField: 'text',
  delimiter: ',',
  maxItems: null,
});

$('.datepicker').datepicker({
    format: 'dd/mm/yyyy', 
    autoclose: true,
});

$('.clockpicker').clockpicker({
    placement: 'bottom',
    align: 'left',
    autoclose: true, 
});
