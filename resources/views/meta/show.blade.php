@extends('layouts.default')
@section('content')
<ol class="breadcrumb header">
    {!! 
        titulo(
            $model->codmeta,
            [
                url("meta") => 'Metas',
                formataData($model->periodofinal, 'EC'),
            ],
            null
        ) 
    !!} 
    <li class='active'>
        <small>
            <a title="Novo" href="{{ url('meta/create') }}"><i class="glyphicon glyphicon-plus"></i></a>
            &nbsp;
            <a title="Editar" href="{{ url("meta/$model->codmeta/edit") }}"><i class="glyphicon glyphicon-pencil"></i></a>
            &nbsp;
            <a title="Excluir" href="{{ url("meta/$model->codmeta") }}" data-excluir data-pergunta="Tem certeza que deseja excluir a meta '{{ $model->observacoes }}'?" data-after-delete="location.replace(baseUrl + '/meta');"><i class="glyphicon glyphicon-trash"></i></a>
        </small>
    </li>   
</ol>
<?php
    $proximos = $model->buscaProximos(8);
    $anteriores = $model->buscaAnteriores(16 - sizeof($proximos));
    if (sizeof($anteriores) < 8) {
        $proximos = $model->buscaProximos(16 - sizeof($anteriores));
    }
?>
<ul class="nav nav-pills">
    @foreach($anteriores as $meta)
    <li role="presentation"><a href="{{ url("meta/$meta->codmeta") }}">{{ formataData($meta->periodofinal, 'EC') }}</a></li>
    @endforeach
    <li role="presentation" class="active"><a href="#">{{ formataData($model->periodofinal, 'EC') }}</a></li>
    @foreach($proximos as $meta)
    <li role="presentation"><a href="{{ url("meta/$meta->codmeta") }}">{{ formataData($meta->periodofinal, 'EC') }}</a></li>
    @endforeach
</ul>        
<div>
    <br>
    <?php
        $metasfiliais = $model->MetaFilialS()->get();
    ?>
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#geral" aria-controls="geral" role="tab" data-toggle="tab">Geral</a></li>
        @foreach($metasfiliais as $metafilial)
        <li role="presentation"><a href="#{{ $metafilial->codfilial }}" aria-controls="{{ $metafilial->codfilial }}" role="tab" data-toggle="tab">{{ $metafilial->Filial->filial }}</a></li>
        @endforeach
    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="geral">
            <p>Tabela geral</p>
        </div>
        @foreach($metasfiliais as $metafilial)
        <div role="tabpanel" class="tab-pane" id="{{ $metafilial->codfilial }}">Tabela do {{ $metafilial->Filial->filial }}</div>
        @endforeach
    </div>
</div>

@section('inscript')
<script type="text/javascript">
$(document).ready(function() {
    // ...
});
</script>
@endsection
@stop