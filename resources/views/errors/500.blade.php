@extends('layouts.default')
@section('content')
<h1 class="header text-danger">{{ $mensagem }}</h1>
<h4><a href="javascript:window.history.back();">Clique para retornar ao Sistema</a></h4>
@stop