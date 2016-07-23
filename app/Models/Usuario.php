<?php

namespace MGLara\Models;

/**
 * Campos
 * @property  bigint                         $codusuario                         NOT NULL DEFAULT nextval('tblusuario_codusuario_seq'::regclass)
 * @property  varchar(50)                    $usuario                            NOT NULL
 * @property  varchar(100)                   $senha                              
 * @property  bigint                         $codecf                             
 * @property  bigint                         $codfilial                          
 * @property  bigint                         $codoperacao                        NOT NULL
 * @property  bigint                         $codpessoa                          
 * @property  varchar(100)                   $impressoratelanegocio              
 * @property  bigint                         $codportador                        
 * @property  varchar(50)                    $impressoratermica                  
 * @property  timestamp                      $alteracao                          
 * @property  bigint                         $codusuarioalteracao                
 * @property  timestamp                      $criacao                            
 * @property  bigint                         $codusuariocriacao                  
 * @property  timestamp                      $ultimoacesso                       
 * @property  date                           $inativo                            
 * @property  varchar(50)                    $impressoramatricial                
 * @property  varchar(100)                   $remember_token                     
 *
 * Chaves Estrangeiras
 * @property  Ecf                            $Ecf                           
 * @property  Filial                         $Filial                        
 * @property  Operacao                       $Operacao                      
 * @property  Pessoa                         $Pessoa                        
 * @property  Portador                       $Portador                      
 * @property  Usuario                        $UsuarioAlteracao
 * @property  Usuario                        $UsuarioCriacao
 *
 * Tabelas Filhas
 * @property  Cest[]                         $CestAlteracaoS
 * @property  Cest[]                         $CestCriacaoS
 * @property  EstoqueLocal[]                 $EstoqueLocalAlteracaoS
 * @property  EstoqueLocal[]                 $EstoqueLocalCriacaoS
 * @property  EstoqueMes[]                   $EstoqueMesAlteracaoS
 * @property  EstoqueMes[]                   $EstoqueMesCriacaoS
 * @property  EstoqueMovimento[]             $EstoqueMovimentoAlteracaoS
 * @property  EstoqueMovimento[]             $EstoqueMovimentoCriacaoS
 * @property  EstoqueSaldoConferencia[]      $EstoqueSaldoConferenciaS
 * @property  EstoqueSaldoConferencia[]      $EstoqueSaldoConferenciaS
 * @property  FamiliaProduto[]               $FamiliaprodutoS
 * @property  FamiliaProduto[]               $FamiliaprodutoS
 * @property  GrupoUsuario[]                 $GrupoUsuarioAlteracaoS
 * @property  GrupoUsuario[]                 $GrupoUsuarioCriacaoS
 * @property  Permissao[]                    $PermissaoAlteracaoS
 * @property  Permissao[]                    $PermissaoCriacaoS
 * @property  RegulamentoIcmsStMt[]          $RegulamentoIcmsStMtAlteracaoS
 * @property  RegulamentoIcmsStMt[]          $RegulamentoIcmsStMtCriacaoS
 * @property  Banco[]                        $BancoAlteracaoS
 * @property  Banco[]                        $BancoCriacaoS
 * @property  BaseRemota[]                   $BaseRemotaAlteracaoS
 * @property  BaseRemota[]                   $BaseRemotaCriacaoS
 * @property  BoletoMotivoOcorrencia[]       $BoletoMotivoOcorrenciaAlteracaoS
 * @property  BoletoMotivoOcorrencia[]       $BoletoMotivoOcorrenciaCriacaoS
 * @property  BoletoRetorno[]                $BoletoRetornoAlteracaoS
 * @property  BoletoRetorno[]                $BoletoRetornoCriacaoS
 * @property  BoletoTipoOcorrencia[]         $BoletoTipoOcorrenciaAlteracaoS
 * @property  BoletoTipoOcorrencia[]         $BoletoTipoOcorrenciaCriacaoS
 * @property  Cfop[]                         $CfopAlteracaoS
 * @property  Cfop[]                         $CfopCriacaoS
 * @property  Cheque[]                       $ChequeAlteracaoS
 * @property  Cheque[]                       $ChequeCriacaoS
 * @property  ChequeEmitente[]               $ChequeEmitenteAlteracaoS
 * @property  ChequeEmitente[]               $ChequeEmitenteCriacaoS
 * @property  Cidade[]                       $CidadeAlteracaoS
 * @property  Cidade[]                       $CidadeCriacaoS
 * @property  Cobranca[]                     $CobrancaAlteracaoS
 * @property  Cobranca[]                     $CobrancaCriacaoS
 * @property  CobrancaHistorico[]            $CobrancaHistoricoAlteracaoS
 * @property  CobrancaHistorico[]            $CobrancaHistoricoCriacaoS
 * @property  CobrancaHistoricoTitulo[]      $CobrancaHistoricoTituloAlteracaoS
 * @property  CobrancaHistoricoTitulo[]      $CobrancaHistoricoTituloCriacaoS
 * @property  Codigo[]                       $CodigoAlteracaoS
 * @property  Codigo[]                       $CodigoCriacaoS
 * @property  ContaContabil[]                $ContaContabilAlteracaoS
 * @property  ContaContabil[]                $ContaContabilCriacaoS
 * @property  CupomFiscal[]                  $CupomFiscalAlteracaoS
 * @property  CupomFiscal[]                  $CupomFiscalCriacaoS
 * @property  CupomFiscalProdutoBarra[]      $CupomFiscalProdutoBarraAlteracaoS
 * @property  CupomFiscalProdutoBarra[]      $CupomFiscalProdutoBarraCriacaoS
 * @property  Ecf[]                          $EcfAlteracaoS
 * @property  Ecf[]                          $EcfCriacaoS
 * @property  Ecf[]                          $EcfS
 * @property  EcfReducaoz[]                  $EcfReducaozAlteracaoS
 * @property  EcfReducaoz[]                  $EcfReducaozCriacaoS
 * @property  Empresa[]                      $EmpresaAlteracaoS
 * @property  Empresa[]                      $EmpresaCriacaoS
 * @property  EstadoCivil[]                  $EstadoCivilAlteracaoS
 * @property  EstadoCivil[]                  $EstadoCivilCriacaoS
 * @property  Estado[]                       $EstadoAlteracaoS
 * @property  Estado[]                       $EstadoCriacaoS
 * @property  EstoqueMovimentoTipo[]         $EstoqueMovimentoTipoAlteracaoS
 * @property  EstoqueMovimentoTipo[]         $EstoqueMovimentoTipoCriacaoS
 * @property  EstoqueSaldo[]                 $EstoqueSaldoAlteracaoS
 * @property  EstoqueSaldo[]                 $EstoqueSaldoCriacaoS
 * @property  Filial[]                       $FilialAcbrNfeMonitorS
 * @property  Filial[]                       $FilialAlteracaoS
 * @property  Filial[]                       $FilialCriacaoS
 * @property  FormaPagamento[]               $FormaPagamentoAlteracaoS
 * @property  FormaPagamento[]               $FormaPagamentoCriacaiS
 * @property  GrupoCliente[]                 $GrupoClienteAlteracaoS
 * @property  GrupoCliente[]                 $GrupoClienteCriacaoS
 * @property  GrupoProduto[]                 $GrupoProdutoAlteracaoS
 * @property  GrupoProduto[]                 $GrupoProdutoCriacaoS
 * @property  Ibptax[]                       $IbptaxAlteracaoS
 * @property  Ibptax[]                       $IbptaxCriacaoS
 * @property  LiquidacaoTitulo[]             $LiquidacaoTituloAlteracaoS
 * @property  LiquidacaoTitulo[]             $LiquidacaoTituloCriacaoS
 * @property  LiquidacaoTitulo[]             $LiquidacaoTituloEstornoS
 * @property  LiquidacaoTitulo[]             $LiquidacaoTituloS
 * @property  Marca[]                        $MarcaAlteracaoS
 * @property  Marca[]                        $MarcaCriacaoS
 * @property  Menu[]                         $MenuAlteracaoS
 * @property  Menu[]                         $MenuCriacaoS
 * @property  MovimentoTitulo[]              $MovimentoTituloAlteracaoS
 * @property  MovimentoTitulo[]              $MovimentoTituloCriacaoS
 * @property  NaturezaOperacao[]             $NaturezaOperacaoAlteracaoS
 * @property  NaturezaOperacao[]             $NaturezaOperacaoCriacaoS
 * @property  Ncm[]                          $NcmAlteracaoS
 * @property  Ncm[]                          $NcmCriacaoS
 * @property  Negocio[]                      $NegocioAcertoEntregaS
 * @property  Negocio[]                      $NegocioAlteracaoS
 * @property  Negocio[]                      $NegocioCriacaoS
 * @property  Negocio[]                      $NegocioS
 * @property  NegocioFormaPagamento[]        $NegocioFormaPagamentoAlteracaoS
 * @property  NegocioFormaPagamento[]        $NegocioFormaPagamentoCriacaoS
 * @property  NegocioProdutoBarra[]          $NegocioProdutoBarraAlteracaoS
 * @property  NegocioProdutoBarra[]          $NegocioProdutoBarraCriacaoS
 * @property  NegocioStatus[]                $NegocioStatusAlteracaoS
 * @property  NegocioStatus[]                $NegocioStatusCriacaoS
 * @property  NfeTerceiro[]                  $NfeTerceiroAlteracaoS
 * @property  NfeTerceiro[]                  $NfeTerceiroCriacaoS
 * @property  NfeTerceiroDuplicata[]         $NfeTerceiroDuplicataAlteracaoS
 * @property  NfeTerceiroDuplicata[]         $NfeTerceiroDuplicataCriacaoS
 * @property  NfeTerceiroItem[]              $NfeTerceiroItemAlteracaoS
 * @property  NfeTerceiroItem[]              $NfeTerceiroItemCriacaoS
 * @property  NotaFiscalCartaCorrecao[]      $NotaFiscalCartaCorrecaoAlteracaoS
 * @property  NotaFiscalCartaCorrecao[]      $NotaFiscalCartaCorrecaoCriacaoS
 * @property  NotaFiscal[]                   $NotaFiscaAlteracaolS
 * @property  NotaFiscal[]                   $NotaFiscalCriacaoS
 * @property  NotaFiscalDuplicatas[]         $NotaFiscalDuplicatasAlteracaoS
 * @property  NotaFiscalDuplicatas[]         $NotaFiscalDuplicatasCriacaoS
 * @property  NotaFiscalProdutoBarra[]       $NotaFiscalProdutoBarraAlteracaoS
 * @property  NotaFiscalProdutoBarra[]       $NotaFiscalProdutoBarraCriacaoS
 * @property  NotaFiscalReferenciada[]       $NotaFiscalReferenciadaAlteracaoS
 * @property  NotaFiscalReferenciada[]       $NotaFiscalReferenciadaCriacaoS
 * @property  Operacao[]                     $OperacaoAlteracaoS
 * @property  Operacao[]                     $OperacaoCriacaoS
 * @property  Pais[]                         $PaisAlteracaoS
 * @property  Pais[]                         $PaisCriacaoS
 * @property  ParametrosGerais[]             $ParametrosGeraisAlteracaoS
 * @property  ParametrosGerais[]             $ParametrosGeraisCriacaoS
 * @property  Pessoa[]                       $PessoaAlteracaoS
 * @property  Pessoa[]                       $PessoaCriacaoS
 * @property  Portador[]                     $PortadorAlteracaoS
 * @property  Portador[]                     $PortadorCriacaoS
 * @property  ProdutoBarra[]                 $ProdutoBarraAlteracaoS
 * @property  ProdutoBarra[]                 $ProdutoBarraCriacaoS
 * @property  Produto[]                      $ProdutoAlteracaoS
 * @property  Produto[]                      $ProdutoCriacaoS
 * @property  ProdutoEmbalagem[]             $ProdutoEmbalagemAlteracaoS
 * @property  ProdutoEmbalagem[]             $ProdutoEmbalagemCriacaoS
 * @property  ProdutoHistoricoPreco[]        $ProdutoHistoricoPrecoAlteracaoS
 * @property  ProdutoHistoricoPreco[]        $ProdutoHistoricoPrecoCriacaoS
 * @property  RegistroSpc[]                  $RegistroSpcAlteracaoS
 * @property  RegistroSpc[]                  $RegistroSpcCriacaoS
 * @property  Sexo[]                         $SexoAlteracaoS
 * @property  Sexo[]                         $SexoCriacaoS
 * @property  SubGrupoProduto[]              $SubGrupoProdutoAlteracaoS
 * @property  SubGrupoProduto[]              $SubGrupoProdutoCriacaoS
 * @property  TipoMovimentoTitulo[]          $TipoMovimentoTituloAlteracaoS
 * @property  TipoMovimentoTitulo[]          $TipoMovimentoTituloCriacaoS
 * @property  TipoProduto[]                  $TipoProdutoAlteracaoS
 * @property  TipoProduto[]                  $TipoProdutoCriacaoS
 * @property  TipoTitulo[]                   $TipoTituloAlteracaoS
 * @property  TipoTitulo[]                   $TipoTituloCriacaoS
 * @property  TituloAgrupamento[]            $TituloAgrupamentoAlteracaoS
 * @property  TituloAgrupamento[]            $TituloAgrupamentoCriacaoS
 * @property  Titulo[]                       $TituloAlteracaoS
 * @property  Titulo[]                       $TituloCriacaoS
 * @property  Tributacao[]                   $TributacaoAlteracaoS
 * @property  Tributacao[]                   $TributacaoCriacaoS
 * @property  TributacaoNaturezaOperacao[]   $TributacaoNaturezaOperacaoAlteracaoS
 * @property  TributacaoNaturezaOperacao[]   $TributacaoNaturezaOperacaoCriacaoS
 * @property  UnidadeMedida[]                $UnidadeMedidaAlteracaoS
 * @property  UnidadeMedida[]                $UnidadeMedidaCriacaoS
 * @property  Usuario[]                      $UsuarioAlteracaoS
 * @property  Usuario[]                      $UsuarioCriacaoS
 */

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use MGLara\Models\MGModel;


