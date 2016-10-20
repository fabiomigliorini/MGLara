<?php

namespace MGLara\Library;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;


use MGLara\Models\Marca;
use MGLara\Models\Produto;
use MGLara\Models\ProdutoVariacao;

/**
 * Description of IntegracaoOpenCart
 *
 * @author escmig98
 * @property Marca[] marcasSistema
 */
class IntegracaoOpenCart {
    
    protected $debug = false;
    
    protected $opencartUser;
    protected $opencartPassword;
    protected $opencartBaseURL;
    protected $opencartLanguagePTBR;
    
    public $token;
    
    protected $curlResponse;
    protected $curlResponseObject;
    protected $curlStatus;
    
    protected $marcasOpenCart = [];
    protected $marcasSistema;
    
    protected $produtosSistema;
    
    protected $produtosOpenCart;
    protected $opcoesProdutoOpenCart;
    //protected $valoresOpcaoProdutoOpenCart;


    /**
     * Construtor
     */
    public function __construct($debug = false)
    {
        $this->debug = $debug;
        
        // Traz variaves de ambiente
        $this->opencartUser = $_ENV['OPENCART_USER'];
        $this->opencartPassword = $_ENV['OPENCART_PASSWORD'];
        $this->opencartBaseURL = $_ENV['OPENCART_BASEURL'];
        $this->opencartLanguagePTBR = $_ENV['OPENCART_LANGUAGE_PTBR'];
    }
    
    /**
     * Verifica retorno do Curl
     * @return boolean
     */
    public function verificaRetorno()
    {
        if ($this->curlStatus != 200) {
            Log::error(class_basename($this) . " - {$this->curlResponse}");
            return false;
        } 
        
        if (!($this->curlResponseObject = json_decode($this->curlResponse))) {
            Log::error(class_basename($this) . " - {$this->curlResponse}");
            return false;
        }
        
        return true;
    }
    
