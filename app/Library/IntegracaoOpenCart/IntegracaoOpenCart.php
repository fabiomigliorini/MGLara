<?php

namespace MGLara\Library\IntegracaoOpenCart;

use Illuminate\Support\Facades\Log;

use MGLara\Library\IntegracaoOpenCart\IntegracaoOpencartBase;
use MGLara\Models\Marca;
use MGLara\Models\SecaoProduto;
use MGLara\Models\FamiliaProduto;
use MGLara\Models\GrupoProduto;
use MGLara\Models\SubGrupoProduto;

class IntegracaoOpenCart extends IntegracaoOpencartBase {
    
    protected $manufacturers = [];
    protected $manufacturersUpdated = [];
    
    protected $categories = [];
    protected $categoriesUpdated = [];
    
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
                if (!$this->deleteManufacturer($id)) {
                    Log::error (class_basename($this) . " - Erro ao excluir Marca $id excedente - $this->response");
                    $retorno = false;
                }
            }
        }
        return $retorno;
    }

    /**
     * 
     * @param type $codmarca
     * @param type $excluir_excedentes
     */
    public function sincronizaMarcas ($codmarca = null, $excluir_excedentes = null) 
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
        if ($excluir_excedentes === null) {
            $excluir_excedentes = empty($codmarca)?true:false;
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
                        $marca = $marca->fresh();
                        $marca->codopencart = $codigos[$i];
                        $marca->save();
                    // senao loga o erro
                    } else {
                        Log::error (class_basename($this) . " - Erro ao criar Marca #$marca->codmarca - $marca->marca - $this->response");
                        $retorno = false;
                    }
                
                // salva o id do opencart no sistem
                } else {
                    $marca = $marca->fresh();
                    $marca->codopencart = $id;
                    $marca->save();
                }
            }
            
            // TODO: logica para saber se precisa atualizar a imagem
            if (!empty($marca->codimagem)) {
                $image_path = base_path('public/imagens/'.$marca->Imagem->observacoes);
                if (file_exists($image_path)) {
                    if (!$this->uploadManufacturerImage($marca->codopencart, $image_path)) {
                        Log::error (class_basename($this) . " - Erro ao fazer UPLOAD da imagem #$marca->codmarca - $marca->marca - {$marca->Imagem->observacoes}");
                        $retorno = false;
                    }
                }
            }
            
            // inclui na lista de marcas atualizadas
            $this->manufacturersUpdated[$marca->codopencart] = $marca;
        }
        
        // chama exclusao de excedentes
        if ($excluir_excedentes) {
            if (!$this->excluiMarcasExcedentes()) {
                $retorno = false;                
            }
        }
        
        return $retorno;
            
    }
    
    public function excluiSecoesExcedentes() 
    {
        $retorno = true;
        foreach ($this->categories as $id => $category) {
            if (!isset($this->categoriesUpdated[$id])) {
                if (!$this->deleteCategory($id)) {
                    Log::error (class_basename($this) . " - Erro ao excluir Categoria #$id excedente - $this->response");
                    $retorno = false;
                }
            }
        }
        return $retorno;
    }
    
    public function buscaSecoesOpenCart ($id = null) 
    {
        if (!$categories = $this->getCategory($id)) {
            return false;
        }
        foreach ($categories as $id => $category) {
            $this->categories[$id] = $category;
        }
        return sizeof($categories);
    }
    
    public function sincronizaSecoes ($codsubgrupo = null, $excluir_excedentes = null) 
    {
        if (empty($codsubgrupo)) {
            $secoes = SecaoProduto::orderBy('codsecaoproduto')->get();
            $familias = FamiliaProduto::orderBy('codfamiliaproduto')->get();
            $grupos = GrupoProduto::orderBy('codgrupoproduto')->get();
            $subgrupos = SubGrupoProduto::orderBy('codsubgrupoproduto')->get();
            $this->buscaSecoesOpenCart();
        } else {
            $subgrupos = SubGrupoProduto::where('codsubgrupoproduto', $codsubgrupo)->get();
            $grupos = collect([$subgrupos[0]->GrupoProduto]);
            $familias = collect([$grupos[0]->FamiliaProduto]);
            $secoes = collect([$familias[0]->SecaoProduto]);
            
            if (!empty($secoes[0]->codopencart)) {
                $this->buscaSecoesOpenCart($secoes[0]->codopencart);
            }
            if (!empty($familias[0]->codopencart)) {
                $this->buscaSecoesOpenCart($familias[0]->codopencart);
            }
            if (!empty($grupos[0]->codopencart)) {
                $this->buscaSecoesOpenCart($grupos[0]->codopencart);
            }
            if (!empty($subgrupos[0]->codopencart)) {
                $this->buscaSecoesOpenCart($subgrupos[0]->codopencart);
            }
        }
        
        // por padrao exclui categorias excedentes somente em caso de sincronizacao completa
        if ($excluir_excedentes === null) {
            $excluir_excedentes = empty($codmarca)?true:false;
        }
        
        $retorno = true;
        
        // Atualiza ou Cria Secoes
        foreach ($secoes as $secao) {
            
            
            // Se ja existe Marca atualiza
            if (isset($this->categories[$secao->codopencart])) {
               
                // Caso falha na atualizacao
                if (!$id = $this->updateCategory($secao->codopencart, 1, null, 1, 4, 1, $secao->secaoproduto, $secao->secaoproduto, $secao->secaoproduto, $secao->secaoproduto, $secao->secaoproduto)) {
                    Log::error (class_basename($this) . " - Erro ao atualizar Marca #$secao->codsecaoproduto($secao->codopencart) - $secao->secaoproduto ");
                    $retorno = false;
                }
                
            } else {
                
                // Caso falha na criacao
                if (!$id = $this->createCategory(1, null, 1, 4, 1, $secao->secaoproduto, $secao->secaoproduto, $secao->secaoproduto, $secao->secaoproduto, $secao->secaoproduto)) {
                    Log::error (class_basename($this) . " - Erro ao criar Secao #$secao->codsecaoproduto - $secao->secaoproduto - $this->response");
                    $retorno = false;
                    
                // salva o id do opencart no sistema
                } else {
                    $secao = $secao->fresh();
                    $secao->codopencart = $id;
                    $secao->save();
                }
                
            }
            
            // inclui na lista de categorias atualizadas
            $this->categoriesUpdated[$secao->codopencart] = $secao;
            
        }
        
        // Atualiza ou Cria Familias
        foreach ($familias as $familia) {
            
            // recarrega o modelo para atualizar o parent_id, em caso de recem incluido
            $familia = $familia->fresh();
            
            // Se ja existe Marca atualiza
            if (isset($this->categories[$familia->codopencart])) {
               
                // Caso falha na atualizacao
                if (!$id = $this->updateCategory($familia->codopencart, 1, $familia->SecaoProduto->codopencart, 1, 4, 1, $familia->familiaproduto, $familia->familiaproduto, $familia->familiaproduto, $familia->familiaproduto, $familia->familiaproduto)) {
                    Log::error (class_basename($this) . " - Erro ao atualizar Familia #$familia->codfamiliaproduto($familia->codopencart) - $familia->familiaproduto ");
                    $retorno = false;
                }
                
            } else {
                
                // Caso falha na criacao
                if (!$id = $this->createCategory(1, $familia->SecaoProduto->codopencart, 1, 4, 1, $familia->familiaproduto, $familia->familiaproduto, $familia->familiaproduto, $familia->familiaproduto, $familia->familiaproduto)) {
                    Log::error (class_basename($this) . " - Erro ao criar Familia #$familia->codfamiliaproduto - $familia->familiaproduto - $this->response");
                    $retorno = false;
                    
                // salva o id do opencart no sistema
                } else {
                    $familia = $familia->fresh();
                    $familia->codopencart = $id;
                    $familia->save();
                }
                
            }
            
            // inclui na lista de categorias atualizadas
            $this->categoriesUpdated[$familia->codopencart] = $familia;
            
        }
        
        // Atualiza ou Cria Grupos
        foreach ($grupos as $grupo) {
            
            // recarrega o modelo para atualizar o parent_id, em caso de recem incluido
            $grupo = $grupo->fresh();
            
            // Se ja existe Marca atualiza
            if (isset($this->categories[$grupo->codopencart])) {
               
                // Caso falha na atualizacao
                if (!$id = $this->updateCategory($grupo->codopencart, 1, $grupo->FamiliaProduto->codopencart, 1, 4, 1, $grupo->grupoproduto, $grupo->grupoproduto, $grupo->grupoproduto, $grupo->grupoproduto, $grupo->grupoproduto)) {
                    Log::error (class_basename($this) . " - Erro ao atualizar Grupo #$grupo->codgrupoproduto($grupo->codopencart) - $grupo->grupoproduto ");
                    $retorno = false;
                }
                
            } else {
                
                // Caso falha na criacao
                if (!$id = $this->createCategory(1, $grupo->FamiliaProduto->codopencart, 1, 4, 1, $grupo->grupoproduto, $grupo->grupoproduto, $grupo->grupoproduto, $grupo->grupoproduto, $grupo->grupoproduto)) {
                    Log::error (class_basename($this) . " - Erro ao criar Grupo #$grupo->codgrupoproduto - $grupo->grupoproduto - $this->response");
                    $retorno = false;
                    
                // salva o id do opencart no sistema
                } else {
                    $grupo = $grupo->fresh();
                    $grupo->codopencart = $id;
                    $grupo->save();
                }
                
            }
            
            // inclui na lista de categorias atualizadas
            $this->categoriesUpdated[$grupo->codopencart] = $grupo;
            
        }
        
        // Atualiza ou Cria Grupos
        foreach ($subgrupos as $subgrupo) {
            
            // recarrega o modelo para atualizar o parent_id, em caso de recem incluido
            $subgrupo = $subgrupo->fresh();
            
            // Se ja existe Marca atualiza
            if (isset($this->categories[$subgrupo->codopencart])) {
               
                // Caso falha na atualizacao
                if (!$id = $this->updateCategory($subgrupo->codopencart, 1, $subgrupo->GrupoProduto->codopencart, 1, 4, 1, $subgrupo->subgrupoproduto, $subgrupo->subgrupoproduto, $subgrupo->subgrupoproduto, $subgrupo->subgrupoproduto, $subgrupo->subgrupoproduto)) {
                    Log::error (class_basename($this) . " - Erro ao atualizar SubGrupo #$subgrupo->codsubgrupoproduto($subgrupo->codopencart) - $subgrupo->subgrupoproduto ");
                    $retorno = false;
                }
                
            } else {
                
                // Caso falha na criacao
                if (!$id = $this->createCategory(1, $subgrupo->GrupoProduto->codopencart, 1, 4, 1, $subgrupo->subgrupoproduto, $subgrupo->subgrupoproduto, $subgrupo->subgrupoproduto, $subgrupo->subgrupoproduto, $subgrupo->subgrupoproduto)) {
                    Log::error (class_basename($this) . " - Erro ao criar SubGrupo #$subgrupo->codsubgrupoproduto - $subgrupo->subgrupoproduto - $this->response");
                    $retorno = false;
                    
                // salva o id do opencart no sistema
                } else {
                    $subgrupo = $subgrupo->fresh();
                    $subgrupo->codopencart = $id;
                    $subgrupo->save();
                }
                
            }
            
            // inclui na lista de categorias atualizadas
            $this->categoriesUpdated[$subgrupo->codopencart] = $subgrupo;
            
        }
        
        // chama exclusao de excedentes
        if ($excluir_excedentes) {
            if (!$this->excluiSecoesExcedentes()) {
                $retorno = false;                
            }
        }
        
        return $retorno;
    }
    
}
