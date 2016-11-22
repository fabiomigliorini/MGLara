@extends('layouts.default')
@section('content')
<ol class="breadcrumb header">
    {!! titulo(null, 'Metas', null) !!}
    <li class='active'>
        <small>
            <a title="Nova" href="{{ url("meta/create") }}"><i class="glyphicon glyphicon-plus"></i></a>
            &nbsp;
            <a class="" data-toggle="collapse" href="#div-filtro" aria-expanded="false" aria-controls="div-filtro"><span class='glyphicon glyphicon-search'></span></a>
        </small>
    </li>   
</ol>
<div class="clearfix"></div>
<div class='collapse' id='div-filtro'>
    <div class='well well-sm' style="padding:9px 0">
    {!! Form::model(
        Request::session()->get('marca.index'), 
        [
            'route' => 'marca.index', 
            'method' => 'GET', 
            'class' => 'form-horizontal', 
            'id' => 'marca-search', 
            'role' => 'search', 
            'autocomplete' => 'off'
        ]
    )!!}
        <div class="col-md-2">
            <div class="form-group">
                {!! Form::label('codmarca', '#', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-md-8">{!! Form::text('codmarca', null, ['class' => 'form-control', 'placeholder' => '#']) !!}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {!! Form::label('marca', 'Marca', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-md-10">{!! Form::text('marca', null, ['class' => 'form-control', 'placeholder' => 'Marca']) !!}</div>
            </div>
        </div>
        <div class="col-md-2">      
            <div class="form-group">
                {!! Form::label('ativo', 'Ativo', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-md-9">{!! Form::select2Ativo('ativo', null, ['class'=> 'form-control', 'id' => 'ativo']) !!}</div>
            </div>      
        </div>
        <div class="col-md-2">      
            <div class="form-group">
                <div class="col-md-offset-2 col-md-10">
                    <button type="submit" class="btn btn-default"><i class="glyphicon glyphicon-search"></i> Buscar</button>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
    {!! Form::close() !!}
</div>
@section('inscript')
<script type="text/javascript">
$(document).ready(function() {
    //...
});
</script>
@endsection
@stop