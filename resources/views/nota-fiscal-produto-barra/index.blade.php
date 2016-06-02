<div class="list-group" id="nfpbs">
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