<!DOCTYPE html>
<html lang="pt-BR">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="MG Papelaria">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ URL::asset('favicon.ico') }}">
    <title>MG Papelaria</title>
    <link href="{{ URL::asset('public/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('public/vendor/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('public/vendor/select2/select2.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('public/css/starter-template.css') }}" rel="stylesheet">
    
    <script src="{{ URL::asset('public/vendor/jquery/2.1.1/jquery.min.js') }}"></script>
    <script src="{{ URL::asset('public/vendor/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ URL::asset('public/vendor/select2/select2-3.4.1min.js') }}"></script>
    <script src="{{ URL::asset('public/vendor/select2/select2_locale_pt-BR.js') }}"></script>
    <script src="{{ URL::asset('public/js/configs.js') }}"></script>
    <style>
        /* Distancia Menu superior */
        html, body {
            padding-top: 7px;
        };
    </style>
  </head>

  <body>

    <script src="/MGLara/public/vendor/vuejs/vue.js"></script>
    <script src="/MGLara/public/vendor/vuejs/vue-resource.min.js"></script>

    <div class="container-fluid">

        <div id="app">
            <div class="col-md-6" style="margin-bottom: 15px">
                <div class="row" v-if="produto != null">
                    <!-- Carousel
                    ================================================== -->
                    <div id="myCarousel" class="carousel slide" data-ride="carousel" data-interval="1500">
                        <!-- Indicators -->
                        <ol class="carousel-indicators" >
                            <li v-for="(imagem, index) in produto.imagens" data-target="#myCarousel" v-bind:data-slide-to="index" v-bind:class="{ active: (index==0) }">{index}8</li>
                        </ol>

                        <div class="carousel-inner" role="listbox">
                          <div class="item" v-for="(imagem, index) in produto.imagens"  v-bind:class="{ active: (index==0) }">
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
            </div>

            <div class="col-md-6" id='div-resultados'>
                <form class="" role="search">
                    <div class='row' id='div-form'>
                        <div class='col-md-4'>
                            <input type="text" class="form-control" id="barras" placeholder="Barras" v-model="barrasDigitado" v-on:change="getProduto" autofocus tabindex="1">
                        </div>
                        <div class='col-md-8'>
                            {!! Form::select2ProdutoBarra('codprodutobarra', null, ['placeholder' => 'Pesquisa de produtos', 'class' => 'form-control', 'id'=>'codprodutobarra', 'tabindex'=>'2', 'class'=>'col-md-12', 'somenteAtivos'=>'1']) !!}
                        </div>
                    </div>
                </form>
                <br>
                
                <div v-if="produto != null">

                    <div class="well well-sm">
                      <div class='pull-right label label-danger' v-if='produto.inativo != null' style="font-size: 1em">Produto Inativo</div>
                      <a v-bind:href="produto.url" style="font-size: 2em">
                        @{{ produto.produto }}
                      </a>
                      <br>
                      <div class='text-muted' style="font-size: 1em">
                        @{{ produto.codproduto.formataCodigo(6) }}
                        &nbsp;&nbsp;/&nbsp;&nbsp;
                        @{{ produto.barras }}
                      </div>
                    </div>

                    <div class="alert alert-success text-center">
                        <div class='container-fluid'>
                            <span class="text-muted pull-left">
                                @{{ produto.unidademedida }}
                                R$
                            </span>
                            <strong style="font-size: 5em">
                                @{{ produto.preco.formataNumero() }}
                            </strong>
                            <ul class="list-group list-group-condensed list-group-hover list-group-striped pull-right">
                                <li class="list-group-item" v-for="(embalagem, index) in produto.embalagens">
                                    <span>
                                        @{{ embalagem.unidademedida }}
                                    </span> 
                                    <span v-if="embalagem.quantidade > 1" >
                                        C/@{{ embalagem.quantidade }} 
                                    </span>
                                    <strong class="pull-right">
                                        &nbsp @{{ embalagem.preco.formataNumero() }}
                                    </strong>
                                </li>
                            </ul>
                        </div>
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
            <div v-if="resultado == false" class='col-md-12' style='position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%);' id='div-erro'>
                    <div class='alert alert-danger text-center'>
                        <h1>
                          <strong v-if="barras != null">
                            @{{ barras }}<br><hr>
                          </strong>
                          @{{ mensagem }}
                        </h1>
                    </div>
            </div>

        </div> 

        <script type="text/javascript">

            Number.prototype.formataCodigo = function (length) {
                var n = this;
                var length = (length == undefined)?8:length;
                var s=n+"",needed=length-s.length;
                if (needed>0) s=(Math.pow(10,needed)+"").slice(1)+s;
                return '#' + s;
            }
            
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
            
            
            function fullScreen() {
                var docEl = window.document.documentElement;
                var requestFullScreen = docEl.requestFullscreen || docEl.mozRequestFullScreen || docEl.webkitRequestFullScreen || docEl.msRequestFullscreen;
                //requestFullScreen.call(docEl);
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
             
            var app = new Vue({
                el: '#app',
                data: {
                    barrasDigitado: null,
                    barras: null,
                    produto: null,
                    resultado: false,
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
                        fullScreen();

                        if (this.barras != null) {
                            this.$http.get('/MGLara/produto/consulta/' + this.barras).then((response) => {
                                
                                this.mensagem = response.body.mensagem;
                                this.resultado = response.body.resultado;

                                if (response.body.resultado) {
                                    this.produto = response.body.produto;
                                    Vue.nextTick(function () {
                                        $("#codprodutobarra").select2("val", null);
                                        $('#myCarousel').carousel(0);
                                    });
                                }

                            }, (response) => {
                                console.log('errror', response);
                                this.mensagem = response.status + ' - ' + response.statusText + ' - ' + response.url;
                                this.resultado = false;
                            });

                        }
                        
                    }
                }

            });
           
        </script>

    </div>

  </body>
</html>


