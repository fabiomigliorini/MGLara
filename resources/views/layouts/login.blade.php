<!DOCTYPE html>
<html lang="pt-BR">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="{{ URL::asset('favicon.ico') }}">
    <title>Autenticação MGSis</title>
    <link href="{{ URL::asset('public/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('public/css/starter-template.css') }}" rel="stylesheet">
    <script src="{{ URL::asset('public/vendor/jquery/2.1.1/jquery.min.js') }}"></script>
    <script src="{{ URL::asset('public/vendor/bootstrap/js/bootstrap.min.js') }}"></script>
    @yield('inscript')
  </head>

  <body>
    <div class="container-fluid">
      @yield('content')
    </div>
  </body>
</html>