@extends('layouts.default')
@section('content')
<ol class="breadcrumb header">
    {!!
        titulo(
            $model->codchequemotivodevolucao,
            [
                url("cheque-motivo-devolucao") => 'Motivos de Devolução',
                $model->chequemotivodevolucao
            ],
            $model->inativo
        )
    !!}
    <li class='active'>
        <small>
            <a title="Novo" href="{{ url('cheque-motivo-devolucao/create') }}"><span class="glyphicon glyphicon-plus"></span></a>
            &nbsp;
            <a title="Alterar" href="{{ url("cheque-motivo-devolucao/$model->codchequemotivodevolucao/edit") }}"><span class="glyphicon glyphicon-pencil"></span></a>

            &nbsp;
            <a title='Excluir' href="{{ url("cheque-motivo-devolucao/$model->codchequemotivodevolucao") }}" data-excluir data-pergunta="Tem certeza que deseja excluir o modelo '{{ $model->modelo }}'?" data-after-delete="location.replace(baseUrl + '/cheque-motivo-devolucao');"><i class="glyphicon glyphicon-trash"></i></a>

        </small>
    </li>
</ol>

<ul class="list-group list-group-condensed list-group-hover list-group-striped" id='divListagemProdutos'>
      <li class='list-group-item'>
        <div class='row'>
            <div class='col-md-4'>
              #
            </div>
            <div class='col-md-8'>
              {{ formataCodigo($model->codchequemotivodevolucao) }}
            </div>
        </div>
      </li>

      <li class='list-group-item'>
        <div class='row'>
            <div class='col-md-4'>
              Número
            </div>
            <div class='col-md-8'>
              {{ formataNumero($model->numero, 0) }}
            </div>
        </div>
      </li>
      <li class='list-group-item'>
        <div class='row'>
            <div class='col-md-4'>
              Descrição
            </div>
            <div class='col-md-8'>
              {{ $model->chequemotivodevolucao }}
            </div>
        </div>
      </li>
</ul>

@stop