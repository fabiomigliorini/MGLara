@extends('layouts.default')
@section('content')
<?php
    $proximos = $model->buscaProximos(8);
    $anteriores = $model->buscaAnteriores(16 - sizeof($proximos));
    if (sizeof($anteriores) < 8) {
        $proximos = $model->buscaProximos(16 - sizeof($anteriores));
    }
?>
<ol class="breadcrumb header">
    {!! titulo(null, 'Metas', null) !!}
    <li class='active'>
        <small>
            <a title="Nova" href="{{ url("meta/create") }}"><i class="glyphicon glyphicon-plus"></i></a>
            <a title="Editar" href="{{ url("meta/create?alterar=$model->codmeta") }}"><i class="glyphicon glyphicon-pencil"></i></a>
        </small>
    </li>   
</ol>
<ul class="nav nav-pills">
    @foreach($anteriores as $meta)
    <li role="presentation"><a href="{{ url("meta/?codmeta=$meta->codmeta") }}">{{ formataData($meta->periodofinal, 'EC') }}</a></li>
    @endforeach
    <li role="presentation" class="active"><a href="#">{{ formataData($model->periodofinal, 'EC') }}</a></li>
    @foreach($proximos as $meta)
    <li role="presentation"><a href="{{ url("meta/?codmeta=$meta->codmeta") }}">{{ formataData($meta->periodofinal, 'EC') }}</a></li>
    @endforeach
</ul>        

<div>
    <br>
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#geral" aria-controls="geral" role="tab" data-toggle="tab">Geral</a></li>
        <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Profile</a></li>
        <li role="presentation"><a href="#messages" aria-controls="messages" role="tab" data-toggle="tab">Messages</a></li>
        <li role="presentation"><a href="#settings" aria-controls="settings" role="tab" data-toggle="tab">Settings</a></li>
    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="geral">
            <p>Tabela geral</p>
        </div>
        <div role="tabpanel" class="tab-pane" id="profile">...</div>
        <div role="tabpanel" class="tab-pane" id="messages">...</div>
        <div role="tabpanel" class="tab-pane" id="settings">...</div>
    </div>
</div>




@section('inscript')
<style type="text/css">
    .tab-pane {
        padding: 10px 0 0;
    }
</style>
<script type="text/javascript">
$(document).ready(function() {
    //...
});
</script>
@endsection
@stop