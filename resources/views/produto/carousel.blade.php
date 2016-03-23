<?php
$dir = str_pad($model->codproduto, 6, "0", STR_PAD_LEFT);
$cmd = shell_exec("ls -d public/imagens/produtos/$dir/*.jpg");
$imagens = explode("\n", $cmd);
$itens = array_pop($imagens);
$i = 36;
?>
@if(!empty($imagens))
<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
  <div class="carousel-inner" role="listbox">
    @foreach($imagens as $imagem)
    <div class="item produto-item">
        <img src="{{ URL::asset($imagem) }}" alt="" style="width:100%; max-height: 500px" id="{{$i++}}">
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
@section('inscript')
<script type="text/javascript">
$(document).ready(function() {
    $('.carousel-inner .item').first().addClass('active');
    $('.carousel').carousel({
        interval:3000
    });
    $('.carousel').on('slide.bs.carousel', function (e) {
        var active = $(e.target).find('.carousel-inner > .item.active > img').attr('id');
        $('.btn-detalhe').attr('href', baseUrl+'/imagem/'+active);
        $('.btn-delete').attr('href', baseUrl+'/imagem/'+active+'/destroy');
    })    
    $('.btn-detalhe, .btn-delete').on('mouseenter', function() {
       $(".carousel").carousel('pause');
    });
    $('.btn-detalhe, .btn-delete').on('mouseleave', function() {
       $(".carousel").carousel('cycle');
    });    
});
</script>
@endsection
@endif