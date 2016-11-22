@extends('layouts.default')
@section('content')
<ol class="breadcrumb header">
{!! 
    titulo(
        null,
        [
            url("meta") => 'Metas',
            'Nova Meta',
        ],
        null
    ) 
!!}
</ol>
<hr>
<br>
{!! Form::model($model, ['method' => 'POST', 'class' => 'form-horizontal', 'id' => 'form-meta', 'route' => 'meta.store']) !!}
    @include('errors.form_error')
    @include('meta.form', ['submitTextButton' => 'Salvar'])
 {!! Form::close() !!}   
@stop