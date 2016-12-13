<?php

namespace MGLara\Models;

/**
 * Campos
 * @property  bigint                         $codfilial                          NOT NULL DEFAULT nextval('tblfilial_codfilial_seq'::regclass)
 * @property  bigint                         $codempresa                         
 * @property  bigint                         $codpessoa                          
 * @property  varchar(20)                    $filial                             NOT NULL
 * @property  boolean                        $emitenfe                           NOT NULL DEFAULT false
 * @property  varchar(100)                   $acbrnfemonitorcaminho              
 * @property  varchar(100)                   $acbrnfemonitorcaminhorede          
 * @property  timestamp                      $acbrnfemonitorbloqueado            
 * @property  bigint                         $acbrnfemonitorcodusuario           
 * @property  numeric(7,0)                   $empresadominio                     
 * @property  varchar(20)                    $acbrnfemonitorip                   
 * @property  bigint                         $acbrnfemonitorporta                
 * @property  varchar(500)                   $odbcnumeronotafiscal               
 * @property  smallint                       $crt                                NOT NULL DEFAULT 1
 * @property  timestamp                      $alteracao                          
 * @property  bigint                         $codusuarioalteracao                
 * @property  timestamp                      $criacao                            
 * @property  bigint                         $codusuariocriacao                  
 * @property  varchar(32)                    $nfcetoken                          
 * @property  varchar(6)                     $nfcetokenid                        
 * @property  smallint                       $nfeambiente                        NOT NULL DEFAULT 2
 * @property  varchar(50)                    $senhacertificado                   
 * @property  bigint                         $ultimonsu                          
 * @property  varchar(200)                   $tokenibpt                          
 *
 * Chaves Estrangeiras
 * @property  Empresa                        $Empresa                       
 * @property  Pessoa                         $Pessoa                        
 * @property  Usuario                        $AcbrNfeMonitorUsuario                       
 * @property  Usuario                        $UsuarioAlteracao
 * @property  Usuario                        $UsuarioCriacao
 *
 * Tabelas Filhas
 * @property  Portador[]                     $PortadorS
 * @property  EstoqueLocal[]                 $EstoqueLocalS
 * @property  Ecf[]                          $EcfS
 * @property  Negocio[]                      $NegocioS
 * @property  NfeTerceiro[]                  $NfeTerceiroS
 * @property  NotaFiscal[]                   $NotaFiscalS
 * @property  Titulo[]                       $TituloS
 * @property  Usuario[]                      $UsuarioS
 * @property  ValeCompra[]                   $ValeCompraS
 */

class Filial extends MGModel
{
    const CRT_SIMPLES = 1;
    const CRT_SIMPLES_EXCESSO = 2;
    const CRT_REGIME_NORMAL = 3;

    const NFEAMBIENTE_PRODUCAO = 1;
    const NFEAMBIENTE_HOMOLOGACAO = 2;
    
    protected $table = 'tblfilial';
    protected $primaryKey = 'codfilial';
    protected $fillable = [
        'codempresa',
        'codpessoa',
        'filial',
        'emitenfe',
        'acbrnfemonitorcaminho',
        'acbrnfemonitorcaminhorede',
        'acbrnfemonitorbloqueado',
        'acbrnfemonitorcodusuario',
        'empresadominio',
        'acbrnfemonitorip',
        'acbrnfemonitorporta',
        'odbcnumeronotafiscal',
        'crt',
        'nfcetoken',
        'nfcetokenid',
        'nfeambiente',
        'senhacertificado',
        'ultimonsu',
        'tokenibpt',
    ];
    protected $dates = [
        'acbrnfemonitorbloqueado',
        'alteracao',
        'criacao',
    ];

    public function validate() {
        
        $this->_regrasValidacao = [
            'filial' => 'required|min:5', 
        ];
    
        $this->_mensagensErro = [
            'filial.required' => 'Preencha o campo filial',
        ];
        
        return parent::validate();
    }  

    // Chaves Estrangeiras
    public function Empresa()
    {
        return $this->belongsTo(Empresa::class, 'codempresa', 'codempresa');
    }

    public function Pessoa()
    {
        return $this->belongsTo(Pessoa::class, 'codpessoa', 'codpessoa');
    }

    public function AcbrNfeMonitorUsuario()
    {
        return $this->belongsTo(Usuario::class, 'codusuario', 'acbrnfemonitorcodusuario');
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
    public function PortadorS()
    {
        return $this->hasMany(Portador::class, 'codfilial', 'codfilial');
    }

    public function EstoqueLocalS()
    {
        return $this->hasMany(EstoqueLocal::class, 'codfilial', 'codfilial');
    }

    #public function Estoquesaldo_2013_2014S()
    #{
    #    return $this->hasMany(Estoquesaldo_2013_2014::class, 'codfilial', 'codfilial');
    #}

    #public function GrupousuariousuarioS()
    #{
    #    return $this->hasMany(Grupousuariousuario::class, 'codfilial', 'codfilial');
    #}

    public function EcfS()
    {
        return $this->hasMany(Ecf::class, 'codfilial', 'codfilial');
    }

    public function NegocioS()
    {
        return $this->hasMany(Negocio::class, 'codfilial', 'codfilial');
    }

    public function NfeTerceiroS()
    {
        return $this->hasMany(NfeTerceiro::class, 'codfilial', 'codfilial');
    }

    public function NotaFiscalS()
    {
        return $this->hasMany(NotaFiscal::class, 'codfilial', 'codfilial');
    }

    public function TituloS()
    {
        return $this->hasMany(Titulo::class, 'codfilial', 'codfilial');
    }

    public function UsuarioS()
    {
        return $this->hasMany(Usuario::class, 'codfilial', 'codfilial');
    }

    public function ValeCompraS()
    {
        return $this->hasMany(ValeCompra::class, 'codfilial');
    }
    
    public function scopeFilial($query, $filial)
    {
        if (trim($filial) != "")
        {
            $query->where('filial', "ILIKE", "%$filial%");
        }
    }     
}
