<?php

namespace MGLara\Console\Commands;

use Illuminate\Console\Command;
use MGLara\Jobs\EstoqueCalculaEstatisticas;
//use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\Log;
use MGLara\Models\Marca;

//use MGLara\Library\IntegracaoOpenCart;
use MGLara\Library\IntegracaoOpenCart\IntegracaoOpenCart;

class SiteExportaProdutosCommand extends Command
{
    const URL_SITE = 'http://webapp15505.cloud683.configrapp.com/';
    //use DispatchesJobs;
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'site:exporta-produtos';

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
        $i = new IntegracaoOpenCart(true);
        $i->token = '230f09725c442eaf40405c0c7995912ac91611bc';
        //$i->getToken();
        //$i->sincronizaMarcas([10000410, 10000295, 138], true);
        //$i->sincronizaMarcas();
        //$i->sincronizaSecoes(1, true);
        $i->sincronizaSecoes();
        //
        //dd($i);
        /*
        $auth = new IntegracaoOpenCartAuth(true);
        $token = $auth->getToken();
        dd($token);
         * 
         */
        /*
        $oc = new IntegracaoOpenCart(true);
        $oc->token = 'e8792e2b6099deed56ae4568cc81e1059612b5f4';
        //$oc->sincronizaProdutos([374, 638]);
        //$oc->sincronizaProdutos([9785]);
        //$oc->sincronizaProdutos([23021]);
        //$oc->sincronizaProdutos([313287]);
        //$oc->sincronizaProdutos([676]);
        $oc->sincronizaProdutos([2060]);
        */
        /*
        Log::info('site:exporta-produtos');
        
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
            Log::error("site:exporta-produtos - Erro ao Gerar Token - $response");
        } else {
            $response_arr = json_decode($response, true);
            if (empty($response_arr['access_token'])) {
                Log::error("site:exporta-produtos - Erro ao Gerar Token - $response");
                return false;
            }
            $token = $response_arr['access_token'];
        } 
        
        // Token temporario
        //$token = '13f8ea04e1728a03a1a71f2340b086eba3e5f595';
        
        // Busca Listagem das marcas do OpenCart
        $ch = curl_init(SELF::URL_SITE . 'index.php?route=rest/manufacturer_admin/manufacturer&limit=10000000');
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
            Log::error("site:exporta-produtos - Erro ao Gerar Token - $response");
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
        //$marcas = Marca::whereNotNull('codimagem')->orderBy('codmarca')->limit(10)->get();
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
                
                $ch = curl_init(SELF::URL_SITE . "index.php?route=rest/manufacturer_admin/manufacturer&id={$marca->codopencart}");
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
                    Log::error("site:exporta-produtos - Erro ao ATUALIZAR marca #{$marca->codmarca} - '$marca->marca' - $response");
                }
                
            // Se nao esta no OpenCart - CRIA
            } else {
                
                $ch = curl_init(SELF::URL_SITE . "index.php?route=rest/manufacturer_admin/manufacturer");
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
                    Log::error("site:exporta-produtos - Erro ao CRIAR marca #{$marca->codmarca} - '$marca->marca' - $response");
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
                            Log::error("site:exporta-produtos - Erro ao CRIAR marca #{$marca->codmarca} - '$marca->marca' - $response");
                        }
                    }
                }                

            }
            
            // TODO: logica para saber se precisa atualizar a imagem
            $atualizar_imagem = empty($marca->codimagem)?false:true;
            
            if ($atualizar_imagem) {
                
                
                $target = SELF::URL_SITE . "index.php?route=rest/manufacturer_admin/manufacturerimages&id={$marca->codopencart}";

                // Create a CURLFile object / procedural method 
                $imagePath = base_path('public/imagens/'.$marca->Imagem->observacoes);
                echo "{$marca->codopencart} - {$marca->marca} - $imagePath\n";
                $imageExtention = preg_replace('/^.*\.([^.]+)$/D', '$1', $imagePath);
                $cfile = curl_file_create($imagePath, mime_content_type($imagePath), $imagePath); // try adding 
                //dd($cfile);
                // Assign POST data
                $imgdata = array('file' => $cfile);

                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, $target);
                //curl_setopt($curl, CURLOPT_USERAGENT,'Opera/9.80 (Windows NT 6.2; Win64; x64) Presto/2.12.388 Version/12.15');
                //curl_setopt($curl, CURLOPT_HTTPHEADER,array('User-Agent: Opera/9.80 (Windows NT 6.2; Win64; x64) Presto/2.12.388 Version/12.15','Referer: http://someaddress.tld','Content-Type: multipart/form-data'));
                curl_setopt($curl, CURLOPT_HTTPHEADER, array(                                                                          
                    "Authorization: Bearer $token"
                ));
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // stop verifying certificate
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 
                curl_setopt($curl, CURLOPT_POST, true); // enable posting
                curl_setopt($curl, CURLOPT_POSTFIELDS, $imgdata); // post images 
                curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true); // if any redirection after upload
                $r = curl_exec($curl); 
                curl_close($curl);
                
                
            }            
            
            // Exclui do array de Marcas do OpenCart
            unset($marcas_site[$marca->codopencart]);
        }
        
        // Percorre as Marcas do OpenCart que sobraram e Exclui
        foreach($marcas_site as $manufacturer_id => $manufacturer) {
            
            $data_string = json_encode(['manufacturers' => [$manufacturer_id]]);
            
            $ch = curl_init(SELF::URL_SITE . "index.php?route=rest/manufacturer_admin/manufacturer");
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
                Log::error("site:exporta-produtos - Erro ao EXCLUIR marca #{$manufacturer_id} - '{$manufacturer['name']}' - $response");
            } else {
                $response = json_decode($response, true);
                if (!$response['success']) {
                    $response = json_encode($response);
                    Log::error("site:exporta-produtos - Erro ao EXCLUIR marca #{$manufacturer_id} - '{$manufacturer['name']}' - $response");
                }
            }
            
        }
        
        */
        
    }
    
}