class Usuario extends MGModel implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    protected $table = 'tblusuario';
    protected $primaryKey = 'codusuario';
    protected $fillable = [
        'usuario',
        'senha',
        'codecf',
        'codfilial',
        'codoperacao',
        'codpessoa',
        'impressoratelanegocio',
        'codportador',
        'impressoratermica',
        'ultimoacesso',
        'inativo',
        'impressoramatricial',
        'remember_token',
    ];
    
    protected $dates = [
        'alteracao',
        'criacao',
        'ultimoacesso',
        'inativo',
    ];

    protected $hidden = ['senha', 'remember_token'];
    
    
    public function validate() {
        
    	if ($this->codusuario)
    		$unique_usuario = "unique:tblusuario,usuario,$this->codusuario,codusuario|required|min:5";
    	else
    		$unique_usuario = "unique:tblusuario,usuario|required|min:5";    	
    	
        $this->_regrasValidacao = [
            'usuario' => $unique_usuario, 
            'senha' => 'required_if:codusuario,null|min:6', 
            'codoperacao' => 'required', 
            'impressoramatricial' => 'required', 
            'impressoratermica' => 'required', 
        ];
    
        $this->_mensagensErro = [
            'usuario.required' => 'O campo usuário não pode ser vazio',
            'usuario.min' => 'O campo usuário deve ter mais de 4 caracteres',
        ];
        
        return parent::validate();
    }

    public function getAuthPassword(){
        return $this->senha;
    }

    
    // Chaves Estrangeiras
    public function Ecf()
    {
        return $this->belongsTo(Ecf::class, 'codecf', 'codecf');
    }

    public function Filial()
    {
        return $this->belongsTo(Filial::class, 'codfilial', 'codfilial');
    }

    public function Operacao()
    {
        return $this->belongsTo(Operacao::class, 'codoperacao', 'codoperacao');
    }

    public function Pessoa()
    {
        return $this->belongsTo(Pessoa::class, 'codpessoa', 'codpessoa');
    }

    public function Portador()
    {
        return $this->belongsTo(Portador::class, 'codportador', 'codportador');
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

    public function CestAlteracaoS()
    {
        return $this->hasMany(Cest::class, 'codusuario', 'codusuarioalteracao');
    }

    public function CestCriacaoS()
    {
        return $this->hasMany(Cest::class, 'codusuario', 'codusuariocriacao');
    }

    public function EstoqueLocalAlteracaoS()
    {
        return $this->hasMany(EstoqueLocal::class, 'codusuario', 'codusuarioalteracao');
    }

    public function EstoqueLocalCriacaoS()
    {
        return $this->hasMany(EstoqueLocal::class, 'codusuario', 'codusuariocriacao');
    }

    public function EstoqueMesAlteracaoS()
    {
        return $this->hasMany(EstoqueMes::class, 'codusuario', 'codusuarioalteracao');
    }

    public function EstoqueMesCriacaoS()
    {
        return $this->hasMany(EstoqueMes::class, 'codusuario', 'codusuariocriacao');
    }

    public function EstoqueMovimentoAlteracaoS()
    {
        return $this->hasMany(EstoqueMovimento::class, 'codusuario', 'codusuarioalteracao');
    }

    public function EstoqueMovimentoCriacaoS()
    {
        return $this->hasMany(EstoqueMovimento::class, 'codusuario', 'codusuariocriacao');
    }
    
    public function EstoqueSaldoConferenciaAlteracaoS()
    {
        return $this->hasMany(EstoqueSaldoConferencia::class, 'codusuario', 'codusuarioalteracao');
    }

    public function EstoqueSaldoConferenciaCriacaoS()
    {
        return $this->hasMany(EstoqueSaldoConferencia::class, 'codusuario', 'codusuariocriacao');
    }

    public function FamiliaProdutoAlteracaoS()
    {
        return $this->hasMany(FamiliaProduto::class, 'codusuario', 'codusuariocriacao');
    }

    public function FamiliaProdutoCriacaoS()
    {
        return $this->hasMany(FamiliaProduto::class, 'codusuario', 'codusuarioalteracao');
    }    

    public function GrupoUsuarioAlteracaoS()
    {
        return $this->hasMany(GrupoUsuario::class, 'codusuario', 'codusuarioalteracao');
    }

    public function GrupoUsuarioCriacaoS()
    {
        return $this->hasMany(GrupoUsuario::class, 'codusuario', 'codusuariocriacao');
    }

    public function GrupoUsuario()
    {
        return $this->belongsToMany(GrupoUsuario::class, 'tblgrupousuariousuario', 'codusuario', 'codgrupousuario')->withPivot('codgrupousuario', 'codfilial');
    }

    public function PermissaoAlteracaoS()
    {
        return $this->hasMany(Permissao::class, 'codusuario', 'codusuarioalteracao');
    }

    public function PermissaoCriacaoS()
    {
        return $this->hasMany(Permissao::class, 'codusuario', 'codusuariocriacao');
    }

    public function RegulamentoIcmsStMtAlteracaoS()
    {
        return $this->hasMany(RegulamentoIcmsStMt::class, 'codusuario', 'codusuarioalteracao');
    }

    public function RegulamentoIcmsStMtCriacaoS()
    {
        return $this->hasMany(RegulamentoIcmsStMt::class, 'codusuario', 'codusuariocriacao');
    }

    public function BancoAlteracaoS()
    {
        return $this->hasMany(Banco::class, 'codusuario', 'codusuarioalteracao');
    }

    public function BancoCriacaoS()
    {
        return $this->hasMany(Banco::class, 'codusuario', 'codusuariocriacao');
    }

    public function BaseRemotaAlteracaoS()
    {
        return $this->hasMany(BaseRemota::class, 'codusuario', 'codusuarioalteracao');
    }

    public function BaseRemotaCriacaoS()
    {
        return $this->hasMany(BaseRemota::class, 'codusuario', 'codusuariocriacao');
    }

    public function BoletoMotivoOcorrenciaAlteracaoS()
    {
        return $this->hasMany(BoletoMotivoOcorrencia::class, 'codusuario', 'codusuarioalteracao');
    }

    public function BoletoMotivoOcorrenciaCriacaoS()
    {
        return $this->hasMany(BoletoMotivoOcorrencia::class, 'codusuario', 'codusuariocriacao');
    }

    public function BoletoRetornoAlteracaoS()
    {
        return $this->hasMany(BoletoRetorno::class, 'codusuario', 'codusuarioalteracao');
    }

    public function BoletoRetornoCriacaoS()
    {
        return $this->hasMany(BoletoRetorno::class, 'codusuario', 'codusuariocriacao');
    }

    public function BoletoTipoOcorrenciaAlteracaoS()
    {
        return $this->hasMany(BoletoTipoOcorrencia::class, 'codusuario', 'codusuarioalteracao');
    }

    public function BoletoTipoOcorrenciaCriacaoS()
    {
        return $this->hasMany(BoletoTipoOcorrencia::class, 'codusuario', 'codusuariocriacao');
    }

    public function CfopAlteracaoS()
    {
        return $this->hasMany(Cfop::class, 'codusuario', 'codusuarioalteracao');
    }

    public function CfopCriacaoS()
    {
        return $this->hasMany(Cfop::class, 'codusuario', 'codusuariocriacao');
    }

    public function ChequeAlteracaoS()
    {
        return $this->hasMany(Cheque::class, 'codusuario', 'codusuarioalteracao');
    }

    public function ChequeCriacaoS()
    {
        return $this->hasMany(Cheque::class, 'codusuario', 'codusuariocriacao');
    }

    public function ChequeEmitenteAlteracaoS()
    {
        return $this->hasMany(ChequeEmitente::class, 'codusuario', 'codusuarioalteracao');
    }

    public function ChequeEmitenteCriacaoS()
    {
        return $this->hasMany(ChequeEmitente::class, 'codusuario', 'codusuariocriacao');
    }

    public function CidadeAlteracaoS()
    {
        return $this->hasMany(Cidade::class, 'codusuario', 'codusuarioalteracao');
    }

    public function CidadeCriacaoS()
    {
        return $this->hasMany(Cidade::class, 'codusuario', 'codusuariocriacao');
    }

    public function CobrancaAlteracaoS()
    {
        return $this->hasMany(Cobranca::class, 'codusuario', 'codusuarioalteracao');
    }

    public function CobrancaCriacaoS()
    {
        return $this->hasMany(Cobranca::class, 'codusuario', 'codusuariocriacao');
    }

    public function CobrancaHistoricoAlteracaoS()
    {
        return $this->hasMany(CobrancaHistorico::class, 'codusuario', 'codusuarioalteracao');
    }

    public function CobrancaHistoricoCriacaoS()
    {
        return $this->hasMany(CobrancaHistorico::class, 'codusuario', 'codusuariocriacao');
    }

    public function CobrancaHistoricoTituloAlteracaoS()
    {
        return $this->hasMany(CobrancaHistoricoTitulo::class, 'codusuario', 'codusuarioalteracao');
    }

    public function CobrancaHistoricoTituloCriacaoS()
    {
        return $this->hasMany(CobrancaHistoricoTitulo::class, 'codusuario', 'codusuariocriacao');
    }

    public function CodigoAlteracaoS()
    {
        return $this->hasMany(Codigo::class, 'codusuario', 'codusuarioalteracao');
    }

    public function CodigoCriacaoS()
    {
        return $this->hasMany(Codigo::class, 'codusuario', 'codusuariocriacao');
    }

    public function ContaContabilAlteracaoS()
    {
        return $this->hasMany(ContaContabil::class, 'codusuario', 'codusuarioalteracao');
    }

    public function ContaContabilCriacaoS()
    {
        return $this->hasMany(ContaContabil::class, 'codusuario', 'codusuariocriacao');
    }

    public function CupomFiscalAlteracaoS()
    {
        return $this->hasMany(CupomFiscal::class, 'codusuario', 'codusuarioalteracao');
    }

    public function CupomFiscalCriacaoS()
    {
        return $this->hasMany(CupomFiscal::class, 'codusuario', 'codusuariocriacao');
    }

    public function CupomFiscalProdutoBarraAlteracaoS()
    {
        return $this->hasMany(CupomFiscalProdutoBarra::class, 'codusuario', 'codusuarioalteracao');
    }

    public function CupomFiscalProdutoBarraCriacaoS()
    {
        return $this->hasMany(CupomFiscalProdutoBarra::class, 'codusuario', 'codusuariocriacao');
    }

    public function EcfAlteracaoS()
    {
        return $this->hasMany(Ecf::class, 'codusuario', 'codusuarioalteracao');
    }

    public function EcfCriacaoS()
    {
        return $this->hasMany(Ecf::class, 'codusuario', 'codusuariocriacao');
    }

    public function EcfS()
    {
        return $this->hasMany(Ecf::class, 'codusuario', 'codusuario');
    }

    public function EcfReducaozAlteracaoS()
    {
        return $this->hasMany(EcfReducaoz::class, 'codusuario', 'codusuarioalteracao');
    }

    public function EcfReducaozCriacaoS()
    {
        return $this->hasMany(EcfReducaoz::class, 'codusuario', 'codusuariocriacao');
    }

    public function EmpresaAlteracaoS()
    {
        return $this->hasMany(Empresa::class, 'codusuario', 'codusuarioalteracao');
    }

    public function EmpresaCriacaoS()
    {
        return $this->hasMany(Empresa::class, 'codusuario', 'codusuariocriacao');
    }

    public function EstadoCivilAlteracaoS()
    {
        return $this->hasMany(EstadoCivil::class, 'codusuario', 'codusuarioalteracao');
    }

    public function EstadoCivilCriacaoS()
    {
        return $this->hasMany(EstadoCivil::class, 'codusuario', 'codusuariocriacao');
    }

    public function EstadoAlteracaoS()
    {
        return $this->hasMany(Estado::class, 'codusuario', 'codusuarioalteracao');
    }

    public function EstadoCriacaoS()
    {
        return $this->hasMany(Estado::class, 'codusuario', 'codusuariocriacao');
    }

    public function EstoqueMovimentoTipoAlteracaoS()
    {
        return $this->hasMany(EstoqueMovimentoTipo::class, 'codusuario', 'codusuarioalteracao');
    }

    public function EstoqueMovimentoTipoCriacaoS()
    {
        return $this->hasMany(EstoqueMovimentoTipo::class, 'codusuario', 'codusuariocriacao');
    }

    public function EstoqueSaldoAlteracaoS()
    {
        return $this->hasMany(EstoqueSaldo::class, 'codusuario', 'codusuarioalteracao');
    }

    public function EstoqueSaldoCriacaoS()
    {
        return $this->hasMany(EstoqueSaldo::class, 'codusuario', 'codusuariocriacao');
    }

    public function FilialAcbrNfeMonitorS()
    {
        return $this->hasMany(Filial::class, 'codusuario', 'acbrnfemonitorcodusuario');
    }

    public function FilialAlteracaoS()
    {
        return $this->hasMany(Filial::class, 'codusuario', 'codusuarioalteracao');
    }

    public function FilialCriacaoS()
    {
        return $this->hasMany(Filial::class, 'codusuario', 'codusuariocriacao');
    }

    public function FormaPagamentoAlteracaoS()
    {
        return $this->hasMany(FormaPagamento::class, 'codusuario', 'codusuarioalteracao');
    }

    public function FormaPagamentoCriacaoS()
    {
        return $this->hasMany(FormaPagamento::class, 'codusuario', 'codusuariocriacao');
    }

    public function GrupoClienteAlteracaoS()
    {
        return $this->hasMany(GrupoCliente::class, 'codusuario', 'codusuarioalteracao');
    }

    public function GrupoClienteCriacaoS()
    {
        return $this->hasMany(GrupoCliente::class, 'codusuario', 'codusuariocriacao');
    }

    public function GrupoProdutoAlteracaoS()
    {
        return $this->hasMany(GrupoProduto::class, 'codusuario', 'codusuarioalteracao');
    }

    public function GrupoProdutoS()
    {
        return $this->hasMany(GrupoProduto::class, 'codusuario', 'codusuariocriacao');
    }

    public function IbptaxAlteracaoS()
    {
        return $this->hasMany(Ibptax::class, 'codusuario', 'codusuarioalteracao');
    }

    public function IbptaxCriacaoS()
    {
        return $this->hasMany(Ibptax::class, 'codusuario', 'codusuariocriacao');
    }

    public function LiquidacaoTituloAlteracaoS()
    {
        return $this->hasMany(LiquidacaoTitulo::class, 'codusuario', 'codusuarioalteracao');
    }

    public function LiquidacaoTituloCriacaoS()
    {
        return $this->hasMany(LiquidacaoTitulo::class, 'codusuario', 'codusuariocriacao');
    }

    public function LiquidacaoTituloEstornoS()
    {
        return $this->hasMany(LiquidacaoTitulo::class, 'codusuario', 'codusuarioestorno');
    }

    public function LiquidacaoTituloS()
    {
        return $this->hasMany(LiquidacaoTitulo::class, 'codusuario', 'codusuario');
    }

    public function MarcaAlteracaoS()
    {
        return $this->hasMany(Marca::class, 'codusuario', 'codusuarioalteracao');
    }

    public function MarcaCriacaoS()
    {
        return $this->hasMany(Marca::class, 'codusuario', 'codusuariocriacao');
    }

    public function MenuAlteracaoS()
    {
        return $this->hasMany(Menu::class, 'codusuario', 'codusuarioalteracao');
    }

    public function MenuCriacaoS()
    {
        return $this->hasMany(Menu::class, 'codusuario', 'codusuariocriacao');
    }

    public function MovimentoTituloAlteracaoS()
    {
        return $this->hasMany(MovimentoTitulo::class, 'codusuario', 'codusuarioalteracao');
    }

    public function MovimentoTituloCriacaoS()
    {
        return $this->hasMany(MovimentoTitulo::class, 'codusuario', 'codusuariocriacao');
    }

    public function NaturezaOperacaoAlteracaoS()
    {
        return $this->hasMany(NaturezaOperacao::class, 'codusuario', 'codusuarioalteracao');
    }

    public function NaturezaOperacaoCriacaoS()
    {
        return $this->hasMany(NaturezaOperacao::class, 'codusuario', 'codusuariocriacao');
    }

    public function NcmAlteracaoS()
    {
        return $this->hasMany(Ncm::class, 'codusuario', 'codusuarioalteracao');
    }

    public function NcmCriacaoS()
    {
        return $this->hasMany(Ncm::class, 'codusuario', 'codusuariocriacao');
    }

    public function NegocioAcertoEntregaS()
    {
        return $this->hasMany(Negocio::class, 'codusuario', 'codusuarioacertoentrega');
    }

    public function NegocioAlteracaoS()
    {
        return $this->hasMany(Negocio::class, 'codusuario', 'codusuarioalteracao');
    }

    public function NegocioCriacaoS()
    {
        return $this->hasMany(Negocio::class, 'codusuario', 'codusuariocriacao');
    }

    public function NegocioS()
    {
        return $this->hasMany(Negocio::class, 'codusuario', 'codusuario');
    }

    public function NegocioFormaPagamentoAlteracaoS()
    {
        return $this->hasMany(NegocioFormaPagamento::class, 'codusuario', 'codusuarioalteracao');
    }

    public function NegocioFormaPagamentoCriacaoS()
    {
        return $this->hasMany(NegocioFormaPagamento::class, 'codusuario', 'codusuariocriacao');
    }

    public function NegocioProdutoBarraAlteracaoS()
    {
        return $this->hasMany(NegocioProdutoBarra::class, 'codusuario', 'codusuarioalteracao');
    }

    public function NegocioProdutoBarraCriacaoS()
    {
        return $this->hasMany(NegocioProdutoBarra::class, 'codusuario', 'codusuariocriacao');
    }

    public function NegocioStatusAlteracaoS()
    {
        return $this->hasMany(NegocioStatus::class, 'codusuario', 'codusuarioalteracao');
    }

    public function NegocioStatusCriacaoS()
    {
        return $this->hasMany(NegocioStatus::class, 'codusuario', 'codusuariocriacao');
    }

    public function NfeTerceiroAlteracaoS()
    {
        return $this->hasMany(NfeTerceiro::class, 'codusuario', 'codusuarioalteracao');
    }

    public function NfeTerceiroCriacaoS()
    {
        return $this->hasMany(NfeTerceiro::class, 'codusuario', 'codusuariocriacao');
    }

    public function NfeTerceiroDuplicataAlteracaoS()
    {
        return $this->hasMany(NfeTerceiroDuplicata::class, 'codusuario', 'codusuarioalteracao');
    }

    public function NfeTerceiroDuplicataCriacaoS()
    {
        return $this->hasMany(NfeTerceiroDuplicata::class, 'codusuario', 'codusuariocriacao');
    }

    public function NfeTerceiroItemAlteracaoS()
    {
        return $this->hasMany(NfeTerceiroItem::class, 'codusuario', 'codusuarioalteracao');
    }

    public function NfeTerceiroItemCriacaoS()
    {
        return $this->hasMany(NfeTerceiroItem::class, 'codusuario', 'codusuariocriacao');
    }

    public function NotaFiscalCartaCorrecaoAlteracaoS()
    {
        return $this->hasMany(NotaFiscalCartaCorrecao::class, 'codusuario', 'codusuarioalteracao');
    }

    public function NotaFiscalCartaCorrecaoCriacaoS()
    {
        return $this->hasMany(NotaFiscalCartaCorrecao::class, 'codusuario', 'codusuariocriacao');
    }

    public function NotaFiscalAlteracaoS()
    {
        return $this->hasMany(NotaFiscal::class, 'codusuario', 'codusuarioalteracao');
    }

    public function NotaFiscalCriacaoS()
    {
        return $this->hasMany(NotaFiscal::class, 'codusuario', 'codusuariocriacao');
    }

    public function NotaFiscalDuplicatasAlteracaoS()
    {
        return $this->hasMany(NotaFiscalDuplicatas::class, 'codusuario', 'codusuarioalteracao');
    }

    public function NotaFiscalDuplicatasCriacaoS()
    {
        return $this->hasMany(NotaFiscalDuplicatas::class, 'codusuario', 'codusuariocriacao');
    }

    public function NotaFiscalProdutoBarraAlteracaoS()
    {
        return $this->hasMany(NotaFiscalProdutoBarra::class, 'codusuario', 'codusuarioalteracao');
    }

    public function NotaFiscalProdutoBarraCriacaoS()
    {
        return $this->hasMany(NotaFiscalProdutoBarra::class, 'codusuario', 'codusuariocriacao');
    }

    public function NotaFiscalReferenciadaAlteracaoS()
    {
        return $this->hasMany(NotaFiscalReferenciada::class, 'codusuario', 'codusuarioalteracao');
    }

    public function NotaFiscalReferenciadaCriacaoS()
    {
        return $this->hasMany(NotaFiscalReferenciada::class, 'codusuario', 'codusuariocriacao');
    }

    public function OperacaoAlteracaoS()
    {
        return $this->hasMany(Operacao::class, 'codusuario', 'codusuarioalteracao');
    }

    public function OperacaoCriacaoS()
    {
        return $this->hasMany(Operacao::class, 'codusuario', 'codusuariocriacao');
    }

    public function PaisAlteracaoS()
    {
        return $this->hasMany(Pais::class, 'codusuario', 'codusuarioalteracao');
    }

    public function PaisCriacaoS()
    {
        return $this->hasMany(Pais::class, 'codusuario', 'codusuariocriacao');
    }

    public function ParametrosGeraisAlteracaoS()
    {
        return $this->hasMany(ParametrosGerais::class, 'codusuario', 'codusuarioalteracao');
    }

    public function ParametrosGeraisCriacaoS()
    {
        return $this->hasMany(ParametrosGerais::class, 'codusuario', 'codusuariocriacao');
    }

    public function PessoaAlteracaoS()
    {
        return $this->hasMany(Pessoa::class, 'codusuario', 'codusuarioalteracao');
    }

    public function PessoaCriacaoS()
    {
        return $this->hasMany(Pessoa::class, 'codusuario', 'codusuariocriacao');
    }

    public function PortadorAlteracaoS()
    {
        return $this->hasMany(Portador::class, 'codusuario', 'codusuarioalteracao');
    }

    public function PortadorCriacaoS()
    {
        return $this->hasMany(Portador::class, 'codusuario', 'codusuariocriacao');
    }

    public function ProdutoBarraAlteracaoS()
    {
        return $this->hasMany(ProdutoBarra::class, 'codusuario', 'codusuarioalteracao');
    }

    public function ProdutoBarraCriacaoS()
    {
        return $this->hasMany(ProdutoBarra::class, 'codusuario', 'codusuariocriacao');
    }

    public function ProdutoAlteracaoS()
    {
        return $this->hasMany(Produto::class, 'codusuario', 'codusuarioalteracao');
    }

    public function ProdutoCriacaoS()
    {
        return $this->hasMany(Produto::class, 'codusuario', 'codusuariocriacao');
    }

    public function ProdutoEmbalagemAlteracaoS()
    {
        return $this->hasMany(ProdutoEmbalagem::class, 'codusuario', 'codusuarioalteracao');
    }

    public function ProdutoEmbalagemCriacaoS()
    {
        return $this->hasMany(ProdutoEmbalagem::class, 'codusuario', 'codusuariocriacao');
    }

    public function ProdutoHistoricoPrecoAlteracaoS()
    {
        return $this->hasMany(ProdutoHistoricoPreco::class, 'codusuario', 'codusuarioalteracao');
    }

    public function ProdutoHistoricoPrecoCriacaoS()
    {
        return $this->hasMany(ProdutoHistoricoPreco::class, 'codusuario', 'codusuariocriacao');
    }

    public function RegistroSpcAlteracaoS()
    {
        return $this->hasMany(RegistroSpc::class, 'codusuario', 'codusuarioalteracao');
    }

    public function RegistroSpcCriacaoS()
    {
        return $this->hasMany(RegistroSpc::class, 'codusuario', 'codusuariocriacao');
    }

    public function SexoAlteracaoS()
    {
        return $this->hasMany(Sexo::class, 'codusuario', 'codusuarioalteracao');
    }

    public function SexoCriacaoS()
    {
        return $this->hasMany(Sexo::class, 'codusuario', 'codusuariocriacao');
    }

    public function SubGrupoProdutoAlteracaoS()
    {
        return $this->hasMany(SubGrupoProduto::class, 'codusuario', 'codusuarioalteracao');
    }

    public function SubGrupoProdutCriacaooS()
    {
        return $this->hasMany(SubGrupoProduto::class, 'codusuario', 'codusuariocriacao');
    }

    public function TipoMovimentoTituloAlteracaoS()
    {
        return $this->hasMany(TipoMovimentoTitulo::class, 'codusuario', 'codusuarioalteracao');
    }

    public function TipoMovimentoTituloCriacaoS()
    {
        return $this->hasMany(TipoMovimentoTitulo::class, 'codusuario', 'codusuariocriacao');
    }

    public function TipoProdutoAlteracaoS()
    {
        return $this->hasMany(TipoProduto::class, 'codusuario', 'codusuarioalteracao');
    }

    public function TipoProdutoCriacaoS()
    {
        return $this->hasMany(TipoProduto::class, 'codusuario', 'codusuariocriacao');
    }

    public function TipoTituloAlteracaoS()
    {
        return $this->hasMany(TipoTitulo::class, 'codusuario', 'codusuarioalteracao');
    }

    public function TipoTituloCriacaoS()
    {
        return $this->hasMany(TipoTitulo::class, 'codusuario', 'codusuariocriacao');
    }

    public function TituloAgrupamentoAlteracaoS()
    {
        return $this->hasMany(TituloAgrupamento::class, 'codusuario', 'codusuarioalteracao');
    }

    public function TituloAgrupamentoCriacaoS()
    {
        return $this->hasMany(TituloAgrupamento::class, 'codusuario', 'codusuariocriacao');
    }

    public function TituloAlteracaoS()
    {
        return $this->hasMany(Titulo::class, 'codusuario', 'codusuarioalteracao');
    }

    public function TituloCriacaoS()
    {
        return $this->hasMany(Titulo::class, 'codusuario', 'codusuariocriacao');
    }

    public function TributacaoAlteracaoS()
    {
        return $this->hasMany(Tributacao::class, 'codusuario', 'codusuarioalteracao');
    }

    public function TributacaoCriacaoS()
    {
        return $this->hasMany(Tributacao::class, 'codusuario', 'codusuariocriacao');
    }

    public function TributacaoNaturezaOperacaoAlteracaoS()
    {
        return $this->hasMany(TributacaoNaturezaOperacao::class, 'codusuario', 'codusuarioalteracao');
    }

    public function TributacaoNaturezaOperacaoCriacaoS()
    {
        return $this->hasMany(TributacaoNaturezaOperacao::class, 'codusuario', 'codusuariocriacao');
    }

    public function UnidadeMedidaAlteracaoS()
    {
        return $this->hasMany(UnidadeMedida::class, 'codusuario', 'codusuarioalteracao');
    }

    public function UnidadeMedidaCriacaoS()
    {
        return $this->hasMany(UnidadeMedida::class, 'codusuario', 'codusuariocriacao');
    }

    public function UsuarioAlteracaoS()
    {
        return $this->hasMany(Usuario::class, 'codusuario', 'codusuarioalteracao');
    }

    public function UsuarioCriacaoS()
    {
        return $this->hasMany(Usuario::class, 'codusuario', 'codusuariocriacao');
    }

    
    
    
    
    // Métodos
    public function extractgrupos()
    {
        $arraygrupos = [];
        foreach($this->GrupoUsuario as $data) 
        {
            $arraygrupos [] = [
                'filial'=>$data->pivot->codfilial, 
                'grupo'=>$data->pivot->codgrupousuario
            ];
        }
        return $arraygrupos;        
    }
    
    public function can($permission = null)
    {
        return (!is_null($permission) && $this->checkPermission($permission));
    }

    public function filiais()
    {
        $filiais = $this->GrupoUsuario->load('Filiais')->fetch('filiais')->toArray();
        return array_map('strtolower', array_unique(array_flatten(array_map(function ($filial) {
            return array_pluck($filial, 'codfilial');
        }, $filiais))));
    }

    protected function checkPermission($perm)
    {
        $permissions = $this->getAllPermissionsFormAllRoles();      
        $permissionArray = is_array($perm) ? $perm : [$perm];

        return count(array_intersect($permissions, $permissionArray));
    }
    
    protected function getAllPermissionsFormAllRoles()
    {
        $permissions = $this->GrupoUsuario->load('PermissaoS')->fetch('permissao_s')->toArray();
       
        return array_map('strtolower', array_unique(array_flatten(array_map(function ($permission) {
            return array_pluck($permission, 'permissao');
        }, $permissions))));
    } 

    static function printers() 
    {
        $o = shell_exec("lpstat -d -p");
        $res = explode("\n", $o);
        $printers = [];
        foreach ($res as $r) 
        {
            if (strpos($r, "printer") !== FALSE) 
            {
                $r = str_replace("printer ", "", $r);
                $r = explode(" ", $r);
                $printers[$r[0]] = $r[0];
            }
        }
        
        return $printers;
    }    
    
    # Buscas #
    public static function filterAndPaginate($codusuario, $usuario, $codpessoa, $codfilial)
    {
        return Usuario::codusuario($codusuario)
            ->usuario($usuario)
            ->codpessoa($codpessoa)
            ->codfilial($codfilial)
            ->orderBy('usuario', 'ASC')
            ->paginate(20);
    }
    
    public function scopeCodusuario($query, $codusuario)
    {
        if ($codusuario)
        {
            $query->where('codusuario', "$codusuario");
        }
    }   
    
    public function scopeUsuario($query, $usuario)
    {
        if (trim($usuario) != "")
        {
            $query->where('usuario', "ILIKE", "%$usuario%");
        }
    }    
    
    public function scopeCodpessoa($query, $codpessoa)
    {
        if ($codpessoa)
        {
            $query->where('codpessoa', "$codpessoa");
        }
    }      
    
    public function scopeCodfilial($query, $codfilial)
    {
        if ($codfilial)
        {
            $query->where('codfilial', "$codfilial");
        }
    }      
}
