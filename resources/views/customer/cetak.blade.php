<!DOCTYPE doctype html>
<html lang="en">
<head>
	<link href="https://fonts.googleapis.com/css?family=Libre+Barcode+39+Text" rel="stylesheet">

	<style>
	body {
		font-family: 'Libre Barcode 39 Text', cursive;
		font-size: 44px;
	}
</style>
</head>
<body>
	<div>{{$no_telp}}</div>
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