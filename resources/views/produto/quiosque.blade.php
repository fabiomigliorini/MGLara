@extends('layouts.quiosque')
@section('content')



<div id="app">
    <div class="col-md-6">
        <div class="row">
            <!-- Carousel
            ================================================== -->
            <div id="myCarousel" class="carousel slide" data-ride="carousel">
                <!-- Indicators -->
                <ol class="carousel-indicators" v-for="(imagem, index) in produto.imagens">
                    <li data-target="#myCarousel" v-bind:data-slide-to="index" v-bind:class="{ active: (index==0) }"></li>
                </ol>

                <div class="carousel-inner" role="listbox">
                  <div class="item" v-for="(imagem, index) in produto.imagens"  v-bind:class="{ active: (index==0) }">
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
        </form>
        <br>
        <span style="font-size: 2em">
            <a v-bind:href="produto.url">
                @{{ produto.produto }}
            </a>
        </span>
        <br>
        <a href="">@{{ produto.secaoproduto }}</a>
        /
        <strong>@{{ produto.familiaproduto }}</strong>
        /
        <strong>@{{ produto.grupoproduto }}</strong>
        /
        <a v-bind:href="produto.subgrupoproduto.url">
            @{{ produto.subgrupoproduto.subgrupoproduto }}
        </a>
        /
        <a v-bind:href="produto.marca.url">
            <img v-if="produto.marca != null" v-bind:src="produto.marca.urlimagem" style="max-height: 40px">
            <span v-if="produto.marca == null">
                @{{ produto.marca.marca }}
            </span>
        </a>
        /
        <strong>@{{ produto.referencia }}</strong>
        <br>
        <br>
        <div class="alert alert-success text-center">
            <span class="text-muted pull-left">
                @{{ produto.unidademedida }}
                R$
            </span>
            <strong style="font-size: 5em">
                @{{ produto.preco.formataNumero() }}
            </strong>
        </div>
        
        <ul class="list-group list-group-condensed list-group-hover list-group-striped">
            <li class="list-group-item" v-for="(embalagem, index) in produto.embalagens">
                <span>@{{ embalagem.unidademedida }}</span> 
                <span v-if="embalagem.quantidade > 1" >C/@{{ embalagem.quantidade }}</span>
                <span class="pull-right">@{{ embalagem.preco.formataNumero() }}</span>
            </li>
        </ul>
        
        <table class="table table-bordered table-condensed table-hover table-striped">
            <thead>
                <tr>
                    <th>
                        Variação
                    </th>
                    <th class="col-md-2 text-center" v-for="(estoquelocal, codestoquelocal) in produto.estoquelocais">
                        <small>@{{ estoquelocal.estoquelocal }} </small>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(variacao, index) in produto.variacoes">
                    <th>
                        @{{ variacao.variacao }}
                    </th>
                    <td class="col-md-2 text-center" v-for="(estoquelocal, codestoquelocal) in produto.estoquelocais">
                        <div v-if="(codestoquelocal in variacao.saldos)">
                            <a v-bind:href="variacao.saldos[codestoquelocal].url">
                                @{{ variacao.saldos[codestoquelocal].saldoquantidade.formataNumero(0)  }}
                            </a>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        
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
            barrasDigitado: '00070330133020',
            produto: { 
            },
            resultado: null,
            mensagem: null,
        },

        ready : function() {
            this.fetchProduto();
        },  

        methods: {
            
            getProduto: function(e) {
                
                //if (e) e.preventDefault();
                
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