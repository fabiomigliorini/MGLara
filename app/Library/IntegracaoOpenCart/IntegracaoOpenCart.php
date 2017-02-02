<?php

namespace MGLara\Library\IntegracaoOpenCart;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

use MGLara\Library\IntegracaoOpenCart\IntegracaoOpenCartBase;
use MGLara\Models\Marca;
use MGLara\Models\SecaoProduto;
use MGLara\Models\FamiliaProduto;
use MGLara\Models\GrupoProduto;
use MGLara\Models\SubGrupoProduto;
use MGLara\Models\Produto;

class IntegracaoOpenCart extends IntegracaoOpenCartBase {
    
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
    public function buscaMarcasOpenCart ($id = 'all') 
    {
        Log::info (class_basename($this) . " - Buscando Marcas OpenCart");
            
        if (!$this->manufacturers = $this->getManufacturer($id)) {
            return false;
        }
        return sizeof($this->manufacturers);
    }
    
    public function buscaSecoesOpenCart ($id = 'all') 
    {
        Log::info (class_basename($this) . " - Buscando Secoes OpenCart");
            
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
        Log::info (class_basename($this) . " - Buscando Opcoes de Produtos OpenCart");
        $this->productOptions = $this->getProductOption($option_id);
        
        Log::info (class_basename($this) . " - Buscando Produtos OpenCart");
        $this->products = $this->getProduct($product_id, $sku);
    }
    
