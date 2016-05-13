<!DOCTYPE html>
<html lang="pt-BR">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="MG Papelaria ">
    <link rel="icon" href="{{ URL::asset('favicon.ico') }}">
    <title>MG papelaria</title>
    <link href="{{ URL::asset('public/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('public/vendor/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">
    <!--<link href="{{ URL::asset('public/vendor/select2/css/select2.min.css') }}" rel="stylesheet">-->
    <link href="{{ URL::asset('public/vendor/select2/select2.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('public/vendor/bootstrap-switch/css/bootstrap3/bootstrap-switch.min.css') }}" rel="stylesheet">
    <!--<link href="{{ URL::asset('public/vendor/bootstrap-switch/toggle-buttons.css') }}" rel="stylesheet">-->
    <link href="{{ URL::asset('public/css/starter-template.css') }}" rel="stylesheet">
    
    <script src="{{ URL::asset('public/vendor/jquery/2.1.1/jquery.min.js') }}"></script>
    <!--<script src="{{ URL::asset('public/vendor/jquery/1.11.3/jquery.min.js') }}"></script>-->
    <script src="{{ URL::asset('public/vendor/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ URL::asset('public/vendor/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
    <script src="{{ URL::asset('public/vendor/bootbox/bootbox.min.js') }}"></script>
    <!--<script src="{{ URL::asset('public/vendor/bootstrap-switch/toggle.buttons.js') }}"></script>-->
    <script src="{{ URL::asset('public/vendor/moment/moment-with-locales.js') }}"></script>
    <script src="{{ URL::asset('public/vendor/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js') }}"></script>
    <!--<script src="{{ URL::asset('public/vendor/select2/js/select2.min.js') }}"></script>-->
    <script src="{{ URL::asset('public/vendor/select2/select2-3.4.1min.js') }}"></script>
    <script src="{{ URL::asset('public/vendor/select2/select2_locale_pt-BR.js') }}"></script>
    <script src="{{ URL::asset('public/vendor/autoNumeric/autoNumeric-min.js') }}"></script>
    <script src="{{ URL::asset('public/vendor/teamdf/jquery.number.min.js') }}"></script>
    <script src="{{ URL::asset('public/vendor/infinite-scroll/infinite-scroll.js') }}"></script>
    <script src="{{ URL::asset('public/vendor/maskedinput/jquery.maskedinput.min.js') }}"></script>
    <script src="{{ URL::asset('public/js/configs.js') }}"></script>
    <script src="{{ URL::asset('public/js/mgsis.js') }}"></script>
    <script src="{{ URL::asset('public/js/set-case.js') }}"></script>
        @yield('inscript')
  </head>

  <body>
    <header class="row">
        @include('includes.header')
    </header>
    <div class="container-fluid">
      @include('errors.flash')
      @yield('content')
    </div>

  </body>
</html>