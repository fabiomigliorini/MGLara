@extends('layouts.default')
@section('content')
<ol class="breadcrumb header">
    {!! 
        titulo(
            $model->codusuario,
            [
                url("usuario") => 'Usuários',
                url("usuario/$model->codusuario") => $model->usuario,
                'Permissões',
            ],
            $model->inativo
        ) 
    !!} 
    <li class='active'>
        <small>
            <a title="Novo" href="{{ url('usuario/create') }}"><i class="glyphicon glyphicon-plus"></i></a>
            &nbsp;
            <a title="Alterar" href="{{ url("marca/$model->codmarca/edit") }}"><i class="glyphicon glyphicon-pencil"></i></a>
            &nbsp;            
            <a title="Detalhes" href="{{ url("usuario/$model->codusuario") }}"><i class="glyphicon glyphicon-eye-open"></i></a>
                {!! Form::open(['method' => 'DELETE', 'route' => ['usuario.destroy', $model->codusuario]]) !!}
                <span class="glyphicon glyphicon-trash"></span>
                {!! Form::submit('Excluir') !!}
                {!! Form::close() !!}            
        </small>
    </li>   
</ol>
<hr>
<div id="registros">
    <div class="panel panel-default">
        <table class="table table-striped table-hover"> 
            <thead> 
                <tr> 
                    <th>Grupo</th>
                    @foreach($filiais as $filial)
                    <th>{{ $filial->filial }} <br> <strong>{{ $filial->codfilial }}</strong></th>
                    @endforeach
                </tr> 
            </thead> 
            <tbody>
                @foreach($grupos as $grupo)
                <tr> 
                    <th scope="row">
                        <a class="text-muted small" href="<?php echo url("grupo-usuario/$grupo->codpermissao");?>">{{formataCodigo($grupo->codgrupousuario)}}</a>
                        <a href="<?php echo url("grupo-usuario/$grupo->codpermissao");?>">{{$grupo->grupousuario}}</a>                
                    </th>
                    @foreach($filiais as $filial)
                    <td>
                        <input 
                            data-filial="{{$filial->codfilial}}"
                            data-grupo="{{$grupo->codgrupousuario}}"
                            <?php echo checkPermissao($filial->codfilial, $grupo->codgrupousuario, $model->extractgrupos());?>
                            type="checkbox" 
                            data-on-text="Sim" 
                            data-off-text="Não" 
                            data-off-color ="danger"
                            class="check-permissao">                   
                    </td>
                    @endforeach
                </tr>   
                @endforeach
            </tbody> 
        </table>    
    </div>    
</div>
@section('inscript')
<script type="text/javascript">
  $(document).ready(function() {
      $(".check-permissao").bootstrapSwitch('size', 'small');
      $('.check-permissao').on('switchChange.bootstrapSwitch', function(event, state) {
          var usuario = '<?php echo $model->codusuario;?>';
          var grupo = this.dataset.grupo;
          var filial = this.dataset.filial;
          var token = '<?php echo csrf_token()?>';
          var action;
          if(state === true) {
              action = 'attach-permissao';
          } else {
              action = 'detach-permissao';
          }
          //console.log(state);
        $.post( baseUrl+"/usuario/"+action, {
            codgrupousuario: grupo, 
            codusuario: usuario,
            codfilial: filial,
            _token: token
        })
        .done(function(data) {
            // ...
        });
      });      
  });
</script>
@endsection
@stop