@extends('layouts.quiosque')
@section('content')

<div id="app">
    <div class='clearfix'>
        <form class="form-inline">
          <div class="form-group">
            <input type="number" class="form-control" id="barrasDigitado" placeholder="Código de Barras" v-model="barrasDigitado" v-on:change="getProduto">
          </div>
          <button type="submit" v-on:click='getProduto'  class="btn btn-default">Send invitation</button>
        </form>    
    </div>
    

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
        R$ @{{ produto.preco }}
        <hr>
        @{{ produto.unidademedida }}
        <hr>
        @{{ barrasDigitado }}
        <hr>
        Resultado: @{{ resultado }} - Mensagem: @{{ mensagem }}
    </div>
    

</div> 


      
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