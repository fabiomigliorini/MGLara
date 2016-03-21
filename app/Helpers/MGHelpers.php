<?php

if(!function_exists('formataData')) {
    function formataData($data, $formato = 'C') {
        
        if(!$data instanceof Carbon\Carbon) {
            $data = new Carbon\Carbon($data);
        }
        
        $formato = strtoupper($formato);
        
        switch ($formato)
        {
            case 'C':
            case 'CURTO':
                return $data->format('d/m/y');
                break;
            
            case 'M':
            case 'MEDIO':
                return $data->format('d/m/Y');
                break;
            
            case 'E':
            case 'EXTENSO':
                // ('%A %d %B %Y');  // Mittwoch 21 Mai 1975
                return $data->formatLocalized('%d %B %Y');
                break;

            case 'EC':
            case 'EXTENSOCURTO':
                return $data->formatLocalized('%b/%Y');
                break;

            case 'L':
            case 'LONGO':
                return $data->format('d/m/Y H:i:s');
                break;
            
            default:
                return $data->format($formato);
                break;
        }
    }
}

if(!function_exists('formataCodigo')) {
    function formataCodigo ($value, $digitos = 8){
        return "#" . str_pad($value, $digitos, "0", STR_PAD_LEFT);
    }
}

if(!function_exists('formataNumero')) {
    function formataNumero ($value, $digitos = 2){
        if ($value === null)
            return $value;
        return number_format($value, $digitos, ",", ".");
    }
}

if(!function_exists('formataNcm')) {
    function formataNcm ($string){
	$string = str_pad(numeroLimpo($string), 8, "-", STR_PAD_RIGHT);
	return formataPorMascara($string, "####.##.##", false);
    }
}

if(!function_exists('formataCest')) {
    function formataCest ($string){
        $string = str_pad(numeroLimpo($string), 7, "-", STR_PAD_RIGHT);
        return formataPorMascara($string, "##.###.##", false);
    }
}

if(!function_exists('formataPorMascara')) {
    function formataPorMascara ($string, $mascara, $somenteNumeros = true){
        if ($somenteNumeros)
            $string = numeroLimpo($string);
        /* @var $caracteres int */
        $caracteres = substr_count($mascara, '#');
        $string = str_pad($string, $caracteres, "0", STR_PAD_LEFT);
        $indice = -1;
        for ($i=0; $i < strlen($mascara); $i++):
            if ($mascara[$i]=='#') $mascara[$i] = $string[++$indice];
        endfor;
        return $mascara;

    }
}

if(!function_exists('numeroLimpo')) {
    function numeroLimpo($string) {
        return preg_replace( '/[^0-9]/', '', $string);
    }
}

if(!function_exists('modelUrl')) {
    function modelUrl($string) {
        return $output = ltrim(strtolower(preg_replace('/[A-Z]/', '-$0', $string)), '-');
    }
}

if(!function_exists('isNull')) {
    function isNull($str) {
        return "<span class='null'>$str</span>";
    }
}

if(!function_exists('checkPermissao')) {
    function checkPermissao($f, $g, $array) {
        foreach ($array as $item) {
            if (isset($item['filial']) && $item['filial'] === $f) {
                if (isset($item['grupo']) && $item['grupo'] === $g) {
                    return 'checked';
                }
            }
        }
        return false;
    }
}

if(!function_exists('linkRel')) {
    function linkRel($text, $url, $id) {
        $link = url($url.'/'.$id);
        return "<a href='$link'>$text</a>";
    }
}