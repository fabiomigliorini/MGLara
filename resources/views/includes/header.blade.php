<!-- Fixed navbar -->
<nav class="navbar navbar-default navbar-fixed-top" style="background: yellow">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ url('home') }}">
                <img src="/MGLara/public/img/MGPapelaria.png" height="100%">
            </a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Comercial <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ url('caixa') }}">Totais de Caixa</a><li>
                        <li role="separator" class="divider"></li>
                        <li><a href="{{ url('vale-compra') }}">Vale Compras</a><li>
                        <li><a href="{{ url('vale-compra-modelo') }}">Modelos de Vale Compras</a><li>
                        <li role="separator" class="divider"></li>
                        <li><a href="{{ url('meta') }}">Metas</a><li>
                    </ul>
                </li>
                <!--
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Financeiro <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ url('pessoa') }}">Pessoas</a><li>
                        <li role="separator" class="divider"></li>
                        <li><a href="#">Liquidações</a><li>
                        <li><a href="#">Titulos</a><li>
                        <li><a href="#">Agrupamentos</a><li>
                        <li role="separator" class="divider"></li>
                        <li><a href="#">Bancos</a><li>
                        <li><a href="#">Cheques</a><li>
                        <li><a href="{{ url('forma-pagamento') }}">Formas de pagamento</a><li>
                        <li><a href="{{ url('grupo-cliente') }}">Grupos de cliente</a><li>
                        <li><a href="{{ url('portador') }}">Portadores</a><li>
                        <li><a href="#">Tipo movimento titulos</a><li>
                        <li><a href="#">Tipo titulos</a><li>
                    </ul>
                </li>
                -->
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Estoque <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <!-- <li><a href="{{ url('estoque-mes') }}">Estoque mês</a></li> -->
                        <!-- <li role="separator" class="divider"></li> -->
                        <!-- <li><a href="#">Consulta de preço</a></li> -->
                        <li><a href="{{ url('produto') }}">Produtos</a><li>
                        <li role="separator" class="divider"></li>
                        <li><a href="{{ url('produto/quiosque') }}">Consulta de Preços</a><li>
                            <!-- <li role="separator" class="divider"></li> -->
                            <!-- <li><a href="#">Etiquetas de Produtos</a></li> -->
                        <li role="separator" class="divider"></li>
                        <li><a href="{{ url('produto-historico-preco') }}">Histórico de Preços</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="{{ url('estoque-saldo') }}">Saldos de Estoque</a></li>
                        <li><a href="{{ url('estoque-saldo/relatorio-analise-filtro') }}">Relatório Análise Saldos de Estoque</a></li>
                        <li><a href="{{ url('estoque-saldo/relatorio-comparativo-vendas-filtro') }}">Relatório Vendas Filial X Saldo Depósito</a></li>
                        <li><a href="{{ url('estoque-saldo/relatorio-fisico-fiscal-filtro') }}">Relatório Saldos Fisico x Fiscal</a></li>
                        <li><a href="{{ url('estoque-saldo/relatorio-transferencias-filtro') }}">Relatório Transferência de Estoque</a></li>
			<!--
                        <li><a href="{{ url('estoque-saldo-conferencia') }}">Conferência de Saldos</a></li>
			-->
                        <li role="separator" class="divider"></li>
                        <li><a href="{{ url('marca') }}">Marcas</a><li>
                        <li><a href="{{ url('secao-produto') }}">Seções de Produto</a><li>
                        <li><a href="{{ url('tipo-produto') }}">Tipos de produtos</a></li>
                        <li><a href="{{ url('unidade-medida') }}">Unidades de medida</a></li>
                        <li><a href="{{ url('ncm') }}">NCM</a></li>
                    </ul>
                </li>
                <!--
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Fiscal <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="#">CFOP</a></li>
                        <li><a href="#">Contas contábeis</a></li>
                        <li><a href="#">Empresas</a></li>
                        <li><a href="#">Naturezas de operação</a></li>
                        <li><a href="{{ url('pais') }}">Países, estados, e cidades</a></li>
                        <li><a href="{{ url('tributacao') }}">Tributações</a></li>
                    </ul>
                </li>
                -->
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Financeiro <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ url('cheque') }}">Cheques</a></li>
                        <li><a href="{{ url('cheque-repasse') }}">Cheque Repasse</a></li>
                        <li><a href="{{ url('cheque-motivo-devolucao') }}">Motivos de Devolução de Cheques</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Admin <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ url('usuario') }}">Usuários</a></li>
                        <li><a href="{{ url('grupo-usuario') }}">Grupos</a></li>
                        <li><a href="{{ url('permissao') }}">Permissões</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="{{ url('imagem') }}">Imagens</a></li>
                    </ul>
                </li>
                <li class="">
                    <a href="{{env('MGSIS_URL')}}">MGsis</span></a>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{ Auth::user()->usuario }} <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="<?php echo url('usuario/' . Auth::user()->codusuario); ?>">Perfil</a></li>
                        <li><a href="<?php echo '/MGLara/auth/logout'; ?>">Sair</a></li>
                    </ul>
                </li>
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</nav>
