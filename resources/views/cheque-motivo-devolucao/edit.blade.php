@extends('layouts.default')
@section('content')
<ol class="breadcrumb header">
    {!!
        titulo(
            $model->codchequemotivodevolucao,
            [
                url("cheque-motivo-devolucao") => 'Motivos de Devolução',
                url("cheque-motivo-devolucao", $model->codchequemotivodevolucao) => $model->modelo,
                'Editar'
            ],
            $model->inativo
        )
    !!}
</ol>
<br>
{!! Form::model($model, ['method' => 'PATCH', 'class' => 'form-horizontal', 'id' => 'form-cheque-motivo-devolucao', 'action' => ['ChequeMotivoDevolucaoController@update', $model->codchequemotivodevolucao] ]) !!}
    @include('errors.form_error')
    @include('cheque-motivo-devolucao.form', ['submitTextButton' => 'Salvar'])
{!! Form::close() !!}

@stop