<h3>Histórico de Cobrança</h3>
<div id="cobrancas">
  <div class="list-group" id="items">
    @foreach($cobrancas as $cobranca)
      <div class="list-group-item">
        <div class="row item">
            <div class="col-md-2">
                <a href="{{ url("cobranca-historico/$cobranca->codcobrancahistorico") }}">{{ formataCodigo($cobranca->codcobrancahistorico) }}</a>
            </div>                            
            <div class="col-md-2">
                {{ formataData($cobranca->alteracao, 'L') }}
            </div>                            
            <div class="col-md-2">
                {{ $cobranca->UsuarioAlteracao->usuario }}
            </div>                            
            <div class="col-md-6">
                {!! nl2br($cobranca->historico) !!}
            </div>                            
        </div>
      </div>    
    @endforeach
    @if (count($cobrancas) === 0)
        <p>Nenhum registro encontrado!</p>
    @endif    
  </div>
  {!! $cobrancas->appends(Request::all())->render() !!}
</div>
