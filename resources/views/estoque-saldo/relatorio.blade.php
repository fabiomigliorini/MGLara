@extends('layouts.print-portrait')
@section('content')
<div class='cabecalho'>
    Título do Relatório
</div>
<div class='conteudo'>
    <!--
    1234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|
    -->
    <table>
        <thead class='negativo'>
            <tr>
                <th class='text-right'>Título Coluna 1</td>
                <th>Título Coluna 1</td>
                <th>Título Coluna 1</td>
                <th>Título Coluna 1</td>
            </tr>
        </thead>
        <tbody class='zebrada'>
            <tr>
                <td class='text-right'>Texto Coluna 1</td>
                <td>Texto Coluna 1</td>
                <td>Texto Coluna 1</td>
                <td>Texto Coluna 1</td>
            </tr>
            <tr>
                <td class='text-right'>Texto Coluna 1</td>
                <td>Texto Coluna 1</td>
                <td>Texto Coluna 1</td>
                <td>Texto Coluna 1</td>
            </tr>
            <tr>
                <td class='text-right'>Texto Coluna 1</td>
                <td>Texto Coluna 1</td>
                <td>Texto Coluna 1</td>
                <td>Texto Coluna 1</td>
            </tr>
            <tr>
                <td>Texto Coluna 1</td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td>rodapé col1</td>
            </tr>
        </tfoot>
    </table>
    <h1>Teste2</h1>
    teste linha 1 <br>
    teste linha 1 <br>
    teste linha 1 <br>
</div>
<div class='rodape'>

</div>
@section('inscript')
<script type="text/javascript">
$(document).ready(function() {
});
</script>
@endsection
@stop
