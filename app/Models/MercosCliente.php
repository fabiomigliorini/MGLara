<?php

namespace MGLara\Models;


class MercosCliente extends MGModel
{
    protected $table = 'tblmercoscliente';
    protected $primaryKey = 'codmercoscliente';
    protected $fillable = [
        'clienteid',
        'codpessoa',
    ];
    protected $dates = [
        'alteracao',
        'criacao',
    ];

    // Chaves Estrangeiras
    public function Pessoa()
    {
        return $this->belongsTo(Pessoa::class, 'codpessoa', 'codpessoa');
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
    public function MercosPedidoS()
    {
        return $this->hasMany(MercosPedido::class, 'codmercoscliente', 'codmercoscliente');
    }

}
