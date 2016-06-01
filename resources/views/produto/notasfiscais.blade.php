<h4>Notas fiscais</h4>
<hr>
<div class="search-bar">
{!! Form::model(Request::all(), ['route' => ['produto.show', 'produto'=> $model->codproduto], 'method' => 'GET', 'class' => 'form-inline', 'id' => 'produto-nfpb-search', 'role' => 'search', 'autocomplete' => 'off'])!!}
    <strong>Lançamento</strong>
    <div class="form-group">
        {!! Form::text('nfpb_saida_de', null, ['class' => 'form-control between', 'id' => 'nfpb_saida_de', 'placeholder' => 'De']) !!}
        {!! Form::text('nfpb_saida_ate', null, ['class' => 'form-control between', 'id' => 'nfpb_saida_ate', 'placeholder' => 'Até']) !!}
    </div>
    <div class="form-group">
        {!! Form::select('nfpb_codfilial', $filiais, ['style'=>'width:100px'], ['id'=>'nfpb_codfilial']) !!}
    </div>  
    <div class="form-group">
        {!! Form::select('nfpb_codnaturezaoperacao', $naturezaop, ['style'=>'width:100px'], ['id' => 'nfpb_codnaturezaoperacao']) !!}
    </div>  


{!! Form::close() !!}
</div>

<br>
<div id="registros">
  <div class="list-group" id="nfpbs">
    @foreach($notas as $data)
      <div class="list-group-item">
        <div class="row item">
            <div class="col-md-4">
                {{ formataData($data->NotaFiscal->saida) }}
                {{ $data->NotaFiscal->Filial->filial }} <br>
                {{ $data->NotaFiscal->NaturezaOperacao->naturezaoperacao }} <br>
                <a href="{{ url("pessoa/{$data->NotaFiscal->Pessoa->codpessoa}") }}">{{ $data->NotaFiscal->Pessoa->fantasia }}</a>
            </div>                            
            <div class="col-md-4">
                {{ formataNumero($data->quantidade) }}
                <?php
                $precounitario = ($data->valortotal + $data->icmsstvalor + $data->ipivalor);
                if ($data->quantidade > 0)
                    $precounitario = $precounitario/$data->quantidade;
                $ipi = '';
                $icmsst = '';
                if ($data->valortotal > 0)
                {
                    $ipi = $data->ipivalor/$data->valortotal;
                    $icmsst = $data->icmsstvalor/$data->valortotal;
                }
                echo $data->ProdutoBarra->Produto->UnidadeMedida->sigla;
                if (isset($data->ProdutoBarra->ProdutoEmbalagem))
                {
                    echo " C/" . formatNumero($data->ProdutoBarra->ProdutoEmbalagem->quantidade, 0);
                    $precounitario /=$data->ProdutoBarra->ProdutoEmbalagem->quantidade;
                }
                ?> <br>
                {{ formataNumero($data->valorunitario) }} <br>

                @if($ipi > 0)
                    {{ formataNumero($ipi * 100, 0) }}  % IPI
                @endif
                <br>
                @if($icmsst > 0)
                    {{ formataNumero($icmsst * 100, 0) }}  % ST
                @endif
            </div>
            <div class="col-md-4">
                {{ formataNumero($precounitario) }} <br>
                <a href="{{ url("nota-fiscal/{$data->NotaFiscal->codnotafiscal}") }}">{{ formataNumeroNota($data->NotaFiscal->emitida, $data->NotaFiscal->serie, $data->NotaFiscal->numero, $data->NotaFiscal->modelo) }}</a> <br>
                {{ $data->ProdutoBarra->barras }}
            </div>
        </div>
      </div>    
    @endforeach
    @if (count($notas) === 0)
        <h4>Nenhum registro encontrado!</h4>
    @endif    
  </div>
  {!! $notas->appends(Request::all())->render() !!}
</div>