    /**
     * Autentica no OpenCart e armazena em $this->token
     * @return boolean
     */
    public function autentica()
    {
        if (!empty($this->token)) {
            return true;
        }
        
        if ($this->debug) {
            Log::debug(class_basename($this) . ' - Autenticando...');
        }
        
        // Usuario e Senha do Site
        $usuario = "{$this->opencartUser}:{$this->opencartPassword}";
        if ($this->debug) {
            Log::debug(class_basename($this) . " - Usuario '$usuario'");
        }
        $chave = base64_encode($usuario);
        
        // Monta Chamada CURL
        $url = $this->opencartBaseURL . 'index.php?route=rest/admin_security/gettoken&grant_type=client_credentials';
        if ($this->debug) {
            Log::debug(class_basename($this) . " - POST - URL '$url' - Chave '$chave'");
        }
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                "Authorization: Basic $chave"
            )
        );

        // Executa
        $this->curlResponse = curl_exec($ch);
        $this->curlStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        // Verifica o Retorno
        if (!$this->verificaRetorno()) {
            Log::error(class_basename($this) . ' - Falha na Autenticação!');
            return false;
        }
        
        // Verifica se veio o token
        if (!isset($this->curlResponseObject->access_token)) {
            Log::error(class_basename($this) . ' - Impossível descobrir token ' . $this->curlResponse);
            return false;            
        }
        
        // Salva o token
        $this->token = $this->curlResponseObject->access_token;
        
        // Retorna verdadeiro
        if ($this->debug) {
            Log::debug(class_basename($this) . " - Autenticado com o token {$this->token}");
        }
        return true;
        
    }

    /**
     * Busca Listagem das marcas do OpenCart
     * @param bigint $id Codigo da Marca no OpenCart
     * @return boolean
     */
    public function buscaMarcasOpenCart()
    {
        if (empty($this->token)) {
            return false;
        }
        
        // Monta Chamada CURL
        $url = $this->opencartBaseURL . 'index.php?route=rest/manufacturer_admin/manufacturer&limit=999999999&page=1';
        if ($this->debug) {
            Log::debug(class_basename($this) . " - GET - URL '$url'");
        }
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                "Authorization: Bearer $this->token"
            )
        );
        
        // Executa
        $this->curlResponse = curl_exec($ch);
        $this->curlStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        // Verifica Retorno
        if (!$this->verificaRetorno()) {
            Log::error(class_basename($this) . ' - Erro ao buscar listagem de marcas no OpenCart!');
            return false;
        }
        
        if (!$this->curlResponseObject->success) {
            Log::error(class_basename($this) . ' - Erro ao buscar listagem de marcas no OpenCart!');
            return false;
        }

        // Transforma a resposta das Marcas, deixando o codigo como chave do array
        foreach ($this->curlResponseObject->data as $key => $manufacturer) {
            $this->marcasOpenCart[$manufacturer->manufacturer_id] = $manufacturer;
        }
        
        return sizeof($this->curlResponseObject->data);
        
    }
    
    public function buscaMarcasSistema($codmarca = null)
    {
        $marcas = Marca::orderBy('codmarca');
        
        if (!empty($codmarca)) {
            $marcas->where('codmarca', $codmarca);
        }
        
        if (!$marcas = $marcas->get()) {
            Log::error(class_basename($this) . " - Não localizado nenhuma marca no sistema '{$codmarca}'!");
            return false;
        }
        
        if (empty($this->marcasSistema)) {
            $this->marcasSistema = $marcas;
        } else {
            $this->marcasSistema = $this->marcasSistema->merge($marcas);
        }
        
        return sizeof($marcas);
    }
    
    public function buscaProdutosOpenCart($id = null, $sku = null)
    {
        if (!$this->autentica()) {
            return false;
        }
        
        if (!empty($id)) {
            $url = $this->opencartBaseURL . "index.php?route=rest/product_admin/products&id=$id";
        } elseif (!empty($sku)) {
            $url = $this->opencartBaseURL . "index.php?route=rest/product_admin/getproductbysku&sku=$sku";
        } else {
            $url = $this->opencartBaseURL . "index.php?route=rest/product_admin/products";
        }
        if ($this->debug) {
            Log::debug(class_basename($this) . " - GET - URL '$url'");
        }        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                "Authorization: Bearer $this->token"
            )
        );
        
        // Executa
        $this->curlResponse = curl_exec($ch);
        $this->curlStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        // Verifica Retorno
        if (!$this->verificaRetorno()) {
            Log::error(class_basename($this) . ' - Erro ao buscar listagem de produtos no OpenCart!');
            return false;
        }
        if (!$this->curlResponseObject->success) {
            Log::error(class_basename($this) . ' - Erro ao buscar listagem de produtos no OpenCart!');
            return false;
        }
        
        // Transforma a resposta dos Produtos, deixando o ID como chave do array
        if (isset($this->curlResponseObject->data->id)) {
            $encontrados = 1;
            // Transforma a resposta dos Produtos, deixando o ID como chave do array
            $this->produtosOpenCart[$this->curlResponseObject->data->id] = $this->curlResponseObject->data;
        } else {
            $encontrados = sizeof($this->curlResponseObject->data);
            // Transforma a resposta dos Produtos, deixando o ID como chave do array
            foreach ($this->curlResponseObject->data as $key => $product) {
                $this->produtosOpenCart[$product->id] = $product;
            }
        }
        
        return $encontrados;
        
    }
    
    public function buscaOpcoesProdutoOpenCart($id = null)
    {
        if (!$this->autentica()) {
            return false;
        }
        
        if (!empty($id)) {
            $url = $this->opencartBaseURL . "index.php?route=rest/option_admin/option&id=$id";
        } else {
            $url = $this->opencartBaseURL . "index.php?route=rest/option_admin/option";
        }
        if ($this->debug) {
            Log::debug(class_basename($this) . " - GET - URL '$url'");
        }        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                "Authorization: Bearer $this->token"
            )
        );
        
        // Executa
        $this->curlResponse = curl_exec($ch);
        $this->curlStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        // Verifica Retorno
        if (!$this->verificaRetorno()) {
            Log::error(class_basename($this) . ' - Erro ao buscar listagem de Opcoes de produto no OpenCart!');
            return false;
        }
        if (!$this->curlResponseObject->success) {
            Log::error(class_basename($this) . ' - Erro ao buscar listagem de Opcoes de produto no OpenCart!');
            return false;
        }
        
        if (isset($this->curlResponseObject->data->option_id)) {
            $encontrados = 1;
            // Transforma a resposta dos Produtos, deixando o ID como chave do array
            $this->opcoesProdutoOpenCart[$this->curlResponseObject->data->option_id] = $this->curlResponseObject->data;
        } else {
            $encontrados = sizeof($this->curlResponseObject->data);
            // Transforma a resposta dos Produtos, deixando o ID como chave do array
            foreach ($this->curlResponseObject->data as $key => $option) {
                $this->opcoesProdutoOpenCart[$option->option_id] = $option;
            }
        }
        
        return $encontrados;
        
    }
    
    /**
     * Sincroniza os registros de ProdutoVariacao do Produto
     * @param ProdutoVariacao $model
     */
    public function sincronizaProdutoVariacao (Produto $prod) 
    {
        $valores_excluir = [];
        $opcoes_excluir = [];
        
        // Se tem mais de uma variação
        if (sizeof($prod->ProdutoVariacaoS) > 1) {

            // Se não estava cadastrado ainda, inclui a opção de variação
            if (!isset($this->opcoesProdutoOpenCart[$prod->codopencartvariacao])) {

                //Monta Chamada
                $data = [
                    'sort_order' => 1,
                    'type' => 'radio',
                    'option_description' => [[
                        'name' => 'Variações',
                        'language_id' => $this->opencartLanguagePTBR,
                    ]],
                ];

                // Listagem dos valores da opcao
                $pvs = [];
                foreach ($prod->ProdutoVariacaoS as $pv) {
                    $pvs[] = $pv;
                    $data['option_value'][] = [
                        "sort_order" => 1,
                        'image' => '',
                        "option_value_description" => [[
                            'language_id' => $this->opencartLanguagePTBR,
                            'name' => $pv->variacao,
                        ]]
                    ];
                }

                // Gera json 
                $data_string = json_encode($data);

                // Monta Chamada CURL
                $url = $this->opencartBaseURL . 'index.php?route=rest/option_admin/option';
                if ($this->debug) {
                    Log::debug(class_basename($this) . " - POST - URL '$url'");
                }
                $ch = curl_init($url);

                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");   
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
                    'Content-Type: application/json',     
                    "Authorization: Bearer $this->token",
                    'Content-Length: ' . strlen($data_string))                                                                       
                );                                                                                                           

                // Executa
                $this->curlResponse = curl_exec($ch);
                $this->curlStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);

                // Verifica o Retorno
                if (!$this->verificaRetorno()) {
                    Log::error(class_basename($this) . ' - Falha ao criar Opcao da Variacao!');
                    return false;
                }

                // Grava ID do OpenCart dos Valores
                $i = 0;
                foreach ($this->curlResponseObject->data->option_values as $opt) {
                    $pvs[$i]->codopencart = $opt->option_value_id;
                    $pvs[$i]->save();
                    $i++;
                }

                // Grava ID do OpenCart da Opcao
                $prod->codopencartvariacao = $this->curlResponseObject->data->option_id;
                $prod->save();

            } else {
                
                $atualizados = [];
                foreach ($this->opcoesProdutoOpenCart[$prod->codopencartvariacao]->option_values as $valor) {
                    
                    // Se não achar opcao no sistema, inclui na listagem para excluir
                    if (!$pv = $prod->ProdutoVariacaoS()->where('codopencart', $valor->option_value_id)->first()) {
                        $valores_excluir[] = $valor->option_value_id;
                        continue;
                    }
                    
                    // Monta Array com Dados
                    $data = [
                        'sort_order' => '1',
                        'image' => '',
                        'option_value_description' => [[
                            'language_id' => $this->opencartLanguagePTBR,
                            'name' => $pv->variacao
                        ]]
                    ];
                    
                    // Gera json 
                    $data_string = json_encode($data);

                    // Monta Chamada CURL
                    $url = $this->opencartBaseURL . "index.php?route=rest/option_value_admin/optionvalue&id={$valor->option_value_id}";
                    if ($this->debug) {
                        Log::debug(class_basename($this) . " - PUT - URL '$url'");
                    }
                    $ch = curl_init($url);

                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");   
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
                        'Content-Type: application/json',     
                        "Authorization: Bearer $this->token",
                        'Content-Length: ' . strlen($data_string))                                                                       
                    );                                                                                                           

                    // Executa
                    $this->curlResponse = curl_exec($ch);
                    $this->curlStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    curl_close($ch);

                    // Verifica o Retorno
                    if (!$this->verificaRetorno()) {
                        Log::error(class_basename($this) . ' - Falha ao criar Valor da Opcao da Variacao!');
                    }

                    // Marca como atualizado
                    $atualizados[] = $valor->option_value_id;
                    
                }
                
                // Busca novas variacoes
                DB::EnableQueryLog();
                $pvs = $prod->ProdutoVariacaoS()->where(function ($query) use($atualizados) {
                    $query->whereNotIn('codopencart', $atualizados)
                        ->orWhereNull('codopencart');
                })->get();
                
                // percorre novas variacoes incluindo como Valor da Opcao
                foreach ($pvs as $pv) {
                    
                    // Monta Array com Dados
                    $data = [
                        'sort_order' => '1',
                        'image' => '',
                        'option_value_description' => [[
                            'language_id' => $this->opencartLanguagePTBR,
                            'name' => $pv->variacao
                        ]]
                    ];
                    
                    // Gera json 
                    $data_string = json_encode($data);

                    // Monta Chamada CURL
                    $url = $this->opencartBaseURL . "index.php?route=rest/option_value_admin/optionvalue&id={$prod->codopencartvariacao}";
                    if ($this->debug) {
                        Log::debug(class_basename($this) . " - POST - URL '$url'");
                    }
                    $ch = curl_init($url);

                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');   
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
                        'Content-Type: application/json',     
                        "Authorization: Bearer $this->token",
                        'Content-Length: ' . strlen($data_string))                                                                       
                    );                                                                                                           

                    // Executa
                    $this->curlResponse = curl_exec($ch);
                    $this->curlStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    curl_close($ch);

                    // Verifica o Retorno
                    if (!$this->verificaRetorno()) {
                        Log::error(class_basename($this) . ' - Falha ao atualizar Valor da Opcao da Variacao!');
                        continue;
                    }
                    
                    if (!$this->curlResponseObject->success) {
                        Log::error(class_basename($this) . " - Falha ao atualizar Valor da Opcao da Variacao! {$this->curlResponse}");
                        continue;
                    }
                    
                    $pv->codopencart = $this->curlResponseObject->data->option_value_id;
                    $pv->save();
                    
                    
                }
                
                
            }
            
        } else {

            // se Existia uma opcao criada no OpenCart
            if (isset($this->opcoesProdutoOpenCart[$prod->codopencartvariacao])) {
                
                // Percorre valores da opcao incluindo na listagem para excluir
                foreach ($this->opcoesProdutoOpenCart[$prod->codopencartvariacao]->option_values as $valor) {
                    $valores_excluir[] = $valor->option_value_id;
                }

                // adiciona opcao na listagem para excluir
                $opcoes_excluir[] = $this->opcoesProdutoOpenCart[$prod->codopencartvariacao]->option_id;
                
            }
            
        }
        
        
        // exclui os valores da opcao para excluir
        if (sizeof($valores_excluir) > 0) {

            $option_values = [];
            foreach ($valores_excluir as $option_value_id) {
                $option_values[] = $option_value_id;
            }
            $data = [
                'option_values' => $option_values
            ];

            $data_string = json_encode($data);

            $url = $this->opencartBaseURL . 'index.php?route=rest/option_value_admin/optionvalue';
            if ($this->debug) {
                Log::debug(class_basename($this) . " - URL '$url'");
            }
            $ch = curl_init($url);

            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');   
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
                'Content-Type: application/json',     
                "Authorization: Bearer $this->token",
                'Content-Length: ' . strlen($data_string))                                                                       
            );                                                                                                           

            // Executa
            $this->curlResponse = curl_exec($ch);
            $this->curlStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            // Verifica o Retorno
            if (!$this->verificaRetorno()) {
                Log::error(class_basename($this) . ' - Falha ao atualizar Valor da Opcao da Variacao!');
            }

        }
        
        // exclui as opcoes para excluir
        if (sizeof($opcoes_excluir) > 0) {

            $option_values = [];
            foreach ($opcoes_excluir as $option_id) {
                $options[] = $option_id;
            }
            $data = [
                'options' => $options
            ];

            $data_string = json_encode($data);

            $url = $this->opencartBaseURL . 'index.php?route=rest/option_admin/option';
            if ($this->debug) {
                Log::debug(class_basename($this) . " - DELETE - URL '$url'");
            }
            $ch = curl_init($url);

            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');   
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
                'Content-Type: application/json',     
                "Authorization: Bearer $this->token",
                'Content-Length: ' . strlen($data_string))                                                                       
            );                                                                                                           

            // Executa
            $this->curlResponse = curl_exec($ch);
            $this->curlStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            // Verifica o Retorno
            if (!$this->verificaRetorno()) {
                Log::error(class_basename($this) . ' - Falha ao atualizar Valor da Opcao da Variacao!');
            }
            
            // Desvincula CODOPENCART do sistema
            if ($this->curlResponseObject->success) {
                Produto::whereIn('codopencartvariacao', $opcoes_excluir)->update(['codopencartvariacao' => null]);
            }

        }
        
        return true;
        
        //TODO: Excluir Variacao Excedente
        
    }
    
    public function sincronizaProduto($prod) {
        
        $barras = '';
        if ($pb = $prod->ProdutoBarras()->whereNull('codprodutoembalagem')->first()) {
            $barras = $pb->barras;
        }
        
        $description = $prod->descricaosite;
        $description .= nl2br($description);
        
        $pes = $prod->ProdutoEmbalagemS()->orderBy('quantidade')->get();
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
        $pvs = $prod->ProdutoVariacaoS;
        if (sizeof($pvs) > 1) {
            foreach($pvs as $pv) {
                $values[] = [
                    'price' => 0,
                    'price_prefix' => '+',
                    'subtract' => 1,
                    'option_value_id' => $pv->codopencart,
                ];
            }
            $product_option = [[
                'type' => 'radio',
                'required' => 1,
                'option_id' => $prod->codopencartvariacao,
                'product_option_value' => $values,
            ]];
        }
        
        // TODO: listagem dos produtos relacionados
        $product_related = [
            43,
            42,
            41
        ];
        
        $data = [
            'model' => $prod->referencia,
            'sku' => $prod->codproduto,
            'quantity' => null,
            'price' => $prod->preco,
            'keyword' => str_pad($prod->codproduto, 6, '0', STR_PAD_LEFT),
            'tax_class_id' => null,
            'manufacturer_id' => $prod->Marca->codopencart,
            'sort_order' => 1,
            'status' => (empty($prod->inativo)?1:0), // 1 - Ativo / 0 - Inativo
            'ean' => $barras,
            'stock_status_id' => 6, // Pre Order
            //'image' => '', 
            //'other_images' => [
            //    '',
            //    ''
            //], 
            'subtract' => 1,
            'product_store' => [
                '0',
            ],
            'product_category' => [
                $prod->SubGrupoProduto->codopencart,
                $prod->SubGrupoProduto->GrupoProduto->codopencart,
                $prod->SubGrupoProduto->GrupoProduto->FamiliaProduto->codopencart,
                $prod->SubGrupoProduto->GrupoProduto->FamiliaProduto->SecaoProduto->codopencart,
            ],
            'product_description' => [[
                'language_id' => $this->opencartLanguagePTBR,
                'name' => $prod->produto,
                'meta_description' => $prod->produto,
                'meta_title' => $prod->produto,
                'meta_keyword' => $prod->produto,
                'description' => $description,
            ]],
            'product_option' => $product_option,
            'product_related' => $product_related,
            
        ];
        
        //dd($data);
        
        if (!isset($this->produtosOpenCart[$prod->codopencart])) {
            
            // Gera json 
            $data_string = json_encode($data);

            // Monta Chamada CURL
            $url = $this->opencartBaseURL . 'index.php?route=rest/product_admin/products';
            if ($this->debug) {
                Log::debug(class_basename($this) . " - POST - URL '$url'");
            }
            $ch = curl_init($url);

            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");   
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
                'Content-Type: application/json',     
                "Authorization: Bearer $this->token",
                'Content-Length: ' . strlen($data_string))                                                                       
            );                                                                                                           

            // Executa
            $this->curlResponse = curl_exec($ch);
            $this->curlStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            //echo "\n\naqui\n\n";
            //dd($this->curlResponseObject);

            // Verifica o Retorno
            if (!$this->verificaRetorno()) {
                Log::error(class_basename($this) . ' - Falha ao criar Opcao da Variacao!');
                return false;
            }

            // Grava ID do OpenCart da Opcao
            $prod->codopencart = $this->curlResponseObject->data->product_id;
            $prod->save();
            
        } else {
            
            // Gera json 
            $data_string = json_encode($data);

            // Monta Chamada CURL
            $url = $this->opencartBaseURL . "index.php?route=rest/product_admin/products&id={$prod->codopencart}";
            if ($this->debug) {
                Log::debug(class_basename($this) . " - PUT - URL '$url'");
            }
            $ch = curl_init($url);

            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");   
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
                'Content-Type: application/json',     
                "Authorization: Bearer $this->token",
                'Content-Length: ' . strlen($data_string))                                                                       
            );                                                                                                           

            // Executa
            $this->curlResponse = curl_exec($ch);
            $this->curlStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            //echo "\n\naqui alterando\n\n";
            //dd($this->curlResponseObject);

            // Verifica o Retorno
            if (!$this->verificaRetorno()) {
                Log::error(class_basename($this) . ' - Falha ao criar Opcao da Variacao!');
                return false;
            }

            // Grava ID do OpenCart da Opcao
            $prod->codopencart = $this->curlResponseObject->data->product_id;
            $prod->save();
                        
        }
        
    }
    
    /**
     * Sincroniza o cadastro dos produtos
     * @param bigint $codproduto
     * @var Produto $produto
     * @return boolean
     */
    public function sincronizaProdutos($codproduto = null) 
    {
        if (!$this->autentica()) {
            return false;
        }
        
        // Busca Produtos
        $produtos = Produto::orderBy('codproduto')->where('site', true);
        if (!empty($codproduto)) {
            if (is_array($codproduto)) {
                $produtos->whereIn('codproduto', $codproduto);
            } else {
                $produtos->where('codproduto', $codproduto);
            }
        }
        if (!($this->produtosSistema = $produtos->get())) {
            Log::error(class_basename($this) . " - Não localizado nenhum produto no sistema '{$codproduto}'!");
            return false;            
        }
        
        // Busca Produtos do OpenCart
        if (empty($codproduto)) {
            $this->buscaProdutosOpenCart();
        } else {
            foreach ($this->produtosSistema as $prod) {
                $this->buscaProdutosOpenCart($prod->codopencart, $prod->codproduto);
            }
        }
        
        // Busca Opcoes do Produtos do OpenCart
        if (empty($codproduto)) {
            $this->buscaOpcoesProdutoOpenCart();
        } else {
            foreach ($this->produtosSistema as $prod) {
                $this->buscaOpcoesProdutoOpenCart($prod->codopencartvariacao);
            }
        }
        
        foreach ($this->produtosSistema as $prod) {
            
            if (empty($prod->Marca->codopencart)) {
                //TODO: logica exportacao marca
            }
            
            if (empty($prod->SubGrupoProduto->codopencart)) {
                //TODO: logica exportacao Categoria do SubGrupoProduto
            }
            
            $this->sincronizaProdutoVariacao($prod);
            $this->sincronizaProduto($prod);
            
        }
        
    }
    
    public function teste()
    {
        if (!$this->autentica()) {
            return false;
        }

        /*
        if (!$marcas = $this->buscaMarcasOpenCart()) {
            return false;
        }
         * 
         */
        
        if (!$this->buscaMarcasSistema(9999999999)) {
            return false;
        }
        
        if (!$this->buscaMarcasSistema(3)) {
            return false;
        }
        
        return true;
    }
    
}
