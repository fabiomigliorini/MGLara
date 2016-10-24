<?php

namespace MGLara\Console\Commands;

use Illuminate\Console\Command;
use MGLara\Jobs\EstoqueCalculaEstatisticas;
//use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\Log;
use MGLara\Models\SecaoProduto;
use MGLara\Models\FamiliaProduto;
use MGLara\Models\GrupoProduto;
use MGLara\Models\SubGrupoProduto;

use MGLara\Library\IntegracaoOpenCart;

class SiteExportaCategoriasCommand extends Command
{
    const URL_SITE = 'http://webapp15505.cloud683.configrapp.com/';
    const LANGUAGE_ID_PTBR = 2;
    //use DispatchesJobs;
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'site:exporta-categorias';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Exporta secoes para o site usando API OpenCart';
    
    protected $categoriasOpenCart = [];
    
    protected $token;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
    
    public function montaArrayOpenCart($categories, $parent_id = null) 
    {
        foreach ($categories as $category_id => $langs) {
            //dd($langs);
            foreach ($langs as $key => $category) {
                //dd($category);
                if ($category['language_id'] == self::LANGUAGE_ID_PTBR) {
                    if (isset($category['categories'])) {
                        $this->montaArrayOpenCart($category['categories']['categories'], $category['category_id']);
                    }
                    unset($category['categories']);
                    $category['parent_id'] = $parent_id;
                    $this->categoriasOpenCart[$category['category_id']] = $category;
                }
            }
        }
        
    }
    
    public function enviaOpenCart($model, $data) 
    {
        
        if (strlen($data['category_description'][0]['name']) < 3) {
            $data['category_description'][0]['name'] = str_pad($data['category_description'][0]['name'], 3, '_', STR_PAD_RIGHT);
        }
            
        if (strlen($data['category_description'][0]['meta_description']) < 3) {
            $data['category_description'][0]['meta_description'] = str_pad($data['category_description'][0]['meta_description'], 3, '_', STR_PAD_RIGHT);
        }
        
        $class = class_basename($model);
        echo "{$class} - {$data['category_description'][0]['name']}\n";
        
        //Se ja esta no OpenCart - ATUALIZA
        if (isset($this->categoriasOpenCart[$model->codopencart])) {
            
            $data['category_description'][0]['name'] = $this->categoriasOpenCart[$model->codopencart]['name'];
            $data['category_description'][0]['description'] = $this->categoriasOpenCart[$model->codopencart]['description'];
            $data['category_description'][0]['sort_order'] = $this->categoriasOpenCart[$model->codopencart]['sort_order'];
            $data['category_description'][0]['meta_title'] = $this->categoriasOpenCart[$model->codopencart]['meta_title'];
            $data['category_description'][0]['meta_description'] = $this->categoriasOpenCart[$model->codopencart]['meta_description'];
            $data['category_description'][0]['meta_keyword'] = $this->categoriasOpenCart[$model->codopencart]['meta_keyword'];

            $data_string = json_encode($data);

            $ch = curl_init(SELF::URL_SITE . "index.php?route=rest/category_admin/category&id={$model->codopencart}");
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");   
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
                'Content-Type: application/json',     
                "Authorization: Bearer $this->token",
                'Content-Length: ' . strlen($data_string))                                                                       
            );                                                                                                           

            $response = curl_exec($ch);
            $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            if ($status != 200) {
                Log::error("site:exporta-categorias - Erro ao ATUALIZAR categoria #{$model->codopencart} - '{$data['category_description'][0]['name']}' - $response");
            }

