@extends('layouts.quiosque')
@section('content')
<script type="text/javascript">
$(document).ready(function() {    
    $("#codproduto").on("select2-selecting", function(e) { 
        var produtoselecionado = e.val;
        console.log (produtoselecionado);
    })
}); 
</script>
<div id="app">
    <div class="col-md-6">
        <div class="row" v-if="produto != null">
            <!-- Carousel
            ================================================== -->
            <div id="myCarousel" class="carousel slide" data-ride="carousel" data-interval="3000">
                <!-- Indicators -->
                <ol class="carousel-indicators" >
                    <li v-for="(imagem, index) in produto.imagens" data-target="#myCarousel" v-bind:data-slide-to="index" v-bind:class="{ active: (index==0) }">{index}</li>
                </ol>

                <div class="carousel-inner" role="listbox">
                  <div class="item text-center" v-for="(imagem, index) in produto.imagens"  v-bind:class="{ active: (index==0) }">
                    <div class="text-center">
                        <img v-bind:src="imagem.url" v-bind:alt="imagem.codimagem">
                    </div>
                  </div>
                </div>

                <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
                  <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                  <span class="sr-only">Previous</span>
                </a>
                <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
                  <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                  <span class="sr-only">Next</span>
                </a>
            </div><!-- /.carousel -->    
        </div>
        <BR>
    </div>
    

    <div class="col-md-6">
        <form class="" role="search">
            <div class="input-group">
                <input type="text" class="form-control" id="barrasDigitado" placeholder="Código de Barras" v-model="barrasDigitado" v-on:change="getProduto">
                <div class="input-group-btn">
                    <button type="submit" v-on:click='getProduto' class="btn btn-default"><span class="glyphicon glyphicon-search"></span></button>
                </div>
            </div>
            <div>
                {!! Form::select2Produto('codproduto', null, ['class' => 'form-control','id'=>'codproduto', 'somenteAtivos'=>'9']) !!}
            </div>
        </form>
        <br>
        
        <div v-if="produto != null">

            <div class="well well-sm" style="font-size: 2em">
                <a v-bind:href="produto.url">
                    @{{ produto.produto }}
                </a>
            </div>

            <div class="alert alert-success text-center">
                <span class="text-muted pull-left">
                    @{{ produto.unidademedida }}
                    R$
                </span>
                <strong style="font-size: 5em">
                    @{{ produto.preco.formataNumero() }}
                </strong>
            </div>
            
            
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a v-bind:href="produto.secaoproduto.url">
                        <img v-if="produto.secaoproduto.urlimagem != null" v-bind:src="produto.secaoproduto.urlimagem" style="max-height: 40px">
                        <span v-if="produto.secaoproduto.urlimagem == null">
                            @{{ produto.secaoproduto.secaoproduto }}
                        </span>
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a v-bind:href="produto.familiaproduto.url">
                        <img v-if="produto.familiaproduto.urlimagem != null" v-bind:src="produto.familiaproduto.urlimagem" style="max-height: 40px">
                        <span v-if="produto.familiaproduto.urlimagem == null">
                            @{{ produto.familiaproduto.familiaproduto }}
                        </span>
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a v-bind:href="produto.grupoproduto.url">
                        <img v-if="produto.grupoproduto.urlimagem != null" v-bind:src="produto.grupoproduto.urlimagem" style="max-height: 40px">
                        <span v-if="produto.grupoproduto.urlimagem == null">
                            @{{ produto.grupoproduto.grupoproduto }}
                        </span>
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a v-bind:href="produto.subgrupoproduto.url">
                        <img v-if="produto.subgrupoproduto.urlimagem != null" v-bind:src="produto.subgrupoproduto.urlimagem" style="max-height: 40px">
                        <span v-if="produto.subgrupoproduto.urlimagem == null">
                            @{{ produto.subgrupoproduto.subgrupoproduto }}
                        </span>
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a v-bind:href="produto.marca.url">
                        <img v-if="produto.marca.urlimagem != null" v-bind:src="produto.marca.urlimagem" style="max-height: 40px">
                        <span v-if="produto.marca.urlimagem == null">
                            @{{ produto.marca.marca }}
                        </span>
                    </a>
                </li>
                <li class="breadcrumb-item active" v-if="produto.referencia != null" >
                    @{{ produto.referencia }}
                </li>
            </ol>

            <ul class="list-group list-group-condensed list-group-hover list-group-striped">
                <li class="list-group-item" v-for="(embalagem, index) in produto.embalagens">
                    <span>
                        @{{ embalagem.unidademedida }}
                    </span> 
                    <span v-if="embalagem.quantidade > 1" >
                        C/@{{ embalagem.quantidade }}
                    </span>
                    <strong class="pull-right">
                        @{{ embalagem.preco.formataNumero() }}
                    </strong>
                </li>
            </ul>

            <table class="table table-bordered table-condensed table-hover table-striped">
                <thead>
                    <tr>
                        <th>
                            Estoque
                        </th>
                        <th class="text-center" v-for="(estoquelocal, codestoquelocal) in produto.estoquelocais">
                            @{{ estoquelocal.estoquelocal }}
                        </th>
                        <th class="text-center">
                            Total
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(variacao, index) in produto.variacoes">
                        <th>
                            @{{ variacao.variacao }}
                        </th>
                        <td class="text-center" v-for="(estoquelocal, codestoquelocal) in produto.estoquelocais">
                            <div v-if="(codestoquelocal in variacao.saldos)">
                                <a v-bind:href="variacao.saldos[codestoquelocal].url">
                                    @{{ variacao.saldos[codestoquelocal].saldoquantidade.formataNumero(0)  }}
                                </a>
                            </div>
                        </td>
                        <th class="text-center">
                            @{{ variacao.saldo.formataNumero(0) }}
                        </th>
                    </tr>
                </tbody>
            </table>
        </div>
        <small class="text-muted">
            Resultado: @{{ resultado }} - Mensagem: @{{ mensagem }}
        </small>
    </div>

</div> 


<script type="text/javascript">
    
    Number.prototype.formataNumero = function(decPlaces, thouSeparator, decSeparator) {
        var n = this,
            decPlaces = isNaN(decPlaces = Math.abs(decPlaces)) ? 2 : decPlaces,
            decSeparator = decSeparator == undefined ? "," : decSeparator,
            thouSeparator = thouSeparator == undefined ? "." : thouSeparator,
            sign = n < 0 ? "-" : "",
            i = parseInt(n = Math.abs(+n || 0).toFixed(decPlaces)) + "",
            j = (j = i.length) > 3 ? j % 3 : 0;
        return sign + (j ? i.substr(0, j) + thouSeparator : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thouSeparator) + (decPlaces ? decSeparator + Math.abs(n - i).toFixed(decPlaces).slice(2) : "");
    };
     
    var app = new Vue({
        el: '#app',
        data: {
            //barrasDigitado: '00070330133020',
            barrasDigitado: '7891027120832',
            produto: null,
            resultado: null,
            mensagem: null,
        },

        ready : function() {
            this.getProduto();
        },  

        methods: {
            
            getProduto: function(e) {
                
                if (e) e.preventDefault();
                
                this.$http.get('/MGLara/produto/consulta/' + this.barrasDigitado).then((response) => {
                    
                    this.retorno = response.body.retorno;
                    this.mensagem = response.body.mensagem;

                    if (response.body.resultado) {
                        this.produto = response.body.produto;
                    } else {
                        console.log(response.body.mensagem);
                        this.produto = {};
                    }

                }, (response) => {
                    console.log('errror', response);
                });                
            },
        }

    });
    app.getProduto();
   
</script>
@stop