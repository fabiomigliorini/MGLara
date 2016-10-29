@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li><a href="{{ url('pessoa') }}"><span class="glyphicon glyphicon-list-alt"></span> Listagem</a></li>             
            <li><a href="{{ url('pessoa/create') }}"><span class="glyphicon glyphicon-plus"></span> Novo</a></li>             
            <li><a href="{{ url("pessoa/$model->codpessoa/edit") }}"><span class="glyphicon glyphicon-pencil"></span> Alterar</a></li> 
            <li>
                {!! Form::open(['method' => 'DELETE', 'id'=>'deleteId', 'route' => ['pessoa.destroy', $model->codpessoa]]) !!}
                <span class="glyphicon glyphicon-trash"></span>
                {!! Form::submit('Excluir') !!}
                {!! Form::close() !!}
            </li>
        </ul>
    </div>
</nav>
<ol class="breadcrumb header">{{ $model->pessoa }}</ol>
<hr>
<div class="row">
    <div class="col-lg-6">
        <table class="detail-view table table-striped table-condensed"> 
            <tbody>  
                <tr> 
                    <th class="col-md-4">#</th> 
                    <td class="col-md-8">{{ formataCodigo($model->codpessoa) }}</td> 
                </tr>
                <tr> 
                    <th>Razão Social</th> 
                    <td>{{ $model->pessoa }}</td> 
                </tr>
                <tr> 
                    <th>Telefone</th> 
                    <td>
                        @if(!$model->cobrancanomesmoendereco)
                            {{ $model->telefone1 }}
                            @if($model->telefone2) {{ '/ '.$model->telefone2 }}@endif
                            @if($model->telefone3) {{ '/ '.$model->telefone3 }}@endif
                        @endif
                        
                    </td> 
                </tr>
                <tr> 
                    <th>Contato</th> 
                    <td>{{ $model->contato }}</td> 
                </tr>
                <tr> 
                    <th>CNPJ/CPF</th> 
                    <td>{{ $model->cnpj }}</td> 
                </tr>
                <tr> 
                    <th>Inscrição Estadual</th> 
                    <td>{{ $model->ie }}</td> 
                </tr>
                <tr> 
                    <th>Endereço</th> 
                    <td>{!! formataEndereco($model->endereco, $model->numero, $model->complemento, $model->bairro, $model->Cidade->cidade, $model->Cidade->Estado->sigla, $model->cep) !!}</td> 
                </tr>
                @if($model->email) 
                <tr> 
                    <th>Email</th> 
                    <td>{{ $model->email }}</td> 
                </tr>
                @endif
                @if($model->emailnfe) 
                <tr> 
                    <th>Email NFE</th> 
                    <td>{{ $model->emailnfe }}</td> 
                </tr>
                @endif
                @if($model->emailcobranca) 
                <tr> 
                    <th>Email cobrança</th> 
                    <td>{{ $model->emailcobranca }}</td> 
                </tr>
                @endif
                @if($model->fisica)
                <tr> 
                    <th>RG</th> 
                    <td>{{ $model->rg}}</td> 
                </tr>                
                <tr> 
                    <th>Sexo</th> 
                    <td>{{ $model->Sexo->sexo or ''}}</td> 
                </tr>
                <tr> 
                    <th>Estado civil</th> 
                    <td>{{ $model->EstadoCivil->estadocivil or '' }}</td> 
                </tr>                
                <tr> 
                    <th>Conjuge</th> 
                    <td>{{ $model->conjuge}}</td> 
                </tr>                
                @endif
            </tbody> 
        </table>
    </div>    
    <div class="col-lg-6">
        <table class="detail-view table table-striped table-condensed"> 
            <tbody>  
                <tr> 
                    <th class="col-md-4">Cliente</th> 
                    <td class="col-md-8">{{ ($model->cliente) ? 'Sim' : 'Não' }}</td> 
                </tr>
                @if($model->cliente)
                <?php $total = $model->totalTitulos();?>
                <tr> 
                    <th>Grupo de Cliente</th> 
                    <td>
                        @if($model->codgrupocliente)
                        <a href="{{ url("grupo-cliente/$model->codgrupocliente") }}">{{ $model->GrupoCliente->grupocliente }}</a>
                        @endif
                    </td> 
                </tr>
                <tr> 
                    <th>Nota Fiscal</th> 
                    <td>
                        {{ $model->getNotaFiscalDescricao() }}
                    </td> 
                </tr>
                <tr> 
                    <th>Consumidor Final</th> 
                    <td>{{ ($model->consumidor) ? 'Sim' : 'Não' }}</td> 
                </tr>
                <tr> 
                    <th>Forma de Pagamento</th> 
                    <td>
                        {{ $model->FormaPagamento->formapagamento or '' }}
                    </td> 
                </tr>
                <tr> 
                    <th>Desconto</th> 
                    <td>{{ ($model->desconto) ? formataNumero($model->desconto).'%' : '' }}</td> 
                </tr>
                <tr> 
                    <th>Credito Bloqueado</th> 
                    <td>{{ ($model->creditobloqueado) ? 'Sim' : 'Não' }}</td> 
                </tr>
                <tr> 
                    <th>Saldo em Aberto</th> 
                    <td>{{ formataNumero($total->saldo) }}</td> 
                </tr>
                <tr> 
                    <th>Limite de Credito</th> 
                    <td>{{ formataNumero($model->credito) or '' }}</td> 
                </tr>
                <tr> 
                    <th>Primeiro Vencimento</th> 
                    <td>{{ formataData($total->vencimento) }} ({{ $total->vencimentodias }} Dias)</td> 
                </tr>
                <tr> 
                    <th>Tolerância de Atraso</th> 
                    <td>{{ $model->toleranciaatraso }} Dias</td> 
                </tr>
                <tr> 
                    <th>Mensagem de Venda</th> 
                    <td>{{ $model->mensagemvenda }}</td> 
                </tr>
                @endif
                <tr> 
                    <th>Observações</th> 
                    <td>{!! nl2br($model->observacoes) !!}</td> 
                </tr>
                <tr> 
                    <th>Vendedor</th> 
                    <td>{{ ($model->vendedor) ? 'Sim' : 'Não' }}</td> 
                </tr>
                <tr> 
                    <th>Fornecedor</th> 
                    <td>{{ ($model->fornecedor) ? 'Sim' : 'Não' }}</td> 
                </tr>
            </tbody> 
        </table>
    </div>    

</div>
@include('includes.autor')
<hr>
<div>
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active">
            <a href="#cobranca" aria-controls="cobranca" role="tab" data-toggle="tab">Histórico de Cobrança</a>
        </li>
        <li role="presentation">
            <a href="#spc" aria-controls="spc" role="tab" data-toggle="tab">Registro SPC</a>
        </li>
    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="cobranca">
            @include('pessoa.cobranca')
        </div>
        <div role="tabpanel" class="tab-pane" id="spc">
            @include('pessoa.spc')
        </div>
    </div>
</div>
@stop