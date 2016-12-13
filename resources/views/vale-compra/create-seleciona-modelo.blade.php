@extends('layouts.default')
@section('content')
<ol class="breadcrumb header">
{!! 
    titulo(
        null,
        [
            url("vale-compra") => 'Vale Compras',
            'Modelo',
        ],
        null
    ) 
!!}   
</ol>
<?php
$favorecidos = $modelos->unique(function ($item) {
    return $item['codpessoafavorecido'].$item['fantasia'];
});
?>
<div class='row'>
  <div class='col-md-3'>
    <ul class="nav nav-pills nav-stacked">
      @foreach ($favorecidos as $i => $fav)
        <li class='{{ $i==0?'active':'' }}'><a data-toggle="tab" href="#menu{{$fav->codpessoafavorecido}}">{{ $fav->fantasia }}</a></li>
      @endforeach
    </ul>
  </div>
  <div class='col-md-9'>
    <div class="tab-content">
      @foreach ($favorecidos as $i => $fav)
        <div id="menu{{$fav->codpessoafavorecido}}" class="tab-pane fade {{ $i==0?'active in':'' }}">
          <ul class='list-group list-group-hover list-group-striped'>
            @foreach ($modelos->where('codpessoafavorecido', $fav->codpessoafavorecido) as $row)
              <li class='list-group-item clearfix'>
                <a href='{{ url("vale-compra/create?codvalecompramodelo=$row->codvalecompramodelo") }}'>
                    <div class="col-md-1 small text-muted">
                        {{ formataCodigo($row->codvalecompramodelo) }}
                    </div>                            
                    <div class="col-md-6">
                        {{ $row->modelo }}
                      <div class="pull-right">
                        {{ formataNumero($row->total) }}
                      </div>                            
                    </div>                            
                    <div class="col-md-2">
                        {{ $row->ano }} / {{ $row->turma }}
                    </div>                            
                    <div class="col-md-3 small text-muted">
                      {!! nl2br($row->observacoes) !!}
                    </div>                            
                  </a>
              </li>
            @endforeach
          </ul>
        </div>
      @endforeach
    </div>
  </div>
</div>



@section('inscript')
<script type="text/javascript">
$(document).ready(function() {
});
</script>
@endsection

@stop