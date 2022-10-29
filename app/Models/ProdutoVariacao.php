<?php

namespace MGLara\Models;

/**
 * Campos
 * @property  bigint                         $codprodutovariacao                 NOT NULL DEFAULT nextval('tblprodutovariacao_codprodutovariacao_seq'::regclass)
 * @property  varchar(100)                   $variacao
 * @property  bigint                         $codproduto                         NOT NULL
 * @property  bigint                         $codmarca
 * @property  timestamp                      $alteracao
 * @property  bigint                         $codusuarioalteracao
 * @property  timestamp                      $criacao
 * @property  bigint                         $codusuariocriacao
 * @property  varchar(50)                    $referencia
 *
 * Chaves Estrangeiras
 * @property  Marca                          $Marca
 * @property  Usuario                        $UsuarioAlteracao
 * @property  Usuario                        $UsuarioCriacao
 * @property  Produto                        $Produto
 *
 * Tabelas Filhas
 * @property  ProdutoBarra[]                 $ProdutoBarraS
 * @property  EstoqueLocalProdutoVariacao[]  $EstoqueLocalProdutoVariacaoS
 */

class ProdutoVariacao extends MGModel
{
    protected $table = 'tblprodutovariacao';
    protected $primaryKey = 'codprodutovariacao';
    protected $fillable = [
        'variacao',
        'codproduto',
        'codmarca',
        'codprodutoimagem',
        'referencia',
    ];
    protected $dates = [
        'alteracao',
        'criacao',
    ];


    // Chaves Estrangeiras
    public function Marca()
    {
        return $this->belongsTo(Marca::class, 'codmarca', 'codmarca');
    }

    public function UsuarioAlteracao()
    {
        return $this->belongsTo(Usuario::class, 'codusuarioalteracao', 'codusuario');
    }

    public function UsuarioCriacao()
    {
        return $this->belongsTo(Usuario::class, 'codusuariocriacao', 'codusuario');
    }

    public function Produto()
    {
        return $this->belongsTo(Produto::class, 'codproduto', 'codproduto');
    }

    public function ProdutoImagem()
    {
        return $this->hasMany(ProdutoImagem::class, 'codprodutoimagem', 'codprodutoimagem');
    }


    // Tabelas Filhas
    public function ProdutoBarraS()
    {
        return $this->hasMany(ProdutoBarra::class, 'codprodutovariacao', 'codprodutovariacao');
    }

    public function EstoqueLocalProdutoVariacaoS()
    {
        return $this->hasMany(EstoqueLocalProdutoVariacao::class, 'codprodutovariacao', 'codprodutovariacao');
    }

    public function ProdutoImagemProdutoVariacaoS()
    {
        return $this->hasMany(ProdutoImagemProdutoVariacao::class, 'codprodutovariacao', 'codprodutovariacao');
    }

    public function MagazordProduto()
    {
        return $this->hasMany(MagazordProduto::class, 'codprodutovariacao', 'codprodutovariacao');
    }

    public function validate()
    {

        $this->_regrasValidacao = [
          'variacao' => "uniqueMultiple:tblprodutovariacao,codprodutovariacao,{$this->codprodutovariacao},variacao,codproduto,{$this->codproduto}",
          'codmarca' => "not_in:{$this->Produto->codmarca}"
        ];
        $this->_mensagensErro = [
            'variacao.unique_multiple' => 'Esta Variação já está cadastrada!',
            'variacao.min' => 'Variação deve ter mais de 3 caracteres!',
            'variacao.required' => 'Já existe uma Variação em branco, preencha a descrição desta nova Variação!',
            'codmarca.not_in' => 'Somente selecione a Marca caso seja diferente do produto!',
        ];

        // Verifica se já existe um "Sem Variacao"
        if (empty($this->variacao)) {
            $qry = $this->Produto->ProdutoVariacaoS()->whereNull('variacao');
            if (!empty($this->codprodutovariacao)) {
                $qry = $qry->where('codprodutovariacao', '!=', $this->codprodutovariacao);
            }
            if ($qry->count() > 0) {
                $this->_regrasValidacao['variacao'] = 'required|' . $this->_regrasValidacao['variacao'];
            }
        }

        $ret = parent::validate();

        return $ret;
    }

    public function vincularProdutoImagemAdicional($arr)
    {
        $codprodutoimagemprodutovariacaos = [];
        if (is_array($arr)) {
            foreach ($arr as $codprodutoimagem) {
                $pipv = ProdutoImagemProdutoVariacao::firstOrNew([
                    'codprodutoimagem' => $codprodutoimagem,
                    'codprodutovariacao' => $this->codprodutovariacao
                ]);
                $pipv->save();
                $codprodutoimagemprodutovariacaos[] = $pipv->codprodutoimagemprodutovariacao;
            }
        }
        $this->ProdutoImagemProdutoVariacaoS()
            ->whereNotIn('codprodutoimagemprodutovariacao', $codprodutoimagemprodutovariacaos)
            ->delete();
    }



}
