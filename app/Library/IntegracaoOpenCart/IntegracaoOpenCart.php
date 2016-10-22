<?php

namespace MGLara\Library\IntegracaoOpenCart;

use Illuminate\Support\Facades\Log;

use MGLara\Library\IntegracaoOpenCart\IntegracaoOpencartBase;
use MGLara\Models\Marca;

class IntegracaoOpenCart extends IntegracaoOpencartBase {
    
    protected $manufacturers;
    protected $manufacturersUpdated = [];
    
    /**
     * Busca as marcas do opencart retornando o numero de registros encontrados
     * @return bigint
     */
    public function buscaMarcasOpenCart () 
    {
        if (!$this->manufacturers = $this->getManufacturer()) {
            return false;
        }
        return sizeof($this->manufacturers);
    }
    
    /**
     * Exclui marcas do opencart que nao estao na listagem de 'manufacturersUpdated'
     * @return boolean
     */
    public function excluiMarcasExcedentes() 
    {
        $retorno = true;
        foreach ($this->manufacturers as $id => $manufacturer) {
            if (!isset($this->manufacturersUpdated[$id])) {
                if (!$this->deleteManufacturer($manufacturer->manufacturer_id)) {
                    Log::error (class_basename($this) . " - Erro ao excluir Marcas excedentes - $this->response");
                    $retorno = false;
                }
            }
        }
        return $retorno;
    }

    /**
     * 
     * @param type $codmarca
     * @param type $excluir_excedente
     */
    public function sincronizaMarcas ($codmarca = null, $excluir_excedente = null) 
    {
        // Busca Marcas no OpenCart
        if (empty($this->manufacturers)) {
            $this->buscaMarcasOpenCart();
        }
        
        // Se não passou marca por parametro, percorre todas marcas do banco
        if (empty($codmarca)) {
            $marcas = Marca::whereNull('inativo')->get();
        // Senao pega Marca Especifica
        } else {
            // Caso tenha vindo somente um codigo, transforma em array
            if (!is_array($codmarca)) {
                $codmarca = [$codmarca];
            }
            // procura as marcas com o codigo
            $marcas = Marca::whereIn('codmarca', $codmarca)->get();
        }

        // por padrao exclui marcas excedentes somente em caso de sincronizacao completa
        if ($excluir_excedente === null) {
            $excluir_excedente = empty($codmarca)?true:false;
        }
        
        $retorno = true;
        // Percorre todas as marcas
        foreach ($marcas as $marca) {
            
            // Se ja existe Marca atualiza
            if (isset($this->manufacturers[$marca->codopencart])) {
                if (!$this->updateManufacturer($marca->codopencart, $marca->marca, $marca->marca, 1)) {
                    Log::error (class_basename($this) . " - Erro ao atualizar Marca #$marca->codmarca($marca->codopencart) - $marca->marca ");
                    $retorno = false;
                }
            // Senao Cria
            } else {
                
                // Caso falha na criacao
                if (!$id = $this->createManufacturer($marca->marca, $marca->marca, 1)) {
                    
                    // tenta procurar se ja existe mas codigo nao estava gravado no sistema
                    if (isset($this->responseObject->error->keyword) && $this->responseObject->error->keyword == 'SEO keyword already in use!') {
                        $codigos = array_keys($this->manufacturers);
                        $i = array_search($marca->marca, array_column($this->manufacturers, 'name'));
                        $marca->codopencart = $codigos[$i];
                        $marca->save();
                    // senao loga o erro
                    } else {
                        Log::error (class_basename($this) . " - Erro ao criar Marca #$marca->codmarca - $marca->marca - $this->response");
                        $retorno = false;
                    }
                
                // salva o id do opencart no sistem
                } else {
                    $marca->codopencart = $id;
                    $marca->save();
                }
            }
            
            // TODO: logica para saber se precisa atualizar a imagem
            if (!empty($marca->codimagem)) {
                if (!$this->uploadManufacturerImage($marca->codopencart, base_path('public/imagens/'.$marca->Imagem->observacoes))) {
                    Log::error (class_basename($this) . " - Erro ao fazer UPLOAD da imagem #$marca->codmarca - $marca->marca - {$marca->Imagem->observacoes}");
                    $retorno = false;
                }
            }
            
            // inclui na lista de marcas atualizadas
            $this->manufacturersUpdated[$marca->codopencart] = $marca;
        }
        
        // chama exclusao de excedentes
        if ($excluir_excedente) {
            if (!$this->excluiMarcasExcedentes()) {
                $retorno = false;                
            }
        }
        
        return $retorno;
            
    }
    
    public function xx () {
        
    }
    
}
