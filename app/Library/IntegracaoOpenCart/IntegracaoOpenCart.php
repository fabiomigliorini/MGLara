<?php

namespace MGLara\Library\IntegracaoOpenCart;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

use MGLara\Library\IntegracaoOpenCart\IntegracaoOpencartBase;
use MGLara\Models\Marca;
use MGLara\Models\SecaoProduto;
use MGLara\Models\FamiliaProduto;
use MGLara\Models\GrupoProduto;
use MGLara\Models\SubGrupoProduto;
use MGLara\Models\Produto;

class IntegracaoOpenCart extends IntegracaoOpencartBase {
    
    protected $manufacturers = [];
    protected $manufacturersUpdated = [];
    
    protected $categories = [];
    protected $categoriesUpdated = [];
    
    protected $productOptions = [];
    protected $productOptionsUpdated = [];
    
    protected $products = [];
    protected $productsUpdated = [];
    
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
    
    public function buscaSecoesOpenCart ($id = 'all') 
    {
        if (!$categories = $this->getCategory($id)) {
            return false;
        }
        foreach ($categories as $id => $category) {
            $this->categories[$id] = $category;
        }
        return sizeof($categories);
    }
    
    public function buscaProdutosOpenCart ($product_id = 'all', $sku = 'all', $option_id = 'all')
    {
        $this->productOptions = $this->getProductOption($option_id);
        $this->products = $this->getProduct($product_id, $sku);
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

    public function excluiVariacoesExcedentes() 
    {
        $retorno = true;
        if (!is_array($this->productOptions)) {
            return $retorno;
        }
        foreach ($this->productOptions as $id => $option) {
            if (!isset($this->productOptionsUpdated[$id])) {
                if (!$this->deleteProductOption($id)) {
                    Log::error (class_basename($this) . " - Erro ao excluir Variacao $id excedente - $this->response");
                    $retorno = false;
                }
            }
        }
        return $retorno;
    }

    /**
     * 
     * @param type $codmarca
     */
    public function exportaMarcas ($marcas)
    {

        $retorno = true;
        
        // Percorre todas as marcas
        foreach ($marcas as $marca) {
            
            // Caso marca ja tenha sido atualizada, continua
            if (isset($this->manufacturersUpdated[$marca->codopencart])) {
                continue;
            }
            
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
        
        return $retorno;
            
    }
    
    public function exportaCategorias ($secoes, $familias, $grupos, $subgrupos) 
    {
        
        $retorno = true;
        
        // Atualiza ou Cria Secoes
        foreach ($secoes as $secao) {
                        
            // Caso secao ja tenha sido atualizada, continua
            if (isset($this->categoriesUpdated[$secao->codopencart])) {
                continue;
            }
            
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

            // Caso familia ja tenha sido atualizada, continua
            if (isset($this->categoriesUpdated[$familia->codopencart])) {
                continue;
            }
                        
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

            // Caso grupo ja tenha sido atualizada, continua
            if (isset($this->categoriesUpdated[$grupo->codopencart])) {
                continue;
            }
            
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

            // Caso subgrupo ja tenha sido atualizada, continua
            if (isset($this->categoriesUpdated[$subgrupo->codopencart])) {
                continue;
            }
            
            // recarrega o modelo para atualizar o parent_id, em caso de recem incluido
            $subgrupo = $subgrupo->fresh();
            
            // Se ja existe Marca atualiza
            if (isset($this->categories[$subgrupo->codopencart])) {
               
                // Caso falha na atualizacao
                if (!$this->updateCategory($subgrupo->codopencart, 1, $subgrupo->GrupoProduto->codopencart, 1, 4, 1, $subgrupo->subgrupoproduto, $subgrupo->subgrupoproduto, $subgrupo->subgrupoproduto, $subgrupo->subgrupoproduto, $subgrupo->subgrupoproduto)) {
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
        
        return $retorno;
    }
    
    public function exportaProdutos ($produtos)
    {
        
        foreach ($produtos as $produto) {
            
            // Atualiza Variacoes
            $variacoes = $produto->ProdutoVariacaoS()->orderBy('codprodutovariacao')->get();
            if (sizeof($variacoes) > 1) {
                
                // Se ja existe Variacao atualiza
                if (isset($this->productOptions[$produto->codopencartvariacao])) {

                    $valores_excluir = [];
                    $atualizados = [];
                    foreach ($this->productOptions[$produto->codopencartvariacao]->option_values as $valor) {
                        
                        // Se não achar opcao no sistema, inclui na listagem para excluir
                        if (!$pv = $produto->ProdutoVariacaoS()->where('codopencart', $valor->option_value_id)->first()) {
                            $valores_excluir[] = $valor->option_value_id;
                            continue;
                        }
                        
                        if (!$this->updateProductOptionValue($pv->codopencart, 1, empty($pv->variacao)?'{ Sem Variação }':$pv->variacao)) {
                            Log::error (class_basename($this) . " - Erro ao atualizar Opcao da Variacao #$pv->codprodutovariacao($valor->option_value_id) - $pv->variacao ");
                            $retorno = false;
                        }
                        
                        // Marca como atualizado
                        $atualizados[] = $valor->option_value_id;
                        
                    }

                    // Busca novas variacoes
                    $pvs = $produto->ProdutoVariacaoS()->where(function ($query) use($atualizados) {
                        $query->whereNotIn('codopencart', $atualizados)
                            ->orWhereNull('codopencart');
                    })->get();
                    
                    // percorre novas variacoes incluindo como Valor da Opcao
                    foreach ($pvs as $pv) {
                        
                        if (!$id = $this->createProductOptionValue ($produto->codopencartvariacao, 1, empty($pv->variacao)?'{ Sem Variação }':$pv->variacao)) {
                            Log::error (class_basename($this) . " - Erro ao Criar Opcao da Variacao #$pv->codprodutovariacao - $pv->variacao ");
                            $retorno = false;

                        // salva o id do opencart no sistema
                        } else {
                            $pv = $pv->fresh();
                            $pv->codopencart = $id;
                            $pv->save();
                        }

                    }
                    
                    if (sizeof($valores_excluir) > 0) {
                        if (!$id = $this->deleteProductOptionValue ($valores_excluir)) {
                            Log::error (class_basename($this) . " - Erro ao Excluir Variacoes Excedentes do Produto #$produto->codproduto($produto->codopencartvariacao) - $produto->produto ");
                            $retorno = false;
                        }
                    }
                    
                } else {

                    $values = [];
                    foreach ($variacoes as $pv) {
                        $values[] = [
                            'image' => '',
                            'sort_order' => 1,
                            'option_value_description' => [[
                                'language_id' => $this->languagePTBR,
                                'name' => empty($pv->variacao)?'{ Sem Variação }':$pv->variacao,
                            ]]
                        ];
                    }

                    // Caso falha na criacao
                    if (!$id = $this->createProductOption(1, 'radio', 'Variações', $values)) {
                        Log::error (class_basename($this) . " - Erro ao criar Variacao #$produto->codproduto - $produto->produto - $this->response");
                        $retorno = false;

                    // salva o id do opencart no sistema
                    } else {
                        $produto = $produto->fresh();
                        $produto->codopencartvariacao = $id;
                        $produto->save();
                        
                        // salva id das opcoes
                        $i = 0;
                        foreach ($this->responseObject->data->option_values as $opt) {
                            $variacoes[$i] = $variacoes[$i]->fresh();
                            $variacoes[$i]->codopencart = $opt->option_value_id;
                            $variacoes[$i]->save();
                            $i++;
                        }
                    }

                }

                // inclui na lista de categorias atualizadas
                $this->productOptionsUpdated[$produto->codopencartvariacao] = $produto;
            }

            // Sincronizacao do Produto
            $barras = '';
            if ($pb = $produto->ProdutoBarras()->whereNull('codprodutoembalagem')->first()) {
                $barras = $pb->barras;
            }

            $description = nl2br($produto->descricaosite);

            $pes = $produto->ProdutoEmbalagemS()->orderBy('quantidade')->get();
            if (sizeof($pes) > 0) {

                if (strlen($description) > 0) {
                    $description .= "\n\n<hr>\n";
                }

                $description .= "<h3>Disponível também nas embalagens:</h3>\n<ul>";

                foreach ($pes as $pe) {
                    $description .= "<li>{$pe->UnidadeMedida->unidademedida} com " . formataNumero($pe->quantidade, 0) . "</li>\n";
                }

                $description .= '</ul>';

            }

            $product_option = [];
            if (sizeof($variacoes) > 1) {
                $values = [];
                foreach($variacoes as $pv) {
                    $values[] = [
                        'price' => 0,
                        'price_prefix' => '+',
                        'subtract' => 1,
                        'points' => null,
                        'points_prefix' => null,
                        'weight' => null,
                        'weight_prefix' => null,
                        'option_value_id' => $pv->codopencart,
                        'quantity' => null
                    ];
                }
                $product_option = [[
                    'type' => 'radio',
                    'required' => 1,
                    'option_id' => $produto->codopencartvariacao,
                    'product_option_value' => $values,
                ]];
            }

            // TODO: listagem dos produtos relacionados
            $sql = "
                select p_rel.codopencart
                from tblprodutobarra pb
                inner join tblnegocioprodutobarra npb on (npb.codprodutobarra = pb.codprodutobarra)
                inner join tblnegocioprodutobarra npb_rel on (npb_rel.codnegocio = npb.codnegocio)
                inner join tblprodutobarra pb_rel on (pb_rel.codprodutobarra = npb_rel.codprodutobarra)
                inner join tblproduto p_rel on (p_rel.codproduto = pb_rel.codproduto)
                where pb.codproduto = {$produto->codproduto}
                and pb_rel.codproduto != {$produto->codproduto}
                and p_rel.codopencart is not null
                and p_rel.site = true
                group by p_rel.codopencart
                order by count(npb_rel.codnegocioprodutobarra) desc
                limit 10
            ";
            $prods_related = DB::select($sql);
            
            $product_related = [];
            foreach ($prods_related as $related) {
                $product_related[] = $related->codopencart;
            }
            
            $model = $produto->referencia;
            $sku = $produto->codproduto;
            $quantity = null;
            $price = $produto->preco;
            $keyword = str_pad($produto->codproduto, 6, '0', STR_PAD_LEFT);
            $tax_class_id = null;
            $manufacturer_id = $produto->Marca->codopencart;
            $sort_order = 1;
            $status = (empty($produto->inativo)?1:0); // 1 - Ativo / 0 - Inativo
            $ean = $barras;
            $stock_status_id = 6; // Pre Order
            $subtract = 1;
            $product_category = [
                $produto->SubGrupoProduto->codopencart,
                $produto->SubGrupoProduto->GrupoProduto->codopencart,
                $produto->SubGrupoProduto->GrupoProduto->FamiliaProduto->codopencart,
                $produto->SubGrupoProduto->GrupoProduto->FamiliaProduto->SecaoProduto->codopencart,
            ];
            $name = $produto->produto;
            $meta_description = $produto->produto;
            $meta_title = $produto->produto;
            $meta_keyword = $produto->produto;
            $description = $description;
            $tag = '';
            $product_option = $product_option;
            $product_related = $product_related;
            
            // Se ja existe produto atualiza
            if (isset($this->products[$produto->codopencart])) {
               
                // Caso falha na atualizacao
                if (!$this->updateProduct(
                    $produto->codopencart,
                    $model, 
                    $sku, 
                    $quantity, 
                    $price, 
                    $keyword, 
                    $tax_class_id,
                    $manufacturer_id,
                    $sort_order,
                    $status,
                    $ean,
                    $stock_status_id,
                    $subtract,
                    $product_category,
                    $name,
                    $meta_description,
                    $meta_title,
                    $meta_keyword,
                    $description,
                    $tag,
                    $product_option,
                    $product_related
                )) {
                    
                    Log::error (class_basename($this) . " - Erro ao atualizar Produto #$produto->codproduto($produto->codopencart) - $produto->produto");
                    $retorno = false;
                }
                
            } else {
                
                // Caso falha na criacao
                if (!$id = $this->createProduct(
                    $model, 
                    $sku, 
                    $quantity, 
                    $price, 
                    $keyword, 
                    $tax_class_id,
                    $manufacturer_id,
                    $sort_order,
                    $status,
                    $ean,
                    $stock_status_id,
                    $subtract,
                    $product_category,
                    $name,
                    $meta_description,
                    $meta_title,
                    $meta_keyword,
                    $description,
                    $tag,
                    $product_option,
                    $product_related
                )) {
                    
                    Log::error (class_basename($this) . " - Erro ao criar Produto #$produto->codproduto - $produto->produto - $this->response");
                    $retorno = false;
                    
                // salva o id do opencart no sistema
                } else {
                    $produto = $produto->fresh();
                    $produto->codopencart = $id;
                    $produto->save();
                }
                
            }
            
            // inclui na lista de categorias atualizadas
            $this->productsUpdated[$produto->codopencart] = $produto;
                
        }
        
    }
    
    public static function sincronizaProdutos ($codproduto = 'all', $excluir_excedentes = 'auto')
    {
        
        $qryProdutos = Produto::where('site', true)->orderBy('codproduto');
        
        if (is_numeric($codproduto)) {
            $produtos = $qryProdutos->where('codproduto', $codproduto)->get();
        } else {
            $produtos = $qryProdutos->get();            
        }
        
        $oc = new IntegracaoOpenCart(true);
        
        $oc->getToken();
        $oc->buscaMarcasOpenCart();
        $oc->buscaSecoesOpenCart();
        $oc->buscaProdutosOpenCart();
        
        foreach ($produtos as $produto) {
            $oc->exportaMarcas([$produto->Marca]);
            $oc->exportaCategorias(
                [$produto->SubGrupoProduto->GrupoProduto->FamiliaProduto->SecaoProduto], 
                [$produto->SubGrupoProduto->GrupoProduto->FamiliaProduto], 
                [$produto->SubGrupoProduto->GrupoProduto], 
                [$produto->SubGrupoProduto]
            );
        }
        
        $oc->exportaProdutos($produtos);
        
        $excluir_excedentes = ($codproduto == 'all')?true:false;
        
        if ($excluir_excedentes == true) {
            $this->excluiMarcasExcedentes();
            $this->excluiSecoesExcedentes();
            $this->excluiVariacoesExcedentes();
            //$this->excluiProdutosExcedentes();
        }
        
    }
    
}
