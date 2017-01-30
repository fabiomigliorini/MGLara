@extends('layouts.default')
@section('content')
<ol class="breadcrumb header">
    {!!
        titulo(
            $model->codcheque,
            [
                url("cheque") => 'Cheque',
                url("cheque", $model->codcheque) => $model->modelo,
                'Editar'
            ],
            $model->inativo
        )
    !!}
</ol>
<br>
{!! Form::model($model, ['method' => 'PATCH', 'class' => '', 'id' => 'form-cheque', 'action' => ['ChequeController@update', $model->codcheque] ]) !!}
    @include('errors.form_error')
    @include('cheque.form', ['submitTextButton' => 'Salvar'])
{!! Form::close() !!}
@stop