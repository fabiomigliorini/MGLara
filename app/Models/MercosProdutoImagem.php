<?php

namespace MGLara\Models;


class MercosProdutoImagem extends MGModel
{
    protected $table = 'tblmercosprodutoimagem';
    protected $primaryKey = 'codmercosprodutoimagem';
    protected $fillable = [
        'codmercosproduto',
        'codimagem',
    ];
    protected $dates = [
        'alteracao',
        'criacao',
    ];

    // Chaves Estrangeiras
    public function MercosProduto()
    {
        return $this->belongsTo(MercosProduto::class, 'codmercosproduto', 'codmercosproduto');
    }

    public function Imagem()
    {
        return $this->belongsTo(Imagem::class, 'codimagem', 'codimagem');
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

}
