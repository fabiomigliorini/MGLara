@extends('layouts.default')
@section('content')
<ol class="breadcrumb header">
{!!
    titulo(
        null,
        [
            url("cheque") => 'Cheque',
            'Novo',
        ],
        $model->inativo
    )
!!}
</ol>
<br>
{!! Form::model($model, ['method' => 'POST', 'class' => '', 'id' => 'form-cheque', 'route' => 'cheque.store']) !!}
    @include('errors.form_error')
    @include('cheque.form', ['submitTextButton' => 'Salvar'])
 {!! Form::close() !!}
@stop