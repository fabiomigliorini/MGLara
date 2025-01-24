<?php

namespace MGLara\Http\Middleware;

use Closure;
use GuzzleHttp\Client;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Authenticate
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // if ($this->auth->guest()) {
        //     if ($request->ajax()) {
        //         return response('Unauthorized.', 401);
        //     } else {
        //         return redirect()->guest('/auth/login');
        //     }
        // }

        try {
            $access_token = Request::capture()->cookies->get('access_token');


            if (!$access_token) {
                return redirect()->guest('/auth/login');
            }

            $client = new Client();
            $responseAuth = $client->get(env('AUTH_API_URL') . '/api/check-token', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $access_token,
                ],
                'verify' => false
            ]);

            $reponseData = json_decode((string) $responseAuth->getBody(), true);

            if ($responseAuth->getStatusCode() === 200) {
                if (!$reponseData['user_id']) {
                    Auth::logout();
                    return redirect()->to(env('AUTH_API_URL') . '/login?redirect_uri=' . url());
                }
                if (Auth::user()) {
                    if (Auth::user()->codusuario != $reponseData['user_id']) {
                        Auth::logout();
                        Auth::loginUsingId($reponseData['user_id']);
                    }
                }

                if ($this->auth->guest()) {
                    Auth::loginUsingId($reponseData['user_id']);
                }
                $reponse = $next($request);
                return $reponse;
            }
        } catch (\Exception $e) {
            if ($e->getCode() == 401) {
                Auth::logout();
                return redirect()->to(env('AUTH_API_URL') . '/login?redirect_uri=' . url());
            } else {
                throw new \Exception("Recebido resposta {$e->getCode()} ao autenticar!", 1);
            }
        }
    }
}
