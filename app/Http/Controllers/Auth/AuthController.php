<?php

namespace MGLara\Http\Controllers\Auth;

use MGLara\Models\Usuario;
use Validator;
use MGLara\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'getLogout']);
    }

    public function loginUsername()
    {
        return 'usuario';
    }    
  
    protected function getCredentials(Request $request)
    {
        $credentials=$request->only($this->loginUsername(), 'password');
        $credentials['inativo'] = null;
        return $credentials;
    } 
    
    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return Usuario::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    public function getLogin()
    {
        if (view()->exists('auth.authenticate')) {
            return view('auth.authenticate');
        }

        return redirect()->to(env('AUTH_API_URL') . '/login?redirect_uri=' . url());

    }


    public function getLogout()
    {

        $access_token = Request::capture()->cookies->get('access_token');

        $client = new Client();

        try {
            $responseAuth = $client->post(env('AUTH_API_URL') . '/api/logout', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $access_token,
                ],
                'verify' => false
            ]); 

            if ($responseAuth->getStatusCode() === 200) {
                Auth::logout();
                $response = redirect($this->loginPath());
                return $response;
            }
        } catch (\Exception $e) {
            if($e->getCode() == 401) {
                Auth::logout();
                $response = redirect($this->loginPath());
                return $response;
            }
        }

        Auth::logout();
        $response = redirect($this->loginPath());
        return $response;

    }

}
