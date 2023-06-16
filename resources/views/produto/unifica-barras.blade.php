@extends('layouts.default')
@section('content')
<ol class="breadcrumb header">
{!! 
    titulo(
        $model->codproduto,
        [
            url("produto") => 'Produtos',
            url("produto/$model->codproduto") => $model->produto,
            'Unifica Barra',
        ],
        $model->inativo,
        6
    ) 
!!}     
</ol>

@foreach ($model->ProdutoVariacaoS()->orderBy('variacao')->get() as $pv)
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">
                {{ $pv->variacao }}  
            </h3>
        </div>
        <div class="panel-body">
            <form class="form-inline">
                <div class="form-group">
                    <label for="codprodutobarraorigem[{{$pv->codprodutovariacao}}]">Origem</label>
                    <select class="form-control" name="codprodutobarraorigem[{{$pv->codprodutovariacao}}]" id="codprodutobarraorigem_{{$pv->codprodutovariacao}}">
                        <option value="">Selecione</option>
                        @foreach($pv->ProdutoBarraS()->leftJoin('tblprodutoembalagem as pe', 'pe.codprodutoembalagem', '=', 'tblprodutobarra.codprodutoembalagem')
                            ->orderBy(DB::raw('coalesce(pe.quantidade, 0)'), 'ASC')
                            ->with('ProdutoEmbalagem')->get() as $pb)
                            <option value="{{$pb->codprodutobarra}}">
                                {{$pb->barras}}
                                @if (!empty($pb->codprodutoembalagem))  
                                    {{$pb->ProdutoEmbalagem->descricao}}
                                @endif
                            </option>
                        @endforeach 
                    </select> 
                </div>
                <div class="form-group">
                    <label for="codprodutobarradestino[{{$pv->codprodutovariacao}}]">Destino</label>
                    <select class="form-control" name="codprodutobarradestino[{{$pv->codprodutovariacao}}]" id="codprodutobarradestino_{{$pv->codprodutovariacao}}">
                        <option value="">Selecione</option>
                        @foreach($pv->ProdutoBarraS()->leftJoin('tblprodutoembalagem as pe', 'pe.codprodutoembalagem', '=', 'tblprodutobarra.codprodutoembalagem')
                            ->orderBy(DB::raw('coalesce(pe.quantidade, 0)'), 'ASC')
                            ->with('ProdutoEmbalagem')->get() as $pb)
                            <option value="{{$pb->codprodutobarra}}">
                                {{$pb->barras}}
                                @if (!empty($pb->codprodutoembalagem))  
                                    {{$pb->ProdutoEmbalagem->descricao}}
                                @endif
                            </option>
                        @endforeach 
                    </select>
                </div>
                 <div id="div-unifica-barras" class="col-md-8 text-right" style="display:none">
                    <b>Aguarde...</b>
                    <img width="20px" src="{{ URL::asset('public/img/carregando.gif') }}">
                </div>
                <button type="button" class="btn btn-primary" onclick="confirmar({{$pv->codprodutovariacao}})">Unificar</button>
            </form>
        </div>
    </div>
@endforeach




{!! Form::close() !!}

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">

function confirmar (codprodutovariacao)
{
    // Busca os codigos selecionados
    var codprodutobarraorigem = $('#codprodutobarraorigem_' + codprodutovariacao + ' option:selected').val();
    var codprodutobarradestino = $('#codprodutobarradestino_' + codprodutovariacao + ' option:selected').val();

    // Valida se origem preenchida
    if (codprodutobarraorigem == '') {
        Swal.fire({
            title: 'Erro',
            text: 'Selecione o código de barras de origem!',
            icon: 'error',
            confirmButtonText: 'Fechar'
        })
        return;
    }

    // valida se destino preenchido
    if (codprodutobarradestino == '') {
        Swal.fire({
            title: 'Erro',
            text: 'Selecione o código de barras de destino!',
            icon: 'error',
            confirmButtonText: 'Fechar'
        })     
        return;
    }    

    // valida se nao selecionou os dois o mesmo
    if (codprodutobarradestino == codprodutobarraorigem) {
        Swal.fire({
            title: 'Erro',
            text: 'O código de barras de destino e origem são o mesmo!',
            icon: 'error',
            confirmButtonText: 'Fechar'
        })     
        return;
    }

    // Pergunta se o usuario tem certeza
    var message =  Swal.fire({
        title: 'Tem certeza que deseja unificar as barras?',
        text: "Essa ação é permanente e nao poderá ser desfeita.",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Cancelar',
        // confirmButtonColor: '#3085d6',
        // cancelButtonColor: '#d33',
        confirmButtonText: 'Unificar'
    }).then((result) => {
        document.getElementById('div-unifica-barras').style.display = 'block';
        if (!result.isConfirmed) {
            document.getElementById('div-unifica-barras').style.display = 'none';
            return;
        }
        unificaBarras(codprodutobarraorigem, codprodutobarradestino);
    });
}

function unificaBarras(codprodutobarraorigem, codprodutobarradestino) 
{

    $.ajax({
        type: "POST",
        url: "{{env('SSO_HOST') }}/api/v1/produto/unifica-barras",
        data: {
            codprodutobarraorigem: codprodutobarraorigem,
            codprodutobarradestino: codprodutobarradestino,
        },
        dataType: "json",
        headers: {
            'Accept': 'application/json'
        },
        success: function (resp) {
            //console.log(resp.data);
            Swal.fire({
                title: 'Sucesso',
                text: 'Barras unificadas com sucesso',
                icon: 'success',
                confirmButtonText: 'Fechar'
            }).then((result) => {
                document.getElementById('div-unifica-barras').style.display = 'none';
                window.location="/MGLara/produto/{{$model->codproduto}}";
            });
        },
        error: function (err) {
            console.info(err);
            var erro = JSON.parse(err.responseText);
            document.getElementById('div-unifica-barras').style.display = 'none';
            Swal.fire({
                title: 'Erro',
                text: "Erro:" + erro.message,
                icon: 'error',
                confirmButtonText: 'Fechar'
            })
        }
    });
}
</script>
@stop