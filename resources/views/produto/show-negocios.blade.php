
{!! Form::model(Request::all(), ['route' => ['produto.show', 'produto'=> $model->codproduto], 'class' => 'form-inline', 'method' => 'GET', 'id' => 'produto-npb-search', 'role' => 'search', 'autocomplete' => 'off'])!!}
        <div class="input-group">
            <span class="input-group-addon">
                {!! Form::label('npb_lancamento_de', 'De') !!}
            </span>
            {!! Form::date('npb_lancamento_de', null, ['class' => 'form-control', 'id' => 'npb_lancamento_de', 'placeholder' => 'De']) !!}
        </div>
        <div class="input-group">
            <span class="input-group-addon">
                {!! Form::label('npb_lancamento_ate', 'Até') !!}
            </span>
            {!! Form::date('npb_lancamento_ate', null, ['class' => 'form-control', 'id' => 'npb_lancamento_ate', 'placeholder' => 'Até']) !!}
        </div>
        <div class="input-group col-md-5">
            {!! Form::select2Filial('npb_codfilial', null, ['style'=>'width:100%', 'id'=>'npb_codfilial']) !!}
        </div>
        <div class="input-group col-md-5">
            {!! Form::select2NaturezaOperacao('npb_codnaturezaoperacao', null, ['style'=>'width:100%', 'id' => 'npb_codnaturezaoperacao']) !!}
        </div>
        <div class='pull-right'>
            <button type="submit" class='btn btn-primary'><span class='glyphicon glyphicon-search'></span></button>
        </div>
{!! Form::hidden('page', 1, ['id'=>'npb_page']) !!}
{!! Form::close() !!}

<hr>

<div class="panel panel-default" id="div-negocios">
    <div class="list-group group-list-striped group-list-hover" id="npbs">
        @foreach($npbs as $npb)
                <?php
                $quantidade = $npb->quantidade;
                $valor = $npb->valorunitario;
                if (!empty($npb->ProdutoBarra->codprodutoembalagem))
                {
                    $quantidade *= $npb->ProdutoBarra->ProdutoEmbalagem->quantidade;
                    $valor /= $npb->ProdutoBarra->ProdutoEmbalagem->quantidade;
                }
                ?>
                <div class='list-group-item'>
                    <div class='row item'>
                        <small>
                        <small>
                            <div class='col-sm-2 '>
                                <div class='col-sm-12'>
                                    <a href="{{ url('negocio', ['id'=>$npb->codnegocio]) }}">
                                        {{ formataCodigo($npb->codnegocio) }}
                                    </a>
                                </div>
                                <div class='col-sm-12 text-muted'>
                                    {{ formataData($npb->Negocio->lancamento) }}
                                </div>
                            </div>
                            <div class='col-sm-4'>
                                <div class='col-sm-12'>
                                    <a href='{{ url('pessoa', ['id'=>$npb->Negocio->codpessoa]) }}'>
                                        {{ $npb->Negocio->Pessoa->fantasia }}
                                    </a>
                                </div>
                                <div class='col-sm-12 text-muted'>
                                    <a href='{{ url('natureza-operacao', ['id'=>$npb->Negocio->codnaturezaoperacao]) }}'>
                                        {{ $npb->Negocio->NaturezaOperacao->naturezaoperacao }}
                                    </a>
                                </div>
                            </div>
                            <div class='col-sm-3'>
                                <div class='col-sm-12 text-muted'>
                                    <a href='{{ url('filial', ['id'=>$npb->Negocio->codfilial]) }}'>
                                        {{ $npb->Negocio->Filial->filial }}
                                    </a>
                                </div>
                                <div class='col-sm-12'>
                                    {{ $npb->ProdutoBarra->ProdutoVariacao->variacao }}
                                </div>
                                <div class='col-sm-12 text-muted'>
                                    {{ $npb->ProdutoBarra->barras }}
                                </div>
                            </div>
                            <div class='col-sm-3'>
                                <div class='col-sm-12 text-right'>
                                    <small class='pull-left'>R$</small> {{ formataNumero($valor, 2) }} 
                                </div>
                                <div class='col-sm-12 text-right text-muted'>
                                    <small class='pull-left'>{{ $model->UnidadeMedida->sigla }}</small> {{ formataNumero($quantidade, 3) }} 
                                </div>
                            </div>
                        </small>
                        </small>
                    </div>
                </div>
        @endforeach
    </div>
    <!--
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
        -->
</div>
<div id="npb_paginacao">{!! $npbs->appends(Request::all())->render() !!}</div>
