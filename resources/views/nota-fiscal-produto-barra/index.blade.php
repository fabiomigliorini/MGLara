<h4>Notas fiscais</h4>
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
    <div class="form-group">
        <input type="text" name="nfpb_codpessoa" id="nfpb_codpessoa" class="form-control search-pessoa" />
    </div>     
    <input name="page" type="hidden" value="1" id="nfpb_page">
{!! Form::close() !!}
</div>
<div id="nfpb_paginacao">{!! $nfpbs->appends(Request::all())->render() !!}</div>    
<div class="list-group list-group-striped list-group-hover" id="nfpbs">
  @foreach($nfpbs as $nfpb)
    <div class="list-group-item">
      <div class="row item">
          <div class="col-md-4">
                {{ formataData($nfpb->NotaFiscal->saida) }}
                {{ $nfpb->NotaFiscal->Filial->filial }} <br>
                {{ $nfpb->NotaFiscal->NaturezaOperacao->naturezaoperacao }} <br>
                <a href="{{ url("pessoa/{$nfpb->NotaFiscal->Pessoa->codpessoa}") }}">{{ $nfpb->NotaFiscal->Pessoa->fantasia }}</a>
          </div>                            
          <div class="col-md-4">
              {{ formataNumero($nfpb->quantidade) }}
              <?php
              $precounitario = ($nfpb->valortotal + $nfpb->icmsstvalor + $nfpb->ipivalor);
              if ($nfpb->quantidade > 0)
                  $precounitario = $precounitario/$nfpb->quantidade;
              $ipi = '';
              $icmsst = '';
              if ($nfpb->valortotal > 0)
              {
                  $ipi = $nfpb->ipivalor/$nfpb->valortotal;
                  $icmsst = $nfpb->icmsstvalor/$nfpb->valortotal;
              }
              echo $nfpb->ProdutoBarra->Produto->UnidadeMedida->sigla;
              if (isset($nfpb->ProdutoBarra->ProdutoEmbalagem))
              {
                  echo " C/" . formatNumero($nfpb->ProdutoBarra->ProdutoEmbalagem->quantidade, 0);
                  $precounitario /=$nfpb->ProdutoBarra->ProdutoEmbalagem->quantidade;
              }
              ?> <br>
              {{ formataNumero($nfpb->valorunitario) }} <br>

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
              <a href="{{ url("nota-fiscal/{$nfpb->NotaFiscal->codnotafiscal}") }}">{{ formataNumeroNota($nfpb->NotaFiscal->emitida, $nfpb->NotaFiscal->serie, $nfpb->NotaFiscal->numero, $nfpb->NotaFiscal->modelo) }}</a> <br>
              {{ $nfpb->ProdutoBarra->barras }}
          </div>
      </div>
    </div>    
  @endforeach
  @if (count($nfpbs) === 0)
      <h4>Nenhum registro encontrado!</h4>
  @endif    
</div>