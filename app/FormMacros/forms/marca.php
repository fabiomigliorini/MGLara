<?php

Form::macro('select2Marca', function()
{
    $input = "<input type='text' id='codmarca' name='codmarca' placeholder='Marca'>";
    $input .= "@section('macros_scripts')<script type='text/javascript' src=" . @asset('app/FormMacros/scripts/marca.js') . "></script>@endsection";
    
    return $input;
});

