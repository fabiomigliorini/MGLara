@extends('layouts.default')
@section('content')
<ol class="breadcrumb header">
{!! 
    titulo(
        $model->codproduto,
        [
            url("produto") => 'Produtos',
            url("produto/$model->codproduto") => $model->produto,
            'Transferir Variação',
        ],
        $model->inativo,
        6
    ) 
!!}     
</ol>
<hr>
{!! Form::open(['method' => 'PATCH', 'class' => 'form-horizontal', 'id' => 'form-produto-transferir-variacao', 'action' => ['ProdutoController@transferirVariacaoSalvar', $model->codproduto]]) !!}
@include('errors.form_error')

<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label class="col-md-3">Variações</label>
            <div class="col-md-9">
                <div class="input-group">
                    @foreach($model->ProdutoVariacaoS()->orderByRaw('variacao ASC NULLS FIRST')->get() as $pv)
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox("codprodutovariacao[]", $pv->codprodutovariacao, false, []); !!}
                                {{ empty($pv->variacao)?'{ Sem Variação }':$pv->variacao }}
                            </label>
                        </div>
                    @endforeach 
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="form-group">
            <label class="col-md-2">Produto Destino: </label>
            <div class="col-sm-7">{!! Form::select2Produto('codproduto', null, ['class'=> 'form-control', 'id' => 'codproduto', 'style'=>'width:100%']) !!}</div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                {!! Form::submit('Transferir', array('class' => 'btn btn-primary')) !!}
            </div>
        </div>
    </div>
</div>

{!! Form::close() !!}

@section('inscript')
<script type="text/javascript">
    
    $(document).ready(function() {

        $('#form-produto-transferir-variacao').on("submit", function(e) {
            var currentForm = this;
            e.preventDefault();
            bootbox.confirm("Tem certeza que deseja transferir as variações?", function(result) {
                if (result) {
                    currentForm.submit();
                }
            });
        });

    });
</script>
@endsection

@stop