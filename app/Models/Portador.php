<?php

namespace MGLara\Models;


/**
 * Campos
 * @property  bigint                         $codportador                        NOT NULL DEFAULT nextval('tblportador_codportador_seq'::regclass)
 * @property  varchar(50)                    $portador                           
 * @property  bigint                         $codbanco                           
 * @property  bigint                         $agencia                            
 * @property  integer                        $agenciadigito                      
 * @property  bigint                         $conta                              
 * @property  integer                        $contadigito                        
 * @property  boolean                        $emiteboleto                        NOT NULL DEFAULT false
 * @property  bigint                         $codfilial                          
 * @property  numeric(20,0)                  $convenio                           
 * @property  varchar(100)                   $diretorioremessa                   
 * @property  varchar(100)                   $diretorioretorno                   
 * @property  integer                        $carteira                           
 * @property  timestamp                      $alteracao                          
 * @property  bigint                         $codusuarioalteracao                
 * @property  timestamp                      $criacao                            
 * @property  bigint                         $codusuariocriacao                  
 *
 * Chaves Estrangeiras
 * @property  Banco                          $Banco                         
 * @property  Filial                         $Filial                        
 * @property  Usuario                        $UsuarioAlteracao
 * @property  Usuario                        $UsuarioCriacao
 *
 * Tabelas Filhas
 * @property  BoletoRetorno[]                $BoletoRetornoS
 * @property  Cobranca[]                     $CobrancaS
 * @property  LiquidacaoTitulo[]             $LiquidacaoTituloS
 * @property  MovimentoTitulo[]              $MovimentoTituloS
 * @property  Titulo[]                       $TituloS
 * @property  Usuario[]                      $UsuarioS
 * @property  ChequeRepasse[]                $ChequeRepasseS
 */

class Portador extends MGModel
{
    const CARTEIRA = 999;
    protected $table = 'tblportador';
    protected $primaryKey = 'codportador';
    protected $fillable = [
        'portador',
        'codbanco',
        'agencia',
        'agenciadigito',
        'conta',
        'contadigito',
        'emiteboleto',
        'codfilial',
        'convenio',
        'diretorioremessa',
        'diretorioretorno',
        'carteira',
    ];
    protected $dates = [
        'alteracao',
        'criacao',
    ];
    
    public function validate() {
        
        $this->_regrasValidacao = [
            'portador' => 'required|min:2',
            'agenciadigito' => 'integer',
            'contadigito' => 'integer',
            'carteira' => 'integer'
        ];
    
        $this->_mensagensErro = [
            'portador.required' => 'Preencha o campo Portador',
            'portador.min' => 'Portador deve ter no mínimo 2 caracteres',
            'agenciadigito.integer' => 'Dígito da agência deve ser um número',
            'contadigito.integer' => 'Dígito da conta deve ser um número',
            'carteira.integer' => 'Carteira deve ser um número'
        ];
        
        return parent::validate();
    }
    

    // Chaves Estrangeiras
    public function Banco()
    {
        return $this->belongsTo(Banco::class, 'codbanco', 'codbanco');
    }

    public function Filial()
    {
        return $this->belongsTo(Filial::class, 'codfilial', 'codfilial');
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
    public function BoletoRetornoS()
    {
        return $this->hasMany(BoletoRetorno::class, 'codportador', 'codportador');
    }

    public function CobrancaS()
    {
        return $this->hasMany(Cobranca::class, 'codportador', 'codportador');
    }

    public function LiquidacaoTituloS()
    {
        return $this->hasMany(LiquidacaoTitulo::class, 'codportador', 'codportador');
    }

    public function MovimentoTituloS()
    {
        return $this->hasMany(MovimentoTitulo::class, 'codportador', 'codportador');
    }

    public function TituloS()
    {
        return $this->hasMany(Titulo::class, 'codportador', 'codportador');
    }

    public function UsuarioS()
    {
        return $this->hasMany(Usuario::class, 'codportador', 'codportador');
    }

    public function ChequeRepasseS()
    {
        return $this->hasMany(ChequeRepasse::class, 'codportador', 'codportador');
    }
    
    // Buscas 
    public static function filterAndPaginate($codportador, $portador, $codbanco)
    {
        return Portador::codportador(numeroLimpo($codportador))
            ->portador($portador)
            ->codbanco($codbanco)
            ->orderBy('portador', 'ASC')
            ->paginate(20);
    }
    
    public function scopeCodportador($query, $codportador)
    {
        if (trim($codportador) === '')
            return;
        
        $query->where('codportador', $codportador);
    }
    
    public function scopePortador($query, $portador)
    {
        if (trim($portador) === '')
            return;
        
        $portador = explode(' ', $portador);
        foreach ($portador as $str)
            $query->where('portador', 'ILIKE', "%$str%");
    }
    
    public function scopeCodbanco($query, $codbanco)
    {
        if (trim($codbanco) === '')
            return;
        
        $query->where('codbanco', $codbanco);
    }    
}
