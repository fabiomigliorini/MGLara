<?php

namespace MGLara\Library\IntegracaoOpenCart;

use Illuminate\Support\Facades\Log;

/**
 * Description of IntegracaoOpencartBase
 *
 * @author escmig98
 */
class IntegracaoOpencartBase {
    
    protected $debug = false;
    protected $url;
    protected $user;
    protected $password;
    protected $languagePTBR;
    
    public $token;
    
    protected $response;
    protected $responseObject;
    protected $status;

    /**
     * Construtor
     */
    public function __construct($debug = false, $url = null, $user = null, $password = null, $language_ptbr = null)
    {
        // Traz variaves de ambiente
        $this->debug = $debug;
        $this->url = (!empty($url))?$url:$_ENV['OPENCART_BASEURL'];
        $this->user = (!empty($user))?$user:$_ENV['OPENCART_USER'];
        $this->password = (!empty($password))?$password:$_ENV['OPENCART_PASSWORD'];
        $this->languagePTBR = (!empty($language_ptbr))?$language_ptbr:$_ENV['OPENCART_LANGUAGE_PTBR'];
    }
    
    public function get($url, $data = null, $http_header = null, $data_as_json = true)
    {
        return $this->curl('GET', $url, $data, $http_header, $data_as_json);
    }
    
    public function post($url, $data = null, $http_header = null, $data_as_json = true)
    {
        return $this->curl('POST', $url, $data, $http_header, $data_as_json);
    }
    
    public function put($url, $data = null, $http_header = null, $data_as_json = true)
    {
        return $this->curl('PUT', $url, $data, $http_header, $data_as_json);
    }
    
    public function delete($url, $data = null, $http_header = null, $data_as_json = true)
    {
        return $this->curl('DELETE', $url, $data, $http_header, $data_as_json);
    }
    
