$(document).ready(function() {
  $('.pagination').addClass('hide');
  var loading_options = {
      finishedMsg: "<div class='end-msg'>Fim dos registros</div>",
      msgText: "<div class='center'>Carregando mais itens...</div>",
      img: 'public/images/ajax-loader.gif'
  };

  $('#items').infinitescroll({
    loading : loading_options,
    navSelector : "#registros .pagination",
    nextSelector : "#registros .pagination li.active + li a",
    itemSelector : "#items div.list-group-item"
  });    
});  