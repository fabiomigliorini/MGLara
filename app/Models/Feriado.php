<?php

namespace MGLara\Models;

use Carbon\Carbon;

/**
 * Campos
 * @property  bigint                         $codferiado                         NOT NULL DEFAULT nextval('tblferiado_codferiado_seq'::regclass)
 * @property  date                           $data                               NOT NULL
 * @property  varchar(100)                   $feriado                            NOT NULL
 * @property  bigint                         $codusuarioalteracao                
 * @property  timestamp                      $criacao                            
 * @property  timestamp                      $alteracao                          
 * @property  bigint                         $codusuariocriacao                  
 *
 * Chaves Estrangeiras
 * @property  Usuario                        $UsuarioCriacao
 * @property  Usuario                        $UsuarioAlteracao
 *
 * Tabelas Filhas
 */

class Feriado extends MGModel
{
    protected $table = 'tblferiado';
    protected $primaryKey = 'codferiado';
    protected $fillable = [
        'data',
        'feriado',
    ];
    protected $dates = [
        'data',
        'criacao',
        'alteracao',
    ];


    // Chaves Estrangeiras
    public function UsuarioCriacao()
    {
        return $this->belongsTo(Usuario::class, 'codusuariocriacao', 'codusuario');
    }

    public function UsuarioAlteracao()
    {
        return $this->belongsTo(Usuario::class, 'codusuarioalteracao', 'codusuario');
    }

    /**
     * Retorna se a data é um feriado
     * @param Carbon $data Data para consulta
     * @return boolean
     */
    public static function feriado (Carbon $data) {
        if (Feriado::where('data', '=', $data)->count() > 0) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Retorna se a data é um dia útil
     * @param Carbon $data Data para consulta
     * @param bool $sabado_dia_util Considera sabado como dia util ou nao
     * @return boolean
     */
    public static function diaUtil (Carbon $data, bool $sabado_dia_util = false) {
        if ($data->dayOfWeek == Carbon::SUNDAY) {
            return false;
        }
        if (!$sabado_dia_util && $data->dayOfWeek == Carbon::SATURDAY) {
            return false;
        }
        return !self::feriado($data);
    }
    
    /**
     * Retorna número de dias úteis entre a $data_inicial e a $data_final
     * @param Carbon $data_inicial Data Incial
     * @param Carbon $data_final Data Final
     * @param bool $sabado_dia_util Considera sabado como dia util ou nao
     * @return int
     */
    public static function diasUteis (Carbon $data_inicial, Carbon $data_final, bool $sabado_dia_util = false) {
        if ($data_final->lt($data_inicial)) {
            return false;
        }
        $data = $data_inicial;
        $dias_uteis = 0;
        
        $feriados = Feriado::where('data', '>=', $data_inicial)->where('data', '<=', $data_final)->get();
        
        while ($data->lte($data_final)) {
            if ($data->dayOfWeek == Carbon::SUNDAY) {
                $data->addDay();
                continue;
            }
            if (!$sabado_dia_util && $data->dayOfWeek == Carbon::SATURDAY) {
                $data->addDay();
                continue;
            }
            if ($feriados->contains('data', $data)) {
                $data->addDay();
                continue;
            }
            $dias_uteis++;
            $data->addDay();
        }
        return $dias_uteis;
    }
    
    /**
     * Retorna Proximo Dia Util
     * @param Carbon $data Data de inicio
     * @param bool $sabado_dia_util Considera sabado como dia util ou nao
     * @return Carbon
     */
    public static function proximoDiaUtil(Carbon $data, bool $sabado_dia_util = false) {
        do {
            if (self::diaUtil($data, $sabado_dia_util)) {
                return $data;
            }
            $data->addDay();
            continue;
        } while (true);
    }
}