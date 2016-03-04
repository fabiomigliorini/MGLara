<?php

$barras_array = $model->ProdutoBarraS;
$barras = [];
foreach ($barras_array as $barr)
{
    
    $barras[] = $barr->codprodutobarra;
}
dd($barras)
?>
<h4>Negócios</h4>
