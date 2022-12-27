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

/* Estoque */
Route::get('estoque/calcula-custo-medio/{id}', 'EstoqueController@calculaCustoMedio');
Route::get('estoque/gera-movimento-conferencia/{id}', 'EstoqueController@geraMovimentoConferencia');
Route::get('estoque/gera-movimento-negocio-produto-barra/{id}', 'EstoqueController@geraMovimentoNegocioProdutoBarra');
Route::get('estoque/gera-movimento-negocio/{id}', 'EstoqueController@geraMovimentoNegocio');
Route::get('estoque/gera-movimento-nota-fiscal-produto-barra/{id}', 'EstoqueController@geraMovimentoNotaFiscalProdutoBarra');
Route::get('estoque/gera-movimento-nota-fiscal/{id}', 'EstoqueController@geraMovimentoNotaFiscal');
Route::get('estoque/gera-movimento-produto/{id}', 'EstoqueController@geraMovimentoProduto');
Route::get('estoque/gera-movimento-produto-variacao/{id}', 'EstoqueController@geraMovimentoProdutoVariacao');
Route::get('estoque/gera-movimento-periodo/{inicial}/{final}', 'EstoqueController@geraMovimentoPeriodo');

/* Acessar da rede interna sem autenticacao, ou da rede externa com autenticacao */
Route::group(['middleware' => 'redeconfiavel'], function() {
    Route::get('produto/quiosque', 'ProdutoController@quiosque');
    Route::get('produto/consulta/{barras}', 'ProdutoController@consulta');
    Route::get('produto-barra/listagem-json', 'ProdutoBarraController@listagemJson');
});

