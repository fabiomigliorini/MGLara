@extends('negocios.template')
@section('navbar')

<li>
    <a href="{{ URL::route('negocios::create') }}"><span class="glyphicon glyphicon-plus"></span> Novo</a>
</li>

@endsection

@section('title')
Negócios
@endsection

@section('body')
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
<?php
$statusStyle = [
    'Aberto'    => 'success',
    'Cancelado' => 'danger',
];
?>
            @foreach($model as $row)
            <tr class="{{ $statusStyle[$row->NegocioStatus->negociostatus] or '' }}">
                <td>{{ formataCodigo($row->codnegocio) }}</td>
                <td nowrap>{{ formataData($row->lancamento, 'L') }}</td>
                <td>{{ $row->NaturezaOperacao->naturezaoperacao }}</td>
                <td>{{ formataNumero($row->valortotal) }}</td>
                <td>{{ $row->Pessoa->fantasia }}</td>
                <td><small>{{ $row->Filial->filial }}</small></td>
                <td><small>{{ $row->Usuario->usuario }}</small></td>
                <td><small>{{ $row->NegocioStatus->negociostatus }}</small></td>
                <td><small>{{ $row->PessoaVendedor->fantasia or '' }}</small></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
