<?php

namespace MGLara\Library\Magazord;

use Illuminate\Support\Facades\Log;

/**
 * Description of MagazordApi
 *
 * @author escmig98
 * @property boolean $debug Modo debug - mostra log erros
 */
class MagazordApi {

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
        $this->url = (!empty($url))?$url:env('MAGAZORD_BASEURL');
        $this->user = (!empty($user))?$user:env('MAGAZORD_USER');
        $this->password = (!empty($password))?$password:env('MAGAZORD_PASSWORD');
        // $this->languagePTBR = (!empty($language_ptbr))?$language_ptbr:env('MAGAZORD_LANGUAGE_PTBR');
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
                // 'Authorization: Bearer ' . $this->token
                'Authorization: Basic '. base64_encode("{$this->user}:{$this->password}") // <---
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
        curl_setopt($ch, CURLOPT_TIMEOUT, 90);

        // Executa
        $this->response = curl_exec($ch);
        $this->status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        // dd($this->status);
        // dd($this->response);

        // Loga Reotrno
        if ($this->debug) {
            Log::debug(class_basename($this) . " - $this->status - $this->response");
        }

        // Limpa responseObject
        $this->responseObject = null;

        $ret = true;

        // Se nao retornou 200 retorna erro
        if ($this->status != 200) {
            $ret = false;
        }

        // decodifica Json
        if (!$this->responseObject = json_decode($this->response)) {
            $ret = false;
        }

        // retorna
        return $ret;

    }

    public function postPreco ($produto, int $tabelaPreco, float $precoVenda)
    {
        // monta Array com dados
        $data = [
            (object) [
                'produto' => $produto,
                'tabelaPreco' => $tabelaPreco,
                'precoVenda' => $precoVenda
            ]
        ];

        // monta URL
        $url = $this->url . "api/v1/preco";

        // aborta caso erro no put
        if (!$this->post($url, $data)) {
            throw new \Exception($this->responseObject->mensagem, 1);
        }

        // aborta se nao veio variavel de success
        if (!isset($this->responseObject->sucesso)) {
            return false;
        }

        // retorna o success
        return $this->responseObject->sucesso;

    }

    public function postEstoque ($produto, int $quantidade)
    {
        // monta Array com dados
        $data = (object) [
            'produto' => $produto,
            'deposito' => intVal(env('MAGAZORD_DEPOSITO')),
            'quantidade' => $quantidade,
            'tipo' => 1, // Fisico
            'tipoOperacao' => 0 // Ajuste
        ];

        // monta URL
        $url = $this->url . "api/v1/estoque";

        // aborta caso erro no put
        if (!$this->post($url, $data)) {
            throw new \Exception($this->responseObject->mensagem, 1);
        }

        // aborta se nao veio variavel de success
        if (!isset($this->responseObject->sucesso)) {
            return false;
        }

        // retorna o success
        return $this->responseObject->sucesso;

    }

    public function postPrecos ($data)
    {

        // monta URL
        $url = $this->url . "api/v1/preco";

        // aborta caso erro no put
        if (!$this->post($url, $data)) {
            throw new \Exception($this->responseObject->mensagem, 1);
        }

        // aborta se nao veio variavel de success
        if (!isset($this->responseObject->sucesso)) {
            return false;
        }

        // retorna o success
        return $this->responseObject->sucesso;

    }

}
