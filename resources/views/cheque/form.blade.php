
<div class='row'>
    <div class='col-md-6'>
        <div class='row'>
            <div class="form-group col-md-12">
                {!! Form::label('cmc7', 'CMC7:', []) !!}
                {!! Form::text('cmc7', null, ['class'=> 'form-control', 'id'=>'cmc7', 'required'=>'required']) !!}
                <div id="warning_cmc7"></div>
            </div>
            <div class="form-group col-md-4">
                {!! Form::label('valor', 'Valor:', []) !!}
                {!! Form::number('valor', null, ['class'=> 'form-control text-right', 'id'=>'valor', 'required'=>'required', 'step'=>'0.01','min'=>'0.01','max'=>'99999999']) !!}
            </div>
            <div class="form-group col-md-4">
                {!! Form::label('emissao', 'Data de Emissão', []) !!}
                {!! Form::date('emissao', formataData($model->emissao,"Y-m-d"), ['class'=> 'form-control', 'id'=>'emissao', 'required'=>'required']) !!}
            </div>
            <div class="form-group col-md-4">
                {!! Form::label('vencimento', 'Data de Vencimento', []) !!}
                {!! Form::date('vencimento', formataData($model->vencimento,"Y-m-d"), ['class'=> 'form-control', 'id'=>'vencimento', 'required'=>'required']) !!}
            </div>


            <div class="col-md-12">
                <div id="CamposEmitentes"></div>
                <button  style="margin: 0 0 15px 0;" type="button" onclick="adicionaemitente();" class="btn btn-success"><i class="glyphicon glyphicon-plus-sign"></i> Adicionar Emitente</button>
            </div>

            <div class="form-group col-md-12">
                {!! Form::label('codpessoa', 'Cliente:', []) !!}
                {!! Form::select2Pessoa('codpessoa', null, ['class'=> 'form-control', 'id'=>'codpessoa', 'placeholder'=>'Cliente', 'required'=>'required']) !!}
            </div>
            <div class="form-group col-md-12">
                {!! Form::submit($submitTextButton, array('class' => 'btn btn-primary')) !!}
            </div>
        </div>
    </div>
    <div class='col-md-6'>
        <div class='row'>
            <div class="form-group col-md-4">
                {!! Form::label('codbanco', 'Banco:', []) !!}
                {!! Form::text('codbanco', null, ['class'=> 'form-control', 'id'=>'codbanco', 'readonly'=>'readonly']) !!}
            </div>
            <div class="form-group col-md-2">
                {!! Form::label('agencia', 'Agência:', []) !!}
                {!! Form::text('agencia', null, ['class'=> 'form-control', 'id'=>'agencia', 'readonly'=>'readonly']) !!}
            </div>
            <div class="form-group col-md-3">
                {!! Form::label('contacorrente', 'Conta:', []) !!}
                {!! Form::text('contacorrente', null, ['class'=> 'form-control', 'id'=>'contacorrente', 'readonly'=>'readonly']) !!}
            </div>
            <div class="form-group col-md-3">
                {!! Form::label('numero', 'Número:', []) !!}
                {!! Form::text('numero', null, ['class'=> 'form-control', 'id'=>'numero', 'readonly'=>'readonly']) !!}
            </div>
            <div class="form-group col-md-12">
                {!! Form::label('observacao', 'Observação:', []) !!}
                {!! Form::textarea('observacao', null, ['class'=> 'form-control', 'id'=>'observacao']) !!}
            </div>
        </div>
    </div>
