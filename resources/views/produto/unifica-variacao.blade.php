@extends('layouts.default')
@section('content')
<ol class="breadcrumb header">
{!! 
    titulo(
        $model->codproduto,
        [
            url("produto") => 'Produtos',
            url("produto/$model->codproduto") => $model->produto,
            'Unifica Variação',
        ],
        $model->inativo,
        6
    ) 
!!}     
</ol>
<hr>

<form>
    <div class="row">
        <div class="col-sm-3">
            <div class="form-group">
                <label for="codprodutovariacaoorigem">Origem</label>
                @foreach($model->ProdutoVariacaoS()->orderByRaw('variacao ASC NULLS FIRST')->get() as $pu)
                    <div class="radio">
                        <label>
                            <input type="radio" id="codprodutovariacaoorigem_{{$pu->codprodutovariacao}}" name="codprodutovariacaoorigem" value="{{$pu->codprodutovariacao}}">  
                            {{ empty($pu->variacao)?'{ Sem Variação }':$pu->variacao }}
                        </label>
                    </div>
                @endforeach         
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <label for="codprodutovariacaodestino">Destino</label>
                @foreach($model->ProdutoVariacaoS()->orderByRaw('variacao ASC NULLS FIRST')->get() as $pv)
                    <div class="radio">
                        <label>
                            <input type="radio" id="codprodutovariacaodestino_{{$pv->codprodutovariacao}}" name="codprodutovariacaodestino" value="{{$pv->codprodutovariacao}}">
                            {{ empty($pv->variacao)?'{ Sem Variação }':$pv->variacao }}
                        </label>
                    </div>
                @endforeach        
            </div>
        </div>
    </div>
    <button type="button" class="btn btn-primary" onclick="confirmar()">Unificar</button>
</form>

{!! Form::close() !!}

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">

function confirmar ()
{
    // Busca os codigos selecionados
    var codprodutovariacaoorigem = $('input[name=codprodutovariacaoorigem]:checked').val();
    var codprodutovariacaodestino = $('input[name=codprodutovariacaodestino]:checked').val();

    // Valida se origem preenchida
    if (codprodutovariacaoorigem == undefined) {
        Swal.fire({
            title: 'Erro',
            text: 'Selecione a Variação de origem!',
            icon: 'error',
            confirmButtonText: 'Fechar'
        })
        return;
    }

    // valida se destino preenchido
    if (codprodutovariacaodestino == undefined) {
        Swal.fire({
            title: 'Erro',
            text: 'Selecione a Variação de destino!',
            icon: 'error',
            confirmButtonText: 'Fechar'
        })     
        return;
    }    

    // valida se nao selecionou os dois o mesmo
    if (codprodutovariacaodestino == codprodutovariacaoorigem) {
        Swal.fire({
            title: 'Erro',
            text: 'a Variação de destino e origem são a mesma!',
            icon: 'error',
            confirmButtonText: 'Fechar'
        })     
        return;
    }

    // Pergunta se o usuario tem certeza
    var message =  Swal.fire({
        title: 'Tem certeza que deseja unificar as Variações?',
        text: "Essa ação é permanente e nao poderá ser desfeita.",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Cancelar',
        // confirmButtonColor: '#3085d6',
        // cancelButtonColor: '#d33',
        confirmButtonText: 'Unificar'
    }).then((result) => {
        if (!result.isConfirmed) {
            return;
        }
        unificaVariacao(codprodutovariacaoorigem, codprodutovariacaodestino);
    });
}



function unificaVariacao(codprodutovariacaoorigem, codprodutovariacaodestino) 
{

    $.ajax({
        type: "POST",
        url: "{{env('SSO_HOST') }}/api/v1/produto/unifica-variacoes",
        data: {
            codprodutovariacaoorigem: codprodutovariacaoorigem,
            codprodutovariacaodestino: codprodutovariacaodestino,
        },
        dataType: "json",
        headers: {
            'Accept': 'application/json'
        },
        success: function (resp) {
            //console.log(resp.data);
            Swal.fire({
                title: 'Sucesso',
                text: 'Variações unificadas com sucesso',
                icon: 'success',
                confirmButtonText: 'Fechar'
            }).then((result) => {
                window.location="/MGLara/produto/{{$model->codproduto}}";
            });
        },
        error: function (err) {
            console.info(err);
            var erro = JSON.parse(err.responseText);
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