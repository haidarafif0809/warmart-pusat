@extends('errors::layout')

@section('title', 'Error')
@if(\App\SettingAplikasi::select('tipe_aplikasi')->first()->tipe_aplikasi == 0)
@section('message', 'Maaf, Terjadi Kesalahan Di WarMart , akan segera kami Perbaiki')
@else
@section('message', 'Maaf, Sedang Terjadi Kesalahan , akan segera kami Perbaiki')
@endif