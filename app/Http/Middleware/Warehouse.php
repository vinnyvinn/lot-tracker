<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use League\Flysystem\Config;

class Warehouse
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

        if(Auth::check()){
            //session('warehouse')
            \Config::set('database.connections.dynamicdb',array(
               'driver' => 'sqlsrv',
                'host' => '127.0.0.1',
                'database' => session('warehouse'),
                'username' => 'sa',
                'password' => 'Omegadotcom@2580',
                'charset'   => 'utf8',
                'prefix' => ''
            ));

        }

        return $next($request);
    }
}
