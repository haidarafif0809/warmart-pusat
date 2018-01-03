<!DOCTYPE doctype html>
<html lang="en">
<head>
</head>
<style type="text/css">

.table>thead>tr>th, .table>tbody>tr>th, .table>tfoot>tr>th, .table>thead>tr>td, .table>tbody>tr>td, .table>tfoot>tr>td{
	padding: 1px;
}

</style>
<body>

	<div class="row">
		<div class="col-md-12">
			<p>Blalala</p>
		</div>
	</div>

</body>
<!--   Core JS Files   -->
<script src="{{ asset('js/app.js?v=1.51')}}" type="text/javascript"></script>

<script>
	$(document).ready(function(){
		window.print();
	});
</script>
@yield('scripts')
</html>
