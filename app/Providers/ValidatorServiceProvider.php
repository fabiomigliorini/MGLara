<?php

namespace MGLara\Providers;

use Illuminate\Support\ServiceProvider;
use Auth;
use MGLara\Models\Ncm;
use MGLara\Models\Tributacao;
use MGLara\Models\Produto;
use MGLara\Models\Marca;
use MGLara\Models\ProdutoEmbalagem;

use Illuminate\Support\Facades\DB;

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
        $this->app['validator']->extend('validaTributacaoSubstituicao', function ($attribute, $value, $parameters)
        {
            $ncm = Ncm::find($parameters)->first();
            $regs = $ncm->regulamentoIcmsStMtsDisponiveis();
            if(empty($regs)) {
                if ($value == Tributacao::SUBSTITUICAO)
                    return false;
            }
            return true;
        });  

        $this->app['validator']->extend('validaTributacao', function ($attribute, $value, $parameters)
        {
            $ncm = Ncm::find($parameters)->first();
            $regs = $ncm->regulamentoIcmsStMtsDisponiveis();
            if (sizeof($regs) > 0) {
                if ($value != Tributacao::SUBSTITUICAO)
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
        
        /**
         * @param string $parameters[0] Nome da Tabela para validar
         * @param string $parameters[1] Chave Primaria
         * @param string $parameters[2] Código do registro sendo alterado
         * @param string $parameters[3] Nome do Campo 1
         * @param string $parameters[4] Nome do Campo 2
         * @param string $parameters[5] Valor do Campo 2
         * @param string $parameters[n] Nome do Campo N
         * @param string $parameters[n+1] Valor do Campo N
         */
        $this->app['validator']->extend('UniqueMultiple', function ($attribute, $value, $parameters)
        {
            $tabela = $parameters[0];
            $pk = $parameters[1];
            $codigo = $parameters[2];
            
            $validar = [$parameters[3] => $value];
            
            //dd(sizeof($parameters));
            
            $i = 4;
            while($i < (sizeof($parameters)))
            {
                $validar[$parameters[$i]] = $parameters[ $i + 1 ];
                $i += 2;
            }
            
            $query = DB::table($tabela);
            
            if (!empty($codigo))
                $query->where($pk, '!=', $codigo);
            
            foreach ($validar as $campo => $valor)
                $query->where($campo, $valor);
            
            $qtd = $query->count();

            if ($qtd > 0)
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
