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
                    <tr>
                        <td><small>7898111920347</small></td>
                        <td>Bola Isopor Styroform 100mm C/10</td>
                        <td>4,000</td>
                        <td>PT-</td>
                        <td>15,00</td>
                        <td>60,00</td>
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
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-sm-4">
        <h3>Detalhes</h3>
        <div class="well">
            <div class="row">
            <div class="text-muted text-left col-sm-5" style="line-height: 45px;">Produtos <span class="badge">14</span></div>
            <div class="text-success text-right col-sm-7" style="font-size: xx-large"><strong>R$ 123.12</strong></div>
            </div>
        </div>
    </div>
</div>
@endsection
