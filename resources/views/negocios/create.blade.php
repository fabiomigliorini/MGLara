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
@if ( ! $errors->isEmpty() )
    <div class="row">
        <div class="col-md-6 col-md-offset-3 alert alert-danger">
        @foreach ( $errors->all() as $error )
            {{ $error }}<br>
        @endforeach
        </div>
    </div>
@endif
<form class="form-horizontal" method="POST" action="{{ URL::route('negocios::store') }}">
    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

    <div class="form-group">
        <label for="filial" class="col-sm-2 control-label">Filial:</label>
        <div class="col-sm-2">
            <select
                placeholder="Filial"
                class="form-control select-search"
                name="codfilial"
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
                name="codestoquelocal"
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
                name="codnaturezaoperacao"
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
                name="codpessoa"
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
                name="codpessoavendedor"
                id="vendedor">
                <option value=""></option>
                @foreach($vendedoresCollection as $vendedor)
                <option value="{{ $vendedor->codpessoa }}">{{ $vendedor->pessoa }}</option>
                @endforeach
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
                id="observacoes"></textarea>
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
