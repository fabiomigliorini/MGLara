@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li>
                <a href="{{ url("pessoa/create") }}"><span class="glyphicon glyphicon-plus"></span> Nova</a>
            </li> 
        </ul>
    </div>
</nav>
<h1 class="header">Pessoas</h1>
<hr>
<div class="search-bar">
{!! Form::model(Request::session()->get('pessoa.index'), ['route' => 'pessoa.index', 'method' => 'GET', 'class' => 'form-inline', 'id' => 'pessoa-search', 'role' => 'search', 'autocomplete' => 'off'])!!}
    <div class="form-group">
        {!! Form::text('codpessoa', null, ['class' => 'form-control search-cod', 'placeholder' => '#']) !!}
    </div>
    <div class="form-group">
        {!! Form::text('pessoa', null, ['class' => 'form-control', 'placeholder' => 'Nome']) !!}
    </div>
<div class="form-group">
        {!! Form::text('cnpj', null, ['class' => 'form-control', 'placeholder' => 'CNPJ/CPF', ' style'=>'width: 110px']) !!}
    </div>        
    <div class="form-group">
        {!! Form::text('email', null, ['class' => 'form-control', 'placeholder' => 'E-mail', ' style'=>'width: 110px']) !!}
    </div>        
    <div class="form-group">
        {!! Form::text('telefone', null, ['class' => 'form-control', 'placeholder' => 'Fone', ' style'=>'width: 110px']) !!}
    </div>
    <div class="form-group">
        {!! Form::select2Ativo('ativo', null, ['class'=> 'form-control', 'id' => 'ativo', 'style'=>'width:120px']) !!}
    </div>
    <div class="form-group">
        {!! Form::select2Cidade('codcidade', null, ['class' => 'form-control','id'=>'codcidade', 'style'=>'width:230px']) !!}
    </div>
    <div class="form-group">
        {!! Form::select2GrupoCliente('codgrupocliente', null, ['placeholder'=>'Grupo Cliente',  'class'=> 'form-control', 'id' => 'codgrupocliente', 'style'=>'width:180px']) !!}
    </div>        
    <button type="submit" class="btn btn-default">Buscar</button>
{!! Form::close() !!}
</div>

<br>
<div id="registros">
  <div class="list-group list-group-striped list-group-hover" id="items">
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
                    {{ formataEndereco(
                        $row->enderecocobranca, 
                        $row->numerocobranca, 
                        $row->complementocobranca, 
                        $row->bairrocobranca, 
                        $row->CidadeCobranca->cidade, 
                        $row->CidadeCobranca->Estado->sigla, 
                        $row->cepcobranca
                    ) }}
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
function atualizaFiltro()
{
    var frmValues = $('#pessoa-search').serialize();
    $.ajax({
        type: 'GET',
        url: baseUrl + '/pessoa',
        data: frmValues
    })
    .done(function (data) {
        $('#items').html(jQuery(data).find('#items').html()); 
    })
    .fail(function () {
        console.log('Erro no filtro');
    });
}    
$(document).ready(function() {
    $('#pessoa-search').change(function() {
        atualizaFiltro()
    });
    
    $('#inativo').select2({
        placeholder: 'Inativo',
        allowClear: true,
        closeOnSelect: true
    });
});
</script>
@endsection
@stop