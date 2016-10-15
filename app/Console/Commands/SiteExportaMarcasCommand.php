<?php

namespace MGLara\Console\Commands;

use Illuminate\Console\Command;
use MGLara\Jobs\EstoqueCalculaEstatisticas;
//use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\Log;
use MGLara\Models\Marca;

class SiteExportaMarcasCommand extends Command
{
    //use DispatchesJobs;
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'site:exporta-marcas';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Exporta marcas para o site usando API OpenCart';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Log::info('site:exporta-marcas');
        
        // Usuario e Senha do Site
        $chave = base64_encode('mgpapelaria:123456');
        
        // Gera o Token de Autenticacao
        $ch = curl_init('http://webapp15505.cloud683.configrapp.com/index.php?route=rest/admin_security/gettoken&grant_type=client_credentials');
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
            Log::error("site:exporta-marcas - Erro ao Gerar Token - $response");
        } else {
            $response_arr = json_decode($response, true);
            if (empty($response_arr['access_token'])) {
                Log::error("site:exporta-marcas - Erro ao Gerar Token - $response");
                return false;
            }
            $token = $response_arr['access_token'];
        } 
        
        // Token temporario
        //$token = '558cb6ad4a66d06a3671e920c8d1de15c5cc91b3';
        
        // Busca Listagem das marcas do OpenCart
        $ch = curl_init('http://webapp15505.cloud683.configrapp.com/index.php?route=rest/manufacturer_admin/manufacturer&limit=10000000');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                "Authorization: Bearer $token"
            )
        );
        
        $response = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($status != 200) {
            Log::error("site:exporta-marcas - Erro ao Gerar Token - $response");
        } else {
            $response = json_decode($response, true);
            if (!$response['success']) {
                return false;
            }
        } 

        // Transforma o array das Marcas, deixando o codigo como chave do array
        $marcas_site = [];
        foreach ($response['data'] as $key => $manufacturer) {
            $marcas_site[$manufacturer['manufacturer_id']] = $manufacturer;
        }
        
        // Busca as Marcas do Sistema
        $marcas = Marca::orderBy('codmarca')->get();
        
        // Percorre todas as marcas
        foreach ($marcas as $marca) {
            
            // String com os dados da Marca
            $data_string = json_encode([
                'name' => $marca->marca,
                'keyword' => $marca->marca,
                'sort_order'=> '0', // TODO: Ordenar de acordo com importancia
                //'image': "image_path", //TODO: Upload Imagem
                //'manufacturer_store':["0"],
            ]);
            
            //Se ja esta no OpenCart - ATUALIZA
            if (isset($marcas_site[$marca->codopencart])) {
                
                $ch = curl_init("http://webapp15505.cloud683.configrapp.com/index.php?route=rest/manufacturer_admin/manufacturer&id={$marca->codopencart}");
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");   
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
                    'Content-Type: application/json',     
                    "Authorization: Bearer $token",
                    'Content-Length: ' . strlen($data_string))                                                                       
                );                                                                                                           
 
                $response = curl_exec($ch);
                $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
                
                if ($status != 200) {
                    Log::error("site:exporta-marcas - Erro ao ATUALIZAR marca #{$marca->codmarca} - '$marca->marca' - $response");
                }
                
            // Se nao esta no OpenCart - CRIA
            } else {
                
                $ch = curl_init("http://webapp15505.cloud683.configrapp.com/index.php?route=rest/manufacturer_admin/manufacturer");
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");   
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
                    'Content-Type: application/json',     
                    "Authorization: Bearer $token",
                    'Content-Length: ' . strlen($data_string))                                                                       
                );                                                                                                           
 
                $response = curl_exec($ch);
                $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
                
                if ($status != 200) {
                    Log::error("site:exporta-marcas - Erro ao CRIAR marca #{$marca->codmarca} - '$marca->marca' - $response");
                } else {
                    $response = json_decode($response, true);
                    if ($response['success']) {
                        $marca->codopencart = $response['data']['id'];
                        $marca->save();
                    } else {
                        if (($response['error']['keyword'] == 'SEO keyword already in use!') && empty($marca->codopencart)) {
                            $codigos = array_keys($marcas_site);
                            $i = array_search($marca->marca, array_column($marcas_site, 'name'));
                            $marca->codopencart = $codigos[$i];
                            $marca->save();
                        } else {
                            $response = json_encode($response);
                            Log::error("site:exporta-marcas - Erro ao CRIAR marca #{$marca->codmarca} - '$marca->marca' - $response");
                        }
                    }
                }                

            }
            
            // Exclui do array de Marcas do OpenCart
            unset($marcas_site[$marca->codopencart]);
        }
        
        // Percorre as Marcas do OpenCart que sobraram e Exclui
        foreach($marcas_site as $manufacturer_id => $manufacturer) {
            
            $data_string = json_encode(['manufacturers' => [$manufacturer_id]]);
            
            $ch = curl_init("http://webapp15505.cloud683.configrapp.com/index.php?route=rest/manufacturer_admin/manufacturer");
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                "Authorization: Bearer $token",
                'Content-Length: ' . strlen($data_string))
            );                                                                                                           

            $response = curl_exec($ch);
            $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($status != 200) {
                Log::error("site:exporta-marcas - Erro ao EXCLUIR marca #{$manufacturer_id} - '{$manufacturer['name']}' - $response");
            } else {
                $response = json_decode($response, true);
                if (!$response['success']) {
                    $response = json_encode($response);
                    Log::error("site:exporta-marcas - Erro ao EXCLUIR marca #{$manufacturer_id} - '{$manufacturer['name']}' - $response");
                }
            }
            
        }
        
        
    }
    
}
