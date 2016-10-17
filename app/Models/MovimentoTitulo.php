<?php

namespace MGLara\Models;

/**
 * Campos
 * @property  bigint                         $codmovimentotitulo                 NOT NULL DEFAULT nextval('tblmovimentotitulo_codmovimentotitulo_seq'::regclass)
 * @property  bigint                         $codtipomovimentotitulo             
 * @property  bigint                         $codtitulo                          
 * @property  bigint                         $codportador                        
 * @property  bigint                         $codtitulorelacionado               
 * @property  numeric(14,2)                  $debito                             
 * @property  numeric(14,2)                  $credito                            
 * @property  varchar(255)                   $historico                          
 * @property  date                           $transacao                          
 * @property  timestamp                      $sistema                            
 * @property  bigint                         $codliquidacaotitulo                
 * @property  bigint                         $codtituloagrupamento               
 * @property  bigint                         $codboletoretorno                   
 * @property  bigint                         $codcobranca                        
 * @property  timestamp                      $alteracao                          
 * @property  bigint                         $codusuarioalteracao                
 * @property  timestamp                      $criacao                            
 * @property  bigint                         $codusuariocriacao                  
 *
 * Chaves Estrangeiras
 * @property  BoletoRetorno                  $BoletoRetorno                 
 * @property  Cobranca                       $Cobranca                      
 * @property  LiquidacaoTitulo               $LiquidacaoTitulo              
 * @property  Portador                       $Portador                      
 * @property  TipoMovimentoTitulo            $TipoMovimentoTitulo           
 * @property  Titulo                         $Titulo                        
 * @property  Titulo                         $Titulo                        
 * @property  TituloAgrupamento              $TituloAgrupamento             
 * @property  Usuario                        $UsuarioAlteracao
 * @property  Usuario                        $UsuarioCriacao
 *
 * Tabelas Filhas
 */

class MovimentoTitulo extends MGModel
{
    protected $table = 'tblmovimentotitulo';
    protected $primaryKey = 'codmovimentotitulo';
    protected $fillable = [
        'codtipomovimentotitulo',
        'codtitulo',
        'codportador',
        'codtitulorelacionado',
        'debito',
        'credito',
        'historico',
        'transacao',
        'sistema',
        'codliquidacaotitulo',
        'codtituloagrupamento',
        'codboletoretorno',
        'codcobranca',
    ];
    protected $dates = [
        'transacao',
        'sistema',
        'alteracao',
        'criacao',
    ];


    // Chaves Estrangeiras
    public function BoletoRetorno()
    {
        return $this->belongsTo(BoletoRetorno::class, 'codboletoretorno', 'codboletoretorno');
    }

    public function Cobranca()
    {
        return $this->belongsTo(Cobranca::class, 'codcobranca', 'codcobranca');
    }

    public function LiquidacaoTitulo()
    {
        return $this->belongsTo(LiquidacaoTitulo::class, 'codliquidacaotitulo', 'codliquidacaotitulo');
    }

    public function Portador()
    {
        return $this->belongsTo(Portador::class, 'codportador', 'codportador');
    }

    public function TipoMovimentoTitulo()
    {
        return $this->belongsTo(TipoMovimentoTitulo::class, 'codtipomovimentotitulo', 'codtipomovimentotitulo');
    }

    public function Titulo()
    {
        return $this->belongsTo(Titulo::class, 'codtitulo', 'codtitulo');
    }

    public function TituloRelacionado()
    {
        return $this->belongsTo(Titulo::class, 'codtitulo', 'codtitulorelacionado');
    }

    public function TituloAgrupamento()
    {
        return $this->belongsTo(TituloAgrupamento::class, 'codtituloagrupamento', 'codtituloagrupamento');
    }

    public function UsuarioAlteracao()
    {
        return $this->belongsTo(Usuario::class, 'codusuarioalteracao', 'codusuario');
    }

    public function UsuarioCriacao()
    {
        return $this->belongsTo(Usuario::class, 'codusuariocriacao', 'codusuario');
    }


    // Tabelas Filhas

}
