<div id="div-imagens">
    <?php
    $imagens = $model->ImagemS()->orderBy('codimagem')->get();
    ?>
    <p>
        <a href="{{ url("/imagem/produto/$model->codproduto") }}">
            Nova Imagem
            <i class="glyphicon glyphicon-plus"></i>
        </a>
    </p>
    <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
        <!--
        <ol class="carousel-indicators">
            @for ($i = 0; $i < sizeof($imagens); $i++)
                <li data-target="#myCarousel" data-slide-to="{{ $i }}" class="{{ ($i == 0)?'active':'' }}"></li>
            @endfor
        </ol>
        -->
        <div class="carousel-inner" role="listbox">
            @if (sizeof($imagens) == 0)
                <div class="item text-center {{ ($i == 0)?'active':'' }}">
                    <img src="<?php echo URL::asset('public/imagens/semimagem.jpg');?>" style='margin: 0 auto;'>
                </div>
            @endif
            @foreach($imagens as $i => $imagem)
                <div class="item text-center {{ ($i == 0)?'active':'' }}">
                    <a href='{{ URL::asset('public/imagens/'.$imagem->observacoes) }}' target="_blank">
                        <img src="<?php echo URL::asset('public/imagens/'.$imagem->observacoes);?>" id="{{$imagem->codimagem}}" style='margin: 0 auto;'>
                    </a>
                    <div class="carousel-caption">
                        <div class="btn-group" role="group">
                            <a class="btn btn-primary" href='{{ url("imagem/produto/$model->codproduto?imagem={$imagem->codimagem}") }}'><i class="glyphicon glyphicon-pencil"></i></a>
                            <a class="btn btn-primary" href="{{ url("imagem/produto/{$model->codproduto}/delete?imagem={$imagem->codimagem}") }}" data-excluir data-pergunta="Tem certeza que deseja excluir a imagem '{{ $imagem->observacoes }}'?" data-after-delete="recarregaDiv('div-imagens');"><i class="glyphicon glyphicon-trash"></i></a>
                        </div>
                    </div>
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
    <br>
</div>
