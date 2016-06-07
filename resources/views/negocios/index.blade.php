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


<form>
    <div class="row">
        <div class="form-group col-sm-2">
            <input type="email" class="form-control" id="id" name="id" placeholder="#">
        </div>
        <div class="col-sm-5 row">
            <div class="col-sm-6">
                <div class="input-group">
                    <span class="input-group-addon">De</span>
                    <input type="text" class="form-control" id="de" name="de">
                </div>
            </div>
            <div class="col-sm-6">
                <div class="input-group">
                    <span class="input-group-addon">Até</span>
                <input type="text" class="form-control" id="ate" name="ate">
                </div>
            </div>
        </div>
        <div class="form-group col-sm-3">
            <select placeholder="Natureza" class="form-control select-search" name="natureza" id="natureza">
                <option value=""></option>
                <option value="0">Devolução de Compra</option>
            </select>
        </div>
        <div class="form-group col-sm-2">
            <select placeholder="Filial" class="form-control select-search" name="filial" id="filial">
                <option value=""></option>
                <option value="0">Deposito</option>
            </select>
        </div>
    </div>

    <div class="row">
        <div class="form-group col-sm-5">
            <select placeholder="Pessoa" class="form-control select-search" name="pessoa" id="pessoa">
            <option value=""></option>
            <option value="0">Consumidor</option>
            </select>
        </div>
        <div class="col-sm-6 row">
            <div class="form-group col-sm-4">
                <select placeholder="Usuário" class="form-control select-search" name="usuario" id="usuario">
                <option value=""></option>
                <option value="0">Steve Jobs</option>
                </select>
            </div>
            <div class="form-group col-sm-4">
                <select placeholder="Status" class="form-control select-search" name="status" id="status">
                <option value=""></option>
                <option value="0">Aberto</option>
                </select>
            </div>
            <div class="form-group col-sm-4">
                <select placeholder="Pagamento" class="form-control select-search" name="pagamento" id="pagamento">
                <option value=""></option>
                <option value="0">A Vista</option>
                </select>
            </div>
        </div>
        <div class="form-group col-sm-1">
            <button type="submit" class="col-sm-12 btn btn-default">Buscar</button>
        </div>
    </div>
</form>


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
            @foreach($model as $row)
            <tr>
                <td>{{ formataCodigo($row->codnegocio) }}</td>
                <td>{{ formataData($row->lancamento, 'L') }}</td>
                <td>{{ $row->NaturezaOperacao->naturezaoperacao }}</td>
                <td>{{ formataNumero($row->valortotal) }}</td>
                <td>{{ $row->Pessoa->fantasia }}</td>
                <td><small>{{ $row->Filial->filial }}</small></td>
                <td><small>{{ $row->Usuario->usuario }}</small></td>
                <td><small>$row->NegocioStatus->negociostatus</small></td>
                <td><small>$row->PessoaVendedor->fantasia</small></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@section('inscript')
<link rel="stylesheet" href="{{ URL::asset('public/css/negocios.css') }}">
<script src="{{ URL::asset('public/js/negocios.js') }}"></script>
@endsection
@stop
