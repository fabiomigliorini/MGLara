<?php

namespace MGLara\Http\Middleware;

use Closure;
use Auth;
class IpInterno
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$this->verificaIpInterno($request->ip())) {
            if(!Auth::user()){
                return redirect()->guest('auth/login');
            }
        }        
        return $next($request);
    }
    
    public function verificaIpInterno ($ip)
    {
        $reserved_ips = array( 
            '2130706433' => 2130706689, #  127.0.0.1 até  127.0.1.1
            '3232235777' => 3232236031, # 192.168.1.1 até 192.168.1.255
            '3232236033' => 3232236287, # 192.168.2.1 até 192.168.2.255
            '3232236289' => 3232236543, # 192.168.3.1 até 192.168.3.255
            '3232236545' => 3232236799, # 192.168.4.1 até 192.168.4.255
        );

        $ip_long = sprintf('%u', ip2long($ip));

        foreach ($reserved_ips as $ip_start => $ip_end)
        {
            if (($ip_long >= $ip_start) && ($ip_long <= $ip_end))
            {
                return TRUE; // IP interno
            }
        }
        return FALSE; // IP externo
    }    
}
