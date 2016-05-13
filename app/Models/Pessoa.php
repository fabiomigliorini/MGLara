<?php

namespace MGLara\Models;

use DB;
use Carbon\Carbon;

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
    const NOTAFISCAL_TRATAMENTOPADRAO = 0;
    const NOTAFISCAL_SEMPRE = 1;
    const NOTAFISCAL_SOMENTE_FECHAMENTO = 2;
    const NOTAFISCAL_NUNCA = 9;

    const CONSUMIDOR = 1;
        
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
    
    public function getCobrancanomesmoenderecoAttribute()
    {
        if (
            ($this->enderecocobranca    <>  $this->endereco   ) or 
            ($this->numerocobranca      <>  $this->numero     ) or 
            ($this->complementocobranca <>  $this->complemento) or 
            ($this->bairrocobranca      <>  $this->bairro     ) or 
            ($this->codcidadecobranca   <>  $this->codcidade  ) or 
            ($this->cepcobranca         <>  $this->cep        ) 
           )
            return false;
        else
            return true;
    }    
    
    public function validate() {
        dd($this);
        $this->_regrasValidacao = [
            'pessoa' => 'required|min:5|max:100', 
            'fantasia' => 'required|min:5|max:50', 
            'contato' => 'max:100',
            'codgrupocliente' => 'required_if:cliente,true',
            'ie' => 'max:20',
            'rg' => 'max:30',
            'conjuge' => 'max:100',
            'numero' => 'required|max:10', 
            'email' => 'required|email|max:100', 
            'telefone1' => 'required|max:50', 
            'telefone2' => 'max:50', 
            'telefone3' => 'max:50', 
            'codcidade' => 'required', 
            'endereco' => 'required|max:100', 
            'bairro' => 'required|max:50', 
            'cep' => 'required|max:10',
            'complemento'=> 'max:50',
            'codcidadecobranca' => 'required', 
            'enderecocobranca' => 'required|max:100', 
            'numerocobranca' => 'required|max:10', 
            'bairrocobranca' => 'required', 
            'complementocobranca'=> 'max:50',
            'cepcobranca' => 'required|max:10',
            'emailcobranca' => 'email|max:100',
            'notafiscal' => 'required|numeric',
            'emailnfe' => 'email|max:100',
            'toleranciaatraso' => 'required|numeric',
            'observacoes' => 'max:10',
            'mensagemvenda' => 'max:500',
            'desconto' => 'numeric|max:50',
            'credito' => 'numeric|max:14',
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
        return $this->belongsTo(Cidade::class, 'codcidadecobranca', 'codcidade');
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

    public function LiquidacaoTituloS()
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
     
    public static function getNotaFiscalOpcoes()
    {
        return array(
            self::NOTAFISCAL_TRATAMENTOPADRAO => "Tratamento Padrão",
            self::NOTAFISCAL_SEMPRE => "Sempre",
            self::NOTAFISCAL_SOMENTE_FECHAMENTO => "Somente no Fechamento",
            self::NOTAFISCAL_NUNCA => "Nunca Emitir",
        );
    }

    public function getNotaFiscalRange()
    {
        return array(
            self::NOTAFISCAL_TRATAMENTOPADRAO,
            self::NOTAFISCAL_SEMPRE,
            self::NOTAFISCAL_SOMENTE_FECHAMENTO,
            self::NOTAFISCAL_NUNCA,
        );
    }
    
    public function getNotaFiscalDescricao()
    {
        $opcoes = $this->getNotaFiscalOpcoes();
        if (!isset($this->notafiscal))
            return null;

        return isset($opcoes[$this->notafiscal]) ? $opcoes[$this->notafiscal] : "Tipo Desconhecido ({$this->notafiscal})";
    }    
	
    public function totalTitulos()
    {
        $query = DB::select('
                SELECT SUM(saldo) AS saldo, MIN(vencimento) AS vencimento 
                FROM tbltitulo 
                WHERE codpessoa = :codpessoa AND saldo != 0', 
                ['codpessoa' => $this->codpessoa]
        )[0];

        $query->vencimentodias = 0;

        if ($query->vencimento) {
            $venc = Carbon::createFromFormat("Y-m-d", $query->vencimento);
            $hoje = Carbon::now();
            $query->vencimentodias = $dif = $hoje->diffInDays($venc, false);
        }

        return $query;
    }

    // Buscas 
    public static function filterAndPaginate($id, $pessoa, $cnpj, $email, $telefone, $inativo, $codcidade, $codgrupocliente)
    {
        return Pessoa::id(numeroLimpo($id))
            ->pessoa($pessoa)
            ->cnpj($cnpj)
            ->email($email)
            ->telefone($telefone)
            ->inativo($inativo)
            ->cidade($codcidade)
            ->grupocliente($codgrupocliente)
            ->orderBy('fantasia', 'ASC')
            ->paginate(20);
    }
    
    public function scopeId($query, $id)
    {
        if (trim($id) === '')
            return;
        
        $query->where('codpessoa', $codpessoa);
    }
    
    public function scopePessoa($query, $pessoa)
    {
        if (trim($pessoa) === '')
            return;
        
        $pessoa = explode(' ', $pessoa);
        foreach ($pessoa as $str)
            $query->where('fantasia', 'ILIKE', "%$str%");
            $query->where('pessoa', 'ILIKE', "%$str%");
    }
    
    public function scopeCnpj($query, $cnpj)
    {
        if (trim($cnpj) === '')
            return;
        
        $cnpj = explode(' ', $cnpj);
        foreach ($cnpj as $str)
            $query->where('cnpj', 'ILIKE', "%$str%");
    }
    
    public function scopeEmail($query, $email)
    {
        if (trim($email) === '')
            return;
        
        $email = explode(' ', $email);
        foreach ($email as $str)
            $query->where('email', 'ILIKE', "%$str%");
            $query->where('emailnfe', 'ILIKE', "%$str%");
            $query->where('emailcobranca', 'ILIKE', "%$str%");
    }
    
    public function scopeTelefone($query, $telefone)
    {
        if (trim($telefone) === '')
            return;
        
        $telefone = explode(' ', $telefone);
        foreach ($telefone as $str)
            $query->where('telefone1', 'ILIKE', "%$str%");
            $query->where('telefone2', 'ILIKE', "%$str%");
            $query->where('telefone3', 'ILIKE', "%$str%");
    }
    
    public function scopeInativo($query, $inativo)
    {
        if (trim($inativo) === '')
            $query->whereNull('inativo');
        
        if($inativo == 1)
            $query->whereNull('inativo');

        if($inativo == 2)
            $query->whereNotNull('inativo');
    }
    
    public function scopeCidade($query, $codcidade)
    {
        if (trim($codcidade) === '')
            return;
        
        $query->where('codcidade', $codcidade);
        $query->orWhere('codcidadecobranca', $codcidade);
    }    
    
    public function scopeGrupocliente($query, $codgrupocliente)
    {
        if (trim($codgrupocliente) === '')
            return;
        
        $query->where('codgrupocliente', $codgrupocliente);
    }
    
    public function scopeCobrancaHistorico($query)
    {
        
    }
}
