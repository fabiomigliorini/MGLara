<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="author" content="MG Papelaria">
    <title>MG Papelaria</title>
    <link href="{{ URL::asset('public/css/print.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('public/vendor/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    <script src="{{ URL::asset('public/vendor/jquery/2.1.1/jquery.min.js') }}"></script>
    <style type='text/css' media='all'>
        @media print {
            @page {
                size: A4 portrait;
            }
        }
        
        @media screen {
            body {
                width: 18.4cm;
            }  
        }
    </style>
    @yield('inscript')
</head>
<body>
    @yield('content')
</body>
</html>