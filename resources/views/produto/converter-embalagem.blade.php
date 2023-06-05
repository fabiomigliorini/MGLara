@extends('layouts.default')
@section('content')
<ol class="breadcrumb header">
{!! 
    titulo(
        $model->codproduto,
        [
            url("produto") => 'Produtos',
            url("produto/$model->codproduto") => $model->produto,
            'Converter Embalagem em Unidade',
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
                <label for="codprodutoembalagem">Embalagem</label>
                @foreach($model->ProdutoEmbalagemS()->orderByRaw('quantidade ASC NULLS FIRST')->get() as $pu)
                    <div class="radio">
                        <label>
                            <input type="radio" id="codprodutoembalagem_{{$pu->codprodutoembalagem}}" name="codprodutoembalagem" value="{{$pu->codprodutoembalagem}}">  
                            {{ $pu->descricao }}
                        </label>
                    </div>
                @endforeach         
            </div>
        </div>
       
    </div>
    <button type="button" class="btn btn-primary" onclick="confirmar()">Converter</button>
</form>

{!! Form::close() !!}

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">


function confirmar ()
{
    // Busca os codigos selecionados
    var codprodutoembalagem = $('input[name=codprodutoembalagem]:checked').val();

    // Valida se origem preenchida
    if (codprodutoembalagem == undefined) {
        Swal.fire({
            title: 'Erro',
            text: 'Selecione a Embalagem!',
            icon: 'error',
            confirmButtonText: 'Fechar'
        })
        return;
    }

    // Pergunta se o usuario tem certeza
    var message =  Swal.fire({
        title: 'Tem certeza que deseja converter a movimentação?',
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
        converter(codprodutoembalagem);
    });
}


function converter(codprodutoembalagem) 
{

    $.ajax({
        type: "POST",
        url: "{{env('SSO_HOST') }}/api/v1/produto/embalagem-para-unidade",
        data: {
            codprodutoembalagem: codprodutoembalagem,
        },
        dataType: "json",
        headers: {
            'Accept': 'application/json'
        },
        success: function (resp) {
            //console.log(resp.data);
            Swal.fire({
                title: 'Sucesso',
                text: 'Movimentação convertida com sucesso!',
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