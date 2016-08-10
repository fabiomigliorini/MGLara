<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

use MGLara\Models\ProdutoBarra;

class ConverteCodigoBarrasInterno234 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        
        DB::EnableQueryLog();
        $pbs = ProdutoBarra::whereRaw("
            barras ilike '%' || cast(codproduto as varchar) || '%' 
            or barras ilike '%-%'
            or char_length(barras) = 6
        ")->get();
        //$pbs = ProdutoBarra::whereRaw("barras ilike lpad(cast(codproduto as varchar), 8, '0') || '%'")->get();
        
        foreach ($pbs as $pb) {
            
            //Formato 1
            //PPPPPP-QQ
            $barras = str_pad($pb->codproduto, 6, '0', STR_PAD_LEFT);
            if (!empty($pb->codprodutoembalagem)) {
                $barras .= '-' . (float) $pb->ProdutoEmbalagem->quantidade;
            }
            
            if ($barras === $pb->barras) {
                $pb->barras = null;
                $pb->save();
                echo("Convertido Formato 1 '{$barras}' para '{$pb->barras}'\n");
                continue;
            }
            
            //Formato 2
            //PPPPPP-QQ (virgula ao inves de .)
            $barras = str_replace('.', ',', $barras);
            if ($barras === $pb->barras) {
                $pb->barras = null;
                $pb->save();
                echo("Convertido Formato 2 '{$barras}' para '{$pb->barras}'\n");
                continue;
            }
            
            //Formato 3
            //PPPPPP-VVVVVVVV-QQ
            $barras = str_pad($pb->codproduto, 6, '0', STR_PAD_LEFT);
            $barras .= '-' . str_pad($pb->codprodutovariacao, 8, '0', STR_PAD_LEFT);
            if (!empty($pb->codprodutoembalagem)) {
                $barras .= '-' . (float) $pb->ProdutoEmbalagem->quantidade;
            }
            
            if ($barras === $pb->barras) {
                $pb->barras = null;
                $pb->save();
                echo("Convertido Formato 3 '{$barras}' para '{$pb->barras}'\n");
                continue;
            }
            
            echo("Ignorado '{$pb->barras}'\n");
        }
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
