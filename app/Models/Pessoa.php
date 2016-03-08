<?php

namespace MGLara\Models;


/**
 * Campos
 * @property  bigint                         $codpessoa                          NOT NULL DEFAULT nextval('tblpessoa_codpessoa_seq'::regclass)
 * @property  varchar(100)                   $pessoa                             NOT NULL
 * @property  varchar(50)                    $fantasia                           NOT NULL
 * @property  date                           $inativo                            
 * @property  boolean                        $cliente                            NOT NULL DEFAULT false
 * @property  boolean                        $fornecedor                         NOT NULL DEFAULT false
 * @property  boolean                        $fisica                             NOT NULL DEFAULT false
 * @property  bigint                         $codsexo                            
 * @property  numeric(14,0)                  $cnpj                               
 * @property  varchar(20)                    $ie                                 
 * @property  boolean                        $consumidor                         NOT NULL DEFAULT true
 * @property  varchar(100)                   $contato                            
 * @property  bigint                         $codestadocivil                     
 * @property  varchar(100)                   $conjuge                            
 * @property  varchar(100)                   $endereco                           
 * @property  varchar(10)                    $numero                             
 * @property  varchar(50)                    $complemento                        
 * @property  bigint                         $codcidade                          
 * @property  varchar(50)                    $bairro                             
 * @property  varchar(8)                     $cep                                
 * @property  varchar(100)                   $enderecocobranca                   
 * @property  varchar(10)                    $numerocobranca                     
 * @property  varchar(50)                    $complementocobranca                
 * @property  bigint                         $codcidadecobranca                  
 * @property  varchar(50)                    $bairrocobranca                     
 * @property  varchar(8)                     $cepcobranca                        
 * @property  varchar(50)                    $telefone1                          
 * @property  varchar(50)                    $telefone2                          
 * @property  varchar(50)                    $telefone3                          
 * @property  varchar(100)                   $email                              
 * @property  varchar(100)                   $emailnfe                           
 * @property  varchar(100)                   $emailcobranca                      
 * @property  bigint                         $codformapagamento                  
 * @property  numeric(14,2)                  $credito                            
 * @property  boolean                        $creditobloqueado                   NOT NULL DEFAULT true
 * @property  varchar(255)                   $observacoes                        
 * @property  varchar(500)                   $mensagemvenda                      
 * @property  boolean                        $vendedor                           NOT NULL DEFAULT false
 * @property  varchar(30)                    $rg                                 
 * @property  numeric(4,2)                   $desconto                           
 * @property  smallint                       $notafiscal                         NOT NULL
 * @property  timestamp                      $alteracao                          
 * @property  bigint                         $codusuarioalteracao                
 * @property  timestamp                      $criacao                            
 * @property  bigint                         $codusuariocriacao                  
 * @property  integer                        $toleranciaatraso                   NOT NULL DEFAULT 7
 * @property  bigint                         $codgrupocliente                    
 *
 * Chaves Estrangeiras
 * @property  Cidade                         $Cidade                        
 * @property  Cidade                         $CidadeCobranca                        
 * @property  EstadoCivil                    $EstadoCivil                   
 * @property  FormaPagamento                 $FormaPagamento                
 * @property  GrupoCliente                   $GrupoCliente                  
 * @property  Sexo                           $Sexo                          
 * @property  Usuario                        $UsuarioAlteracao
 * @property  Usuario                        $UsuarioCriacao
 *
 * Tabelas Filhas
 * @property  CobrancaHistorico[]            $CobrancaHistoricoS
 * @property  CupomFiscal[]                  $CupomFiscalS
 * @property  Filial[]                       $FilialS
 * @property  LiquidacaoTitulo[]             $LiquidacaoTituloS
 * @property  Negocio[]                      $NegocioPessoaS
 * @property  Negocio[]                      $NegocioVendedorS
 * @property  NfeTerceiro[]                  $NfeTerceiroS
 * @property  NotaFiscal[]                   $NotaFiscalS
 * @property  RegistroSpc[]                  $RegistroSpcS
 * @property  TituloAgrupamento[]            $TituloAgrupamentoS
 * @property  Titulo[]                       $TituloS
 * @property  Usuario[]                      $UsuarioS
 */

