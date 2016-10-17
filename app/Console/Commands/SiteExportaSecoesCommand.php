<?php

namespace MGLara\Console\Commands;

use Illuminate\Console\Command;
use MGLara\Jobs\EstoqueCalculaEstatisticas;
//use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\Log;
use MGLara\Models\SecaoProduto;

class SiteExportaSecoesCommand extends Command
{
    const URL_SITE = 'http://webapp15505.cloud683.configrapp.com/';
    //use DispatchesJobs;
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'site:exporta-secoes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Exporta secoes para o site usando API OpenCart';

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
        Log::info('site:exporta-secoes');
        
        /*
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
            Log::error("site:exporta-secoes - Erro ao Gerar Token - $response");
        } else {
            $response_arr = json_decode($response, true);
            if (empty($response_arr['access_token'])) {
                Log::error("site:exporta-secoes - Erro ao Gerar Token - $response");
                return false;
            }
            $token = $response_arr['access_token'];
        } 
        */
        // Token temporario
        $token = '13f8ea04e1728a03a1a71f2340b086eba3e5f595';
        
        // Busca Listagem das secoes do OpenCart
        $ch = curl_init(SELF::URL_SITE . 'index.php?route=rest/category_admin/category&level=99999');
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
            Log::error("site:exporta-secoes - Erro ao Gerar Token - $response");
        } else {
            $response = json_decode($response, true);
            if (!$response['success']) {
                return false;
            }
        } 

        dd($response);
        // Transforma o array das Secoes, deixando o codigo como chave do array
        $secoes_site = [];
        foreach ($response['data'] as $key => $category) {
            $secoes_site[$category['category_id']] = $category;
        }
        
        dd($secoes_site);
        
        // Busca as Secoes do Sistema
        //$secoes = SecaoProduto::whereNotNull('codimagem')->orderBy('codsecaoproduto')->limit(10)->get();
        $secoes = SecaoProduto::orderBy('codsecaoproduto')->get();
        
        // Percorre todas as secoes
        foreach ($secoes as $secaoproduto) {

            // String com os dados da SecaoProduto
            $data_string = json_encode([
                'name' => $secaoproduto->secaoproduto,
                'keyword' => $secaoproduto->secaoproduto,
                'sort_order'=> '0', // TODO: Ordenar de acordo com importancia
                //'image': "image_path", //TODO: Upload Imagem
                //'manufacturer_store':["0"],
            ]);
            
            //Se ja esta no OpenCart - ATUALIZA
            if (isset($secoes_site[$secaoproduto->codopencart])) {
                
                $ch = curl_init(SELF::URL_SITE . "index.php?route=rest/manufacturer_admin/manufacturer&id={$secaoproduto->codopencart}");
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
                    Log::error("site:exporta-secoes - Erro ao ATUALIZAR secaoproduto #{$secaoproduto->codsecaoproduto} - '$secaoproduto->secaoproduto' - $response");
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
                    Log::error("site:exporta-secoes - Erro ao CRIAR secaoproduto #{$secaoproduto->codsecaoproduto} - '$secaoproduto->secaoproduto' - $response");
                } else {
                    $response = json_decode($response, true);
                    if ($response['success']) {
                        $secaoproduto->codopencart = $response['data']['id'];
                        $secaoproduto->save();
                    } else {
                        if (($response['error']['keyword'] == 'SEO keyword already in use!') && empty($secaoproduto->codopencart)) {
                            $codigos = array_keys($secoes_site);
                            $i = array_search($secaoproduto->secaoproduto, array_column($secoes_site, 'name'));
                            $secaoproduto->codopencart = $codigos[$i];
                            $secaoproduto->save();
                        } else {
                            $response = json_encode($response);
                            Log::error("site:exporta-secoes - Erro ao CRIAR secaoproduto #{$secaoproduto->codsecaoproduto} - '$secaoproduto->secaoproduto' - $response");
                        }
                    }
                }                

            }
            
            // TODO: logica para saber se precisa atualizar a imagem
            $atualizar_imagem = empty($secaoproduto->codimagem)?false:true;
            
            if ($atualizar_imagem) {
                
                
                $target = SELF::URL_SITE . "index.php?route=rest/manufacturer_admin/manufacturerimages&id={$secaoproduto->codopencart}";

                // Create a CURLFile object / procedural method 
                $imagePath = base_path('public/imagens/'.$secaoproduto->Imagem->observacoes);
                echo "{$secaoproduto->codopencart} - {$secaoproduto->secaoproduto} - $imagePath\n";
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
                
                //dd($r);
                /*
                
                $postFields['file'] = curl_file_create($imagePath, "image/$imageExtention", 'file');
                */

                /*
                //$postFields['file'] = "@$imagePath;type=image/$imageExtention";
                $postFields['file'] = new \CURLFile($imagePath);
                dd($postFields['file']);
                //$x = new \CURLFile($filename)

                //dd(SELF::URL_SITE . "index.php?route=rest/manufacturer_admin/manufacturerimages&id={$secaoproduto->codopencart}");
                */
                //dd($postFields);
                
                /*
                $ch = curl_init(SELF::URL_SITE . "index.php?route=rest/manufacturer_admin/manufacturerimages&id={$secaoproduto->codopencart}");
                curl_setopt ($ch, CURLOPT_POST, 1);
                curl_setopt ($ch, CURLOPT_POSTFIELDS, $postFields);
                curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
                 * 
                 */
                /*
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");   
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_SAFE_UPLOAD, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);                                                                  
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
                 * 
                 */
                /*
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
                    'Content-Type: application/json',     
                    "Authorization: Bearer $token"
                ));

                $response = curl_exec($ch);
                $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
                dd($response);
                if ($status != 200) {
                    echo("ERROR");
                } else {
                    echo($response);
                }
                 * 
                 */
                
            }            
            
            // Exclui do array de Secoes do OpenCart
            unset($secoes_site[$secaoproduto->codopencart]);
        }
        
        // Percorre as Secoes do OpenCart que sobraram e Exclui
        foreach($secoes_site as $manufacturer_id => $manufacturer) {
            
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
                Log::error("site:exporta-secoes - Erro ao EXCLUIR secaoproduto #{$manufacturer_id} - '{$manufacturer['name']}' - $response");
            } else {
                $response = json_decode($response, true);
                if (!$response['success']) {
                    $response = json_encode($response);
                    Log::error("site:exporta-secoes - Erro ao EXCLUIR secaoproduto #{$manufacturer_id} - '{$manufacturer['name']}' - $response");
                }
            }
            
        }
        
        
        
    }
    
}