    /**
     * Exclui marcas do opencart que nao estao na listagem de 'manufacturersUpdated'
     * @return boolean
     */
    public function excluiMarcasExcedentes() 
    {
        $retorno = true;
        if (!is_array($this->manufacturers)) {
            return $retorno;
        }
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
        if (!is_array($this->categories)) {
            return $retorno;
        }
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

    public function excluiProdutosExcedentes() 
    {
        $retorno = true;
        if (!is_array($this->products)) {
            return $retorno;
        }
        foreach ($this->products as $id => $product) {
            if (!isset($this->productsUpdated[$id])) {
                if (!$this->deleteProduct($id)) {
                    Log::error (class_basename($this) . " - Erro ao excluir Produto $id excedente - $this->response");
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
            
            Log::info (class_basename($this) . " - Exportando Marca #$marca->codmarca - $marca->marca ");
            
            $image = null;
            if (!empty($marca->codimagem)) {
                $image = 'imagens/' . $marca->Imagem->observacoes;                
            }
            
            // Se ja existe Marca atualiza
            if (isset($this->manufacturers[$marca->codopencart])) {
                if (!$this->updateManufacturer($marca->codopencart, $marca->marca, $marca->marca, 1, $image)) {
                    Log::error (class_basename($this) . " - Erro ao atualizar Marca #$marca->codmarca($marca->codopencart) - $marca->marca - $this->response");
                    $retorno = false;
                }
            // Senao Cria
            } else {
                
                // Caso falha na criacao
                if (!$id = $this->createManufacturer($marca->marca, $marca->marca, 1, $image)) {
                    
                    // tenta procurar se ja existe mas codigo nao estava gravado no sistema
                    if (isset($this->responseObject->error->keyword) && $this->responseObject->error->keyword == 'SEO keyword already in use!') {
                        $manufacturer_id = collect($this->manufacturers)->where('name', $marca->marca)->first()->manufacturer_id;
                        DB::table('tblmarca')->where('codmarca', $marca->codmarca)->update(['codopencart' => $manufacturer_id]);

                        $marca = $marca->fresh();
                    // senao loga o erro
                    } else {
                        Log::error (class_basename($this) . " - Erro ao criar Marca #$marca->codmarca - $marca->marca - $this->response");
                        $retorno = false;
                    }
                
                // salva o id do opencart no sistem
                } else {
                    DB::table('tblmarca')->where('codmarca', $marca->codmarca)->update(['codopencart' => $id]);
                    $marca = $marca->fresh();
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
            
            Log::info (class_basename($this) . " - Exportando Secao #$secao->codsecaoproduto - $secao->secaoproduto ");
            
            // Se ja existe Marca atualiza
            if (isset($this->categories[$secao->codopencart])) {
               
                // Caso falha na atualizacao
                if (!$id = $this->updateCategory($secao->codopencart, 1, null, 1, 4, 1, $secao->secaoproduto, $secao->secaoproduto, $secao->secaoproduto, $secao->secaoproduto, $secao->secaoproduto)) {
                    Log::error (class_basename($this) . " - Erro ao atualizar Marca #$secao->codsecaoproduto($secao->codopencart) - $secao->secaoproduto - $this->response");
                    $retorno = false;
                }
                
            } else {
                
                // Caso falha na criacao
                if (!$id = $this->createCategory(1, null, 1, 4, 1, $secao->secaoproduto, $secao->secaoproduto, $secao->secaoproduto, $secao->secaoproduto, $secao->secaoproduto)) {
                    Log::error (class_basename($this) . " - Erro ao criar Secao #$secao->codsecaoproduto - $secao->secaoproduto - $this->response");
                    $retorno = false;
                    
                // salva o id do opencart no sistema
                } else {
                    DB::table('tblsecaoproduto')->where('codsecaoproduto', '=', $secao->codsecaoproduto)->update(['codopencart' => $id]);
                    $secao = $secao->fresh();
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
            
            Log::info (class_basename($this) . " - Exportando Familia #$familia->codfamiliaproduto - $familia->familiaproduto ");
            
                        
            // recarrega o modelo para atualizar o parent_id, em caso de recem incluido
            $familia = $familia->fresh();
            
            // Se ja existe Marca atualiza
            if (isset($this->categories[$familia->codopencart])) {
               
                // Caso falha na atualizacao
                if (!$id = $this->updateCategory($familia->codopencart, 1, $familia->SecaoProduto->codopencart, 1, 4, 1, $familia->familiaproduto, $familia->familiaproduto, $familia->familiaproduto, $familia->familiaproduto, $familia->familiaproduto)) {
                    Log::error (class_basename($this) . " - Erro ao atualizar Familia #$familia->codfamiliaproduto($familia->codopencart) - $familia->familiaproduto - $this->response");
                    $retorno = false;
                }
                
            } else {
                
                // Caso falha na criacao
                if (!$id = $this->createCategory(1, $familia->SecaoProduto->codopencart, 1, 4, 1, $familia->familiaproduto, $familia->familiaproduto, $familia->familiaproduto, $familia->familiaproduto, $familia->familiaproduto)) {
                    Log::error (class_basename($this) . " - Erro ao criar Familia #$familia->codfamiliaproduto - $familia->familiaproduto - $this->response");
                    $retorno = false;
                    
                // salva o id do opencart no sistema
                } else {
                    DB::table('tblfamiliaproduto')->where('codfamiliaproduto', '=', $familia->codfamiliaproduto)->update(['codopencart' => $id]);
                    $familia = $familia->fresh();
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
            
            Log::info (class_basename($this) . " - Exportando Grupo #$grupo->codgrupoproduto - $grupo->grupoproduto ");
            
            // recarrega o modelo para atualizar o parent_id, em caso de recem incluido
            $grupo = $grupo->fresh();
            
            // Se ja existe Marca atualiza
            if (isset($this->categories[$grupo->codopencart])) {
               
                // Caso falha na atualizacao
                if (!$id = $this->updateCategory($grupo->codopencart, 1, $grupo->FamiliaProduto->codopencart, 1, 4, 1, $grupo->grupoproduto, $grupo->grupoproduto, $grupo->grupoproduto, $grupo->grupoproduto, $grupo->grupoproduto)) {
                    Log::error (class_basename($this) . " - Erro ao atualizar Grupo #$grupo->codgrupoproduto($grupo->codopencart) - $grupo->grupoproduto - $this->response");
                    $retorno = false;
                }
                
            } else {
                
                // Caso falha na criacao
                if (!$id = $this->createCategory(1, $grupo->FamiliaProduto->codopencart, 1, 4, 1, $grupo->grupoproduto, $grupo->grupoproduto, $grupo->grupoproduto, $grupo->grupoproduto, $grupo->grupoproduto)) {
                    Log::error (class_basename($this) . " - Erro ao criar Grupo #$grupo->codgrupoproduto - $grupo->grupoproduto - $this->response");
                    $retorno = false;
                    
                // salva o id do opencart no sistema
                } else {
                    DB::table('tblgrupoproduto')->where('codgrupoproduto', '=', $grupo->codgrupoproduto)->update(['codopencart' => $id]);
                    $grupo = $grupo->fresh();
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
            
            Log::info (class_basename($this) . " - Exportando SubGrupo #$subgrupo->codsubgrupoproduto - $subgrupo->subgrupoproduto ");
            
            // recarrega o modelo para atualizar o parent_id, em caso de recem incluido
            $subgrupo = $subgrupo->fresh();
            
            // Se ja existe Marca atualiza
            if (isset($this->categories[$subgrupo->codopencart])) {
               
                // Caso falha na atualizacao
                if (!$this->updateCategory($subgrupo->codopencart, 1, $subgrupo->GrupoProduto->codopencart, 1, 4, 1, $subgrupo->subgrupoproduto, $subgrupo->subgrupoproduto, $subgrupo->subgrupoproduto, $subgrupo->subgrupoproduto, $subgrupo->subgrupoproduto)) {
                    Log::error (class_basename($this) . " - Erro ao atualizar SubGrupo #$subgrupo->codsubgrupoproduto($subgrupo->codopencart) - $subgrupo->subgrupoproduto - $this->response");
                    $retorno = false;
                }
                
            } else {
                
                // Caso falha na criacao
                if (!$id = $this->createCategory(1, $subgrupo->GrupoProduto->codopencart, 1, 4, 1, $subgrupo->subgrupoproduto, $subgrupo->subgrupoproduto, $subgrupo->subgrupoproduto, $subgrupo->subgrupoproduto, $subgrupo->subgrupoproduto)) {
                    Log::error (class_basename($this) . " - Erro ao criar SubGrupo #$subgrupo->codsubgrupoproduto - $subgrupo->subgrupoproduto - $this->response");
                    $retorno = false;
                    
                // salva o id do opencart no sistema
                } else {
                    DB::table('tblsubgrupoproduto')->where('codsubgrupoproduto', '=', $subgrupo->codsubgrupoproduto)->update(['codopencart' => $id]);
                    $subgrupo = $subgrupo->fresh();
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
            
            Log::info (class_basename($this) . " - Exportando Produto #$produto->codproduto - $produto->produto ");
            
            $produto = $produto->fresh();
            
            if (!isset($this->manufacturersUpdated[$produto->Marca->codopencart])) {
                $this->exportaMarcas([$produto->Marca]);
            }
            
            if (!isset($this->categoriesUpdated[$produto->SubGrupoProduto->codopencart])) {
                $this->exportaCategorias(
                    [$produto->SubGrupoProduto->GrupoProduto->FamiliaProduto->SecaoProduto], 
                    [$produto->SubGrupoProduto->GrupoProduto->FamiliaProduto], 
                    [$produto->SubGrupoProduto->GrupoProduto], 
                    [$produto->SubGrupoProduto]
                );
            }
            
            $produto = $produto->fresh();
            
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
                            Log::error (class_basename($this) . " - Erro ao atualizar Opcao da Variacao #$pv->codprodutovariacao($valor->option_value_id) - $pv->variacao - $this->response");
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
                            Log::error (class_basename($this) . " - Erro ao Criar Opcao da Variacao #$pv->codprodutovariacao - $pv->variacao - $this->response");
                            $retorno = false;

                        // salva o id do opencart no sistema
                        } else {
                            DB::table('tblprodutovariacao')->where('codprodutovariacao', '=', $pv->codprodutovariacao)->update(['codopencart' => $id]);
                            $pv = $pv->fresh();
                        }

                    }
                    
                    if (sizeof($valores_excluir) > 0) {
                        if (!$id = $this->deleteProductOptionValue ($valores_excluir)) {
                            Log::error (class_basename($this) . " - Erro ao Excluir Variacoes Excedentes do Produto #$produto->codproduto($produto->codopencartvariacao) - $produto->produto - $this->response");
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
                        DB::table('tblproduto')->where('codproduto', '=', $produto->codproduto)->update(['codopencartvariacao' => $id]);
                        $produto = $produto->fresh();
                        
                        // salva id das opcoes
                        $i = 0;
                        foreach ($this->responseObject->data->option_values as $opt) {
                            DB::table('tblprodutovariacao')->where('codprodutovariacao', '=', $variacoes[$i]->codprodutovariacao)->update(['codopencart' => $opt->option_value_id]);
                            $variacoes[$i] = $variacoes[$i]->fresh();
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
                        'quantity' => 1
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
                limit 3
            ";
            $prods_related = DB::select($sql);
            
            $product_related = [];
            foreach ($prods_related as $related) {
                $product_related[] = $related->codopencart;
            }
            
            $model = str_pad($produto->referencia, 2, '_', STR_PAD_LEFT);
            $sku = str_pad($produto->codproduto, 6, '0', STR_PAD_LEFT);
            $quantity = 1;
            $price = $produto->preco;
            $keyword = $sku;
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
            
            $other_images = [];
            foreach ($produto->ImagemS()->orderBy('codimagem')->get() as $pi) {
                $other_images[] = 'imagens/'.$pi->observacoes;
            }
            $image = array_shift($other_images);
            
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
                    $image,
                    $other_images,
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
                    
                    Log::error (class_basename($this) . " - Erro ao atualizar Produto #$produto->codproduto($produto->codopencart) - $produto->produto - $this->response");
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
                    $image,
                    $other_images,
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
                    DB::table('tblproduto')->where('codproduto', '=', $produto->codproduto)->update(['codopencart' => $id]);
                    $produto = $produto->fresh();
                }
                
            }
            
            // inclui na lista de categorias atualizadas
            $this->productsUpdated[$produto->codopencart] = $produto;
                
        }
        
    }
    
    public static function sincronizaProdutos ($codproduto = 'all', $excluir_excedentes = 'auto')
    {

        // Marca como site os que tem imagem e estao ativos
        DB::update('update tblproduto set site = true where site = false and inativo is null and codproduto in (select tblprodutoimagem.codproduto from tblprodutoimagem)');
        // Tira do site os que estao inativos ou nao tem imagens
        DB::update('update tblproduto set site = false where site = true and (inativo is not null or codproduto not in (select tblprodutoimagem.codproduto from tblprodutoimagem))');
        
        Log::info ("Inicio Sincronizacao de Produtos OpenCart ($codproduto)");
        
        $qryProdutos = Produto::where('site', true)->whereNull('inativo')->orderBy('codproduto');
        
        if ($codproduto != 'all') {
            $produtos = $qryProdutos->where('codproduto', $codproduto)->get();
        } else {
            $produtos = $qryProdutos->get();            
        }
        
        $oc = new IntegracaoOpenCart();
        
        $oc->getToken();
        
        if ($codproduto != 'all' && isset($produtos[0])) {
            $oc->buscaMarcasOpenCart($produtos[0]->Marca->codopencart);
            $oc->buscaProdutosOpenCart($produtos[0]->codopencart, str_pad($produtos[0]->codproduto, 6, '0', STR_PAD_LEFT), $produtos[0]->codopencartvariacao);
            $oc->buscaSecoesOpenCart($produtos[0]->SubGrupoProduto->codopencart);
            $oc->buscaSecoesOpenCart($produtos[0]->SubGrupoProduto->GrupoProduto->codopencart);
            $oc->buscaSecoesOpenCart($produtos[0]->SubGrupoProduto->GrupoProduto->FamiliaProduto->codopencart);
            $oc->buscaSecoesOpenCart($produtos[0]->SubGrupoProduto->GrupoProduto->FamiliaProduto->SecaoProduto->codopencart);
        } else {
            $oc->buscaMarcasOpenCart();
            $oc->buscaSecoesOpenCart();
            $oc->buscaProdutosOpenCart();
        }
        
        $oc->exportaProdutos($produtos);
        
        if ($excluir_excedentes === 'auto') {
            $excluir_excedentes = ($codproduto == 'all')?true:false;
        }
        if ($excluir_excedentes == true) {
            $oc->excluiProdutosExcedentes();
            $oc->excluiVariacoesExcedentes();
            $oc->excluiMarcasExcedentes();
            $oc->excluiSecoesExcedentes();
        }
        
        Log::info ("Final Sincronizacao de Produtos OpenCart");
        
        return true;
        
    }
    
}
