@extends('layouts.default')
@section('content')
<ol class="breadcrumb header">
{!!
    titulo(
        null,
        [
            url("cheque-motivo-devolucao") => 'Motivos de Devolução',
            'Novo',
        ],
        $model->inativo
    )
!!}
</ol>
<br>
{!! Form::model($model, ['method' => 'POST', 'class' => 'form-horizontal', 'id' => 'form-cheque-motivo-devolucao', 'route' => 'cheque-motivo-devolucao.store']) !!}
    @include('errors.form_error')
    @include('cheque-motivo-devolucao.form', ['submitTextButton' => 'Salvar'])
 {!! Form::close() !!}
@stop