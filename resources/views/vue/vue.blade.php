@extends('index')

@section('title', 'BlackJack')

@section('content')
<navbar :activated="{{json_encode( empty(session('status')) ? 2 : session('status') )}}"></navbar>
<router-view></router-view>
@endsection

@section('pagescript')
<script src="js/vueapp.js"></script>
@endsection