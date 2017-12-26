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
    $xeroxs     = collect($dados['xerox']);

?>
    <h1>Vendedores</h1>
    <table class="relatorio">
      <thead>
          <tr>
              <th class="filial">Filial</th>
              <th class="vendedor">Vendedor</th>
              <th class="comissao">Comissão</th>
          </tr>
      </thead>
      <tbody>
          @foreach($vendedores->sortBy('pessoa') as $vendedor)
          <tr>
              <td scope="row">{{ $vendedor['filial'] }}</td>
              <td>{{ $vendedor['pessoa'] }}</td>
              <td class="text-right"><strong>{{ formataNumero($vendedor['valorcomissaovendedor']) }}</strong></td>
          </tr>
          @endforeach
      </tbody> 
    </table>
    <h1>Xerox</h1>
    <table class="relatorio">
        <thead>
            <tr>
                <th class="filial">Filial</th>
                <th class="vendedor">Vendedor</th>
                <th class="comissao">Comissão</th>
            </tr>
        </thead>
        <tbody>
            @foreach($xeroxs as $xerox)
            <tr>
                <td>{{ $xerox['filial'] }}</td>
                <td>{{ $xerox['pessoa'] }}</td>
                <td class="text-right"><strong>{{ formataNumero($xerox['comissao']) }}</strong></td>
            </tr>
            @endforeach
        </tbody> 
    </table>     

    
</div>
<div class='rodape'>
</div>
@section('inscript')
<style>
table {
    text-align: left
}
td, th {
    padding: 0.04cm;
    text-align: left
}
th.filial {
    width: 20%
}
th.vendedor {
    width: 60%
}
th.comissao {
    width: 20%
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
