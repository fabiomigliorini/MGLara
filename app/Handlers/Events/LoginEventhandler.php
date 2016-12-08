<?php

namespace MGLara\Handlers\Events;

use MGLara\Events;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use MGLara\Models\Usuario;
use Carbon\Carbon;
use DB;

class LoginEventhandler
{
    /**
     * Create the event handler.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Events  $event
     * @return void
     */
    public function handle(Usuario $usuario)
    {
        DB::table('tblusuario')->where('codusuario', '=', $usuario->codusuario)->update(['ultimoacesso' => Carbon::now()]);
        $usuario->fresh();
    }
}
