@extends('layouts.default')
@section('content')
<ol class="breadcrumb header">
    {!!
        titulo(
            $model->codcheque,
            [
                url("cheque") => 'Cheque',
                $model->cheque
            ],
            $model->inativo
        )
    !!}
    <li class='active'>
        <small>
            <a title="Novo" href="{{ url('cheque/create') }}"><span class="glyphicon glyphicon-plus"></span></a>
            &nbsp;
            <a title="Alterar" href="{{ url("cheque/$model->codcheque/edit") }}"><span class="glyphicon glyphicon-pencil"></span></a>

            &nbsp;
            <a title='Excluir' href="{{ url("cheque/$model->codcheque") }}" data-excluir data-pergunta="Tem certeza que deseja excluir o cheque '{{ $model->modelo }}'?" data-after-delete="location.replace(baseUrl + '/cheque');"><i class="glyphicon glyphicon-trash"></i></a>

        </small>
    </li>
</ol>
<div class="row">
    <div class="col-md-6">
        <ul class="list-group list-group-condensed list-group-hover list-group-striped" id='divListagemProdutos'>
              <li class='list-group-item'>
                <div class='row'>
                    <div class='col-md-4'>
                      #
                    </div>
                    <div class='col-md-8'>
                      {{ formataCodigo($model->codcheque) }}
                    </div>
                </div>
              </li>

              <li class='list-group-item'>
                <div class='row'>
                    <div class='col-md-4'>
                      Valor
                    </div>
                    <div class='col-md-8'>
                      R$ {{ formataNumero($model->valor, 2) }}
                    </div>
                </div>
              </li>
              <li class='list-group-item'>
                <div class='row'>
                    <div class='col-md-4'>
                      Data de Emissão
                    </div>
                    <div class='col-md-8'>
                      {{ formataData($model->emissao) }}
                    </div>
                </div>
              </li>
              <li class='list-group-item'>
                <div class='row'>
                    <div class='col-md-4'>
                      Data de Vencimento
                    </div>
                    <div class='col-md-8'>
                      {{ formataData($model->vencimento) }}
                    </div>
                </div>
              </li>
              <li class='list-group-item'>
                <div class='row'>
                    <div class='col-md-4'>
                      CMC7
                    </div>
                    <div class='col-md-8'>
                      {{ $model->cmc7 }}
                    </div>
                </div>
              </li>
              <li class='list-group-item'>
                <div class='row'>
                    <div class='col-md-4'>
                      Pessoa
                    </div>
                    <div class='col-md-8'>
                      <a href="{{ url('pessoa', $model->codpessoa) }}">
                      {{ $model->Pessoa->pessoa }}
                      </a>
                    </div>
                </div>
              </li>
        </ul>
        <div class="panel panel-default">
            <div class="panel-heading"><strong>Emitentes</strong></div>
            <table class="table">
                @foreach ($model->ChequeEmitenteS as $emit)
                <tr>
                    <td width='140'>{{ formataCpfCnpj($emit->cnpj) }}</td>
                    <td>{{ $emit->emitente }}</td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
    <div class="col-md-6">
        <ul class="list-group list-group-condensed list-group-hover list-group-striped" id='divListagemProdutos'>
              <li class='list-group-item'>
                <div class='row'>
                    <div class='col-md-3'>
                      Banco
                    </div>
                    <div class='col-md-8'>
                      {{ $model->Banco->banco }}
                    </div>
                </div>
              </li>
              <li class='list-group-item'>
                <div class='row'>
                    <div class='col-md-3'>
                      Agencia
                    </div>
                    <div class='col-md-8'>
                      {{ $model->agencia }}
                    </div>
                </div>
              </li>
              <li class='list-group-item'>
                <div class='row'>
                    <div class='col-md-3'>
                      Conta Corrente
                    </div>
                    <div class='col-md-8'>
                      {{ $model->contacorrente }}
                    </div>
                </div>
              </li>
              <li class='list-group-item'>
                <div class='row'>
                    <div class='col-md-3'>
                        Número do cheque
                    </div>
                    <div class='col-md-8'>
                      {{ $model->numero }}
                    </div>
                </div>
              </li>
        </ul>
    </div>
</div>
<? /*
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
              {{ formataCodigo($model->codchequemotivodevolucao) }}
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
*/ ?>
@stop