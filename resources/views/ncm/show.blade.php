@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li><a href="{{ url('ncm') }}"><span class="glyphicon glyphicon-list-alt"></span> Listagem</a></li>             
            <li><a href="{{ url('ncm/create') }}"><span class="glyphicon glyphicon-plus"></span> Nova</a></li>             
            <li><a href="{{ url("ncm/$model->codncm/edit") }}"><span class="glyphicon glyphicon-pencil"></span> Alterar</a></li> 
            <li>
                <a href="{{ url("ncm/$model->codncm") }}" data-excluir data-pergunta="Tem certeza que deseja excluir a NCM '{{ $model->descricao }}'?" data-after-delete="location.replace(baseUrl + '/ncm');"><i class="glyphicon glyphicon-trash"></i> Excluir</a>
            </li>
        </ul>
    </div>
</nav>
<h1 class="header">
    {!! 
        titulo(
            $model->codncm,
            formataNcm($model->ncm),
            $model->inativo
        ) 
    !!} 
</h1>
@include('includes.autor')
<div class="row">
  <div class="col-lg-12">
      <table class="detail-view table table-striped table-condensed"> 
        <tbody>
          <tr>
            <th class="col-md-2">Descrição</th>
            <td class="col-md-10">{{ $model->descricao }}</td>
          </tr>
          @if(!empty($model->codncmpai))
          <tr> 
            <th>Filho de</th> 
            <td>
                <a href="{{ url("ncm/{$model->Ncm->codncm}") }}">
                    {{ formataNcm($model->Ncm->ncm) }}
                </a>
            </td>
          </tr>
          @endif
        </tbody> 
      </table>
  </div>    
</div>
<div class="row">
    <div class="col-md-4">
        <h3>ICMS/ST <small>Regulamento de ICMS Substituição Tributária do Estado de Mato Grosso - Anexo X</small></h3>
        @foreach($model->regulamentoIcmsStMtsDisponiveis() as $key => $value)
        <table class="detail-view table table-striped table-condensed"> 
          <tbody>
            <tr>
              <th class="col-md-2">#</th>
              <td class="col-md-10">{{ $value[$key]->codregulamentoicmsstmt }}</td>
            </tr>
            <tr> 
              <th>Subitem</th> 
              <td>{{ $value[$key]->subitem }}</td>
            </tr>
            <tr> 
              <th>Descrição</th> 
              <td>{{ $value[$key]->descricao }}</td>
            </tr>
            <tr> 
              <th>NCM</th> 
              <td>{{ $value[$key]->ncm }}</td>
            </tr>
            <tr> 
              <th>Ncm Exceto</th> 
              <td>{{ $value[$key]->ncmexceto }}</td>
            </tr>
            <tr> 
              <th>ICMS ST Sul</th> 
              <td>{{ $value[$key]->icmsstsul }}</td>
            </tr>
            <tr> 
              <th>ICMS ST Norte</th> 
              <td>{{ $value[$key]->icmsstnorte }}</td>
            </tr>
          </tbody> 
        </table>        
        @endforeach
    </div>
    <div class="col-md-4">
        <h3>CEST <small>Código Especificador da Substituição Tributária - Anexo I</small></h3>
    </div>
    <div class="col-md-4">
        <h3>IBPT <small>Instituto Brasileiro de Planejamento e Tributação</small></h3>
        @foreach ($model->IbptaxS as $item)
            <p>{{ $item->codibptax }}</p>
        @endforeach
    </div>
</div>
@if (count($filhos) > 0)
<h1>Filhos</h1>
<div id="registros">
  <div class="list-group list-group-striped list-group-hover" id="items">
    @foreach($filhos as $row)
      <div class="list-group-item">
        <div class="row item">
            <div class="col-md-1">
                <a class="" href="{{ url("ncm/$row->codncm") }}">{{ formataNcm($row->ncm) }}</a>
            </div>                            
            <div class="col-md-11">
                <a href="{{ url("ncm/$row->codncm") }}">
                    {{ $row->descricao}}
                </a>
            </div>
        </div>
      </div>    
    @endforeach
  </div>
  {!! $filhos->render() !!}
</div>
@endif


@section('inscript')
<script type="text/javascript">
function scroll()
{
    var loading_options = {
        finishedMsg: "<div class='end-msg'>Fim dos registros</div>",
        msgText: "<div class='center'>Carregando mais itens...</div>",
        img: baseUrl + '/public/img/ajax-loader.gif'
    };

    $('#items').infinitescroll({
        loading : loading_options,
        navSelector : "#registros .pagination",
        nextSelector : "#registros .pagination li.active + li a",
        itemSelector : "#items div.list-group-item",
    });    
}
$(document).ready(function() {
    scroll();
});
</script>
@endsection
@stop