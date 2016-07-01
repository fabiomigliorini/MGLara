{!! Form::text('codmarca', null, ['class' => 'form-control','id'=>'codmarca', 'style'=>'width:100%']) !!}
@section('inscriptComponentes')
<script type="text/javascript">
$(document).ready(function() {
    var inativo = {{ $inativo }}
    $('#codmarca').select2({
        placeholder:'Marca',
        minimumInputLength: 1,
        allowClear: true,
        closeOnSelect: true,
      
        formatResult: function(item) {
            var markup = "<div class='row-fluid'>";
            markup    += item.marca;
            markup    += "</div>";
            return markup;
        },
        formatSelection: function(item) { 
            return item.marca; 
        },
        ajax:{
            url: baseUrl + "/marca/ajax",
            dataType: 'json',
            quietMillis: 500,
            data: function(term, inativo, page) { 
            return {
            	q: term, 
            	inativo: inativo
            }; 
        },
        results: function(data,page) {
            var more = (page * 20) < data.total;
            return {results: data.items};
        }},
        initSelection: function (element, callback) {
            $.ajax({
              type: "GET",
              url: baseUrl + "/marca/ajax",
              data: "id="+$('#codmarca').val(),
              dataType: "json",
              success: function(result) { callback(result); }
              });
        },
        width: 'resolve'
    });
});     
</script>    
@endsection