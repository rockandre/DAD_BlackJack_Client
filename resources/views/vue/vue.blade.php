@extends('index')

@section('title', 'BlackJack')

@section('content')
<nav class="navbar navbar-expand-md navbar-dark bg-dark">
	<button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>
	<router-link to="/" class="navbar-brand">BlackJack</router-link>
	<div class="collapse navbar-collapse" id="navbarNav">
		<ul class="navbar-nav mr-auto">
			<li class="nav-item">
				<a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="#">Features</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="#">Pricing</a>
			</li>
		</ul>
		<ul class="navbar-nav justify-content-end">
			<li class="nav-item">
				<a href="{{ url('admin') }}">Painel de Administracao</a>
			</li>
			<li class="nav-item">
				<router-link to="/login" class="nav-link">Login</router-link>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="#">Register <span class="sr-only">(current)</span></a>
			</li>
		</ul>
	</div>
</nav>
<router-view></router-view>
@endsection

@section('pagescript')
<script src="js/vueapp.js"></script>
@endsection