    public function curl($request, $url, $data = null, $http_header = null, $data_as_json = true)
    {
        // Padrao de autorizacao como Bearer $this->token
        if (empty($http_header)) {
            $http_header = [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $this->token
            ];
        }
        
        // codifica como json os dados
        $data_string = null;
        if (!empty($data)) {
            $data_string = ($data_as_json)?json_encode($data):$data;
        }
        
        // Loga Execucao
        if ($this->debug) {
            Log::debug(class_basename($this) . " - $request - $url - " . ($data_as_json?"$data_string - ":'') . json_encode($http_header));
        }
            
        // Monta Chamada CURL
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $request);
        if (!empty($data_string)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            if ($data_as_json) {
                $http_header[] = 'Content-Length: ' . strlen($data_string);
            }
        }
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $http_header);

        // Executa
        $this->response = curl_exec($ch);
        $this->status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        // Loga Reotrno
        if ($this->debug) {
            Log::debug(class_basename($this) . " - $this->status - $this->response");
        }

        // Limpa responseObject
        $this->responseObject = null;

        // Se nao retornou 200 retorna erro
        if ($this->status != 200) {
            return false;
        }
        
        // decodifica Json
        if (!$this->responseObject = json_decode($this->response)) {
            return false;
        }
        
        // retorna
        return true;
        
    }
    
    /**
     * Autentica no OpenCart e armazena em $this->token
     * @return token ou false em caso de erro
     */
    public function getToken()
    {
        // Se ja tem token retorna
        if (!empty($this->token)) {
            return $this->token;
        }
        
        // Monta chave com Usuario:Senha
        $chave = base64_encode("{$this->user}:{$this->password}");
        
        // monta URL
        $url = $this->url . 'index.php?route=rest/admin_security/gettoken&grant_type=client_credentials';
        
        // monta Heather com autorizacao
        $http_header =  [
            'Content-Type: application/json',
            "Authorization: Basic $chave"
        ];
        
        // executa POST
        $ret = $this->post($url, null, $http_header);
        
        // se nao veio o token retorna false
        if (!isset($this->responseObject->access_token)) {
            return false;
        }
        
        // seta token e retorna
        $this->token = $this->responseObject->access_token;
        return $this->token;
        
    }
    
    public function parseManufacturers($manufacturers) 
    {
        $return = [];
        foreach ($manufacturers as $key => $manufacturer) {
            $return[$manufacturer->manufacturer_id] = $manufacturer;
        }
        return $return;
    }
    
    public function getManufacturer () 
    {

        // monta URL
        $url = $this->url . 'index.php?route=rest/manufacturer_admin/manufacturer&limit=10000000';
        
        // aborta se falhou na chamada get 
        if (!$this->get($url)) {
            return false;
        }
        
        // aborta se nao retornou sucesso
        if (!$this->responseObject->success) {
            return false;
        }
        
        // aborta se nao veio array com dados
        if (!isset($this->responseObject->data)) {
            return false;
        }
        
        // retorna array
        return $this->parseManufacturers($this->responseObject->data);
        
    }
    
    public function updateManufacturer ($id, $name, $keyword, $sort_order) 
    {
        // monta Array com dados
        $data = [
            'name' => $name,
            'keyword' => $keyword,
            'sort_order'=> $sort_order,
        ];

        // monta URL
        $url = $this->url . "index.php?route=rest/manufacturer_admin/manufacturer&id={$id}";

        // aborta caso erro no put
        if (!$this->put($url, $data)) {
            return false;
        }
        
        // aborta se nao veio variavel de success
        if (!isset($this->responseObject->success)) {
            return false;
        }
        
        // retorna o success
        return $this->responseObject->success;
        
    }
    
    public function createManufacturer ($name, $keyword, $sort_order) 
    {
        // monta Array com dados
        $data = [
            'name' => $name,
            'keyword' => $keyword,
            'sort_order'=> $sort_order,
        ];

        // monta URL
        $url = $this->url . 'index.php?route=rest/manufacturer_admin/manufacturer';

        // aborta caso erro no post
        if (!$this->post($url, $data)) {
            return false;
        }
        
        // aborta se nao veio variavel de success
        if (!isset($this->responseObject->success)) {
            return false;
        }
        
        // aborta se nao retornou success
        if (!$this->responseObject->success) {
            return false;
        }
        
        // retorna o id
        return $this->responseObject->data->id;
        
    }
    
    public function deleteManufacturer ($ids)
    {
        // se passou somente um id, transforma em array
        if (!is_array($ids)) {
            $ids = [$ids];
        }
        
        // monta Array com dados
        $data = ['manufacturers' => $ids];

        // monta URL
        $url = $this->url . 'index.php?route=rest/manufacturer_admin/manufacturer';
            
        // aborta caso erro no delete
        if (!$this->delete($url, $data)) {
            return false;
        }
        
        // aborta se nao veio variavel de success
        if (!isset($this->responseObject->success)) {
            return false;
        }
        
        // retorna o success
        return $this->responseObject->success;
        
    }
    
    public function uploadManufacturerImage ($id, $image_path) {
                
        // monta Array com dados
        $cfile = curl_file_create($image_path, mime_content_type($image_path), $image_path); 
        $data = array('file' => $cfile);
        //dd($data);
        
        // monta URL
        $url = $this->url . "index.php?route=rest/manufacturer_admin/manufacturerimages&id={$id}";
        
        // monta Heather com autorizacao
        $http_header =  [
            "Authorization: Bearer $this->token"
        ];
        
        // aborta caso erro no post
        if (!$this->post($url, $data, $http_header, false)) {
            return false;
        }
        
        // aborta se nao veio variavel de success
        if (!isset($this->responseObject->success)) {
            return false;
        }
        
        // retorna o success
        return $this->responseObject->success;
        
    }
    
    public function parseCategories($categories, $parent_id = null, &$return = [])
    {
        // percorre objeto das categorias recebido deixando como chave o id da categoria e o id da lingua
        foreach ($categories as $category_id => $langs) {
            $category = [
                'category_id' => $category_id,
                'parent_id' => $parent_id
            ];
            foreach ($langs as $key => $lang) {
                //dd($lang);
                $category['sort_order'] = $lang->sort_order;
                $category['category_description'][$lang->language_id] = $lang;
                if ($lang->language_id == $this->languagePTBR && isset($lang->categories)) {
                    $this->parseCategories($lang->categories->categories, $category_id, $return);
                }
                unset($lang->categories);
            }
            $return[$category_id] = (object) $category;
        }
        return $return;
        
    }
    
    public function getCategory($id = null, $level = 9999999999) 
    {
        
        // monta URL
        if (empty($id)) {
            $url = $this->url . "index.php?route=rest/category_admin/category&level={$level}";
        } else {
            $url = $this->url . "index.php?route=rest/category_admin/category&id={$id}";
        }
        
        // aborta se falhou na chamada get 
        if (!$this->get($url)) {
            return false;
        }
        
        // aborta se nao retornou sucesso
        if (!$this->responseObject->success) {
            return false;
        }
        
        // aborta se nao veio array com dados
        if (!isset($this->responseObject->data->categories)) {
            return false;
        }
        
        // retorna array de categorias
        return $this->parseCategories($this->responseObject->data->categories);
        
    }
    
    public function updateCategory ($id, $sort_order, $parent_id, $top, $column, $status, $name, $description, $meta_title, $meta_description, $meta_keyword) 
    {
        // monta Array com dados
        $data = [
            'sort_order' => $sort_order,
            'category_store' => [0],
            'parent_id' => $parent_id,
            'top' => $top,
            'column' => $column,
            'status' => $status, //1 - Ativo / 0 - Inativo
            'category_description' => [
                0 => [
                    'language_id' => $this->languagePTBR,
                    'name' => $name,
                    'description' => $description,
                    'meta_title' => $meta_title,
                    'meta_description' => $meta_description,
                    'meta_keyword' => $meta_keyword,
                ]
            ]
        ];

        // monta URL
        $url = $this->url . "index.php?route=rest/category_admin/category&id={$id}";

        // aborta caso erro no put
        if (!$this->put($url, $data)) {
            return false;
        }
        
        // aborta se nao veio variavel de success
        if (!isset($this->responseObject->success)) {
            return false;
        }
        
        // retorna o success
        return $this->responseObject->success;
        
    }
    
    public function createCategory ($sort_order, $parent_id, $top, $column, $status, $name, $description, $meta_title, $meta_description, $meta_keyword) 
    {
        // monta Array com dados
        $data = [
            'sort_order' => $sort_order,
            'category_store' => [0],
            'parent_id' => $parent_id,
            'top' => $top,
            'column' => $column,
            'status' => $status, //1 - Ativo / 0 - Inativo
            'category_description' => [
                0 => [
                    'language_id' => $this->languagePTBR,
                    'name' => $name,
                    'description' => $description,
                    'meta_title' => $meta_title,
                    'meta_description' => $meta_description,
                    'meta_keyword' => $meta_keyword,
                ]
            ]
        ];
        
        // monta URL
        $url = $this->url . 'index.php?route=rest/category_admin/category';

        // aborta caso erro no post
        if (!$this->post($url, $data)) {
            return false;
        }
        
        // aborta se nao veio variavel de success
        if (!isset($this->responseObject->success)) {
            return false;
        }
        
        // aborta se nao retornou success
        if (!$this->responseObject->success) {
            return false;
        }
        
        // retorna o id
        return $this->responseObject->data->id;
        
    }

    public function deleteCategory ($ids)
    {
        // se passou somente um id, transforma em array
        if (!is_array($ids)) {
            $ids = [$ids];
        }
        
        // monta Array com dados
        $data = ['categories' => $ids];

        // monta URL
        $url = $this->url . 'index.php?route=rest/category_admin/category';
            
        // aborta caso erro no delete
        if (!$this->delete($url, $data)) {
            return false;
        }
        
        // aborta se nao veio variavel de success
        if (!isset($this->responseObject->success)) {
            return false;
        }
        
        // retorna o success
        return $this->responseObject->success;
        
    }
    
}
