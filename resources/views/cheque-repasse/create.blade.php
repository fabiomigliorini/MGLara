@extends('layouts.default')
@section('content')
<ol class="breadcrumb header">
{!!
    titulo(
        null,
        [
            url("cheque-repasse") => 'Cheque Repasse',
            'Novo',
        ],
        $model->inativo
    )
!!}
</ol>


{!! Form::model($model, ['method' => 'POST', 'class' => '', 'id' => 'form-cheque-repasse', 'route' => 'cheque-repasse.store']) !!}
    @include('errors.form_error')
    @include('cheque-repasse.form', ['submitTextButton' => 'Salvar'])
 {!! Form::close() !!}
@stop