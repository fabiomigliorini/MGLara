
<nav class="navbar navbar-inverse navbar-fixed-top">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="">MGSis</a>
    </div>
    <div id="navbar" class="collapse navbar-collapse">
      <ul class="nav navbar-nav">
        <li><a href="<?php echo url('/');?>">Dashboard</a></li>
        <!-- 
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Comercial <span class="caret"></span></a>
            <ul class="dropdown-menu">
                <li><a href="<?php echo url('negocio');?>">Negócios</a></li>
                <li><a href="#">Notas Fiscais</a></li>
                <li><a href="#">NFe de Terceiros</a></li>
            </ul>
        </li>
        -->
        <!--
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Financeiro <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="#">Pessoas</a><li>
            <li role="separator" class="divider"></li>
            <li><a href="#">Liquidações</a><li>
            <li><a href="#">Titulos</a><li>
            <li><a href="#">Agrupamentos</a><li>
            <li role="separator" class="divider"></li>
            <li><a href="#">Bancos</a><li>
            <li><a href="#">Cheques</a><li>
            <li><a href="#">Formas de pagamento</a><li>
            <li><a href="#">Grupos de cliente</a><li>
            <li><a href="#">Portadores</a><li>
            <li><a href="#">Tipo movimento titulos</a><li>
            <li><a href="#">Tipo titulos</a><li>
          </ul>
        </li>
        -->
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Estoque <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="{{ url('estoque-mes') }}">Estoque mês</a></li>
            <!-- <li role="separator" class="divider"></li> -->
            <!-- <li><a href="#">Consulta de preço</a></li> -->
            <li><a href="<?php echo url('produto');?>">Produtos</a><li>
            <!-- <li role="separator" class="divider"></li> -->
            <!-- <li><a href="#">Etiquetas de Produtos</a></li> -->
            <!-- <li role="separator" class="divider"></li> -->
            <!-- <li><a href="#">Histórico de Preços</a></li> -->
            <!-- <li role="separator" class="divider"></li> -->
            <!-- <li><a href="<?php echo url('grupo-produto');?>">Grupos de Produtos</a><li> -->
            <!-- <li><a href="<?php echo url('marca');?>">Marcas</a><li> -->
            <!-- <li><a href="#">NCM</a></li> -->
            <!-- <li><a href="#">Tipos de produtos</a></li> -->
            <!-- <li><a href="#">Unidades de medida</a></li> -->
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
            <li><a href="#">Países, estados, e cidades</a></li>
            <li><a href="#">Tributações</a></li>
          </ul>
        </li>
        -->        
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Admin <span class="caret"></span></a>
            <ul class="dropdown-menu">
                <li><a href="<?php echo url('usuario');?>">Usuários</a></li>
                <li><a href="<?php echo url('grupo-usuario');?>">Grupos</a></li>
                <li><a href="<?php echo url('permissao');?>">Permissões</a></li>
            </ul>
        </li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{ Auth::user()->usuario }} <span class="caret"></span></a>
            <ul class="dropdown-menu">
                <li><a href="<?php echo url('usuario/'.Auth::user()->codusuario);?>">Perfil</a></li>
                <li><a href="<?php echo url('auth/logout');?>">Sair</a></li>
            </ul>
        </li> 
      </ul>  
    </div>
  </div>
</nav>
