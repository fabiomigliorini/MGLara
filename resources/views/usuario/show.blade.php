@extends('layouts.default')
@section('content')
<?php 
    $grupos = $usuario->extractgrupos();
    $admin = false;
    foreach ($grupos as $grupo)
    {
        if ($grupo['grupo'] == '1') {
        $admin = true;
        }
    }
?>
<ol class="breadcrumb header">
{!! 
    titulo(
        $model->codusuario,
        [
            url("usuario") => 'Usuários',
            $model->usuario,
        ],
        $model->inativo
    ) 
!!}
    <li class='active'>
        <small>
            <a title="Novo" href="{{ url('usuario/create') }}"><i class="glyphicon glyphicon-plus"></i></a>
            &nbsp;
            <a title="Alterar" href="{{ url("usuario/$model->codusuario/edit") }}"><i class="glyphicon glyphicon-pencil"></i></a>
            &nbsp;
            @if($admin)
                <a title="Permissões" href="{{ url("usuario/$model->codusuario/permissao") }}"><span class="glyphicon glyphicon-lock"></span></a>
                &nbsp;
                @if(empty($model->inativo))
                <a title="Inativar" href="" id="inativar-usuario"><i class="glyphicon glyphicon-ban-circle"></i></a>
                &nbsp;
                @else
                <a title="Ativar" href="" id="inativar-usuario"><i class="glyphicon glyphicon-ok-sign"></i></a>
                &nbsp;
                @endif
                <a title="Excluir" href="{{ url("usuario/$model->codusuario") }}" data-excluir data-pergunta="Tem certeza que deseja excluir o usuario'{{ $model->usuario }}'?" data-after-delete="location.replace(baseUrl + '/usuario');"><i class="glyphicon glyphicon-trash"></i></a>
            @endif
        </small>
    </li>   
</ol>
<hr>
<div class="row">
  <div class="col-lg-12">
      <table class="detail-view table table-striped table-condensed"> 
        <tbody>  
          <tr> 
            <th class="col-md-2">#</th> 
            <td class="col-md-10">{{ formataCodigo($model->codusuario) }}</td> 
          </tr>
          <tr> 
            <th>Usuário</th> 
            <td>{{ $model->usuario }}</td> 
          </tr>
          <tr> 
            <th>ECF</th> 
            <td>{!! isset($model->codecf) ? $model->Ecf['ecf'] : isNull('Vazio') !!}</td> 
          </tr>
          <tr> 
            <th>Filial</th> 
            <td>{!! isset($model->codfilial) ? $model->Filial['filial'] : isNull('Vazio') !!}</td> 
          </tr>
          <tr> 
            <th>Operação</th> 
            <td>{!! isset($model->codoperacao) ? $model->Operacao['operacao'] : isNull('Vazio') !!}</td> 
          </tr>
          <tr> 
            <th>Pessoa</th> 
            <td>{!! isset($model->codpessoa) ? linkRel($model->Pessoa['pessoa'], 'pessoa', $model->codpessoa) : isNull('Vazio') !!}</td> 
          </tr>
          <tr> 
            <th>Impressora Matricial</th> 
            <td>{{ $model->impressoramatricial }}</td> 
          </tr> 
          <tr> 
            <th>Impressora Térmica</th> 
            <td>{{ $model->impressoratermica }}</td> 
          </tr>
          <tr> 
            <th>Impressora tela negócio</th> 
            <td>{{ $model->impressoratelanegocio }}</td> 
          </tr>
          <tr> 
            <th>Último acesso</th> 
            <td>{{ formataData($model->ultimoacesso, 'L') }}</td> 
          </tr>           
          <tr> 
            <th>Inativo</th> 
            <td>{{ formataData($model->inativo, 'M') }}</td> 
          </tr> 
        </tbody> 
      </table>
  </div>    
</div>
<hr>
@include('includes.autor')
@section('inscript')
<script type="text/javascript">
$(document).ready(function() {
    
    $('#inativar-usuario').on("click", function(e) {
        e.preventDefault();
        var codusuario = {{ $model->codusuario }};
        var token = '{{ csrf_token() }}';
        var inativo = '{{ $model->inativo }}';
        if(inativo.length === 0) {
            acao = 'inativar';
        } else {
            acao = 'ativar';
        }        
        bootbox.confirm("Tem certeza que deseja "+acao+"?", function(result) {
            if(result) {
                $.post(baseUrl + '/usuario/inativo', {
                    codusuario: codusuario,
                    acao: acao,
                    _token: token
                }).done(function (data) {
                    location.reload();
                }).fail(function (error){
                  location.reload();          
              });
            }  
        });
    });
    
    console.log($('#negocio_codproduto').val());    
});
</script>
@endsection
@stop
