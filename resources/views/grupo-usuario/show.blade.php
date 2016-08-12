@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li><a href="<?php echo url('grupo-usuario');?>"><span class="glyphicon glyphicon-list-alt"></span> Listagem</a></li>             
            <li><a href="<?php echo url('grupo-usuario/create');?>"><span class="glyphicon glyphicon-plus"></span> Novo</a></li>             
            <li><a href="<?php echo url("grupo-usuario/$model->codgrupousuario/edit");?>"><span class="glyphicon glyphicon-pencil"></span> Alterar</a></li> 
            <li>
                {!! Form::open(['method' => 'DELETE', 'route' => ['grupo-usuario.destroy', $model->codgrupousuario]]) !!}
                <span class="glyphicon glyphicon-trash"></span>
                {!! Form::submit('Excluir') !!}
                {!! Form::close() !!}
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
<div class="clearfix"></div>
<div class='collapse' id='div-filtro'>
    <div class='well well-sm' style="padding:9px 0">
        {!! Form::model(Request::session()->get('grupo-usuario.show'), [
            'method' => 'GET', 
            'class' => 'form-horizontal',
            'id' => 'grupo-usuario-search',
            'role' => 'search'
        ])!!}
        <div class="col-md-2">
            <div class="form-group">
                {!! Form::label('permissao', '#', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-md-10">{!! Form::text('codpermissao', null, ['class' => 'form-control', 'placeholder' => '#']) !!}</div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group">
              {!! Form::label('permissao', 'Permissão', ['class' => 'col-sm-2 control-label']) !!}
              <div class="col-md-9">{!! Form::text('permissao', null, ['class' => 'form-control', 'placeholder' => 'Permissão']) !!}</div>
            </div>
        </div>
        <div class="col-md-3">    
            <div class="form-group">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-default pull-left">
                        <i class=" glyphicon glyphicon-search"></i> Buscar
                    </button>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
        <div class="clearfix"></div>
    </div>
    {!! Form::close() !!}
</div>
<div id="registros">
  <div class="list-group list-group-striped list-group-hover" id="items">
    @foreach($permissoes as $permissao)
      <div class="list-group-item">
        <div class="row item">
          <div class="col-md-2">
            <a href="<?php echo url("permissao/$permissao->codpermissao");?>">{{formataCodigo($permissao->codpermissao)}}</a>
          </div>                            
          <div class="col-md-7">
            <a href="<?php echo url("permissao/$permissao->codpermissao");?>">{{$permissao->observacoes}}</a>
          </div>
          <div class="col-md-2">
            <input 
                id="{{$permissao->codpermissao}}"
                <?php if (!empty($permissao->GrupoUsuario->contains($model->codgrupousuario))):?> checked <?php endif; ?>
                type="checkbox" 
                data-on-text="Sim" 
                data-off-text="Não" 
                data-off-color ="danger"
                class="check-permissao">              
          </div>  
        </div>
      </div>    
    @endforeach
    @if (count($permissoes) === 0)
        <h3>Nenhum registro encontrado!</h3>
    @endif    
  </div>
  <?php echo $permissoes->appends(Request::session()->get('grupo-usuario.show'))->render();?>
</div>

@section('inscript')
<script type="text/javascript">
  $(document).ready(function() {
      $('#grupo-usuario-search').change(function() {
        this.submit();
      });       
      $(".check-permissao").bootstrapSwitch('size', 'small');
      $('.check-permissao').on('switchChange.bootstrapSwitch', function(event, state) {
          var grupo = '<?php echo $model->codgrupousuario;?>';
          var permissao = this.id;
          var token = '<?php echo csrf_token()?>';
          var action;
          if(state === true) {
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