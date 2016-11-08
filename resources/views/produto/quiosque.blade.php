@extends('layouts.quiosque')
@section('content')

<div id="app">
    <div class='clearfix'>
        <form class="form-inline">
          <div class="form-group">
            <input type="text" class="form-control" id="barrasDigitado" placeholder="Código de Barras" v-model="barrasDigitado">
          </div>
          <button type="submit" v-on:click='getProduto'  class="btn btn-default">Send invitation</button>
        </form>    
    </div>
    
    <div class='text-warning'>
    @{{ produto.codproduto }}
    </div>
    @{{ produto.produto }}
    @{{ produto.preco }}
    <hr>
    Resultado: @{{ resultado }} - Mensagem: @{{ mensagem }}

</div> 


      
	<script type="text/javascript">

		var app = new Vue({

			el: '#app',

			data: {
				barrasDigitado: '000001',
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
						
					}, (response) => {
					    console.log('errror', response);
					});                
		        },
		    }

		});
		app.getProduto();
	</script>
@stop