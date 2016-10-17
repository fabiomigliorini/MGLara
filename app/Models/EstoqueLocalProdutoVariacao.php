<?php

namespace MGLara\Models;

/**
 * Campos
 * @property  bigint                         $codestoquelocalprodutovariacao     NOT NULL DEFAULT nextval('tblestoquelocalprodutovariacao_codestoquelocalprodutovariacao_seq'::regclass)
 * @property  bigint                         $codestoquelocal                    NOT NULL
 * @property  bigint                         $codprodutovariacao                 NOT NULL
 * @property  bigint                         $corredor                           
 * @property  bigint                         $prateleira                         
 * @property  bigint                         $coluna                             
 * @property  bigint                         $bloco                              
 * @property  timestamp                      $alteracao                          
 * @property  bigint                         $codusuarioalteracao                
 * @property  timestamp                      $criacao                            
 * @property  bigint                         $codusuariocriacao                  
 *
 * Chaves Estrangeiras
 * @property  EstoqueLocal                   $EstoqueLocal                  
 * @property  ProdutoVariacao                $ProdutoVariacao
 * @property  Usuario                        $UsuarioAlteracao
 * @property  Usuario                        $UsuarioCriacao
 *
 * Tabelas Filhas
 * @property  Estoquesaldo[]                 $EstoquesaldoS
 */

class EstoqueLocalProdutoVariacao extends MGModel
{
    protected $table = 'tblestoquelocalprodutovariacao';
    protected $primaryKey = 'codestoquelocalprodutovariacao';
    protected $fillable = [
        'codestoquelocal',
        'codprodutovariacao',
        'corredor',
        'prateleira',
        'coluna',
        'bloco',
    ];
    protected $dates = [
        'alteracao',
        'criacao',
    ];


    // Chaves Estrangeiras
    public function EstoqueLocal()
    {
        return $this->belongsTo(EstoqueLocal::class, 'codestoquelocal', 'codestoquelocal');
    }

    public function ProdutoVariacao()
    {
        return $this->belongsTo(ProdutoVariacao::class, 'codprodutovariacao', 'codprodutovariacao');
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
    public function EstoqueSaldoS()
    {
        return $this->hasMany(EstoqueSaldo::class, 'codestoquelocalprodutovariacao', 'codestoquelocalprodutovariacao');
    }
    
    public static function buscaOuCria($codprodutovariacao, $codestoquelocal)
    {
        $elpv = self::where('codprodutovariacao', $codprodutovariacao)->where('codestoquelocal', $codestoquelocal)->first();
        if ($elpv == false)
        {
            $elpv = new EstoqueLocalProdutoVariacao;
            $elpv->codprodutovariacao = $codprodutovariacao;
            $elpv->codestoquelocal = $codestoquelocal;
            $elpv->save();
        }
        return $elpv;
    }


}
