<?php

namespace MGLara\Providers;

use Illuminate\Support\ServiceProvider;
use Auth;
use MGLara\Models\Ncm;
use MGLara\Models\Tributacao;
use MGLara\Models\Produto;
use MGLara\Models\Marca;
use MGLara\Models\ProdutoEmbalagem;

class ValidatorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app['validator']->extend('validaFilial', function ($attribute, $value, $parameters)
        {
            if (in_array($value, Auth::user()->filiais())){
                return true;
            } else {
                return false;
            }  
        });   
        
	/**
	 * Verifica se o usuario selecionou um NCM com 8 Digitos
	 */
        $this->app['validator']->extend('validaNcm', function ($attribute, $value, $parameters)
        {
            $ncm = Ncm::find($value);
            if (strlen($ncm->ncm) == 8)
                return true;
            else
                return false;
        });          

        /**
	 *  valida se existe regulamento de ICMS ST no MT para o NCM selecionado
	 *  se existe pede para colocar como SUBSTITUICAO
	 *  se não, não deixa marcar como SUBSTITUICAO
	 */
        $this->app['validator']->extend('validaTributacao', function ($attribute, $value, $parameters)
        {
            $ncm = Ncm::find($parameters)->first();
            $regs = $ncm->regulamentoIcmsStMtsDisponiveis();
                
            if (sizeof($regs) > 0) {
                if ($value != Tributacao::SUBSTITUICAO)
                    return false;
            } else {
                if ($value == Tributacao::SUBSTITUICAO)
                    return false;
            }
            return true;
        });    
        
        $this->app['validator']->extend('validaMarca', function ($attribute, $value, $parameters)
        {
            $marca = Marca::find($parameters[0]);
            if (!empty($value) && !empty($parameters[0]) && $parameters[1] == '')
            {
                if (strpos(strtoupper($value), strtoupper($marca->marca)) === false) {
                    return false;
                } else {
                    return true;
                }    
            } else {
                return true;
            }
        }); 
        
        $this->app['validator']->extend('validaPrecoMin', function ($attribute, $value, $parameters)
        {
            $produto = Produto::find($parameters[0]);
            
            if ($value <= $produto->preco)
                return false;
            
            return true;
        });        
        
        $this->app['validator']->extend('validaPrecoMax', function ($attribute, $value, $parameters)
        {
            $produto = Produto::find($parameters[0]);
            
            if ($value >= ($produto->preco * $parameters[1]))
                return false;
            
            return true;
        });        
        
        $this->app['validator']->extend('validaQuantidade', function ($attribute, $value, $parameters)
        {
            if(empty($parameters[1]))
                $parameters[1] = 0;
            
            $query = ProdutoEmbalagem::validaQuantidade($parameters[0], $value, $parameters[1]);
            
            if (!$query)
                return false;
            
            return true;
        });        
        
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        parent::boot();

    }
}
