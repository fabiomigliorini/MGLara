@extends('layouts.print-portrait')
@section('content')
<div class='cabecalho'>
  <a href="{{ url("meta/$model->codmeta") }}">
    Comissões {{ formataData($model->periodofinal, 'EC') }}
  </a>
</div>
<div class='conteudo'>
    <!--
    1234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|
    -->
<?php
    $vendedores = collect($dados['vendedores']);
    //dd($vendedores);
?>
      <table class="relatorio">
          <thead>
              <tr>
                  <th style="text-align: left;">Filial</th>
                  <th style="text-align: left;">Vendedor</th>
                  <th class="text-right">Comissão</th>
              </tr>
          </thead>
          <tbody>
              @foreach($vendedores->sortBy('pessoa') as $vendedor)
              <tr>
                  <td scope="row">{{ $vendedor['filial'] }}</td>
                  <td>{{ $vendedor['pessoa'] }}</td>
                  <td class="text-right">{{ formataNumero($vendedor['valorcomissaovendedor']) }}</td>
              </tr>
              @endforeach
          </tbody> 
      </table>

    
</div>
<div class='rodape'>
</div>
@section('inscript')
<style>
td, th {
    padding: 0.05cm;
}  
/*  .codigo {
      width: 1.1cm;
  }
  .produto {
      width: 8cm;
  }
  .preco {
      width: 1cm;
  }
  .unidademedida {
      width: 0.3cm;
  }
  .variacao {
      width: 2cm;
  }
  .referencia {
      width: 1.5cm;
  }
  .data {
      width: 1.1cm;
  }
  .quantidade {
      width: 0.5cm;
  }
  .valor {
      width: 1cm;
  }
  .local {
      width: 0.7cm;
  }
  .prateleira {
      width: 1.4cm;
  }
  .saldo {
      width: 0.8cm;
  }
  .dias {
      width: 0.8cm;
  }
  .medio {
      width: 1cm;
  }
  .minimo {
      width: 0.5cm;
  }
  .maximo {
      width: 0.5cm;
  }
  .bimestre {
      width: 0.5cm;
  }
  .semestre {
      width: 0.5cm;
  }
  .ano {
      width: 0.5cm;
  }
  .quinzena {
      width: 0.5cm;
  }
*/  
</style>
<script type="text/javascript">
    /*
$(document).ready(function() {
    var color = $('a:link').css('color');
    console.log(color);
});
*/
</script>
@endsection
@stop
