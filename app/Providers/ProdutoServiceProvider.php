<?php

namespace MGLara\Providers;

use Illuminate\Support\ServiceProvider;
use MGLara\Models\Produto;
use MGLara\Models\ProdutoBarra;
use MGLara\Models\ProdutoEmbalagem;
use MGLara\Models\ProdutoHistoricoPreco;

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
            $pb->barras = str_pad($pe->codproduto, 6, "0", STR_PAD_LEFT).'-'.(int)$pe->quantidade;
            $pb->codprodutoembalagem = $pe->codprodutoembalagem;
            try {
                $pb->save();
                return true;
            } catch (Exception $ex) {
                return false;
            }
        });
        
        Produto::updated(function ($produto) {
            if($produto->getOriginal('preco') != $produto->preco)
            {
                $model = new ProdutoHistoricoPreco();
                $model->codproduto  = $produto->codproduto;
                $model->precoantigo = $produto->getOriginal('preco');
                $model->preconovo   = $produto->preco;
                try {
                    $model->save();
                    return true;
                } catch (Exception $ex) {
                    return false;
                }
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
