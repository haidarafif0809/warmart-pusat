@extends('layouts.app')
@section('content')
<div id="vue-app">
	<router-view name="satuanIndex"></router-view>

	<router-view>
		
	</router-view>
</div>


@endsection

@section('scripts')
<script type="text/javascript" src="{{ asset('js/app.js?v=1.2')}}"></script>

@endsection
