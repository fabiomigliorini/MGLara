<h4>Negócios</h4>
<hr>
<div class="search-bar">
{!! Form::model(Request::all(), ['route' => 'produto.index', 'method' => 'GET', 'class' => 'form-inline', 'id' => 'produto-search', 'role' => 'search', 'autocomplete' => 'off'])!!}
    <div class="form-group">
        <select placeholder="Filial" class="form-control" name="notas_codfilial" id="notas_codfilial" style="width: 70px;">
            <option value=""></option>
        </select>
    </div>
    <div class="form-group">
        <select placeholder="Natureza" class="form-control" name="notas_codnaturezaoperacao" id="notas_codnaturezaoperacao" style="width: 70px;">
            <option value=""></option>
        </select>
    </div>


{!! Form::close() !!}
</div>

<br>
<div id="registros">
  <div class="list-group" id="items">
    @foreach($model->produtoNegocios() as $data)
      <div class="list-group-item">
        <div class="row item">
            <div class="col-md-3">
                {{ formataData($data->Negocio->lancamento) }}
            </div>                            
            <div class="col-md-3">
                {{ $data->Negocio->Filial->filial }}
            </div>
            <div class="col-md-3">
                {{ formataNumero($data->quantidade) }}
            </div>
        </div>
      </div>    
    @endforeach
    @if (count($model->produtoNegocios()) === 0)
        <h3>Nenhum registro encontrado!</h3>
    @endif    
  </div>
  <?php echo $model->produtoNegocios()->appends(Request::all())->render();?>
</div>
