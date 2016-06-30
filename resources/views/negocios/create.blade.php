@extends('negocios.template')
@section('navbar')

<li>
    <a href="{{ URL::route('negocios::index') }}"><span class="glyphicon glyphicon-list-alt"></span> Listagem</a>
</li>

@endsection

@section('title')
Novo Negócio
@endsection

@section('body')
<form class="form-horizontal">

    <div class="form-group">
        <label for="filial" class="col-sm-2 control-label">Filial:</label>
        <div class="col-sm-2">
            <select
                placeholder="Filial"
                class="form-control select-search"
                name="filial"
                id="filial">
                <option value=""></option>
                <option value="0">Consumidor</option>
            </select>
        </div>
    </div>

    <div class="form-group">
        <label for="local-estoque" class="col-sm-2 control-label">Local Estoque:</label>
        <div class="col-sm-2">
            <select
                placeholder="Local Estoque"
                class="form-control select-search"
                name="local-estoque"
                id="local-estoque">
                <option value=""></option>
                <option value="0">Local Estoque</option>
            </select>
        </div>
    </div>

    <div class="form-group">
        <label for="natureza-de-operacao" class="col-sm-2 control-label">Natureza de Operação</label>
        <div class="col-sm-3">
            <select
                placeholder="Natureza de Operação"
                class="form-control select-search"
                name="natureza-de-operacao"
                id="natureza-de-operacao">
                <option value=""></option>
                <option value="0">Natureza de Operação</option>
            </select>
        </div>
    </div>

    <div class="form-group">
        <label for="pessoa" class="col-sm-2 control-label">Pessoa</label>
        <div class="col-sm-5">
            <select
                placeholder="Pessoa"
                class="form-control select-search"
                name="pessoa"
                id="pessoa">
                <option value=""></option>
                <option value="0">Pessoa</option>
            </select>
        </div>
    </div>

    <div class="form-group">
        <label for="vendedor" class="col-sm-2 control-label">Vendedor</label>
        <div class="col-sm-5">
            <select
                placeholder="Vendedor"
                class="form-control select-search"
                name="vendedor"
                id="vendedor">
                <option value=""></option>
                <option value="0">Vendedor</option>
            </select>
        </div>
    </div>

    <div class="form-group">
        <label for="observacoes" class="col-sm-2 control-label">Observações</label>
        <div class="col-sm-5">
            <textarea
                class="form-control"
                rows="6"
                maxlength="500"
                tabindex="-1"
                name="observacoes"
                id="observacoes">
            </textarea>
        </div>
    </div>

    <hr>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
          <button type="submit" class="btn btn-primary">Salvar</button>
        </div>
    </div>

</form>
@endsection
