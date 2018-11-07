@extends('layouts.default')
@section('content')
<ol class="breadcrumb header">
    {!!
        titulo(
            $model->codcheque,
            [
                url("cheque") => 'Cheque',
                $model->cheque
            ],
            $model->inativo
        )
    !!}
    <li class='active'>
        <small>
            <a title="Novo" href="{{ url('cheque/create') }}"><span class="glyphicon glyphicon-plus"></span></a>
            @if (!empty($model->indstatus<>2 and $model->indstatus<>5))
            &nbsp;
            <a title="Alterar" href="{{ url("cheque/$model->codcheque/edit") }}"><span class="glyphicon glyphicon-pencil"></span></a>
            &nbsp;
            <a title='Excluir' href="{{ url("cheque/$model->codcheque") }}" data-excluir data-pergunta="Tem certeza que deseja excluir o cheque '{{ $model->modelo }}'?" data-after-delete="location.replace(baseUrl + '/cheque');"><i class="glyphicon glyphicon-trash"></i></a>
            @endif
        </small>
    </li>
</ol>
<div class="row">
    <div class="col-md-6">
        <ul class="list-group list-group-condensed list-group-hover list-group-striped" id='divListagemProdutos'>
              <li class='list-group-item'>
                <div class='row'>
                    <div class='col-md-4'>
                      #
                    </div>
                    <div class='col-md-8'>
                      {{ formataCodigo($model->codcheque) }}
                    </div>
                </div>
              </li>

              <li class='list-group-item'>
                <div class='row'>
                    <div class='col-md-4'>
                      Valor
                    </div>
                    <div class='col-md-8'>
                      R$ {{ formataNumero($model->valor, 2) }}
                    </div>
                </div>
              </li>
              <li class='list-group-item'>
                <div class='row'>
                    <div class='col-md-4'>
                      Data de Emissão
                    </div>
                    <div class='col-md-8'>
                      {{ formataData($model->emissao) }}
                    </div>
                </div>
              </li>
              <li class='list-group-item'>
                <div class='row'>
                    <div class='col-md-4'>
                      Data de Vencimento
                    </div>
                    <div class='col-md-8'>
                      {{ formataData($model->vencimento) }}
                    </div>
                </div>
              </li>
              <li class='list-group-item'>
                <div class='row'>
                    <div class='col-md-4'>
                      CMC7
                    </div>
                    <div class='col-md-8'>
                      {{ $model->cmc7 }}
                    </div>
                </div>
              </li>
              <li class='list-group-item'>
                <div class='row'>
                    <div class='col-md-4'>
                      Pessoa
                    </div>
                    <div class='col-md-8'>
                      <a href="{{ url('pessoa', $model->codpessoa) }}">
                      {{ $model->Pessoa->pessoa }}
                      </a>
                    </div>
                </div>
              </li>
        </ul>
        <div class="panel panel-default">
            <div class="panel-heading"><strong>Emitentes</strong></div>
            <table class="table">
                @foreach ($model->ChequeEmitenteS as $emit)
                <tr>
                    <td width='140'>{{ formataCpfCnpj($emit->cnpj) }}</td>
                    <td>{{ $emit->emitente }}</td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
    <div class="col-md-6">
        <ul class="list-group list-group-condensed list-group-hover list-group-striped" id='divListagemProdutos'>
              <li class='list-group-item'>
                <div class='row'>
                    <div class='col-md-3'>
                      Banco
                    </div>
                    <div class='col-md-8'>
                      {{ $model->Banco->banco }}
                    </div>
                </div>
              </li>
              <li class='list-group-item'>
                <div class='row'>
                    <div class='col-md-3'>
                      Agencia
                    </div>
                    <div class='col-md-8'>
                      {{ $model->agencia }}
                    </div>
                </div>
              </li>
              <li class='list-group-item'>
                <div class='row'>
                    <div class='col-md-3'>
                      Conta Corrente
                    </div>
                    <div class='col-md-8'>
                      {{ $model->contacorrente }}
                    </div>
                </div>
              </li>
              <li class='list-group-item'>
                <div class='row'>
                    <div class='col-md-3'>
                        Número do cheque
                    </div>
                    <div class='col-md-8'>
                      {{ $model->numero }}
                    </div>
                </div>
              </li>
              <li class='list-group-item'>
                <div class='row'>
                    <div class='col-md-3'>
                        Status
                    </div>
                    <div class='col-md-8'>
                        <span class='label {{ $indstatus_class[$model->indstatus] }}'>
                         {{ $indstatus_descricao[$model->indstatus] }}
                         </span>
                    </div>
                </div>
              </li>
              <li class='list-group-item'>
                <div class='row'>
                    <div class='col-md-3'>
                        Observação
                    </div>
                    <div class='col-md-8'>
                    @if (!empty($model->observacao))
                        {{ $model->observacao }}
                    @else
                        Não há observações cadastradas.
                    @endif
                    </div>
                </div>
              </li>
        </ul>
    </div>
</div>
@stop
