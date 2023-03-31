@extends('layouts.default')
@section('content')
@if (empty($mensagem)):
	<h1 class="header text-danger">Erro 500</h1>
@else
	<h1 class="header text-danger">{{ $mensagem }}</h1>
@endif
<h4><a href="javascript:window.history.back();">Clique para retornar ao Sistema</a></h4>
@stop
