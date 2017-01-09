@extends('layouts.default')
@section('content')
<ol class="breadcrumb header">
    {!! 
        titulo(
            $model->codimagem,
            [
                url("imagem") => 'Imagens',
                $model->observacoes
            ],
            $model->inativo
        ) 
    !!}
    <li class='active'>
        <small>
            @if(empty($model->inativo))
            <a title="Inativar" href="" id="inativo-imagem"><i class="glyphicon glyphicon-ban-circle"></i></a>
            &nbsp;
            @else
            <a title="Excluir" href="{{ url("imagem/$model->codimagem") }}" data-excluir data-pergunta="Tem certeza que deseja excluir a imagem '{{ $model->observacoes }}'?" data-after-delete="location.replace(baseUrl + '/imagem');"><i class="glyphicon glyphicon-trash"></i></a>
            @endif
        </small>
    </li>   
</ol>
@include('includes.autor')
<div>
    <div class="col-xs-6">
    @if(empty($model->inativo))    
        <h3>Relacionamentos</h3>
        
        <hr>
        @foreach($model->GrupoProdutoS as $grupo)
        <p>
            <strong>Grupo:</strong> <a href="{{ url("grupo-produto/{$grupo->codgrupoproduto}") }}">{{ $grupo->grupoproduto }}</a>
        </p>
        @endforeach
        
        @foreach($model->MarcaS as $marca)
        <p>
            <strong>Marca:</strong> <a href="{{ url("marca/{$marca->codmarca}") }}">{{ $marca->marca }}</a>
        </p>
        @endforeach

        @foreach($model->SecaoProdutoS as $secao)
        <p>
            <strong>Seçao Produto:</strong> <a href="{{ url("secao-produto/{$secao->codsecaoproduto}") }}">{{ $secao->secaoproduto }}</a>
        </p>
        @endforeach

        @foreach($model->FamiliaProdutoS as $familia)
        <p>
            <strong>Família Produto:</strong> <a href="{{ url("familia-produto/{$familia->codfamiliaproduto}") }}">{{ $familia->familiaproduto }}</a>
        </p>
        @endforeach
       
        @foreach($model->SubGrupoProdutoS as $subgrupo)
        <p>
            <strong>Sub Grupo:</strong> <a href="{{ url("sub-grupo-produto/{$subgrupo->codsubgrupoproduto}") }}">{{ $subgrupo->subgrupoproduto }}</a>
        </p>
        @endforeach
       
        @foreach($model->ProdutoS as $produto)
        <p>
            <strong>Produto:</strong>  <a href="{{ url("produto/{$produto->codproduto}") }}">{{ $produto->produto }}</a>
        </p>
        @endforeach
        
    @endif
    </div>
    <div class="col-xs-6">
        <img class="img-responsive" src="<?php echo URL::asset('public/imagens/'.$model->observacoes);?>">
    </div>
</div>
@section('inscript')
<script type="text/javascript">
$(document).ready(function() {
    $('#inativo-imagem').on("click", function(e) {
        e.preventDefault();
        var codimagem = {{ $model->codimagem }};
        var token = '{{ csrf_token() }}';
        var inativo = '{{ $model->inativo }}';
        var produto = '{{ $model->ProdutoS->first()->codproduto or "" }}';
        if(inativo.length === 0) {
            acao = 'inativar';
        } else {
            acao = 'ativar';
        }        
        bootbox.confirm("Tem certeza que deseja "+acao+"?", function(result) {
            if(result) {
                $.post(baseUrl + '/imagem/inativar', {
                    codimagem: codimagem,
                    produto:produto,
                    acao: acao,
                    _token: token
                }).done(function (data) {
                    location.reload();
                }).fail(function (error){
                  location.reload();          
              });
            }  
        });
    });

});
</script>
@endsection
@stop
