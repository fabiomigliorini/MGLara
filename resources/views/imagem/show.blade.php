@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
	<div class="container-fluid"> 
		<ul class="nav navbar-nav">
			<li>
				<a href="{{ url('') }}"><span class="glyphicon glyphicon-plus-alt"></span> Nova</a>
			</li> 
		</ul>
	</div>
</nav>
<h1 class="header">Imagem #</h1>
<hr>
detalhes

@stop