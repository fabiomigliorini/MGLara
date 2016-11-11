@extends('layouts.quiosque')
@section('content')

<div id="app">
    <div class='row'>
        <form class="form col-md-12">
            <div class="input-group">
                <input type="number" class="form-control input-lg" id="barrasDigitado" placeholder="Código de Barras" v-model="barrasDigitado" v-on:change="getProduto">
                <span class="input-group-btn">
                    <button type="submit" v-on:click='getProduto'  class="btn btn-default btn-lg">
                        <i class="glyphicon glyphicon-search"></i>
                    </button>
                </span>
            </div>            
        </form>    
    </div>
    
    <div class="row">
        <h2 class="col-md-12">
            @{{ produto.produto }}
        </h2>
    </div>
    <br>
    
    <!-- Carousel
    ================================================== -->
    <div id="myCarousel" class="carousel slide col-md-6" data-ride="carousel">
      <!-- Indicators -->
      <ol class="carousel-indicators" v-for="(imagem, codimagem, index) in produto.imagens">
          <li data-target="#myCarousel" v-bind:data-slide-to="index" v-bind:class="{ active: imagem.primeira }"></li>
      </ol>

      <div class="carousel-inner" role="listbox">
        <div class="item" v-for="(imagem, codimagem, index) in produto.imagens"  v-bind:class="{ active: imagem.primeira }">
          <img v-bind:src="imagem.url" v-bind:alt="imagem.codimagem">
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

    <div class="col-md-6" role="alert">
        <div class="row">
            <div class="col-md-12">
                <h1 class="list-group-item" style="margin: 0">
                    <strong>@{{ produto.preco }}</strong>
                </h1>
            </div>
            <div class="col-md-4">
                <h4>
                    <small>Marca</small> <br>
                    <strong>@{{ produto.marca.marca }}</strong>
                </h4>
            </div>
            <div class="col-md-4">
                <h4>
                    <small>Ref</small><br>
                    <strong>@{{ produto.referencia }}</strong>
                </h4>
            </div>
            <div class="col-md-4">
                <h4>
                    <small>Unidade</small> <br>
                    <strong>@{{ produto.unidademedida }}</strong>
                </h4>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-4">
                <h4>
                    <small>Seção</small> <br>
                    <strong>@{{ produto.secaoproduto }}</strong>
                </h4>
            </div>

            <div class="col-md-4">
                <h4>
                    <small>Família</small><br>
                    <strong>@{{ produto.familiaproduto }}</strong>
                </h4>
            </div>
            <div class="col-md-4">
                <h4>
                    <small>Grupo</small> <br>
                    <strong>@{{ produto.grupoproduto }}</strong>
                </h4>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-4">
                <h4>
                    <small>Sub Grupo</small><br>
                    <strong>@{{ produto.subgrupoproduto }}</strong>
                </h4>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-12">
                <ul class="list-group">
                    <li class="list-group-item active"><strong>VARIAÇÕES</strong></li>
                    <li class="list-group-item" v-for="(variacao, codprodutovariacao, index) in produto.variacoes">
                        <strong>@{{ variacao.variacao }}</strong> <span class="pull-right">ref. @{{ variacao.referencia }}</span>
                            <div v-for="(barra, codprodutobarra, index) in variacao.barras">
                                @{{ barra.barras }} - @{{ barra.unidademedida }} <span v-if="barra.quantidade">/ @{{ barra.quantidade }}</span>
                            </div>
                    </li>
                </ul>
            </div>
        </div>    
   


<!--        @{{ barrasDigitado }}-->
        <hr>
        Resultado: @{{ resultado }} - Mensagem: @{{ mensagem }}
    </div>
    

</div> 


<style>
    hr {margin: 5px 0;}
</style>      
<script type="text/javascript">
    var app = new Vue({
        el: '#app',
        data: {
            barrasDigitado: '00070330133020',
            produto: { 
            },
            resultado: null,
            mensagem: null,
        },

        ready : function()
        {
            this.fetchProduto();
        },  

        methods:
        {
        getProduto: function(e)
            {
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

                codimagemprimeira = Object.keys(this.produto.imagens)[0];
                this.produto.imagens[codimagemprimeira].primeira = true;

                }, (response) => {
                    console.log('errror', response);
                });                
            },
        }

    });
    app.getProduto();
</script>
@stop