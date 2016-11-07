<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
  <div class="carousel-inner" role="listbox">
    @foreach($model->ImagemS as $imagem)
        <div class="item text-center">
            <a href='{{ url("imagem/produto/$model->codproduto?imagem={$imagem->codimagem}") }}'>
                <i class="glyphicon glyphicon-pencil"></i> 
            </a>
            <a class="btn-delete" href="{{ url("imagem/produto/{$model->codproduto}/delete?imagem={$imagem->codimagem}") }}">
                <i class="glyphicon glyphicon-trash"></i> 
            </a>
            <img src="<?php echo URL::asset('public/imagens/'.$imagem->observacoes);?>" id="{{$imagem->codimagem}}" style='margin: 0 auto;'>
        </div>
    @endforeach
  </div>
  <!-- Controls -->
  <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
</div>