<?php

if(!function_exists('dateBR')) {
    function dateBR($date) {
        if(!$date instanceof \DateTime) {
            $date = new \DateTime($date);
        }
        return $date->format('d/m/Y');
    }
}

if(!function_exists('dateBRfull')) {
    function dateBRfull($date) {
        if(!$date instanceof \DateTime) {
            $date = new \DateTime($date);
        }
        return $date->format('d/m/Y H:i:s');
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