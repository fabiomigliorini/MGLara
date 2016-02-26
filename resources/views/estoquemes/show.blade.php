@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li><a href="{{ url('estoquemes') }}"><span class="glyphicon glyphicon-list-alt"></span> Listagem</a></li>             
        </ul>
    </div>
</nav>

<h1 class="header">Kardex cod: {{ $model->codestoquemes }}</h1>
<hr>
<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th rowspan="2">#</th>
            <th rowspan="2">DATA</th>
            <th rowspan="2">PRODUTO</th>
            <th colspan="2">ENTRADAS</th>
            <th colspan="2">SAIDAS</th>
            <th colspan="2">SALDO</th>
            <th rowspan="2">CUSTO UNIT.</th>
        </tr>
        <tr>
            <th>QTD</th>
            <th>VALOR</th>
            <th>QTD</th>
            <th>VALOR</th>
            <th>QTD</th>
            <th>VALOR</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <th>init</th>
            <td>init</td>
            <td>init</td>
            <td>init</td>
            <td>init</td>
            <td>init</td>
            <td>init</td>
            <td>init</td>
            <td>init</td>
            <td>init</td>
        </tr>        
        @foreach($model->EstoqueMovimento as $row)    
        <tr>
            <th>{{ formataCodigo($row->codestoquemovimento) }}</th>
            <td>{{ dateBRfull($row->data) }}</td>
            <td></td>
            <td>{{ $row->entradaquantidade }}</td>
            <td>{{ $row->entradavalor }}</td>
            <td>{{ $row->saidaquantidade }}</td>
            <td>{{ $row->saidavalor }}</td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        @endforeach
        @if (count($model) === 0)
        <tr>
            <th colspan="10">Nenhum registro encontrado!</th>
        </tr>
        @endif
    <tfoot>
        <tr>
            <th colspan="10">footer</th>
        </tr>
    </tfoot>
    </tbody>
</table>
<hr>
@stop