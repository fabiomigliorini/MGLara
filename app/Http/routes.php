<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/




// Rotas de autenticação
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');

Route::group(['middleware' => 'auth'], function() {
    /* Página inicial */
    Route::resource('home','DashboardController');  
    Route::resource('/','DashboardController');  

    /* Usuários */
    Route::resource('usuario','UsuarioController');       
    Route::resource('usuario/{codusuario}/permissao','UsuarioController@permissao');       
    Route::resource('usuario/attach-permissao','UsuarioController@attachPermissao');       
    Route::resource('usuario/detach-permissao','UsuarioController@detachPermissao');       

    /* Grupos de usuários */
    Route::resource('grupo-usuario','GrupoUsuarioController');     
    Route::post('grupo-usuario/attach-permissao','GrupoUsuarioController@attachPermissao');     
    Route::post('grupo-usuario/detach-permissao','GrupoUsuarioController@detachPermissao');     

    /* Permissões */
    Route::resource('permissao','PermissaoController');   

    /* Pessoas */
    Route::resource('pessoa','PessoaController');
    Route::get('pessoa-ajax', 'PessoaController@ajax');

    /* Filiais */
    Route::resource('filial','FilialController');
    Route::get('filial-ajax', 'FilialController@ajax');
    
    /* Estoque mês */
    Route::resource('estoque-mes','EstoqueMesController');       
    
    /* EstoqueSaldo */
    Route::resource('estoque-saldo','EstoqueSaldoController');       
    
    /* GrupoProduto */
    Route::resource('grupo-produto','GrupoProdutoController');           
    Route::resource('grupo-produto/{id}/busca-codproduto','GrupoProdutoController@buscaCodproduto');           

    /* Marca */
    Route::resource('marca','MarcaController');           
    Route::resource('marca/{id}/busca-codproduto','MarcaController@buscaCodproduto');           
    
    /* SubGrupoProduto */
    Route::resource('sub-grupo-produto','SubGrupoProdutoController');           
    Route::resource('sub-grupo-produto/{id}/busca-codproduto','SubGrupoProdutoController@buscaCodproduto');           
    
    /* Produto */
    Route::get('produto/cobre-estoque-negativo','ProdutoController@cobreEstoqueNegativo');           
    Route::resource('produto/ajax','ProdutoController@ajaxProduto');
    Route::resource('produto/estoque-saldo','ProdutoController@estoqueSaldo');
    Route::resource('produto','ProdutoController');
    Route::resource('produto/{id}/recalcula-movimento-estoque','ProdutoController@recalculaMovimentoEstoque');
    Route::resource('produto/{id}/recalcula-custo-medio','ProdutoController@recalculaCustoMedio');           
    Route::resource('produto/{id}/cobre-estoque-negativo','ProdutoController@cobreEstoqueNegativo');           

    /* Estoque movimento */
    Route::resource('estoque-movimento','EstoqueMovimentoController');       
    Route::resource('estoque-movimento/create/{codestoquemes?}','EstoqueMovimentoController@create');      
    
    /* NotaFiscal */
    Route::resource('nota-fiscal','NotaFiscalController');           
    
    /* Auxiliares */
    Route::resource('printers','UsuarioController@printers');
    
    /* Gerador de Codigo */
    Route::resource('gerador-codigo/model/{tabela}','GeradorCodigoController@model');   
});