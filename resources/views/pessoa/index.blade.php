@extends('layouts.default')
@section('content')
<?php
    use MGLara\Models\GrupoCliente;
    $grupos = [''=>''] + GrupoCliente::lists('grupocliente', 'codgrupocliente')->all();
?>
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li>
                <a href="{{ url("pessoa/create") }}"><span class="glyphicon glyphicon-plus"></span> Novo</a>
            </li> 
        </ul>
    </div>
</nav>
<h1 class="header">Pessoas</h1>
<hr>
<div class="search-bar">
{!! Form::model(Request::all(), ['route' => 'pessoa.index', 'method' => 'GET', 'class' => 'form-inline', 'id' => 'pessoa-search', 'role' => 'search', 'autocomplete' => 'off'])!!}
    <div class="form-group">
        {!! Form::text('codpessoa', null, ['class' => 'form-control search-cod', 'placeholder' => '#']) !!}
    </div>
    <div class="form-group">
        {!! Form::text('pessoa', null, ['class' => 'form-control', 'placeholder' => 'Nome']) !!}
    </div>
<div class="form-group">
        {!! Form::text('cnpj', null, ['class' => 'form-control', 'placeholder' => 'CNPJ/CPF', ' style'=>'width: 120px']) !!}
    </div>        
    <div class="form-group">
        {!! Form::text('email', null, ['class' => 'form-control', 'placeholder' => 'E-mail', ' style'=>'width: 120px']) !!}
    </div>        
    <div class="form-group">
        {!! Form::text('telefone', null, ['class' => 'form-control', 'placeholder' => 'Fone', ' style'=>'width: 120px']) !!}
    </div>
    <div class="form-group">
        <select placeholder="Inativo" class="form-control" name="inativo" id="inativo" style="width: 120px;">
            <option value=""></option>
            <option value="0">Todos</option>
            <option value="1">Ativos</option>
            <option value="2">Inativos</option>
        </select>
    </div>
    <div class="form-group">
        {!! Form::text('cidade', null, ['class' => 'form-control', 'id'=> 'cidade', 'style'=> 'width: 180px;']) !!}
    </div>
    <div class="form-group">
        <div style="width: 180px">{!! Form::select('grupocliente', $grupos, ['class'=> 'form-control'], ['id' => 'grupocliente', 'style'=>'width:100%']) !!}</div>
    </div>        
    <button type="submit" class="btn btn-default">Buscar</button>
{!! Form::close() !!}
</div>

<br>
<div id="registros">
  <div class="list-group" id="items">
    @foreach($model as $row)
      <div class="list-group-item" @if(!empty($row->inativo)) style="background:#f2dede" @endif>
        <div class="row item">
            <div class="col-md-4">
                <a href="{{ url("pessoa/$row->codpessoa") }}">{{ $row->fantasia }}</a>
                @if(!empty($row->inativo))
                    <br>
                    <span class="label label-danger pull-right">Inativado em {{ formataData($row->inativo, 'L')}} </span>
                @endif
                <br>
                @if(!empty($row->inclusaoSpc))
                    <span class="label label-warning pull-right">SPC em {{ $row->inclusaoSpc }}</span>
                @endif
                <small class="muted">
                    <b>{{ $row->pessoa }}</b>
                </small>
                <br>
                <small class="muted">
                    <a href="{{ url("pessoa/$row->codpessoa") }}">{{ formataCodigo($row->codpessoa) }}</a>
                </small>
                @if(!empty($row->contato))
                    <br>
                    <small class="muted">
                        {{ $row->contato }}
                    </small>
                @endif
                @if(!empty($row->codgrupocliente))
                <br>
                <small class="muted">
                    <a href="{{ url("grupo-cliente/{$row->GrupoCliente->codgrupocliente}") }}">{{ $row->GrupoCliente->grupocliente }}</a>
                </small>
                @endif
            </div>                            
            <div class="col-md-6">
                {{ $row->telefone1 }}
                @if($row->telefone2) {{ '/ '.$row->telefone2 }}@endif
                @if($row->telefone3) {{ '/ '.$row->telefone3 }}@endif
                <div class="muted small">
                    {!! formataEndereco($row->endereco, $row->numero, $row->complemento, $row->bairro, $row->Cidade->cidade, $row->Cidade->Estado->sigla, $row->cep) !!}
                </div>
                @if(!$row->cobrancanomesmoendereco)
                <div class="muted small">
                    {{ formataEndereco($row->enderecocobranca, $row->numerocobranca, $row->complementocobranca, $row->bairrocobranca, $row->CidadeCobranca->cidade, $row->CidadeCobranca->Estado->sigla, $row->cepcobranca) }}
                </div>
                @endif
                @if(!empty($row->contato) or !empty($row->email) or !empty($row->emailnfe) or !empty($row->emailcobranca))
                <div class="muted small">
                    <a href="mailto:{{ $row->email }}">{{ $row->email }}</a>
                    @if($row->email <> $row->emailnfe)
                        <a href="mailto:{{ $row->emailnfe }}">{{ $row->emailnfe }}</a>
                    @endif
                    @if($row->email <> $row->emailcobranca and $row->emailnfe <> $row->emailcobranca)
                        <a href="mailto:{{ $row->emailcobranca }}">{{ $row->emailcobranca }}</a>
                    @endif
                </div>
                @endif
            </div>                            
            <div class="col-md-2 text-right text-muted small">
                <strong>{{ formataCnpjCpf($row->cnpj, $row->fisica) }}</strong>
                <div>
                @if (!empty($row->ie))
                    {{ formataInscricaoEstadual($row->ie, $row->Cidade->Estado->sigla) }}
                @endif
                {{ $row->rg }}
                </div>                
            </div>                            
        </div>
      </div>    
    @endforeach
    @if (count($model) === 0)
        <h3>Nenhum registro encontrado!</h3>
    @endif    
  </div>
  <?php echo $model->appends(Request::all())->render();?>
</div>
@section('inscript')
<script type="text/javascript">
$(document).ready(function() {
    $('#pessoa-search').change(function() {
        this.submit();
    });
    $('#grupocliente').select2({
        placeholder: 'Grupo de cliente',
        allowClear:true,
        closeOnSelect:true
    })<?php echo (app('request')->input('grupocliente') ? ".select2('val'," .app('request')->input('grupocliente').");" : ';'); ?>    
    $('#cidade').select2({
        minimumInputLength:3,
        allowClear:true,
        closeOnSelect:true,
        placeholder:'Cidade',
        formatResult:function(item) {
            var markup = "";
            markup    += "<b>" + item.cidade + "</b>&nbsp;";
            return markup;
        },
        formatSelection:function(item) { 
            return item.cidade; 
        },
        ajax:{
            url:baseUrl+"/cidade/ajax",
            dataType:'json',
            quietMillis:500,
            data:function(term, page) { 
                return {q: term}; 
            },
            results:function(data, page) {
                var more = (page * 20) < data.total;
                return {results: items.data};
            }
        },
        initSelection:function (element, callback) {
            $.ajax({
                type: "GET",
                url: baseUrl+"/cidade/ajax",
                data: "id="+$('#cidade').val(),
                dataType: "json",
                success: function(result) { callback(result); }
            });
        },
        width:'resolve'
    });    

});
</script>
@endsection
@stop