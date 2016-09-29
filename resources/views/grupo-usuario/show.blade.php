@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li><a href="<?php echo url('grupo-usuario');?>"><span class="glyphicon glyphicon-list-alt"></span> Listagem</a></li>             
            <li><a href="<?php echo url('grupo-usuario/create');?>"><span class="glyphicon glyphicon-plus"></span> Novo</a></li>             
            <li><a href="<?php echo url("grupo-usuario/$model->codgrupousuario/edit");?>"><span class="glyphicon glyphicon-pencil"></span> Alterar</a></li> 
            <li>
                <a href="{{ url("grupo-usuario/$model->codgrupousuario") }}" data-excluir data-pergunta="Tem certeza que deseja excluir o grupo de usuário'{{ $model->grupousuario }}'?" data-after-delete="location.replace(baseUrl + '/grupo-usuario');"><i class="glyphicon glyphicon-trash"></i> Excluir</a>
            </li>
        </ul>
    </div>
</nav>
<h1 class="header">
    {!! 
        titulo(
            $model->codgrupousuario,
            $model->grupousuario,
            $model->inativo
        ) 
    !!}
    <a class="btn btn-primary pull-right" role="button" data-toggle="collapse" href="#div-filtro" aria-expanded="false" aria-controls="div-filtro">
        <span class='glyphicon glyphicon-search'></span>
    </a>
</h1>
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
function atualizaFiltro()
{
    scroll();
    var frmValues = $("#permissoes-search").serialize();
    $.ajax({
        type: 'GET',
        url: baseUrl + '/grupo-usuario/' + {{ $model->codgrupousuario }},
        data: frmValues
    })
    .done(function (data) {
        $('#items').html(jQuery(data).find('#items').html());
    })
    .fail(function () {
        console.log('Erro no filtro');
    });

    $('#items').infinitescroll('update', {
        state: {
            currPage: 1,
            isDestroyed: false,
            isDone: false             
        },
        path: ['?page=', '&'+frmValues]
    });
}

function scroll()
{
    var loading_options = {
        finishedMsg: "<div class='end-msg'>Fim dos registros</div>",
        msgText: "<div class='center'>Carregando mais itens...</div>",
        img: baseUrl + '/public/img/ajax-loader.gif'
    };

    $('#items').infinitescroll({
        loading : loading_options,
        navSelector : "#registros .pagination",
        nextSelector : "#registros .pagination li.active + li a",
        itemSelector : "#items div.list-group-item",
    });    
}
    
$(document).ready(function() {
    scroll();
    $("#permissoes-search").on("change", function (event) {
        $('#items').infinitescroll('destroy');
        atualizaFiltro();
    }).on('submit', function (event){
        event.preventDefault();
        $('#items').infinitescroll('destroy');
        atualizaFiltro();
    });
    
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
});
</script>
@endsection
@stop