<?php

namespace MGLara\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use Illuminate\Http\Request;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    protected $datas;
    protected $numericos;
    
//    public static function store(Request $request)
//    {
//        $this->converteDatas(['data' => $request->input('data')]);
//        
//    }


    public static function converteDatas($datas, $formato = 'd/m/Y H:i:s')
    {
        foreach ($datas as $key => $value)
        {
            if(empty($value)) {
                Input::merge([$key=>null]);
            } else {
                Input::merge(array($key => Carbon::createFromFormat(
                    $formato, 
                    $value)->toDateTimeString()
                ));
            }
        }
    }
    
    public static function converteNumericos(array $numericos)
    {
        foreach ($numericos as $key => $value)
        {
            Input::merge(array(
                $key => str_replace(',', '.', (str_replace('.', '', $value)))));
        }
    }
    
}
