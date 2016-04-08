<?php

namespace MGLara\Providers;

use Illuminate\Support\ServiceProvider;
use Auth;
use MGLara\Models\Ncm;
use MGLara\Models\Tributacao;

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
            $ncm = Ncm::find($value);
            if (!empty($ncm))
                return;            

            if (sizeof($ncm->regulamentoIcmsStMtsDisponiveis()) > 0) {
                if ($value != Tributacao::SUBSTITUICAO)
                    return false;
            } else {
                if ($value == Tributacao::SUBSTITUICAO)
                    return false;
            }
            
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
