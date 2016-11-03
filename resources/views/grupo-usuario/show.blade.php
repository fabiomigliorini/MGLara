@extends('layouts.default')
@section('content')
<ol class="breadcrumb header">
    {!! 
        titulo(
            $model->codgrupousuario,
            [
                url("grupo-usuario") => 'Grupos de Usuários',
                $model->grupousuario,
            ],
            $model->inativo
        ) 
    !!}
    <li class='active'>
        <small>
            <a title="Novo" href="{{ url('grupo-usuario/create') }}"><i class="glyphicon glyphicon-plus"></i></a>
            &nbsp;
            <a title="Alterar" href="{{ url("grupo-usuario/$model->codgrupousuario/edit") }}"><i class="glyphicon glyphicon-pencil"></i></a>
            &nbsp;
            <a title="Excluir" href="{{ url("grupo-usuario/$model->codgrupousuario") }}" data-excluir data-pergunta="Tem certeza que deseja excluir o grupo de usuário'{{ $model->grupousuario }}'?" data-after-delete="location.replace(baseUrl + '/grupo-usuario');"><i class="glyphicon glyphicon-trash"></i></a>
        </small>
    </li>   
</ol>
@include('includes.autor')
<?php		 
$dados = [];		
foreach($permissoes as $permissao){ 
    $key = explode('.', $permissao->permissao);
    if(!isset($dados[$key[0]])){ 
        $dados[$key[0]] = array(); 
    } 
    $dados[$key[0]][] = $permissao; 
} 
?>
@foreach($dados as $key => $value)
<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingOne">
      <h4 class="panel-title">
        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#{{ $key }}" aria-expanded="true" aria-controls="{{ $key }}">
          {{ $key }}
        </a>
      </h4>
    </div>
    <div id="{{ $key }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
        <div class="panel-body" style="padding: 0">
            <table class="table table-striped hover"> 
                <tbody> 
                @foreach($value as $permissao)
                    <tr> 
                        <th class="col-md-1">
                            <a href="<?php echo url("permissao/$permissao->codpermissao");?>">{{formataCodigo($permissao->codpermissao)}}</a>
                        </th> 
                        <td class="col-md-3">
                            <a href="<?php echo url("permissao/$permissao->codpermissao");?>">{{$permissao->observacoes}}</a>
                        </td> 
                        <td class="col-md-5">
                            <input 
                                id="{{$permissao->codpermissao}}"
                                <?php if (!empty($permissao->GrupoUsuario->contains($model->codgrupousuario))):?> checked <?php endif; ?>
                                type="checkbox" 
                                data-on-text="Sim" 
                                data-off-text="Não" 
                                data-off-color ="danger"
                                class="check-permissao">                              
                        </td> 
                    </tr> 
                @endforeach
                </tbody> 
            </table>          
        </div>
    </div>
  </div>
</div>
@endforeach

@section('inscript')
<style type="text/css">
.panel-heading {
    padding: 10px 15px;
}
.table {
    margin-bottom: 0;
}
.panel-group {
    margin-bottom: 10px;
}
</style>
<script type="text/javascript">
$(document).ready(function() {
    $(".check-permissao").bootstrapSwitch('size', 'small');
    $('.check-permissao').on('switchChange.bootstrapSwitch', function(event, state) {
        var grupo = '<?php echo $model->codgrupousuario;?>';
        var permissao = this.id;
        var token = '<?php echo csrf_token()?>';
        var action;
        if(state === false) {
            action = 'detach-permissao';
        } else {
            action = 'attach-permissao';
        }
        $.post( baseUrl+"/grupo-usuario/"+action, {
            codgrupousuario: grupo, 
            codpermissao: permissao,
            _token: token
        })
        .done(function(data) {
            // ...
        });
    });

    $('#expandir').click(function() {
      $('.panel-collapse:not(".in")').collapse('show');
    });    
    
});
</script>
@endsection
@stop