<?php

namespace MGLara\Models;

/**
 * Campos
 * @property  bigint                         $codvalecompra                      NOT NULL DEFAULT nextval('tblvalecompra_codvalecompra_seq'::regclass)
 * @property  bigint                         $codvalecompramodelo                
 * @property  bigint                         $codpessoafavorecido                NOT NULL
 * @property  bigint                         $codpessoa                          NOT NULL
 * @property  varchar(200)                   $observacoes                        NOT NULL
 * @property  varchar(50)                    $aluno                              
 * @property  varchar(30)                    $turma                              
 * @property  numeric(14,2)                  $totalprodutos                      
 * @property  numeric(14,2)                  $desconto                           
 * @property  numeric(14,2)                  $total                              
 * @property  bigint                         $codtitulo                          
 * @property  timestamp                      $inativo                            
 * @property  timestamp                      $criacao                            
 * @property  bigint                         $codusuariocriacao                  
 * @property  timestamp                      $alteracao                          
 * @property  bigint                         $codusuarioalteracao                
 *
 * Chaves Estrangeiras
 * @property  Usuario                        $UsuarioCriacao
 * @property  Usuario                        $UsuarioAlteracao
 * @property  Pessoa                         $PessoaFavorecido
 * @property  Pessoa                         $Pessoa
 * @property  ValeCompraModelo               $ValeCompraModelo
 * @property  Titulo                         $Titulo                        
 *
 * Tabelas Filhas
 * @property  ValeCompraFormaPagamento[]     $ValeCompraFormaPagamentoS
 * @property  ValeCompraProdutoBarra[]       $ValeCompraProdutoBarraS
 */

class ValeCompra extends MGModel
{
    protected $table = 'tblvalecompra';
    protected $primaryKey = 'codvalecompra';
    protected $fillable = [
        'codvalecompramodelo',
        'codpessoafavorecido',
        'codpessoa',
        'observacoes',
        'aluno',
        'turma',
        'totalprodutos',
        'desconto',
        'total',
        'codtitulo',
        'inativo',
    ];
    protected $dates = [
        'inativo',
        'criacao',
        'alteracao',
    ];


    // Chaves Estrangeiras
    public function UsuarioCriacao()
    {
        return $this->belongsTo(Usuario::class, 'codusuario', 'codusuariocriacao');
    }

    public function UsuarioAlteracao()
    {
        return $this->belongsTo(Usuario::class, 'codusuario', 'codusuarioalteracao');
    }

    public function PessoaFavorecido()
    {
        return $this->belongsTo(Pessoa::class, 'codpessoa', 'codpessoafavorecido');
    }

    public function Pessoa()
    {
        return $this->belongsTo(Pessoa::class, 'codpessoa', 'codpessoa');
    }

    public function ValeCompraModelo()
    {
        return $this->belongsTo(ValeCompraModelo::class, 'codvalecompramodelo', 'codvalecompramodelo');
    }

    public function Titulo()
    {
        return $this->belongsTo(Titulo::class, 'codtitulo', 'codtitulo');
    }


    // Tabelas Filhas
    public function ValeCompraFormaPagamentoS()
    {
        return $this->hasMany(ValeCompraFormaPagamento::class, 'codvalecompra', 'codvalecompra');
    }

    public function ValeCompraProdutoBarraS()
    {
        return $this->hasMany(ValeCompraProdutoBarra::class, 'codvalecompra', 'codvalecompra');
    }


}