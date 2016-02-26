@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li><a href="<?php echo url('usuario');?>"><span class="glyphicon glyphicon-list-alt"></span> Listagem</a></li>             
            <li><a href="<?php echo url('usuario/create');?>"><span class="glyphicon glyphicon-plus"></span> Novo</a></li>             
            <li><a href="<?php echo url("usuario/$model->codusuario/edit");?>"><span class="glyphicon glyphicon-pencil"></span> Alterar</a></li> 
            <li>
                {!! Form::open(['method' => 'DELETE', 'route' => ['usuario.destroy', $model->codusuario]]) !!}
                <span class="glyphicon glyphicon-trash"></span>
                {!! Form::submit('Excluir') !!}
                {!! Form::close() !!}
            </li>
        </ul>
    </div>
</nav>
<h1 class="header">Permissões usuário #{{$model->codusuario}}</h1>
<div class="search-bar">
  {!! Form::model(Request::all(), [
    'method' => 'GET', 
    'class' => 'form-inline',
    'id' => 'grupousuario-search',
    'role' => 'search'
  ])!!}
  <div class="form-group">
    {!! Form::text('codpermissao', null, ['class' => 'form-control search-cod', 'placeholder' => '#']) !!}
  </div>
  <div class="form-group">
    {!! Form::text('permissao', null, ['class' => 'form-control', 'placeholder' => 'Permissão']) !!}
  </div>
  <button type="submit" class="btn btn-default">Buscar</button>
{!! Form::close() !!}
</div>

<br>
<div id="registros">
  <div class="list-group col-md-12" id="items">
      <div class="list-group-item">
          <div class="row item">
              <div class="col-md-1">#</div>
              <div class="col-md-3">Grupo</div>
              @foreach($filiais as $filial)
              <div class="col-md-1">{{$filial->filial}} <br> <strong>{{$filial->codfilial}}</strong></div>
              @endforeach
          </div>
      </div>
    @foreach($grupos as $grupo)
    
    
      <div class="list-group-item">
        <div class="row item">
          <div class="col-md-1">
            <a href="<?php echo url("grupousuario/$grupo->codpermissao");?>">#{{$grupo->codgrupousuario}}</a>
          </div>                            
          <div class="col-md-3">
            <a href="<?php echo url("grupousuario/$grupo->codpermissao");?>">{{$grupo->grupousuario}}</a>
            <br>

          </div>
          @foreach($filiais as $filial)
          <div class="col-md-1">
            <input 
                data-filial="{{$filial->codfilial}}"
                data-grupo="{{$grupo->codgrupousuario}}"
                <?php echo checkPermissao($filial->codfilial, $grupo->codgrupousuario, $model->extractgrupos());?>
                type="checkbox" 
                data-on-text="sim" 
                data-off-text="não" 
                data-off-color ="danger"
                class="check-permissao">               
          </div>
          @endforeach            
        </div>
      </div>

    
    @endforeach
    @if (count($grupos) === 0)
        <h3>Nenhum registro encontrado!</h3>
    @endif    
  </div>
  <?php echo $grupos->appends(Request::all())->render();?>
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
          var acao;
          if(state === true) {
              action = 'attach-permissao';
              acao = 'adicionada';              
          } else {
              action = 'detach-permissao';
              acao = 'removida';
          }
          //console.log(state);
        $.post( baseUrl+"/usuario/"+action, {
            codgrupousuario: grupo, 
            codusuario: usuario,
            codfilial: filial,
            _token: token
        })
        .done(function(data) {
            console.log('Permissão '+acao)
        });
      });      
  });
</script>
@endsection
@stop