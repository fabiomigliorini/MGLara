@extends('layouts.print-portrait')
@section('content')
<div class='cabecalho'>
    Histórico de Preços
</div>
<div class='conteudo'>
        <table>
            <thead class='negativo'>
                <tr>
                    <th>#</th>
                    <th>Produto</th>
                    <th>UM</th>
                    <th>Referência</th>
                    <th>Marca</th>
                    <th class="text-right">Atual</th>
                    <th class="text-right">Novo</th>
                    <th class="text-right">Antigo</th>
                    <th>Usuário</th>
                    <th>Data</th>
                </tr>
            </thead>
            <tbody class='zebrada'>
                @foreach($dados as $row)
                    <tr>
                        <td class="codigo">{{ formataCodigo($row->codproduto, 6) }}</td>
                        <td class="produto"><a href="{{ url("produto/$row->codproduto") }}">{{ $row->Produto->produto }}</a></td>
                        <td class="unidade">
                            @if(isset($row->codprodutoembalagem))
                                {{ $row->ProdutoEmbalagem->UnidadeMedida->sigla }} /{{ formataNumero($row->ProdutoEmbalagem->quantidade, 0) }}
                            @else
                                {{ $row->Produto->UnidadeMedida->sigla }}
                            @endif                            
                        </td>
                        <td>{{ $row->Produto->referencia }}</td>
                        <td class="marca">{{ $row->Produto->Marca->marca }}</td>
                        <td class="text-success preco">
                            <strong>
                            <?php  
                            if (isset($row->ProdutoEmbalagem)) {
                                echo $row->ProdutoEmbalagem->preco;
                            } else {
                                echo formataNumero($row->Produto->preco);
                            }?>
                            </strong>
                        </td>
                        <td class="text-warning preco">{{ formataNumero($row->preconovo) }}</td>
                        <td class="text-danger preco"><del>{{ formataNumero($row->precoantigo) }}</del></td>
                        <td>{{ $row->UsuarioCriacao->usuario }}</td>
                        <td class="data">{{ formataData($row->alteracao, 'L') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
</div>
<div class='rodape'>

</div>
@section('inscript')
<style type="text/css">
table {
    border-spacing: 0;
    border-collapse: collapse;
}
td, th {
    padding: 4px;
}
th {
    text-align: left;
}
td.codigo {
    width: 37px;
}
td.produto {
  width: 200px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  display: flex;
  margin: 0;
  padding: 4px 0;
}
td.preco {
  width: 37px;
  text-align: right;
}
td.data {
  width: 89px;
}
td.marca {
  width: 100px;
}
td.unidade {
  width: 37px;
}
</style>
<script type="text/javascript">
$(document).ready(function() {
});
</script>
@endsection
@stop
