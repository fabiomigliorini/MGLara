<?php

namespace MGLara\Library\Mercos;

use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

/**
 * Description of MercosApi
 *
 * @author escmig98
 * @property boolean $debug Modo debug - mostra log erros
 */
class MercosApi {

    protected $debug = false;
    protected $url;
    protected $user;
    protected $password;
    protected $languagePTBR;

    public $token;

    protected $response;
    public $responseObject;
    protected $status;

    /**
     * Construtor
     */
    public function __construct($debug = false, $url = null, $user = null, $password = null, $language_ptbr = null)
    {
        // Traz variaves de ambiente
        $this->debug = $debug;
        $this->url = (!empty($url))?$url:env('MERCOS_BASEURL');
        $this->applicationToken = (!empty($user))?$user:env('MERCOS_APPLICATION_TOKEN');
        $this->companyToken = (!empty($password))?$password:env('MERCOS_COMPANY_TOKEN');
        // $this->languagePTBR = (!empty($language_ptbr))?$language_ptbr:env('MERCOS_LANGUAGE_PTBR');
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
                'ApplicationToken: '. $this->applicationToken,
                'CompanyToken: '. $this->companyToken
            ];
        }

        // codifica como json os dados
        $data_string = null;
        if (!empty($data)) {
            $data_string = ($data_as_json)?json_encode($data):$data;
        } else {
            $url = $endpoint . '?' . http_build_query($data);
            curl_setopt($ch, CURLOPT_URL, $url);
        }

        // Loga Execucao
        if ($this->debug) {
            Log::debug(class_basename($this) . " - $request - $url - " . ($data_as_json?"$data_string - ":'') . json_encode($http_header));
        }

        // Monta Chamada CURL
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $request);
        if ($request == 'GET') {
            $url = $url . '?' . http_build_query($data);
            curl_setopt($ch, CURLOPT_URL, $url);
        }
        if (!empty($data_string)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            if ($data_as_json) {
                $http_header[] = 'Content-Length: ' . strlen($data_string);
            }
        }
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $http_header);
        curl_setopt($ch, CURLOPT_TIMEOUT, 90);

        // Executa
        $this->error = null;
        $this->errno = null;
        $this->status = null;
        $this->headers = null;
        $this->response = curl_exec($ch);
        if ($this->response === false) {
            $this->error = curl_error($ch);
            $this->errno = curl_errno($ch);
        } else {
            $this->status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        }
        $headerSize = curl_getinfo($ch , CURLINFO_HEADER_SIZE);
        $headerStr = substr($this->response , 0 , $headerSize );
        $this->headers = $this->parseHeader($headerStr);
        curl_close($ch);

        // Loga Reotrno
        if ($this->debug) {
            Log::debug(class_basename($this) . " - $this->status - $this->response");
        }

        // Limpa responseObject
        $this->responseObject = null;
        $ret = true;

        // Se nao retornou 200 retorna erro
        if (!in_array($this->status, [200, 201])) {
            $ret = false;
        }

        // decodifica Json
        if (!empty($this->response)) {
            $bodyStr = substr($this->response, $headerSize);
            $this->responseObject = json_decode($bodyStr);
        }

        // retorna
        return $ret;

    }

    function parseHeader($response)
    {
        if (!preg_match_all('/([A-Za-z\-]{1,})\:(.*)\\r/', $response, $matches)
                || !isset($matches[1], $matches[2])){
            return false;
        }
        $headers = [];
        foreach ($matches[1] as $index => $key){
            $headers[trim($key)] = trim($matches[2][$index]);
        }
        return $headers;
    }

    public function postProdutos (
        $nome,
        $preco_tabela,
        $preco_minimo,
        $codigo,
        $comissao = null,
        $ipi = null,
        $tipo_ipi = 'P',
        $st = null,
        $moeda = 0,
        $unidade,
        $saldo_estoque,
        $observacoes,
        $grade_cores = null,
        $grade_tamanhos = null,
        $excluido = false,
        $ativo = true,
        $categoria_id = null,
        $codigo_ncm,
        $multiplo = null,
        $peso_bruto = null,
        $largura = null,
        $altura = null,
        $comprimento = null,
        $peso_dimensoes_unitario = true,
        $exibir_no_b2b = true)
    {
        // monta Array com dados
        $data = (object) [
            'nome' => $nome,
            'preco_tabela' => $preco_tabela,
            'preco_minimo' => $preco_minimo,
            'codigo' => $codigo,
            'comissao' => $comissao,
            'ipi' => $ipi,
            'tipo_ipi' => $tipo_ipi,
            'st' => $st,
            'moeda' => $moeda,
            'unidade' => $unidade,
            'saldo_estoque' => $saldo_estoque,
            'observacoes' => $observacoes,
            'grade_cores' => $grade_cores,
            'grade_tamanhos' => $grade_tamanhos,
            'excluido' => $excluido,
            'ativo' => $ativo,
            'categoria_id' => $categoria_id,
            'codigo_ncm' => $codigo_ncm,
            'multiplo' => $multiplo,
            'peso_bruto' => $peso_bruto,
            'largura' => $largura,
            'altura' => $altura,
            'comprimento' => $comprimento,
            'peso_dimensoes_unitario' => $peso_dimensoes_unitario,
            'exibir_no_b2b' => $exibir_no_b2b
        ];

        // monta URL
        $url = $this->url . "api/v1/produtos";

        // aborta caso erro no put
        if (!$this->post($url, $data)) {
            throw new \Exception($this->response, 1);
        }

        return $this->status == 201;
    }

    public function putProdutos (
        $id,
        $nome,
        $preco_tabela,
        $preco_minimo,
        $codigo,
        $comissao = null,
        $ipi = null,
        $tipo_ipi = 'P',
        $st = null,
        $moeda = 0,
        $unidade,
        $saldo_estoque,
        $observacoes,
        $grade_cores = null,
        $grade_tamanhos = null,
        $excluido = false,
        $ativo = true,
        $categoria_id = null,
        $codigo_ncm,
        $multiplo = null,
        $peso_bruto = null,
        $largura = null,
        $altura = null,
        $comprimento = null,
        $peso_dimensoes_unitario = true,
        $exibir_no_b2b = true)
    {
        // monta Array com dados
        $data = (object) [
            'id' => $id,
            'nome' => $nome,
            'preco_tabela' => $preco_tabela,
            'preco_minimo' => $preco_minimo,
            'codigo' => $codigo,
            'comissao' => $comissao,
            'ipi' => $ipi,
            'tipo_ipi' => $tipo_ipi,
            'st' => $st,
            'moeda' => $moeda,
            'unidade' => $unidade,
            'saldo_estoque' => $saldo_estoque,
            'observacoes' => $observacoes,
            'grade_cores' => $grade_cores,
            'grade_tamanhos' => $grade_tamanhos,
            'excluido' => $excluido,
            'ativo' => $ativo,
            'categoria_id' => $categoria_id,
            'codigo_ncm' => $codigo_ncm,
            'multiplo' => $multiplo,
            'peso_bruto' => $peso_bruto,
            'largura' => $largura,
            'altura' => $altura,
            'comprimento' => $comprimento,
            'peso_dimensoes_unitario' => $peso_dimensoes_unitario,
            'exibir_no_b2b' => $exibir_no_b2b
        ];

        // monta URL
        $url = $this->url . "api/v1/produtos/{$id}";

        // aborta caso erro no put
        if (!$this->put($url, $data)) {
            throw new \Exception($this->response, 1);
        }

        return $this->status == 201;
    }

    public function getProdutos (Carbon $alterado_apos)
    {

        $data = [];
        if (!empty($alterado_apos)) {
            $alt = clone $alterado_apos;
            $alt->setTimezone('America/Sao_Paulo');
            $data ['alterado_apos'] = $alterado_apos->format('Y-m-d H:i:s');
        }

        // monta URL
        $url = $this->url . "api/v1/produtos";

        // aborta caso erro no put
        if (!$this->get($url, $data)) {
            throw new \Exception($this->response, 1);
        }

        if ($this->status != 200) {
            return false;
        }

        return $this->responseObject;
    }

    public function postImagensProduto (
        $produto_id,
        $ordem,
        $imagem_base64)
    {
        // monta Array com dados
        $data = (object) [
            'produto_id' => $produto_id,
            'ordem' => $ordem,
            'imagem_base64' => $imagem_base64,
        ];

        // monta URL
        $url = $this->url . "api/v1/imagens_produto";

        // aborta caso erro no put
        if (!$this->post($url, $data)) {
            throw new \Exception($this->response, 1);
        }

        return $this->status == 201;
    }

}
