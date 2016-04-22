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

    /* Integracao Dominio */
    Route::get('dominio/estoque','DominioController@estoque');               
    Route::resource('dominio','DominioController');           
    
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
    Route::get('estoque-saldo/{id}/zera','EstoqueSaldoController@zera');        
    
    /* GrupoProduto */
    Route::post('grupo-produto/inativo','GrupoProdutoController@inativo');
    Route::resource('grupo-produto/{id}/busca-codproduto','GrupoProdutoController@buscaCodproduto');           
    Route::resource('grupo-produto','GrupoProdutoController');           
               
    /* NCM */
    Route::get('ncm/ajax','NcmController@ajax');           
    Route::resource('ncm','NcmController');           
               
    /* CEST */
    Route::get('cest/ajax','CestController@ajax');           
    Route::resource('cest','CestController');           
               
    /* Marca */
    Route::get('marca/ajax','MarcaController@ajax');           
    Route::resource('marca/inativo','MarcaController@inativo');           
    Route::resource('marca/{id}/busca-codproduto','MarcaController@buscaCodproduto'); 
    Route::resource('marca','MarcaController');           
              
    /* SubGrupoProduto */
    Route::resource('sub-grupo-produto/{id}/busca-codproduto','SubGrupoProdutoController@buscaCodproduto');           
    Route::post('sub-grupo-produto/inativo','SubGrupoProdutoController@inativo');  
    Route::resource('sub-grupo-produto','SubGrupoProdutoController');           
         
    /* Produto */
    Route::get('produto/cobre-estoque-negativo','ProdutoController@cobreEstoqueNegativo');           
    Route::resource('produto/busca-barras','ProdutoController@buscaPorBarras');
    Route::resource('produto/ajax','ProdutoController@ajaxProduto');
    Route::resource('produto/estoque-saldo','ProdutoController@estoqueSaldo');
    Route::resource('produto','ProdutoController');
    Route::resource('produto/{id}/recalcula-movimento-estoque','ProdutoController@recalculaMovimentoEstoque');
    Route::resource('produto/{id}/recalcula-custo-medio','ProdutoController@recalculaCustoMedio');           
    Route::resource('produto/{id}/cobre-estoque-negativo','ProdutoController@cobreEstoqueNegativo');
    
    Route::resource('produto-barra','ProdutoBarraController');
    
    /* Pais */
    Route::resource('pais','PaisController');
    
    /* Estado */
    Route::resource('estado','EstadoController');
    
    /* Cidades */
    Route::resource('cidade','CidadeController');
   
    /* Unidades de medida */
    Route::resource('unidade-medida','UnidadeMedidaController');
   
    /* Portadores */
    Route::resource('portador', 'PortadorController');
   
    /* Grupos de cliente */
    Route::resource('grupo-cliente', 'GrupoClienteController');
   
    /* Tipos de produto */
    Route::resource('tipo-produto', 'TipoProdutoController');
   
    /* Formas de pagamento */
    Route::resource('forma-pagamento', 'FormaPagamentoController');
    
    
    Route::resource('produto-embalagem','ProdutoEmbalagemController');

    /* Imagem */
    Route::resource('imagem/produto/{id}/delete','ImagemController@produtoDelete');
    Route::post('imagem/produtostore/{id}','ImagemController@produtoStore');       
    Route::resource('imagem/produto','ImagemController@produto');       
    Route::resource('imagem/edit','ImagemController@edit');
    Route::resource('imagem/lixeira','ImagemController@lixeira');
    Route::get('imagem/esvaziar-lixeira','ImagemController@esvaziarLixeira');
    Route::resource('imagem','ImagemController'); 
           
    
    /* Nota Fiscal */
    Route::resource('nota-fiscal','NotaFiscalController');           
    
    /* Estoque Movimento */
    Route::resource('estoque-movimento','EstoqueMovimentoController');
    Route::resource('estoque-movimento/create/{codestoquemes?}','EstoqueMovimentoController@create');     
    
    /* Gerador de Codigo */
    Route::get('gerador-codigo/model/{tabela}','GeradorCodigoController@model');       
    //Route::resource('gerador-codigo','GeradorCodigoController');       
      
    
    /* Auxiliares */
    Route::resource('printers','UsuarioController@printers');
    
});
