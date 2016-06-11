<div class="list-group" id="npbs">
  @foreach($npbs as $npb)
    <div class="list-group-item">
        <div class="row item">
            <div class="col-md-4">
                {{ formataData($npb->Negocio->lancamento, 'L') }}
                {{ $npb->Negocio->Filial->filial }} <br>
                {{ $npb->Negocio->NaturezaOperacao->naturezaoperacao }} <br>
                <a href="{{ url("pessoa/{$npb->Negocio->Pessoa->codpessoa}") }}">{{ $npb->Negocio->Pessoa->fantasia }}</a>
            </div>                            
            <div class="col-md-4">
                {{ formataNumero($npb->quantidade) }} <br>
                <?php $precounitario = ($npb->valortotal)/$npb->quantidade; ?>
                
                
                <br>
                {{ $npb->valorunitario }}
            </div>
            <div class="col-md-4">
                {{ formataNumero($precounitario) }} <br>
                {{ $npb->codprodutobarra }} <br>
                
                
                
            </div>
        </div>
    </div>    
  @endforeach
  @if (count($npbs) === 0)
      <h3>Nenhum registro encontrado!</h3>
  @endif    
</div>