<?php

namespace MGLara\Models;


class MercosPedidoItem extends MGModel
{
    protected $table = 'tblmercospedidoitem';
    protected $primaryKey = 'codmercospedidoitem';
    protected $fillable = [
        'itemid',
        'codmercospedido',
        'codnegocioprodutobarra',
    ];
    protected $dates = [
        'alteracao',
        'criacao',
    ];

    // Chaves Estrangeiras
    public function MercosPedido()
    {
        return $this->belongsTo(MercosPedido::class, 'codmercospedido', 'codmercospedido');
    }

    public function NegocioProdutoBarra()
    {
        return $this->belongsTo(NegocioProdutoBarra::class, 'codnegocioprodutobarra', 'codnegocioprodutobarra');
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
