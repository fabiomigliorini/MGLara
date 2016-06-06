@extends('layouts.default')
@section('content')

<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
    <div class="container-fluid">
        <ul class="nav navbar-nav">
            <li>
                <a href="http://mglara.local/MGLara/grupo-cliente/create"><span class="glyphicon glyphicon-plus"></span> Novo</a>
            </li>
        </ul>
    </div>
</nav>
<h1 class="header">Negócios</h1>
<hr>


<form class="form-inline">
  <div class="form-group">
    <input type="email" class="form-control" id="id" name="id" placeholder="#">
  </div>
  <div class="input-group">
    <span class="input-group-addon">De</span>
    <input type="text" class="form-control" id="de" name="de">
  </div>
  <div class="input-group">
    <span class="input-group-addon">Até</span>
    <input type="text" class="form-control" id="ate" name="ate">
  </div>
    <select placeholder="Natureza" class="form-control select-search input-large" name="natureza" id="natureza">
        <option value=""></option>
        <option value="0">Devolução de Compra</option>
    </select>
    <select placeholder="Pessoa" class="form-control select-search input-xlarge" name="pessoa" id="pessoa">
        <option value=""></option>
        <option value="0">Consumidor</option>
    </select>
    <select placeholder="Filial" class="form-control select-search input-medium" name="filial" id="filial">
        <option value=""></option>
        <option value="0">Deposito</option>
    </select>
    <select placeholder="Usuário" class="form-control select-search input-medium" name="usuario" id="usuario">
        <option value=""></option>
        <option value="0">Steve Jobs</option>
    </select>
    <select placeholder="Status" class="form-control select-search input-medium" name="status" id="status">
        <option value=""></option>
        <option value="0">Aberto</option>
    </select>
    <select placeholder="Pagamento" class="form-control select-search input-medium" name="pagamento" id="pagamento">
        <option value=""></option>
        <option value="0">A Vista</option>
    </select>

  <button type="submit" class="btn btn-default">Buscar</button>
</form>

<hr>


<div class="panel panel-default">
    <table class="table table-hover table-striped">
        <tbody>
            <tr>
                <th>#ID</th>
                <th>Data</th>
                <th>Natureza</th>
                <th>Valor</th>
                <th>Pessoa</th>
                <th><small>Filial</small></th>
                <th><small>Usuário</small></th>
                <th><small>Status</small></th>
                <th><small>Cliente</small></th>
            </tr>
        </tbody>
        <tbody>
            @for ($i = 0; $i < 10; $i++)
            <tr>
                <td>#00411920</td>
                <td>31/05/2016 13:22:25</td>
                <td>Venda</td>
                <td>6,00</td>
                <td>Consumidor</td>
                <td><small>Deposito</small></td>
                <td><small>Steve Jobs</small></td>
                <td><small>Aberto</small></td>
                <td><small>Cliente</small></td>
            </tr>
            @endfor
        </tbody>
    </table>
</div>

@section('inscript')
<link rel="stylesheet" href="{{ URL::asset('public/css/negocios.css') }}">
<script src="{{ URL::asset('public/js/negocios.js') }}"></script>
@endsection
@stop
