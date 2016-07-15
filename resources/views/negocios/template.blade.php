@extends('layouts.default')
@section('content')

<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
    <div class="container-fluid">
        <ul class="nav navbar-nav">
            @yield('navbar')
        </ul>
    </div>
</nav>
<h1 class="header">@yield('title')</h1>
<hr>

@yield('body')

@section('inscript')
<link rel="stylesheet" href="{{ URL::asset('public/css/negocios.css') }}">
<script src="{{ URL::asset('public/js/negocios.js') }}"></script>
@endsection
@stop
