<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;

use MGLara\Http\Requests;
use MGLara\Http\Controllers\Controller;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class GeradorCodigoController extends Controller
{

    /**
     * Gera código do Model para a tabela selecionada
     *
     * @param  varchar  $tabela
     * @return \Illuminate\Http\Response
     */
    public function model($tabela)
    {
        
        $sql = "
            SELECT 
                    column_name
                  , data_type
                  , udt_name
                  , character_maximum_length
                  , numeric_precision
                  , numeric_scale
                  , is_nullable
                  , column_default
            FROM information_schema.columns
            WHERE table_name = '{$tabela}'
            --ORDER BY column_name
        ";
        
        $cols = DB::select($sql);
        
        $sql = "
            SELECT
                  ccu.table_name AS foreign_table_name
                , ccu.column_name AS foreign_column_name 
                , kcu.column_name 
            FROM 
                information_schema.table_constraints AS tc 
                JOIN information_schema.key_column_usage AS kcu
                  ON tc.constraint_name = kcu.constraint_name
                JOIN information_schema.constraint_column_usage AS ccu
                  ON ccu.constraint_name = tc.constraint_name
            WHERE constraint_type = 'FOREIGN KEY' 
            AND tc.table_name='{$tabela}';
        ";
            
        $pais = DB::select($sql);
        
        $sql = "
            SELECT
                  tc.table_name AS foreign_table_name
                , kcu.column_name AS foreign_column_name 
                , ccu.column_name 
            FROM 
                information_schema.table_constraints AS tc 
                JOIN information_schema.key_column_usage AS kcu
                  ON tc.constraint_name = kcu.constraint_name
                JOIN information_schema.constraint_column_usage AS ccu
                  ON ccu.constraint_name = tc.constraint_name
            WHERE constraint_type = 'FOREIGN KEY' 
            AND ccu.table_name='{$tabela}';
        ";
            
        $filhas = DB::select($sql);
        
        return view('gerador-codigo.model', compact('tabela', 'cols', 'pais', 'filhas'));
        
    }

}
