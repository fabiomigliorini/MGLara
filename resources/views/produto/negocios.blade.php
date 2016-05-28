<h4>Negócios ({{ count($negocios) }})</h4>
<hr>
<div class="search-bar">
{!! Form::model(Request::all(), ['route' => 'produto.index', 'method' => 'GET', 'class' => 'form-inline', 'id' => 'produto-search', 'role' => 'search', 'autocomplete' => 'off'])!!}
    <div class="form-group">
        <select placeholder="Filial" class="form-control" name="notas_codfilial" id="notas_codfilial" style="width: 70px;">
            <option value=""></option>
        </select>
    </div>
    <div class="form-group">
        <select placeholder="Natureza" class="form-control" name="notas_codnaturezaoperacao" id="notas_codnaturezaoperacao" style="width: 70px;">
            <option value=""></option>
        </select>
    </div>
{!! Form::close() !!}
</div>
<br>
<div class="list-group" id="items">
  @foreach($negocios as $negocio)
    <div class="list-group-item">
        <div class="row item">
            <div class="col-md-4">
                {{ formataData($negocio->Negocio->lancamento, 'L') }}
                {{ $negocio->Negocio->Filial->filial }} <br>
                {{ $negocio->Negocio->NaturezaOperacao->naturezaoperacao }} <br>
                <a href="{{ url("pessoa/{$negocio->Negocio->Pessoa->codpessoa}") }}">{{ $negocio->Negocio->Pessoa->fantasia }}</a>
            </div>                            
            <div class="col-md-4">
                {{ formataNumero($negocio->quantidade) }} <br>
                <?php $precounitario = ($negocio->valortotal)/$negocio->quantidade; ?>
                {{ $negocio->ProdutoBarra->Produto->UnidadeMedida->sigla }}
                @if(!empty($negocio->ProdutoBarra->ProdutoEmbalagem))
                    C/ {{ formataNumero($negocio->ProdutoBarra->ProdutoEmbalagem->quantidade, 0) }}
                    <?php $precounitario /=$negocio->ProdutoBarra->ProdutoEmbalagem->quantidade;?>
                @endif
                <br>
                {{ $negocio->valorunitario }}
            </div>
            <div class="col-md-4">
                {{ formataNumero($precounitario) }} <br>
                {{ $negocio->codprodutobarra }} <br>
                {{ $negocio->ProdutoBarra->barras }} <br>
                <a href="{{ url("negocio/{$negocio->Negocio->codnegocio}") }}">{{ formataCodigo($negocio->Negocio->codnegocio) }}</a>
            </div>
        </div>
    </div>    
  @endforeach
  @if (count($negocios) === 0)
      <h3>Nenhum registro encontrado!</h3>
  @endif    
</div>
{!! $negocios->appends(Request::all())->render() !!}
