<?php

namespace MGLara\Providers;

use Illuminate\Support\ServiceProvider;
use MGLara\Models\Produto;
use MGLara\Models\ProdutoBarra;
use MGLara\Models\ProdutoEmbalagem;

class ProdutoServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        
        Produto::created(function ($produto) {
            $pb = new ProdutoBarra();
            $pb->codproduto = $produto->codproduto;
            $pb->barras = str_pad($produto->codproduto, 6, '0', STR_PAD_LEFT);
            try {
                $pb->save();
                return true;
            } catch (Exception $ex) {
                return false;
            }
        });
        
        ProdutoEmbalagem::created(function ($pe) {
            $pb = new ProdutoBarra();
            $pb->codproduto = $pe->codproduto;
            $pb->barras = 
                    str_pad($pe->codproduto, 6, '0', STR_PAD_LEFT)
                    . '-' .
                    $pe->quantidade
                    ;
            $pb->codprodutoembalagem = $pe->codprodutoembalagem;
            
            try {
                $pb->save();
                
                return true;
            } catch (Exception $ex) {
                return false;
            }
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
