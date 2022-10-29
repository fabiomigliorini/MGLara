<?php

namespace MGLara\Models;

class ProdutoImagemProdutoVariacao extends MGModel
{
    protected $table = 'tblprodutoimagemprodutovariacao';
    protected $primaryKey = 'codprodutoimagemprodutovariacao';
    protected $fillable = [
        'codprodutoimagem',
        'codprodutovariacao',
    ];
    protected $dates = [
        'alteracao',
        'criacao',
    ];


    // Chaves Estrangeiras
    public function UsuarioAlteracao()
    {
        return $this->belongsTo(Usuario::class, 'codusuarioalteracao', 'codusuario');
    }

    public function UsuarioCriacao()
    {
        return $this->belongsTo(Usuario::class, 'codusuariocriacao', 'codusuario');
    }

    public function ProdutoImagem()
    {
        return $this->belongsTo(ProdutoImagem::class, 'codprodutoimagem', 'codprodutoimagem');
    }

    public function ProdutoVariacao()
    {
        return $this->belongsTo(ProdutoVariacao::class, 'codprodutovariacao', 'codprodutovariacao');
    }

}
