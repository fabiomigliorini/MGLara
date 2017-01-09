@extends('layouts.default')
@section('content')
<ol class="breadcrumb header">
    {!! 
        titulo(
            $model->codvalecompramodelo,
            [
                url("vale-compra-modelo") => 'Modelos de Vale Compras',
                $model->modelo
            ],
            $model->inativo
        ) 
    !!}    
    <li class='active'>
        <small>
            <a title="Novo" href="{{ url('vale-compra-modelo/create') }}"><span class="glyphicon glyphicon-plus"></span></a>
            &nbsp;
            <a title="Alterar" href="{{ url("vale-compra-modelo/$model->codvalecompramodelo/edit") }}"><span class="glyphicon glyphicon-pencil"></span></a>
            &nbsp;
            @if(empty($model->inativo))
              <a title='Inativar' href="{{ url('vale-compra-modelo/inativar') }}" data-inativar data-codigo="{{ $model->codvalecompramodelo }}" data-acao="inativar" data-pergunta="Tem certeza que deseja inativar o modelo {{ $model->modelo }}? " data-after-inativar="location.reload()"><span class="glyphicon glyphicon-ban-circle"></span></a>
            @else
              <a title='Ativar' href="{{ url('vale-compra-modelo/inativar') }}" data-inativar data-codigo="{{ $model->codvalecompramodelo }}" data-acao="ativar" data-pergunta="Tem certeza que deseja ativar o modelo {{ $model->modelo }}? " data-after-inativar="location.reload()"><span class="glyphicon glyphicon-ok-sign"></span></a>
            @endif
            &nbsp;
            <a title='Excluir' href="{{ url("vale-compra-modelo/$model->codvalecompramodelo") }}" data-excluir data-pergunta="Tem certeza que deseja excluir o modelo '{{ $model->modelo }}'?" data-after-delete="location.replace(baseUrl + '/vale-compra-modelo');"><i class="glyphicon glyphicon-trash"></i></a>
            
        </small>
    </li>   
</ol>
<div class="row">
  <div class='col-md-8'>
    <ul class="list-group list-group-condensed list-group-hover list-group-striped" id='divListagemProdutos'>
    @foreach ($model->ValeCompraModeloProdutoBarraS as $vcmpb)
      <li class='list-group-item'>
        <div class='row'>
          <div class='col-md-2'>
            {{ $vcmpb->ProdutoBarra->barras }}
          </div>
          <div class='col-md-5'>
            <?php $inativo = $vcmpb->ProdutoBarra->Produto->inativo; ?>
            @if (!empty($inativo))
              <s><a href='{{ url('produto', $vcmpb->ProdutoBarra->codproduto) }}'>{{ $vcmpb->ProdutoBarra->descricao() }}</a></s>
              <span class='text-danger'>
                  inativo desde {{ formataData($vcmpb->ProdutoBarra->Produto->inativo) }}
              </span>
            @else
              <a href='{{ url('produto', $vcmpb->ProdutoBarra->codproduto) }}'>
                {{ $vcmpb->ProdutoBarra->descricao() }}
              </a>
            @endif
            
          </div>
          <div class='col-md-2 text-right'>
            {{ formataNumero($vcmpb->quantidade, 3) }}
            {{ $vcmpb->ProdutoBarra->UnidadeMedida->sigla }}
          </div>
          <div class='col-md-1 text-right'>
            {{ formataNumero($vcmpb->preco, 2) }}
          </div>
          <div class='col-md-2 text-right'>
            {{ formataNumero($vcmpb->total, 2) }}
          </div>
        </div>
      </li>
    @endforeach
      <li class='list-group-item'>
        <b>
            @if (!empty($model->desconto))
                <div class='row'>
                  <div class='col-md-10 text-right'>
                    Total Produtos
                  </div>
                  <div class='col-md-2 text-right'>
                    {{ formataNumero($model->totalprodutos, 2) }}
                  </div>
                </div>

                <div class='row'>
                  <div class='col-md-10 text-right'>
                    Desconto
                  </div>
                  <div class='col-md-2 text-right'>
                    {{ formataNumero($model->desconto, 2) }}
                  </div>
                </div>
            @endif

            <div class='row'>
              <div class='col-md-10 text-right'>
                Total
              </div>
              <div class='col-md-2 text-right'>
                {{ formataNumero($model->total, 2) }}
              </div>
            </div>
        </b>
      </li>
    </ul>
    @include('includes.autor')
  </div>
  
  <div class='col-md-4'>
    <ul class="list-group list-group-condensed list-group-hover list-group-striped" id='divListagemProdutos'>
      <li class='list-group-item'>
        <div class='row'>
            <div class='col-md-4'>
              #
            </div>
            <div class='col-md-8'>
              {{ formataCodigo($model->codvalecompramodelo) }}
            </div>
        </div>
      </li>
      <li class='list-group-item'>
        <div class='row'>
            <div class='col-md-4'>
              Modelo
            </div>
            <div class='col-md-8'>
              {{ $model->modelo }}
            </div>
        </div>
      </li>
      <li class='list-group-item'>
        <div class='row'>
            <div class='col-md-4'>
              Ano/Turma
            </div>
            <div class='col-md-8'>
              {{ $model->ano }} / {{ $model->turma }}
            </div>
        </div>
      </li>
      <li class='list-group-item'>
        <div class='row'>
            <div class='col-md-4'>
              Favorecido
            </div>
            <div class='col-md-8'>
              <a href='{{ url('pessoa', $model->codpessoafavorecido) }}'>
                {{ $model->PessoaFavorecido->fantasia }}
              </a>
            </div>
        </div>
      </li>
      <li class='list-group-item'>
        <div class='row'>
            <div class='col-md-4'>
              Observações
            </div>
            <div class='col-md-8'>
              {!! nl2br($model->observacoes) !!}
            </div>
        </div>
      </li>
    </ul>
  </div>
</div>

@section('inscript')
<script type="text/javascript">
$(document).ready(function() {
    
    
});
</script>
@endsection
@stop