<?php

namespace MGLara\Models;
use Illuminate\Support\Facades\DB;

/**
 * Campos
 * @property  bigint                         $codestoquemovimento                NOT NULL DEFAULT nextval('tblestoquemovimento_codestoquemovimento_seq'::regclass)
 * @property  bigint                         $codestoquemovimentotipo            NOT NULL
 * @property  numeric(14,3)                  $entradaquantidade                  
 * @property  numeric(14,2)                  $entradavalor                       
 * @property  numeric(14,3)                  $saidaquantidade                    
 * @property  numeric(14,2)                  $saidavalor                         
 * @property  bigint                         $codnegocioprodutobarra             
 * @property  bigint                         $codnotafiscalprodutobarra          
 * @property  bigint                         $codestoquesaldoconferencia
 * @property  bigint                         $codestoquemes                      NOT NULL
 * @property  boolean                        $manual                             NOT NULL DEFAULT false
 * @property  timestamp                      $data                               NOT NULL
 * @property  timestamp                      $alteracao                          
 * @property  bigint                         $codusuarioalteracao                
 * @property  timestamp                      $criacao                            
 * @property  bigint                         $codusuariocriacao                  
 * @property  bigint                         $codestoquemovimentoorigem          
 *
 * Chaves Estrangeiras
 * @property  EstoqueMovimento               $EstoqueMovimentoOrigem             
 * @property  EstoqueMovimentoTipo           $EstoqueMovimentoTipo          
 * @property  NegocioProdutoBarra            $NegocioProdutoBarra           
 * @property  NotaFiscalProdutoBarra         $NotaFiscalProdutoBarra        
 * @property  EstoqueSaldoConferencia        $EstoqueSaldoConferencia
 * @property  Usuario                        $UsuarioAlteracao
 * @property  Usuario                        $UsuarioCriacao
 * @property  EstoqueMes                     $EstoqueMes                    
 *
 * Tabelas Filhas
 * @property  EstoqueMovimento[]             $EstoqueMovimentoS
 */

class EstoqueMovimento extends MGModel
{
    protected $table = 'tblestoquemovimento';
    protected $primaryKey = 'codestoquemovimento';
    protected $fillable = [
        'codestoquemovimentotipo',
        'entradaquantidade',
        'entradavalor',
        'saidaquantidade',
        'saidavalor',
        'codnegocioprodutobarra',
        'codnotafiscalprodutobarra',
        'codestoquemes',
        'manual',
        'data',
        'codestoquemovimentoorigem',
    ];    
    protected $dates = [
        'data',
        'alteracao',
        'criacao',
    ];
        
    // Chaves Estrangeiras
    public function EstoqueMovimentoOrigem()
    {
        return $this->belongsTo(EstoqueMovimento::class, 'codestoquemovimentoorigem', 'codestoquemovimento');
    }

    public function EstoqueMovimentoTipo()
    {
        return $this->belongsTo(EstoqueMovimentoTipo::class, 'codestoquemovimentotipo', 'codestoquemovimentotipo');
    }

    public function NegocioProdutoBarra()
    {
        return $this->belongsTo(NegocioProdutoBarra::class, 'codnegocioprodutobarra', 'codnegocioprodutobarra');
    }

    public function NotaFiscalProdutoBarra()
    {
        return $this->belongsTo(NotaFiscalProdutoBarra::class, 'codnotafiscalprodutobarra', 'codnotafiscalprodutobarra');
    }

    public function UsuarioAlteracao()
    {
        return $this->belongsTo(Usuario::class, 'codusuarioalteracao', 'codusuario');
    }

    public function UsuarioCriacao()
    {
        return $this->belongsTo(Usuario::class, 'codusuariocriacao', 'codusuario');
    }

    public function EstoqueMes()
    {
        return $this->belongsTo(EstoqueMes::class, 'codestoquemes', 'codestoquemes');
    }

    // Tabelas Filhas
    public function EstoqueMovimentoS()
    {
        return $this->hasMany(EstoqueMovimento::class, 'codestoquemovimentoorigem', 'codestoquemovimento');
    }
    
    public function validate() {
        $i = 0; $validate = 'numeric';
        if(!empty($this->entradaquantidade)) {
            $i++;
        }
        if (!empty($this->entradavalor)) {
            $i++;
        }
        if (!empty($this->saidaquantidade)) {
            $i++;
        }
        if (!empty($this->saidavalor)) {
            $i++;
        }
        if ($i == 0) {
            $validate = 'required|numeric';
        }
        $this->_regrasValidacao = [
            'entradaquantidade' => $validate,
            'entradavalor' => $validate,
            'saidaquantidade' => $validate,
            'saidavalor' => $validate,
        ];

        $this->_mensagensErro = [
            'entradaquantidade.required' => 'Pelo menos 1 dos valores de entrada e/ou saída dever ser preechido',
            'entradavalor.required' => 'Pelo menos 1 dos valores de entrada e/ou saída dever ser preechido',
            'saidaquantidade.required' => 'Pelo menos 1 dos valores de entrada e/ou saída dever ser preechido',
            'saidavalor.required' => 'Pelo menos 1 dos valores de entrada e/ou saída dever ser preechido',
        ];
        
        return parent::validate();
    }
    
    
    public function save(array $options = Array())
    {
        
        /*
        $this->EstoqueMes->entradaquantidade += $this->entradaquantidade - $this->original['entradaquantidade'];
        $this->EstoqueMes->entradavalor += $this->entradavalor - $this->original['entradavalor'];
        
        $this->EstoqueMes->saidaquantidade += $this->saidaquantidade - $this->original['saidaquantidade'];
        $this->EstoqueMes->saidavalor += $this->saidavalor - $this->original['saidavalor'];
        
        $this->EstoqueMes->saldoquantidade = $this->EstoqueMes->entradaquantidade - $this->EstoqueMes->saidaquantidade;
        $this->EstoqueMes->saldovalor = $this->EstoqueMes->entradavalor - $this->EstoqueMes->saidavalor;
        
        if ($this->EstoqueMes->saldoquantidade <> 0)
            $this->EstoqueMes->customedio = $this->EstoqueMes->saldovalor / $this->EstoqueMes->saldoquantidade;
        */
        
        $ret = parent::save($options);

        /*
        if ($ret)
            $ret = $this->EstoqueMes->save();
        */
        
        return $ret;
    }
   

    public function scopeNegativos($query, $negativos)
    {
        if (trim($negativos) === '')
            return;
        
        if($negativos == 0)
            return;

        if($negativos == 1)
            return $query->where(DB::raw('COALESCE(entradaquantidade,0) - COALESCE(saidaquantidade,0)'), '<', 0);
            //return $query->whereRaw('(entradaquantidade - saidaquantidade) < 0');

    }

}