</div>
@section('inscript')
<script src="{{ URL::asset('public/js/formatacmc7.js') }}"></script>
<script type="text/javascript">
$(document).ready(function() {

    @foreach ($model->ChequeEmitenteS as $emit)
        CarregaCampos('{{ $emit->codchequeemitente }}','{{ $emit->cnpj }}','{{ $emit->emitente }}');
    @endforeach

    //------- Mascaras
    $("#cmc7").mask("<99999999<9999999999>999999999999:");

    var emissao = $("#emissao").val();
    $("#emissao").change(function() {
        if($("#vencimento").val()== emissao || $("#vencimento").val()== ''){
            $("#vencimento").val($("#emissao").val());
        }
        emissao = $("#emissao").val();
    });
    $('#emitente').Setcase();

    //------- Valida CMC7
    $("#cmc7").change(function() {
        var cmc7 = document.getElementById("cmc7").value;

        if(cmc7.length==34){
            $.ajax({
                type: 'GET',
                url: '{{ url('cheque/consulta') }}/' + cmc7,
                dataType: 'json',
                success: function(retorno) {

                    if(retorno.valido){
                        $("#warning_cmc7").html("<span class='label label-success'><i class='glyphicon glyphicon-ok'></i> CMC7 Válido</span>");

                        $("#codbanco").val(retorno.banco);
                        $("#agencia").val(retorno.agencia);
                        $("#numero").val(retorno.numero);
                        $("#contacorrente").val(retorno.contacorrente);

                        if(retorno.ultimo.codpessoa != null){
                            $('#codpessoa').val(retorno.ultimo.codpessoa).change();
                        }
                        console.log(retorno.ultimo.emitentes);
                        var n  = null;
                        $.each(retorno.ultimo.emitentes, function( i, val ) {
                            CarregaCampos('',val.cnpj,val.emitente);
                            n = 1;
                        });
                        if(n == null){
                            CarregaCampos('','','');
                        }

                    } else {
                        bootbox.alert('<span class="text-danger">CMC7 Inválido</span>');
                        $('#cmc7').val('');
                        $('#cmc7').focus();
                        $("#warning_cmc7").html("<span class='label label-danger'><i class='glyphicon glyphicon-remove'></i> CMC7 Inválido</span>");
                    }
                },
                error: function (XHR, textStatus) {
                    bootbox.alert('<span class="text-danger">Erro</span>');
                    console.log(XHR);
                    console.log(textStatus);
                }
            });
        }
    });

    $(':input:enabled:visible:first').focus();
    $('#chequemotivodevolucao').Setcase();

    $('#form-cheque').on("submit", function(e) {
        var currentForm = this;
        e.preventDefault();
        bootbox.confirm("Tem certeza que deseja salvar?", function(result) {
            if (result) {
                currentForm.submit();
            }
        });
    });

});
$(document).on('click', '#remover', function(){
    // consulta se tem mais.
    var cademitente = ($('div#cademitente').length);
    if(cademitente!=1){
        $(this).parent().parent().remove();
    }else{
        bootbox.alert('<span class="text-danger">Não é possivel excluir!</span>');
    }
});
$(document).on('change', '#cnpj', function(){
    // verefica se ja há pessoa selecionada
    var codpessoa = $('#codpessoa').val();

    var cnpj = $(this).val();
    var campocnpj = $(this);
    $.ajax({
        type: 'GET',
        url: '{{ url('cheque/consultaemitente') }}/' + cnpj,
        dataType: 'json',
        success: function(retorno) {
            if( retorno.codpessoa   != null){
                if(codpessoa== null || codpessoa==''){
                    $('#codpessoa').val(retorno.codpessoa).change();
                }
                campocnpj.parent().parent().find("#emitente").val(retorno.pessoa);
            }
        },
        error: function (XHR, textStatus) {
            //bootbox.alert('<span class="text-danger">Erro</span>');
            console.log(XHR);
            console.log(textStatus);
        }
    });


});
//------ Funcao que Carrega Campos
function CarregaCampos(codchequeemitente,cnpj,emitente){

    var html = null;
    html = "<div id='cademitente' class='row'>";
    html = html +  '<input type="hidden" name="chequeemitente_codchequeemitente[]" value="'+codchequeemitente+'">';
    html = html +  '<div class="form-group col-md-4"><label>Cnpj</label>';
    html = html +  '<input type="text" class="form-control" id="cnpj" name="chequeemitente_cnpj[]" value="'+cnpj+'" required="required" placeholder="Cpf/Cnpj">';
    html = html +  '</div>';
    html = html +  '<div class="form-group col-md-7"><label>Emitente</label>';
    html = html +  '<input type="text" class="form-control" id="emitente" name="chequeemitente_emitente[]" value="'+emitente+'" required="required" placeholder="Nome completo">';
    html = html +  '</div>';
    html = html +  '<div class="form-group col-md-1"><label>&nbsp;&HorizontalLine;&nbsp;</label><a class="btn btn-danger row" id="remover"><i class="glyphicon glyphicon-trash"></i></a></div>';
    html = html +  '</div>';
    $("#CamposEmitentes").append(html);
}
function adicionaemitente(){

    if($('#cmc7').val()!= null && $('#cmc7').val()!= undefined && $('#cmc7').val()!=''){
        CarregaCampos('','','');

        $("input#cnpj").last().focus();

    }else{
        bootbox.alert('<span class="text-danger">Digite o CMC7 para incluir emitentes</span>');
    }
}
</script>
@endsection
