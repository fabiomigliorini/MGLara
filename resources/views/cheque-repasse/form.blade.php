<div class="panel panel-default">
    <div class='panel-body'>

        <div class='row'>
            <div class="form-group col-md-2">
                {!! Form::label('vencimento_de', 'De', ['control-label']) !!}
                {!! Form::date('vencimento_de', '2017-01-01', ['class'=> 'form-control text-right', 'id'=>'vencimento_de']) !!}
            </div>
            <div class="form-group col-md-2">
                {!! Form::label('vencimento_ate', 'Até', ['control-label']) !!}
                {!! Form::date('vencimento_ate', '2017-01-31', ['class'=> 'form-control text-right', 'id'=>'vencimento_ate']) !!}
            </div>
            <div class="form-group col-md-2">
                <label class="col-md-12">&nbsp;</label>
                <button type="button" id="pesquisar" class="btn btn-primary"><i class="glyphicon glyphicon-filter"></i> Filtrar</button>
            </div>
        </div>

    </div>
</div>
<div class="list-group list-group-striped list-group-hover" id="items">

</div>
@section('inscript')
<script type="text/javascript">
$(document).ready(function() {

    $(':input:enabled:visible:first').focus();
    $('#chequemotivodevolucao').Setcase();


    $("#pesquisar").click(function() {
        var vencimento_de = $("#vencimento_de").val();
        var vencimento_ate = $("#vencimento_ate").val();

        if((vencimento_de=='' || vencimento_de== null) && (vencimento_ate=='' || vencimento_ate== null)){
            bootbox.alert('<span class="text-danger">Faça o filtro para prosseguir</span>');
            return false;
        }
        $.ajax({
            type: 'POST',
            url: '{{ url('cheque-repasse/consulta') }}/',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {'vencimento_de':vencimento_de,'vencimento_ate':vencimento_ate},
            success: function(retorno) {

                if(retorno.status){
                    var html = '';
                    //---- Exibe informação
                    $.each(retorno.cheques, function( i, val ) {

                        html = html + '<div class="list-group-item">';
                            html = html + '<div class="row item">';
                                html = html + '<div class="col-md-1 text-muted"><input type="checkbox"></div>';
                                html = html + '<div class="col-md-1 text-right"><b>'+val.valor+'</b></div>';
                                html = html + '<div class="col-md-1 text-center"><b>'+val.vencimento+'</b></div>';
                                html = html + '<div class="col-md-5"><a href="'+val.linkpessoa+'"><b>'+val.pessoa+'</b><br></a><span class="text-muted">'+val.emitentes+'</span></div>';

                                html = html + '<div class="col-md-1 text-muted">'+val.banco+'<br>'+val.agencia+'</div>';
                                html = html + '<div class="col-md-1 text-right text-muted">'+val.contacorrente+'<br>'+val.numero+'</div>';
                                html = html + '<div class="col-md-1 text-muted text-center">'+val.emissao+'</div>';
                                html = html + '<div class="col-md-1 text-muted"><a href="'+val.linkcheque+'">'+val.codcheque+'</a></div>';
                            html = html + '</div>';
                        html = html + '</div>';

                    });
                    $("#items").html(html);
                }
            },
            error: function (XHR, textStatus) {
                bootbox.alert('<span class="text-danger">Erro</span>');
                console.log(XHR);
                console.log(textStatus);
            }
        });

    });

    $('#form-cheque-repasse').on("submit", function(e) {
        var currentForm = this;
        e.preventDefault();
        bootbox.confirm("Tem certeza que deseja salvar?", function(result) {
            if (result) {
                currentForm.submit();
            }
        });
    });

});

</script>
@endsection
