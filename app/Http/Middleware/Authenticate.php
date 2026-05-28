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

            // RFC 7662 Token Introspection — POST com body `token` + client_credentials (Basic Auth)
            $client = new Client();
            $responseAuth = $client->post(env('AUTH_API_URL') . '/oauth/introspect', [
                'auth' => [env('SSO_CLIENT_ID'), env('SSO_CLIENT_SECRET')],
                'form_params' => [
                    'token' => $access_token,
                    'token_type_hint' => 'access_token',
                ],
                'verify' => false,
            ]);

            $responseData = json_decode((string) $responseAuth->getBody(), true);

            if ($responseAuth->getStatusCode() === 200) {
                // RFC 7662 §2.2: `active` (REQUIRED bool) é o indicador primário
                if (empty($responseData['active'])) {
                    Auth::logout();
                    return redirect()->to(env('AUTH_API_URL') . '/login?redirect_uri=' . url());
                }

                // OIDC sub é string per spec; codusuario é int — cast explícito
                $codusuario = (int) ($responseData['sub'] ?? 0);
                if (!$codusuario) {
                    Auth::logout();
                    return redirect()->to(env('AUTH_API_URL') . '/login?redirect_uri=' . url());
                }

                if (Auth::user()) {
                    if (Auth::user()->codusuario != $codusuario) {
                        Auth::logout();
                        Auth::loginUsingId($codusuario);
                    }
                }

                if ($this->auth->guest()) {
                    Auth::loginUsingId($codusuario);
                }
                return $next($request);
            }
        } catch (\Exception $e) {
            if ($e->getCode() == 401) {
                Auth::logout();
                return redirect()->to(env('AUTH_API_URL') . '/login?redirect_uri=' . url());
            } else {
                throw new \Exception($e->getMessage(), 1);
            }
        }
    }
}
