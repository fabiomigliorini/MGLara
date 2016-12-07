<?php

namespace MGLara\Models;

/**
 * Campos
 * @property  bigint                         $codformapagamento                  NOT NULL DEFAULT nextval('tblformapagamento_codformapagamento_seq'::regclass)
 * @property  varchar(50)                    $formapagamento                     NOT NULL
 * @property  boolean                        $boleto                             NOT NULL DEFAULT false
 * @property  boolean                        $fechamento                         NOT NULL DEFAULT false
 * @property  boolean                        $notafiscal                         NOT NULL DEFAULT false
 * @property  bigint                         $parcelas                           
 * @property  bigint                         $diasentreparcelas                  
 * @property  boolean                        $avista                             NOT NULL DEFAULT false
 * @property  varchar(5)                     $formapagamentoecf                  
 * @property  boolean                        $entrega                            NOT NULL DEFAULT false
 * @property  timestamp                      $alteracao                          
 * @property  bigint                         $codusuarioalteracao                
 * @property  timestamp                      $criacao                            
 * @property  bigint                         $codusuariocriacao                  
 * @property  boolean                        $valecompra                         NOT NULL DEFAULT false
 *
 * Chaves Estrangeiras
 * @property  Usuario                        $UsuarioAlteracao
 * @property  Usuario                        $UsuarioCriacao
 *
 * Tabelas Filhas
 * @property  ValeCompraFormaPagamento[]     $ValeCompraFormaPagamentoS
 * @property  NegocioFormaPagamento[]        $NegocioFormaPagamentoS
 * @property  Pessoa[]                       $PessoaS
 */

class FormaPagamento extends MGModel
{
    protected $table = 'tblformapagamento';
    protected $primaryKey = 'codformapagamento';
    protected $fillable = [
        'formapagamento',
        'boleto',
        'fechamento',
        'notafiscal',
        'parcelas',
        'diasentreparcelas',
        'avista',
        'formapagamentoecf',
        'entrega',
        'valecompra',
    ];
    protected $dates = [
        'alteracao',
        'criacao',
    ];

    public function validate() {
        $this->_regrasValidacao = [
            'formapagamento' => 'required|min:5', 
        ];    
        $this->_mensagensErro = [
            'formapagamento.required' => 'Forma de Pagamento não pode ser vazio.',
            'formapagamento.min' => 'Forma de Pagamento tem que ter mais de 4 caracteres.',
        ];
        return parent::validate();
    }    
    
    // Chaves Estrangeiras
    public function UsuarioAlteracao()
    {
        return $this->belongsTo(Usuario::class, 'codusuarioalteracao', 'codusuario');
    }

    public function UsuarioCriacao()
    {
        return $this->belongsTo(Usuario::class, 'codusuariocriacao', 'codusuario');
    }


    // Tabelas Filhas
    public function ValeCompraFormaPagamentoS()
    {
        return $this->hasMany(ValeCompraFormaPagamento::class, 'codformapagamento', 'codformapagamento');
    }
    
    public function NegocioFormaPagamentoS()
    {
        return $this->hasMany(NegocioFormaPagamento::class, 'codformapagamento', 'codformapagamento');
    }

    public function PessoaS()
    {
        return $this->hasMany(Pessoa::class, 'codformapagamento', 'codformapagamento');
    }
    
    // Buscas 
    public static function filterAndPaginate($codformapagamento, $formapagamento)
    {
        return formapagamento::codformapagamento(numeroLimpo($codformapagamento))
            ->formapagamento($formapagamento)
            ->orderBy('codformapagamento', 'ASC')
            ->paginate(20);
    }
    
    public function scopeCodformapagamento($query, $codcodformapagamento)
    {
        if (trim($codcodformapagamento) === '')
            return;
        
        $query->where('codformapagamento', $codcodformapagamento);
    }
    
    public function scopeFormapagamento($query, $formapagamento)
    {
        if (trim($formapagamento) === '')
            return;
        
        $formapagamento = explode(' ', $formapagamento);
        foreach ($formapagamento as $str)
            $query->where('formapagamento', 'ILIKE', "%$str%");
    } 
}

