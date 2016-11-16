@extends('layouts.quiosque')
@section('content')

<div id="app">
    <div class="col-md-6">
        <div class="row" v-if="produto != null">
            <!-- Carousel
            ================================================== -->
            <div id="myCarousel" class="carousel slide" data-ride="carousel" data-interval="1500">
                <!-- Indicators -->
                <ol class="carousel-indicators" >
                    <li v-for="(imagem, index) in produto.imagens" data-target="#myCarousel" v-bind:data-slide-to="index" v-bind:class="{ active: (index==0) }">{index}</li>
                </ol>

                <div class="carousel-inner" role="listbox">
                  <div class="item text-center" v-for="(imagem, index) in produto.imagens"  v-bind:class="{ active: (index==0) }">
                    <div class="text-center">
                        <img v-bind:src="imagem.url" v-bind:alt="imagem.codimagem" style='max-width: 100%; max-height: 100%'>
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
    

    <div class="col-md-6" id='div-resultados'>
        <form class="" role="search">
            <div class='row' id='div-form'>
                <div class='col-md-4'>
                    <input type="text" class="form-control" id="barras" placeholder="Barras" v-model="barrasDigitado" v-on:change="getProduto" autofocus tabindex="1">
                </div>
                <div class='col-md-8'>
                    {!! Form::select2ProdutoBarra('codprodutobarra', null, ['placeholder' => 'Pesquisa de produtos', 'class' => 'form-control', 'id'=>'codprodutobarra', 'tabindex'=>'2', 'somenteAtivos'=>'1']) !!}
                </div>
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
    </div>
    <div v-if="produto == null" class='col-md-12' style='position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%);' id='div-erro'>
            <div class='alert alert-danger text-center'>
                <h1>@{{ mensagem }}</h1>
            </div>
    </div>

</div> 

<script type="text/javascript">

    function toggleFullScreen() {
        var doc = window.document;
        var docEl = doc.documentElement;

        var requestFullScreen = docEl.requestFullscreen || docEl.mozRequestFullScreen || docEl.webkitRequestFullScreen || docEl.msRequestFullscreen;
        var cancelFullScreen = doc.exitFullscreen || doc.mozCancelFullScreen || doc.webkitExitFullscreen || doc.msExitFullscreen;

        //if(!doc.fullscreenElement && !doc.mozFullScreenElement && !doc.webkitFullscreenElement && !doc.msFullscreenElement) {
            requestFullScreen.call(docEl);
        //}
        //else {
        //    cancelFullScreen.call(doc);
        //}
    }  

    function focoBarras() {
        if (!$("#codprodutobarra").select2("isFocused")) {
            $("#barras").focus();
        }
    }
    
    $(document).ready(function() {  

        // Foco no campo de codigo de barras
        $("#barras").blur(function() {
            focoBarras();
        });
        $("#codprodutobarra").on("select2-blur", function(e) {
            focoBarras();
        });
        focoBarras();


        // Ao selecionar #codprodutobarra dispara busca
        $("#codprodutobarra").on("select2-selecting", function(e) { 
            app.barrasDigitado = e.object.barras;
            app.getProduto();
        });

        app.getProduto();
    }); 
    
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
            barrasDigitado: null,
            barras: null,
            produto: null,
            resultado: null,
            mensagem: 'Leia o código de barras!',
        },

        ready : function() {
            this.getProduto();
        },  

        methods: {
            
            getProduto: function(e) {
                
                this.barras = this.barrasDigitado;
                this.barrasDigitado = null;
                this.produto = null;
                toggleFullScreen();

                if (this.barras != null) {
                    this.$http.get('/MGLara/produto/consulta/' + this.barras).then((response) => {
                        
                        this.mensagem = response.body.mensagem;

                        if (response.body.resultado) {
                            this.produto = response.body.produto;
                            Vue.nextTick(function () {
                                $("#codprodutobarra").select2("val", null);
                                $('#myCarousel').carousel();
                            });
                        }

                    }, (response) => {
                        console.log('errror', response);
                        this.mensagem = response.status + ' - ' + response.statusText + ' - ' + response.url;
                    });


                }
                
            }
        }

    });
   
</script>
@stop