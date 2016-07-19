@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li><a href="{{ url("estoque-saldo-conferencia") }}"><span class="glyphicon glyphicon-list-alt"></span> Listagem</a></li>
        </ul>
    </div>
</nav>
<h1 class="header">
    {!! 
    titulo(
        NULL ,
        [
            url("estoque-saldo-conferencia") => 'Conferência Saldo de Estoque',
            'Selecione',
        ],
        NULL
    ) 
!!}
</h1>
<hr>
{!! Form::model(null, ['method' => 'GET', 'class' => 'form-horizontal', 'id' => 'form-estoque-saldo-conferencia-selecao', 'route' => 'estoque-saldo-conferencia.create']) !!}

    @include('errors.form_error')
    
    <div class="form-group">
        <div class="col-sm-2">
            {!! Form::label('codestoquelocal', 'Local:') !!}
        </div>
        <div class="col-sm-2">
            {!! Form::select2EstoqueLocal('codestoquelocal', $codestoquelocal, ['class'=> 'form-control', 'required'=>'required', 'id'=>'codestoquelocal', 'style'=>'width:100%']) !!}
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-2">
            {!! Form::label('fiscal', 'Tipo:') !!}
        </div>
        <div class="col-sm-2">
            {!! Form::select2('fiscal', ['0'=>'Fisico', '1'=>'Fiscal'], $fiscal, ['id'=>'fiscal', 'data-off-text' => 'Físico', 'data-on-text' => 'Fiscal', 'style'=>'width:100%']) !!}
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-2">
            {!! Form::label('barras', 'Código de Barras:') !!}
        </div>
        <div class="col-sm-2">
            {!! Form::text('barras', $barras, ['autofocus' => true, 'class'=> 'form-control text-right', 'required'=>'required', 'id'=>'barras', 'placeholder'=>'Código de Barras', 'style'=>'width:100%']) !!}
        </div>
    </div>
    <div class='form-group' style='position:relative'>
        <div class="col-sm-2 col-sm-offset-2">
            {!! Form::submit('Buscar', array('class' => 'btn btn-primary', 'id'=>'Submit')) !!}
        </div>
    </div>


{!! Form::close() !!}   

<hr>

@section('inscript')
<script type="text/javascript">
$(document).ready(function() {

    $('#barras').focus();
    
});

</script>
@endsection



@stop