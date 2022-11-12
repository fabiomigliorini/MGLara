<?php

if(!function_exists('formataData')) {
    function formataData($data, $formato = 'C') {

        if (empty($data)) {
            return null;
        }

        if(!$data instanceof Carbon\Carbon) {
            $data = new Carbon\Carbon($data);
        }

        switch (strtoupper($formato))
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
                return $data->format('d/m/Y H:i');
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

if(!function_exists('mask')) {
    function mask($val, $mask){
        $maskared = '';
        $k = 0;
        for($i = 0; $i<=strlen($mask)-1; $i++){
            if($mask[$i] == '#')
            {
               if(isset($val[$k]))
               $maskared .= $val[$k++];
            }
            else
            {
               if(isset($mask[$i]))
                   $maskared .= $mask[$i];
            }
        }
        return $maskared;
    }
}
if(!function_exists('formataCnpj')) {
    function formataCnpj ($value){
        return mask($value,'##.###.###/####-##');
    }
}
if(!function_exists('formataCpf')) {
    function formataCpf ($value){
        return mask($value,'###.###.###-##');
    }
}
if(!function_exists('formataCpfCnpj')) {
    function formataCpfCnpj ($value){
        $value = preg_replace("/[^0-9]/", "", $value);
        if(strlen($value)>11){
            return formataCnpj($value);
        }else{
            return formataCpf($value);
        }
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
        return preg_replace('/\D/', '', $string);
        // return preg_replace('/[^0-9\s]/', '', $string);
    }
}

if(!function_exists('converteParaNumerico')) {
    function converteParaNumerico($value) {
        return str_replace(',', '.', (str_replace('.', '', $value)));
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

if(!function_exists('formataCnpjCpf')) {
    function formataCnpjCpf ($string, $fisica = '?')
    {
        if ($fisica == '?') {
            $string = numeroLimpo($string);
            if (strlen($string) <= 11)
                $fisica = true;
            else
                $fisica = false;
        }

        if ($fisica)
            return formataPorMascara($string, '###.###.###-##');
        else
            return formataPorMascara($string, '##.###.###/####-##');
    }
}

if(!function_exists('formataPorMascara')) {
    function formataPorMascara($string, $mascara, $somenteNumeros = true)
    {
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

if(!function_exists('formataInscricaoEstadual')) {
    function formataInscricaoEstadual($string, $siglaestado)
    {
        $mascara = array(
            'AC' => '##.###.###/###-##',
            'AL' => '#########',
            'AP' => '#########',
            'AM' => '##.###.###-#',
            'BA' => '#######-##',
            'CE' => '########-#',
            'DF' => '###########-##',
            'ES' => '###.###.##-#',
            'GO' => '##.###.###-#',
            'MA' => '#########',
            'MT' => '##.###.###-#',
            'MS' => '#########',
            'MG' => '###.###.###/####',
            'PA' => '##-######-#',
            'PB' => '########-#',
            'PR' => '########-##',
            'PE' => '##.#.###.#######-#',
            'PI' => '#########',
            'RJ' => '##.###.##-#',
            'RN' => '##.###.###-#',
            'RS' => '###-#######',
            'RO' => '#############-#',
            'RR' => '########-#',
            'SC' => '###.###.###',
            'SP' => '###.###.###.###',
            'SE' => '#########-#',
            'TO' => '###########',
        );

        if (!array_key_exists($siglaestado, $mascara))
            return $string;
        else
            return formataPorMascara($string, $mascara[$siglaestado]);
    }
}

if(!function_exists('formataEndereco')) {
    function formataEndereco($endereco = null, $numero = null, $complemento = null, $bairro = null, $cidade = null, $estado = null, $cep = null, $multilinha = false)
    {
        $retorno = $endereco;

        if (!empty($numero))
            $retorno .= ', ' . $numero;

        $q = $retorno;

        if (!empty($complemento))
            $retorno .= ' - ' . $complemento;

        if (!empty($bairro))
            $retorno .= ' - ' . $bairro;

        if (!empty($cidade)){
            $retorno .= ' - ' . $cidade;
            $q .= ' - ' . $cidade;
        }

        if (!empty($estado)){
            $retorno .= ' / ' . $estado;
            $q .= ' / ' . $estado;
        }

        if (!empty($cep))
            $retorno .= ' - ' . formataCep($cep);

        $q = urlencode($q);

        if ($multilinha)
            $retorno = str_replace (" - ", "<br>", $retorno);

        return "<a href='http://maps.google.com/maps?q=$q' target='_blank'>". $retorno."</a>";
    }
}

if(!function_exists('formataCep')) {
    function formataCep ($string)
    {
        return formataPorMascara($string, "##.###-###");
    }
}

if(!function_exists('formataLocalEstoque')) {
    function formataLocalEstoque ($corredor, $prateleira, $coluna, $bloco)
    {
	if (!empty($corredor) || !empty($prateleira) || !empty($coluna) || !empty($bloco))
        {
            $corredor = str_pad($corredor, 2, '0', STR_PAD_LEFT);
            $prateleira = str_pad($prateleira, 2, '0', STR_PAD_LEFT);
            $coluna = str_pad($coluna, 2, '0', STR_PAD_LEFT);
            $bloco = str_pad($bloco, 2, '0', STR_PAD_LEFT);
            return "{$corredor}.{$prateleira}.{$coluna}.{$bloco}";
        }
        return '';
    }
}

if(!function_exists('formataNumeroNota')) {
    function formataNumeroNota ($emitida, $serie, $numero, $modelo)
    {
        return (($emitida)?"N-":"T-") . $serie . "-" . (!empty($modelo)?$modelo . "-":"") . formataPorMascara($numero, "########");
    }
}

if(!function_exists('formataChaveNfe')) {
    function formataChaveNfe ($chave)
    {
        return formataPorMascara($chave, "#### #### #### #### #### #### #### #### #### #### ####");
    }
}

if(!function_exists('removeAcentos')) {
    function removeAcentos($string)
    {
        $map = [
            'á' => 'a',
            'à' => 'a',
            'ã' => 'a',
            'â' => 'a',
            'é' => 'e',
            'ê' => 'e',
            'í' => 'i',
            'ó' => 'o',
            'ô' => 'o',
            'õ' => 'o',
            'ú' => 'u',
            'ü' => 'u',
            'ç' => 'c',
            'Á' => 'A',
            'À' => 'A',
            'Ã' => 'A',
            'Â' => 'A',
            'É' => 'E',
            'Ê' => 'E',
            'Í' => 'I',
            'Ó' => 'O',
            'Ô' => 'O',
            'Õ' => 'O',
            'Ú' => 'U',
            'Ü' => 'U',
            'Ç' => 'C'
        ];
        return strtr($string, $map);
    }
}

if(!function_exists('titulo')) {
    function titulo ($codigo, $descricao, $inativo, $digitos_codigo = 8)
    {
        if (is_string($descricao)) {
            $descricao = [$descricao];
        }

        $html = '';

        $i = 0;

        foreach ($descricao as $url => $titulo) {

            if (is_numeric($url)) {
                $url = null;
            }

            $html .= ' <li class="' . (empty($url)?'active':'') . '">';
            $html .= (empty($url))?'':"<a href='$url'>";
            $html .= (empty($inativo))?'':'<del>';
            if ($i === 1 && !empty($codigo)) {
                $html .= '<small>' . formataCodigo($codigo, $digitos_codigo) . '</small> - ';
            }
            $html .= $titulo;
            $html .= (empty($inativo))?'':'</del>';;
            $html .= (empty($url))?'':"</a>";
            $html .= '</li>';

            $i++;

        }

        if(!empty($inativo)) {
            $html .= ' <li class="text-danger">Inativo desde ' . formataData($inativo, 'L') . '</li>';
        }

        return $html;
    }
}

if(!function_exists('inativo')) {
    function inativo ($inativo)
    {
        if(!empty($inativo))
            return "<span class='label label-danger'>Inativo desde ". formataData($inativo, 'L'). "</span>";
    }
}

if(!function_exists('listagemTitulo')) {
    function listagemTitulo ($titulo, $inativo)
    {
        if(!empty($inativo))
            return "<del>$titulo</del>";
        else
            return $titulo;
    }
}


if(!function_exists('formataEstoqueMinimoMaximo')) {
    function formataEstoqueMinimoMaximo ($minimo, $maximo, $saldo = 'Vazio')
    {
        $html = '';
        if (!empty($minimo)) {
            $class = ($saldo !== 'Vazio' && $saldo < $minimo)?'text-danger':'';
            $html .= " <span class='$class'>" . formataNumero($minimo, 0) . " <span class='glyphicon glyphicon-arrow-down'></span></span> ";
        }

        if (!empty($maximo)) {
            $class = ($saldo !== 'Vazio' && $saldo > $maximo)?'text-danger':'';
            $html .= " <span class='$class'>" . formataNumero($maximo, 0) . " <span class='glyphicon glyphicon-arrow-up'></span></span> ";
        }

        if (empty($html)) {
            $html = '&nbsp;';
        }


        return $html;
    }
}

if(!function_exists('urlArrGet')) {
    function urlArrGet ($arrGet = [], $path = null, $parameters = [], $secure = null)
    {
        foreach ($arrGet as $key => $data) {
            if ($data instanceof Carbon\Carbon) {
                $arrGet[$key] = $data->format('Y-m-d H:i:s');
            }
        }
        return url($path, $parameters, $secure) . '?' . http_build_query($arrGet);
    }

}
