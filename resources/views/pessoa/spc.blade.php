<h3>Registros SPC</h3>
<div id="spc">
<div class="list-group" id="items">
    <div class="list-group-item">
        <div class="row item">
            <div class="col-md-2">#</div>                            
            <div class="col-md-1">Inclusão</div>                            
            <div class="col-md-1">Baixa</div>                            
            <div class="col-md-1 text-right">Valor</div>                            
            <div class="col-md-7">Observaçães</div>                            
        </div>
    </div>       
    @foreach($spcs as $spc)
      <div class="list-group-item">
        <div class="row item">
            <div class="col-md-2">
                <a href="{{ url("registro-spc/$spc->codregistrospc") }}">{{ formataCodigo($spc->codregistrospc) }}</a>
            </div>                            
            <div class="col-md-1">
                <a href="{{ url("registro-spc/$spc->codregistrospc") }}">{{ formataData($spc->inclusao) }}</a>
            </div>                            
            <div class="col-md-1">
                {{ formataData($spc->baixa) }}
            </div>                            
            <div class="col-md-1 text-right">
                {{ formataNumero($spc->valor) }}
            </div>                            
            <div class="col-md-7">
                {!! nl2br($spc->observacoes) !!}
            </div>                            
        </div>
      </div>    
    @endforeach
    @if(count($spcs) === 0)
        <p>Nenhum registro encontrado!</p>
    @endif    
  </div>
  {!! $spcs->appends(Request::all())->render() !!}
</div>
