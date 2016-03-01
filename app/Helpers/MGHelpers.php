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
                //TODO
                return $data->format('d/m/Y') . 'DATA POR EXTENSO - IMPLEMENTAR';
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