<h4>Negócios</h4>
<div class="search-bar">
{!! Form::model(Request::all(), ['route' => ['produto.show', 'produto'=> $model->codproduto], 'method' => 'GET', 'class' => 'form-inline', 'id' => 'produto-npb-search', 'role' => 'search', 'autocomplete' => 'off'])!!}
    <strong>Lançamento</strong>
    <div class="form-group">
        {!! Form::text('npb_lancamento_de', null, ['class' => 'form-control between', 'id' => 'npb_lancamento_de', 'placeholder' => 'De']) !!}
        {!! Form::text('npb_lancamento_ate', null, ['class' => 'form-control between', 'id' => 'npb_lancamento_ate', 'placeholder' => 'Até']) !!}
    </div>
    <div class="form-group">
        {!! Form::select('npb_codfilial', $filiais, ['style'=>'width:100px'], ['id'=>'npb_codfilial']) !!}
    </div>  
    <div class="form-group">
        {!! Form::select('npb_codnaturezaoperacao', $naturezaop, ['style'=>'width:100px'], ['id' => 'npb_codnaturezaoperacao']) !!}
    </div>
    <div class="form-group">
      <input type="text" name="npb_codpessoa" id="npb_codpessoa" class="form-control search-pessoa" />
    </div>    
    <input name="page" type="hidden" value="1" id="npb_page">
{!! Form::close() !!}
</div>
<div id="npb_paginacao">{!! $npbs->appends(Request::all())->render() !!}</div>    
<div class="list-group group-list-striped group-list-hover" id="npbs">
  @foreach($npbs as $npb)
    <div class="list-group-item">
        <div class="row item">
            <div class="col-md-4">
                {{ formataData($npb->Negocio->lancamento, 'L') }}
                {{ $npb->Negocio->Filial->filial }} <br>
                {{ $npb->Negocio->NaturezaOperacao->naturezaoperacao }} <br>
                <a href="{{ url("pessoa/{$npb->Negocio->Pessoa->codpessoa}") }}">{{ $npb->Negocio->Pessoa->fantasia }}</a>
            </div>                            
            <div class="col-md-4">
                {{ formataNumero($npb->quantidade) }} <br>
                <?php $precounitario = ($npb->valortotal)/$npb->quantidade; ?>
                
                
                <br>
                {{ $npb->valorunitario }}
            </div>
            <div class="col-md-4">
                {{ formataNumero($precounitario) }} <br>
                {{ $npb->codprodutobarra }} <br>
                
                
                
            </div>
        </div>
    </div>    
  @endforeach
  @if (count($npbs) === 0)
      <h3>Nenhum registro encontrado!</h3>
  @endif    
</div>