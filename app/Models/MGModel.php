<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace MGLara\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

/**
 * Description of Model
 *
 * @author escmig05
 */
abstract class MGModel extends Model {

    const CREATED_AT = 'criacao';
    const UPDATED_AT = 'alteracao';
    public $timestamps = true;
    
    protected $_regrasValidacao;
    protected $_mensagensErro;
    public $_validator;
    
    public function UsuarioCriacao()
    {
        return $this->belongsTo(Usuario::class, 'codusuariocriacao', 'codusuario');
    }
    
    public function UsuarioAlteracao()
    {
        return $this->belongsTo(Usuario::class, 'codusuarioalteracao', 'codusuario');
    }     
    
    #public function __construct() {
        
    #}


    /*
    public function nullIfBlank($field)
    {
        return trim($field) !== '' ? $field : null;
    }
     * 
     */
    
    public static function boot()
    {
        parent::boot();

        static::creating(function($model)
        {
            $model->attributes['codusuariocriacao'] = Auth::user()->codusuario;
        });
        
        static::updating(function($model)
        {
            $model->attributes['codusuarioalteracao'] = Auth::user()->codusuario;
        });
        
        static::saving(function($model)
        {
            foreach ($model->toArray() as $fieldName => $fieldValue) {
                if ( $fieldValue === '' ) {
                    $model->attributes[$fieldName] = null;
                }
            }

            return true;
        });
    }
    
    public function validate() {
        
        $this->_validator = Validator::make(
            $this->getAttributes(), 
            $this->_regrasValidacao, 
            $this->_mensagensErro
        );

        
        if ($this->_validator->fails())
            return false;
        else
            return true;        
        
    }
    
}
