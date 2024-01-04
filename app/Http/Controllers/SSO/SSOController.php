<?php

namespace MGLara\Http\Controllers\SSO;

use MGLara\Http\Controllers\Controller;
use MGLara\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use GuzzleHttp;
use InvalidArgumentException;

class SSOController extends Controller
{
    public function getLogin(Request $request)
    {
        $request->session()->put("state", $state =  Str::random(40));
        $query = http_build_query([
            "client_id" => env("SSO_CLIENT_ID"),
            "redirect_uri" => env("SSO_CLIENT_CALLBACK") ,
            "response_type" => "code",
            "scope" => env("SSO_SCOPES"),
            "state" => $state,
            "prompt" => false
        ]);
        return redirect(env("SSO_HOST") .  "/oauth/authorize?" . $query);
    }
    public function getCallback(Request $request)
    {
        $http = new \GuzzleHttp\Client;

        try{
              $response = $http->post(env('SSO_HOST') .  "/oauth/token", [
                    'form_params' => [
                    "grant_type" => "authorization_code",
                    "client_id" => env("SSO_CLIENT_ID"),
                    "client_secret" => env("SSO_CLIENT_SECRET"),
                    "redirect_uri" => env("SSO_CLIENT_CALLBACK") ,
                    "code" => $request->code
                ]
            ]);
          //  return json_decode((string) $response->getBody(), true);
            $request->session()->put(json_decode((string) $response->getBody(), true));
            return redirect(route("sso.connect"));

        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            if ($e->getCode() === 400) {
                return response()->json('Requisição Invalida, existe parametros invalidos, por favor verifique e tente novamente', $e->getCode());
            } else if ($e->getCode() === 401) {
                return response()->json('Suas credenciais estão incorretas, por favor tente novamente', $e->getCode());
            }

            return response()->json('erro desconhecido, por favor tente novamente.', $e->getCode());
        }
    }
   
    public function connectUser(Request $request)
    {
    $access_token = $request->session()->get("access_token");
    $http = new \GuzzleHttp\Client;
     $response = $http->request('GET', env('SSO_HOST') .  "/api/v1/auth/user", [
        'headers' => [
            "Accept" => "application/json",
            "Authorization" => "Bearer " . $access_token
            ]
        ]);
    // return json_decode((string) $response->getBody(), true);
     $userArray = json_decode((string) $response->getBody(), true);
        try {
            $usuario = $userArray['data']['usuario'];
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            return redirect("login")->withError("Falha ao obter informações de login! Tente novamente.");
        }
        $user = Usuario::where("usuario", $usuario)->first();
        Auth::login($user);
        return redirect()->intended();
    }
}
