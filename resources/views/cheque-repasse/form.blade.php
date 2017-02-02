<div class="panel panel-default">
    <div class='panel-body'>
        <div class='row'>
            <div class="form-group col-md-2">
                {!! Form::label('vencimento_de', 'De', ['control-label']) !!}
                {!! Form::date('vencimento_de', null, ['class'=> 'form-control text-right', 'id'=>'vencimento_de']) !!}
            </div>
            <div class="form-group col-md-2">
                {!! Form::label('vencimento_ate', 'Até', ['control-label']) !!}
                {!! Form::date('vencimento_ate', null, ['class'=> 'form-control text-right', 'id'=>'vencimento_ate']) !!}
            </div>
            <div class="form-group col-md-2">
                <label class="col-md-12">&nbsp;</label>
                <button type="button" id="pesquisar" class="btn btn-primary"><i class="glyphicon glyphicon-filter"></i> Filtrar</button>
            </div>
        </div>
    </div>
</div>
@section('inscript')
<script type="text/javascript">
$(document).ready(function() {

    $(':input:enabled:visible:first').focus();
    $('#chequemotivodevolucao').Setcase();


    //------- BTN
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

                if(retorno.valido){

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