Route::group(['middleware' => 'auth'], function() {

    Route::get('estoque/zera-saldo/{id}/{tipo}', 'EstoqueController@zeraSaldo');
    Route::get('estoque/gera-saldo-conferencia-negocio/{id}', 'EstoqueController@geraSaldoConferenciaNegocio');



    /* Página inicial */
    Route::resource('home', 'DashboardController');
    Route::resource('/', 'DashboardController');

    /* Integracao Dominio */
    Route::get('dominio/estoque', 'DominioController@estoque');
    Route::resource('dominio', 'DominioController');

    /* Usuários */
    Route::resource('usuario', 'UsuarioController');
    Route::post('usuario/inativar', 'UsuarioController@inativar');
    Route::resource('usuario/{codusuario}/permissao', 'UsuarioController@permissao');
    Route::post('usuario/attach-permissao', 'UsuarioController@attachPermissao');
    Route::post('usuario/detach-permissao', 'UsuarioController@detachPermissao');

    /* Grupos de usuários */
    Route::resource('grupo-usuario', 'GrupoUsuarioController');
    Route::post('grupo-usuario/attach-permissao', 'GrupoUsuarioController@attachPermissao');
    Route::post('grupo-usuario/detach-permissao', 'GrupoUsuarioController@detachPermissao');

    /* Permissões */
    Route::resource('permissao', 'PermissaoController');

    /* Pessoas */
    Route::get('pessoa/listagem-json', 'PessoaController@listagemJson');
    Route::resource('pessoa', 'PessoaController');

    /* Filiais */
    Route::get('filial/listagem-json', 'FilialController@listagemJson');
    Route::resource('filial', 'FilialController');

    /* Estoque mês */
    Route::resource('estoque-mes', 'EstoqueMesController');

    /* EstoqueSaldo */
    Route::get('estoque-saldo/relatorio-analise-filtro', 'EstoqueSaldoController@relatorioAnaliseFiltro');
    Route::get('estoque-saldo/relatorio-analise', 'EstoqueSaldoController@relatorioAnalise');
    Route::get('estoque-saldo/relatorio-comparativo-vendas-filtro', 'EstoqueSaldoController@relatorioComparativoVendasFiltro');
    Route::get('estoque-saldo/relatorio-comparativo-vendas', 'EstoqueSaldoController@relatorioComparativoVendas');
    Route::get('estoque-saldo/relatorio-fisico-fiscal-filtro', 'EstoqueSaldoController@relatorioFisicoFiscalFiltro');
    Route::get('estoque-saldo/relatorio-fisico-fiscal', 'EstoqueSaldoController@relatorioFisicoFiscal');
    Route::resource('estoque-saldo', 'EstoqueSaldoController');
    Route::get('estoque-saldo/{id}/zera', 'EstoqueSaldoController@zera');

    /* GrupoProduto */
    Route::post('grupo-produto/inativar', 'GrupoProdutoController@inativar');
    Route::resource('grupo-produto/listagem-json', 'GrupoProdutoController@listagemJson');
    Route::resource('grupo-produto/{id}/busca-codproduto', 'GrupoProdutoController@buscaCodproduto');
    Route::resource('grupo-produto', 'GrupoProdutoController');

    /* NCM */
    Route::get('ncm/listagem-json', 'NcmController@listagemJson');
    Route::resource('ncm', 'NcmController');

    /* CEST */
    Route::get('cest/listagem-json', 'CestController@listagemJson');
    Route::resource('cest', 'CestController');

    /* Marca */
    Route::get('marca/listagem-json', 'MarcaController@listagemJson');
    Route::resource('marca/inativar', 'MarcaController@inativar');
    Route::resource('marca/{id}/busca-codproduto', 'MarcaController@buscaCodproduto');
    Route::resource('marca', 'MarcaController');

    /* SubGrupoProduto */
    Route::resource('sub-grupo-produto/{id}/busca-codproduto', 'SubGrupoProdutoController@buscaCodproduto');
    Route::get('sub-grupo-produto/listagem-json', 'SubGrupoProdutoController@listagemJson');
    Route::post('sub-grupo-produto/inativar', 'SubGrupoProdutoController@inativar');
    Route::resource('sub-grupo-produto', 'SubGrupoProdutoController');

    /* Produto */
    Route::get('produto/cobre-estoque-negativo', 'ProdutoController@cobreEstoqueNegativo');
    Route::resource('produto/busca-barras', 'ProdutoController@buscaPorBarras');
    Route::resource('produto/listagem-json', 'ProdutoController@listagemJsonProduto');
    Route::resource('produto/descricao', 'ProdutoController@listagemJsonDescricao');
    Route::resource('produto/popula-secao-produto', 'ProdutoController@populaSecaoProduto');
    Route::resource('produto/estoque-saldo', 'ProdutoController@estoqueSaldo');
    Route::resource('produto/inativar', 'ProdutoController@inativar');
    Route::patch('produto/{id}/transferir-variacao-salvar', 'ProdutoController@transferirVariacaoSalvar');
    Route::get('produto/{id}/transferir-variacao', 'ProdutoController@transferirVariacao');
    Route::resource('produto', 'ProdutoController');
    Route::post('produto/{id}/revisado', 'ProdutoController@revisado');

    /* Magazord Produto */
    Route::get('produto/{id}/magazord/sincroniza', 'ProdutoController@sincronizaProdutoMagazord');
    Route::get('produto/{id}/magazord', 'ProdutoController@editMagazord');
    Route::patch('produto/{id}/magazord', 'ProdutoController@updateMagazord');

    /* Mercos */
    Route::get('mercos/produto/{id}/exporta', 'MercosController@exportaProduto');
    Route::get('mercos/produto/{id}/atualiza', 'MercosController@atualizaProduto');
    Route::get('mercos/pedido/apos/{alterado_apos}', 'MercosController@importaPedidoApos');
    Route::get('mercos/pedido/{codpedido}/faturamento', 'MercosController@exportaFaturamento');
    Route::get('mercos/cliente/apos/{alterado_apos}', 'MercosController@importaClienteApos');

    /* Estoque Saldo Conferencia */
    Route::resource('estoque-saldo-conferencia', 'EstoqueSaldoConferenciaController');

    /* Produto Variacao */
    Route::get('produto-variacao/listagem-json', 'ProdutoVariacaoController@listagemJson');
    Route::resource('produto-variacao', 'ProdutoVariacaoController');

    /* Produto Barra */
    Route::resource('produto-barra', 'ProdutoBarraController');

    /* Produto Embalagem */
    Route::resource('produto-embalagem', 'ProdutoEmbalagemController');

    /* Estoque Local Produto Variacao */
    Route::resource('estoque-local-produto-variacao', 'EstoqueLocalProdutoVariacaoController');

    /* Pais */
    Route::resource('pais', 'PaisController');

    /* Estado */
    Route::resource('estado', 'EstadoController');

    /* Cidades */
    Route::resource('cidade/listagem-json', 'CidadeController@listagemJson');
    Route::resource('cidade', 'CidadeController');

    /* Unidades de medida */
    Route::resource('unidade-medida', 'UnidadeMedidaController');

    /* Portadores */
    Route::resource('portador', 'PortadorController');

    /* Tributações */
    Route::resource('tributacao', 'TributacaoController');

    /* Grupos de cliente */
    Route::resource('grupo-cliente', 'GrupoClienteController');

    /* Tipos de produto */
    Route::resource('tipo-produto', 'TipoProdutoController');

    /* Formas de pagamento */
    Route::resource('forma-pagamento', 'FormaPagamentoController');

    /* Histórico de preços */
    Route::get('produto-historico-preco/relatorio', 'ProdutoHistoricoPrecoController@relatorio');
    Route::resource('produto-historico-preco', 'ProdutoHistoricoPrecoController');

    /* Nota fiscal produto barra */
    Route::resource('nota-fiscal-produto-barra', 'NotaFiscalProdutoBarraController');

    /* Negócio produto barra */
    Route::resource('negocio-produto-barra', 'NegocioProdutoBarraController');

    /* Seção Produto */
    Route::post('secao-produto/inativar', 'SecaoProdutoController@inativar');
    Route::resource('secao-produto', 'SecaoProdutoController');

    /* Família Produto */
    Route::post('familia-produto/inativar', 'FamiliaProdutoController@inativar');
    Route::resource('familia-produto/listagem-json', 'FamiliaProdutoController@listagemJson');
    Route::resource('familia-produto', 'FamiliaProdutoController');

    /* Imagem */
    Route::resource('imagem/{id}/delete', 'ImagemController@delete');
    Route::delete('imagem/produto/{id}/delete', 'ImagemController@produtoDelete');
    Route::post('imagem/produtostore/{id}', 'ImagemController@produtoStore');
    //Route::resource('imagem/produto-imagens','ImagemController@produtoImagens');
    Route::resource('imagem/produto', 'ImagemController@produto');
    Route::resource('imagem/edit', 'ImagemController@edit');
    Route::resource('imagem/lixeira', 'ImagemController@lixeira');
    Route::get('imagem/esvaziar-lixeira', 'ImagemController@esvaziarLixeira');
    Route::post('imagem/inativar', 'ImagemController@inativar');
    Route::resource('imagem', 'ImagemController');

    /* Nota Fiscal */
    Route::get('nota-fiscal/gera-transferencias/{codfilial}', 'NotaFiscalController@geraTransferencias');
    Route::resource('nota-fiscal', 'NotaFiscalController');

    /* Negocio */
    Route::resource('negocio', 'NegocioController');

    /* Metas */
    Route::get('meta/{id}/relatorio', 'MetaController@relatorio');
    Route::resource('meta', 'MetaController');

    /* Estoque Movimento */
    Route::get('estoque-movimento/create/{codestoquemes}', 'EstoqueMovimentoController@create');
    Route::resource('estoque-movimento', 'EstoqueMovimentoController');

    /* Gerador de Codigo */
    Route::get('gerador-codigo/model/{tabela}', 'GeradorCodigoController@model');
    //Route::resource('gerador-codigo','GeradorCodigoController');

    /* Auxiliares */
    Route::resource('printers', 'UsuarioController@printers');

    /* Vale Compras */
    Route::post('vale-compra-modelo/inativar', 'ValeCompraModeloController@inativar');
    Route::resource('vale-compra-modelo', 'ValeCompraModeloController');
    Route::post('vale-compra/inativar', 'ValeCompraController@inativar');
    Route::resource('vale-compra', 'ValeCompraController');
    Route::get('vale-compra/{id}/imprimir', 'ValeCompraController@imprimir');

    Route::resource('/caixa', 'CaixaController@index');

    /* Cheques */
    Route::resource('cheque', 'ChequeController');
    Route::get('cheque/consulta/{cmc7}', 'ChequeController@consulta');
    Route::get('cheque/consultaemitente/{cnpj}', 'ChequeController@consultaemitente');

    Route::resource('cheque-motivo-devolucao', 'ChequeMotivoDevolucaoController');

    Route::resource('cheque-repasse', 'ChequeRepasseController');
    Route::post('cheque-repasse/consulta', 'ChequeRepasseController@consulta');

});

/*
Route::group(['prefix' => 'negocios', 'as' => 'negocios::', 'middleware' => 'auth'], function () {
    Route::get('/', ['as' => 'index', 'uses' => 'NegociosController@index']);

    Route::get('/create', ['as' => 'create', 'uses' => 'NegociosController@create']);
    Route::post('/store', ['as' => 'store', 'uses' => 'NegociosController@store']);

    Route::get('/{id}', ['as' => 'view', 'uses' => 'NegociosController@view']);
});

*/
