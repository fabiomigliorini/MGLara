<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="author" content="MG Papelaria">
    <title>MG Papelaria</title>
    <link href="{{ URL::asset('public/css/print.css') }}" rel="stylesheet">
    <style type='text/css' media='all'>
        @media print {
            @page {
                size: A4 landscape;
            }
        }
        
        @media screen {
            body {
                width: 27.1cm;
            }  
        }
    </style>
    @yield('inscript')
</head>
<body>
    @yield('content')
</body>
</html>