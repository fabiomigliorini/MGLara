<?php

namespace MGLara\Models;
use DB;

/**
 * Campos
 * @property  bigint                         $codimagem                          NOT NULL DEFAULT nextval('tblimagem_codimagem_seq'::regclass)
 * @property  varchar(200)                   $observacoes
 * @property  timestamp                      $inativo
 * @property  timestamp                      $criacao
 * @property  bigint                         $codusuariocriacao
 * @property  timestamp                      $alteracao
 * @property  bigint                         $codusuarioalteracao
 *
 * Chaves Estrangeiras
 *
 * Tabelas Filhas
 * @property  FamiliaProduto[]               $FamiliaProdutoS
 * @property  GrupoProduto[]                 $GrupoProdutoS
 * @property  Marca[]                        $MarcaS
 * @property  ProdutoImagem[]                $ProdutoImagemS
 * @property  SecaoProduto[]                 $SecaoProdutoS
 * @property  SubGrupoProduto[]              $SubGrupoProdutoS
 */

class Imagem extends MGModel
{
    protected $table = 'tblimagem';
    protected $primaryKey = 'codimagem';
    protected $fillable = [
        'observacoes',
        'arquivo',
        'inativo',
    ];
    protected $dates = [
        'inativo',
        'criacao',
        'alteracao',
    ];


    // Chaves Estrangeiras


    // Tabelas Filhas
    public function ProdutoS()
    {
        return $this->belongsToMany(Produto::class, 'tblprodutoimagem', 'codimagem', 'codproduto');
    }


    public function FamiliaProdutoS()
    {
        return $this->hasMany(FamiliaProduto::class, 'codimagem', 'codimagem');
    }

    public function GrupoProdutoS()
    {
        return $this->hasMany(GrupoProduto::class, 'codimagem', 'codimagem');
    }

    public function MarcaS()
    {
        return $this->hasMany(Marca::class, 'codimagem', 'codimagem');
    }

    public function SecaoProdutoS()
    {
        return $this->hasMany(SecaoProduto::class, 'codimagem', 'codimagem');
    }

    public function SubGrupoProdutoS()
    {
        return $this->hasMany(SubGrupoProduto::class, 'codimagem', 'codimagem');
    }

    public function MercosProdutoImagemS()
    {
        return $this->hasMany(MercosProdutoImagem::class, 'codimagem', 'codimagem');
    }


    // Buscas
    public static function search($parametros)
    {
        $query = Imagem::query();
        switch (isset($parametros['ativo']) ? $parametros['ativo']:'9')
        {
            case 1: //Ativos
                $query->ativo();
                break;
            case 2: //Inativos
                $query->inativo();
                break;
            case 9; //Todos
            default:
        }

        return $query;
    }

    public function scopeInativo($query)
    {
        $query->whereNotNull('inativo');
    }

    public function scopeAtivo($query)
    {
        $query->whereNull('inativo');
    }

    public static function relacionamentos($id)
    {
        $sql = "
            SELECT
                  tc.table_name AS table_name
                --, kcu.column_name AS foreign_column_name
                --, ccu.column_name
            FROM
                information_schema.table_constraints AS tc
                JOIN information_schema.key_column_usage AS kcu
                  ON tc.constraint_name = kcu.constraint_name
                JOIN information_schema.constraint_column_usage AS ccu
                  ON ccu.constraint_name = tc.constraint_name
            WHERE constraint_type = 'FOREIGN KEY'
            AND ccu.table_name='tblimagem';
        ";

        $query = DB::select($sql);

        foreach ($query as $rel)
        {
            $table_name = DB::select("SELECT * FROM $rel->table_name WHERE codimagem = $id");
            if(!empty($table_name)) {
                $classe = str_replace('tbl', '', $rel->table_name);
                $classe = str_replace('produto', 'Produto', $classe);
                $classe = str_replace('imagem', 'Imagem', $classe);
                $classe = str_replace('grupo', 'Grupo', $classe);
                $classe = str_pad(ucfirst($classe), 30, ' ');
                $classe = trim($classe);

                $Model = "\MGLara\Models\\$classe";
            }
        }

        return $Model;
    }
}
