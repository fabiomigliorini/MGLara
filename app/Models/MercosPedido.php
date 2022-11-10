<?php

namespace MGLara\Models;


class MercosPedido extends MGModel
{
    protected $table = 'tblmercospedido';
    protected $primaryKey = 'codmercospedido';
    protected $fillable = [
        'pedidoid',
        'numero',
        'condicaopagamento',
        'enderecoentrega',
        'codnegocio',
        'codmercoscliente',
        'ultimaalteracaomercos',
    ];
    protected $dates = [
        'ultimaalteracaomercos',
        'criacao',
        'alteracao',
    ];

    // Chaves Estrangeiras
    public function Negocio()
    {
        return $this->belongsTo(Negocio::class, 'codnegocio', 'codnegocio');
    }

    public function MercosCliente()
    {
        return $this->belongsTo(MercosCliente::class, 'codmercoscliente', 'codmercoscliente');
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
    public function MercosPedidoItemS()
    {
        return $this->hasMany(MercosPedidoItem::class, 'codmercospedido', 'codmercospedido');
    }

}
