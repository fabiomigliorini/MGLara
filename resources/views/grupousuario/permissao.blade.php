<h1 class="header">Permissões</h1>
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
  <div class="list-group col-md-9" id="items">
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
                <?php if (empty($permissao->GrupoUsuario->contains($model->codgrupousuario))):?> checked <?php endif; ?>
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
  <?php echo $permissoes->appends(Request::all())->render();?>
</div>

@section('inscript')
<script type="text/javascript">
  $(document).ready(function() {
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
        $.post( baseUrl+"/grupousuario/"+action, {
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