class Pessoa extends MGModel
{
    protected $table = 'tblpessoa';
    protected $primaryKey = 'codpessoa';
    protected $fillable = [
        'pessoa',
        'fantasia',
        'inativo',
        'cliente',
        'fornecedor',
        'fisica',
        'codsexo',
        'cnpj',
        'ie',
        'consumidor',
        'contato',
        'codestadocivil',
        'conjuge',
        'endereco',
        'numero',
        'complemento',
        'codcidade',
        'bairro',
        'cep',
        'enderecocobranca',
        'numerocobranca',
        'complementocobranca',
        'codcidadecobranca',
        'bairrocobranca',
        'cepcobranca',
        'telefone1',
        'telefone2',
        'telefone3',
        'email',
        'emailnfe',
        'emailcobranca',
        'codformapagamento',
        'credito',
        'creditobloqueado',
        'observacoes',
        'mensagemvenda',
        'vendedor',
        'rg',
        'desconto',
        'notafiscal',
        'toleranciaatraso',
        'codgrupocliente',
    ];
    protected $dates = [
        'inativo',
        'alteracao',
        'criacao',
    ];
    
    public function validate() {
        
        $this->_regrasValidacao = [
            'pessoa' => 'required|min:3', 
        ];
    
        $this->_mensagensErro = [
            'pessoa.required' => 'Preencha o campo pessoa',
        ];
        
        return parent::validate();
    }    
    
    /*
    public function Usuario()
    {
        return $this->hasMany(Usuario::class, 'codpessoa', 'codpessoa');
    }
    */
    

    // Chaves Estrangeiras
    public function Cidade()
    {
        return $this->belongsTo(Cidade::class, 'codcidade', 'codcidade');
    }

    public function CidadeCobranca()
    {
        return $this->belongsTo(Cidade::class, 'codcidade', 'codcidadecobranca');
    }

    public function EstadoCivil()
    {
        return $this->belongsTo(EstadoCivil::class, 'codestadocivil', 'codestadocivil');
    }

    public function FormaPagamento()
    {
        return $this->belongsTo(FormaPagamento::class, 'codformapagamento', 'codformapagamento');
    }

    public function GrupoCliente()
    {
        return $this->belongsTo(GrupoCliente::class, 'codgrupocliente', 'codgrupocliente');
    }

    public function Sexo()
    {
        return $this->belongsTo(Sexo::class, 'codsexo', 'codsexo');
    }

    public function UsuarioAlteracao()
    {
        return $this->belongsTo(Usuario::class, 'codusuario', 'codusuarioalteracao');
    }

    public function UsuarioCriacao()
    {
        return $this->belongsTo(Usuario::class, 'codusuario', 'codusuariocriacao');
    }


    // Tabelas Filhas
    public function CobrancaHistoricoS()
    {
        return $this->hasMany(CobrancaHistorico::class, 'codpessoa', 'codpessoa');
    }

    public function CupomfiscalS()
    {
        return $this->hasMany(CupomFiscal::class, 'codpessoa', 'codpessoa');
    }

    public function FilialS()
    {
        return $this->hasMany(Filial::class, 'codpessoa', 'codpessoa');
    }

    public function LiquidacaotituloS()
    {
        return $this->hasMany(LiquidacaoTitulo::class, 'codpessoa', 'codpessoa');
    }

    public function NegocioPessoaS()
    {
        return $this->hasMany(Negocio::class, 'codpessoa', 'codpessoa');
    }

    public function NegocioVendedorS()
    {
        return $this->hasMany(Negocio::class, 'codpessoa', 'codpessoavendedor');
    }

    public function NfeTerceiroS()
    {
        return $this->hasMany(NfeTerceiro::class, 'codpessoa', 'codpessoa');
    }

    public function NotaFiscalS()
    {
        return $this->hasMany(NotaFiscal::class, 'codpessoa', 'codpessoa');
    }

    public function RegistroSpcS()
    {
        return $this->hasMany(RegistroSpc::class, 'codpessoa', 'codpessoa');
    }

    public function TituloAgrupamentoS()
    {
        return $this->hasMany(TituloAgrupamento::class, 'codpessoa', 'codpessoa');
    }

    public function TituloS()
    {
        return $this->hasMany(Titulo::class, 'codpessoa', 'codpessoa');
    }

    public function UsuarioS()
    {
        return $this->hasMany(Usuario::class, 'codpessoa', 'codpessoa');
    }    
     


    
    public function scopePessoa($query, $pessoa)
    {
        if (trim($pessoa) != "")
        {
            $query->where('pessoa', "ILIKE", "%$pessoa%");
        }
    } 
}