        // Se nao esta no OpenCart - CRIA
        } else {

            $data_string = json_encode($data);

            $ch = curl_init(SELF::URL_SITE . "index.php?route=rest/category_admin/category");
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");   
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
                'Content-Type: application/json',     
                "Authorization: Bearer $this->token",
                'Content-Length: ' . strlen($data_string))                                                                       
            );                                                                                                           

            $response = curl_exec($ch);
            
            $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($status != 200) {
                Log::error("site:exporta-categorias - Erro ao CRIAR categoria '{$data['category_description'][0]['name']}' - $response");
            } else {
                $response = json_decode($response, true);
                if ($response['success']) {
                    $model->codopencart = $response['data']['id'];
                    $model->save();
                } else {
                    $response = json_encode($response);
                    Log::error("site:exporta-categorias - Erro ao CRIAR categoria '{$data['category_description'][0]['name']}' - $response");
                }
            }                

        }
        
    }
    
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Log::info('site:exporta-categorias');
        
        $xx = new IntegracaoOpenCart();
        $xx->teste();
        
        // Usuario e Senha do Site
        $chave = base64_encode('mgpapelaria:123456');
        
        // Gera o Token de Autenticacao
        $ch = curl_init(SELF::URL_SITE . 'index.php?route=rest/admin_security/gettoken&grant_type=client_credentials');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                "Authorization: Basic $chave"
            )
        );

        $response = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($status != 200) {
            Log::error("site:exporta-categorias - Erro ao Gerar Token - $response");
        } else {
            $response_arr = json_decode($response, true);
            if (empty($response_arr['access_token'])) {
                Log::error("site:exporta-categorias - Erro ao Gerar Token - $response");
                return false;
            }
            $this->token = $response_arr['access_token'];
        } 
        
        // Token temporario
        //$this->token = '13f8ea04e1728a03a1a71f2340b086eba3e5f595';

        // Busca Listagem das secoes do OpenCart
        $ch = curl_init(SELF::URL_SITE . 'index.php?route=rest/category_admin/category&level=99999999');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                "Authorization: Bearer $this->token"
            )
        );
        
        $response = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($status != 200) {
            Log::error("site:exporta-categorias - Erro ao Gerar Token - $response");
        } else {
            $response = json_decode($response, true);
            if ((!$response['success']) && ($response['error'] != 'No category found')) {
                return false;
            }
        } 

        // Transforma o array das Secoes, deixando o codigo como chave do array
        if (!empty($response['data']['categories'])) {
            $this->montaArrayOpenCart($response['data']['categories']);
        }
        
        // Busca as Secoes do Sistema
        $secoes = SecaoProduto::orderBy('codsecaoproduto')->get();
        
        // Percorre todas as secoes
        foreach ($secoes as $secaoproduto) {
            
            // String com os dados da SecaoProduto
            $data = [
                'sort_order' => 1,
                'category_store' => [0],
                'parent_id' => null,
                'top' => 1,
                'column' => 4, //TODO: Logica para determinar quantas colunas no layout da loja
                'status' => 1, //1 - Ativo / 0 - Inativo
                'category_description' => [
                    0 => [
                        'language_id' => self::LANGUAGE_ID_PTBR,
                        'name' => $secaoproduto->secaoproduto,
                        'description' => $secaoproduto->secaoproduto,
                        'meta_title' => $secaoproduto->secaoproduto,
                        'meta_description' => $secaoproduto->secaoproduto,
                        'meta_keyword' => $secaoproduto->secaoproduto,
                    ]
                ]
            ];
            
            $this->enviaOpenCart($secaoproduto, $data);
                
            // Exclui do array de Secoes do OpenCart
            unset($this->categoriasOpenCart[$secaoproduto->codopencart]);
        }
        
        // Busca as Secoes do Sistema
        $familias = FamiliaProduto::orderBy('codfamiliaproduto')->get();
        
        // Percorre todas as secoes
        foreach ($familias as $familia) {
            
            // String com os dados da SecaoProduto
            $data = [
                'sort_order' => 1,
                'category_store' => [0],
                'parent_id' => $familia->SecaoProduto->codopencart,
                'top' => 1,
                'column' => 4, //TODO: Logica para determinar quantas colunas no layout da loja
                'status' => 1, //1 - Ativo / 0 - Inativo
                'category_description' => [
                    0 => [
                        'language_id' => self::LANGUAGE_ID_PTBR,
                        'name' => $familia->familiaproduto,
                        'description' => $familia->familiaproduto,
                        'meta_title' => $familia->familiaproduto,
                        'meta_description' => $familia->familiaproduto,
                        'meta_keyword' => $familia->familiaproduto,
                    ]
                ]
            ];
            
            $this->enviaOpenCart($familia, $data);
                
            // Exclui do array de Secoes do OpenCart
            unset($this->categoriasOpenCart[$familia->codopencart]);
        }
        
        // Busca as Grupos do Sistema
        $grupos = GrupoProduto::orderBy('codgrupoproduto')->get();
        
        // Percorre todas as secoes
        foreach ($grupos as $grupo) {
            
            // String com os dados da SecaoProduto
            $data = [
                'sort_order' => 1,
                'category_store' => [0],
                'parent_id' => $grupo->FamiliaProduto->codopencart,
                'top' => 1,
                'column' => 4, //TODO: Logica para determinar quantas colunas no layout da loja
                'status' => 1, //1 - Ativo / 0 - Inativo
                'category_description' => [
                    0 => [
                        'language_id' => self::LANGUAGE_ID_PTBR,
                        'name' => $grupo->grupoproduto,
                        'description' => $grupo->grupoproduto,
                        'meta_title' => $grupo->grupoproduto,
                        'meta_description' => $grupo->grupoproduto,
                        'meta_keyword' => $grupo->grupoproduto,
                    ]
                ]
            ];
            $this->enviaOpenCart($grupo, $data);
                
            // Exclui do array de Secoes do OpenCart
            unset($this->categoriasOpenCart[$grupo->codopencart]);
        }


        // Busca os Sub Grupos do Sistema
        $subgrupos = SubGrupoProduto::orderBy('codsubgrupoproduto')->get();
        
        // Percorre todas os subgrupos
        foreach ($subgrupos as $subgrupo) {
            
            // String com os dados da SecaoProduto
            $data = [
                'sort_order' => 1,
                'category_store' => [0],
                'parent_id' => $subgrupo->GrupoProduto->codopencart,
                'top' => 1,
                'column' => 4, //TODO: Logica para determinar quantas colunas no layout da loja
                'status' => 1, //1 - Ativo / 0 - Inativo
                'category_description' => [
                    0 => [
                        'language_id' => self::LANGUAGE_ID_PTBR,
                        'name' => $subgrupo->subgrupoproduto,
                        'description' => $subgrupo->subgrupoproduto,
                        'meta_title' => $subgrupo->subgrupoproduto,
                        'meta_description' => $subgrupo->subgrupoproduto,
                        'meta_keyword' => $subgrupo->subgrupoproduto,
                    ]
                ]
            ];
            $this->enviaOpenCart($subgrupo, $data);
                
            // Exclui do array de Secoes do OpenCart
            unset($this->categoriasOpenCart[$subgrupo->codopencart]);
        }
        
        // Percorre as Secoes do OpenCart que sobraram e Exclui
        foreach($this->categoriasOpenCart as $category_id => $category) {
            
            $data_string = json_encode(['categories' => [$category_id]]);
            
            $ch = curl_init(SELF::URL_SITE . "index.php?route=rest/category_admin/category");
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                "Authorization: Bearer $this->token",
                'Content-Length: ' . strlen($data_string))
            );                                                                                                           

            $response = curl_exec($ch);
            $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($status != 200) {
                Log::error("site:exporta-categorias - Erro ao EXCLUIR categoria #{$category_id} - '{$category['name']}' - $response");
            } else {
                $response = json_decode($response, true);
                if (!$response['success']) {
                    $response = json_encode($response);
                    Log::error("site:exporta-categorias - Erro ao EXCLUIR categoria #{$category_id} - '{$category['name']}' - $response");
                }
            }
            
        }
        
    }
    
}
