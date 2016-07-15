@extends('negocios.template')
@section('navbar')

<li>
    <a href="{{ URL::route('negocios::index') }}"><span class="glyphicon glyphicon-list-alt"></span> Listagem</a>
</li>

@endsection

@section('title')
Negócio # {{ $model->codnegocio }}
@endsection

@section('body')
<div class="row">
    <div class="col-sm-8">
        <h3>Produtos</h3>
        <form class="form-inline">
            <div class="form-group">
                <label class="sr-only" for="exampleInputAmount">Quantidade</label>
                <div class="input-group">
                    <div class="input-group-addon">Quantidade</div>
                    <input type="text" class="form-control" id="exampleInputAmount" placeholder="Amount">
                </div>
            </div>
            <div class="form-group">
                <label class="sr-only" for="exampleInputAmount">Código</label>
                <div class="input-group">
                    <div class="input-group-addon">Código</div>
                    <input type="text" class="form-control" id="exampleInputAmount" placeholder="Amount">
                </div>
            </div>
            <button type="submit" class="btn btn-default">Adicionar</button>
        </form>
        <br>
        <form>
            <div class="form-group">
            <select
                placeholder="Pesquisa de produto ($ ordena por preço)"
                class="form-control select-search"
                name="codestoquelocal"
                id="local-estoque">
                <option value=""></option>
            </select>
            </div>
        </form>
        <div class="panel panel-default">
            <table class="table table-hover table-striped">
                <tbody>
                    <tr>
                        <th>#ID</th>
                        <th>Name</th>
                        <th>Quantidade</th>
                        <th>Volume</th>
                        <th>Valor Unitário</th>
                        <th>Valor Total</th>
                        <th></th>
                        <th></th>
                    </tr>
                </tbody>
                <tbody>
                    @foreach($model->NegocioProdutoBarras as $produto)
                    <tr>
                        <td><small>{{ formataCodigo($produto->ProdutoBarra->codproduto) }}</small></td>
                        <td>{{ $produto->ProdutoBarra->produto->produto }}</td>
                        <td>{{ $produto->quantidade }}</td>
                        <td>{{ $produto->ProdutoBarra->produto->UnidadeMedida->sigla }}</td>
                        <td>{{ formataNumero($produto->valorunitario) }}</td>
                        <td>{{ formataNumero($produto->valortotal) }}</td>
                        <td>
                            <small>
                                <a href="#">
                                    <span class="glyphicon glyphicon-pencil"></span>
                                </a>
                            </small>
                        </td>
                        <td>
                            <small>
                                <a href="#">
                                    <span class="glyphicon glyphicon-trash"></span>
                                </a>
                            </small>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-sm-4">
        <h3>Detalhes</h3>
        <div class="well">
            <div class="row">
            <div class="text-muted text-left col-sm-5" style="line-height: 45px;">Produtos <span class="badge">{{ formataNumero($model->quantidadeDeProdutos(), 0) }}</span></div>
            <div class="text-success text-right col-sm-7" style="font-size: xx-large"><strong>R$ {{ formataNumero($model->valorprodutos) }}</strong></div>
            </div>
        </div>
        <table class="table table-striped">
            <thead>
                <th><strong>#</strong></th>
                <th>{{ formataCodigo($model->codnegocio) }}</th>
            </thead>
            <tbody>
                <tr>
                    <td class="text-right" nowrap><strong>Natureza de Operação</strong></td>
                    <td>{{ $model->Operacao->operacao . ' - ' . $model->NaturezaOperacao->naturezaoperacao }}</td>
                </tr>
                <tr>
                    <td class="text-right" nowrap><strong>Pessoa</strong></td>
                    <td>
                        <a href="{{ url('pessoa', [$model->Pessoa->codpessoa]) }}">{{ $model->Pessoa->fantasia }}</a>
                    </td>
                </tr>
                <tr>
                    <td class="text-right" nowrap><strong>Vendedor</strong></td>
                    <td>
                        <a href="{{ url('pessoa', [$model->PessoaVendedor->codpessoa]) }}">{{ $model->PessoaVendedor->fantasia }}</a>
                    </td>
                </tr>
                <tr>
                    <td class="text-right" nowrap><strong>Lançamento</strong></td>
                    <td>{{ formataData($model->lancamento, 'L') }}</td>
                </tr>
                <tr>
                    <td class="text-right" nowrap><strong>Status</strong></td>
                    <td>{{ $model->NegocioStatus->negociostatus }}</td>
                </tr>
                <tr>
                    <td class="text-right" nowrap><strong>Filial</strong></td>
                    <td>{{ $model->Filial->filial }}</td>
                </tr>
                <tr>
                    <td class="text-right" nowrap><strong>Local Estoque</strong></td>
                    <td>{{ $model->EstoqueLocal->estoquelocal }}</td>
                </tr>
                <tr>
                    <td class="text-right" nowrap><strong>Usuário</strong></td>
                    <td>{{ $model->Usuario->usuario }}</td>
                </tr>
                <tr>
                    <td class="text-right" nowrap><strong>Observações</strong></td>
                    <td>{{ $model->observacoes }}</td>
                </tr>
            </tbody>
        </table>
        <small class="muted">
            Criado em {{ formataData($model->lancamento, 'L') }} por <a href="{{ url('usuario', [$model->Usuario->codusuario]) }}">{{ $model->Usuario->usuario }}</a> Alterado
        </small>
    </div>
</div>
@endsection
