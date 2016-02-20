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
        return $date->format('d/m/Y h:m:s');
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