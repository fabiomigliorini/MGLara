@extends('layouts.default')
@section('content')
<ol class="breadcrumb header">Exportação Domínio</ol>
<hr>
<form class='form-horizontal'>
<?php
use MGLara\Models\Filial;
$filiais = [''=>''] + Filial::orderBy('codfilial')->lists('filial', 'codfilial')->all();

?>
<div class="form-group">
    {!! Form::label('codfilial', 'Filial', ['class'=>'col-sm-2 control-label']) !!}
    <div class="col-md-2 col-xs-4">
        {!! Form::select('codfilial', $filiais, null, ['class'=> 'form-control', 'style'=>'width:100%', 'id'=>'codfilial']) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('chkEstoque', 'chkEstoque',['class'=>'col-sm-2 control-label']) !!}
    <div class="col-md-2 col-xs-4">
        {!! Form::checkbox('chkEstoque', true, null, ['id'=>'chkEstoque', 'class'=> 'form-control', 'data-off-text' => 'Não', 'data-on-text' => 'Sim']) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('mes', 'Mês', ['class'=>'col-sm-2 control-label']) !!}
    <div class="col-md-2 col-xs-4">
        {!! Form::text('mes', null, ['class'=> 'form-control text-center', 'id'=>'mes', 'required'=>'required']) !!}
    </div>
</div>

<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        {!! Form::button('Gerar Arquivo', array('class' => 'btn btn-primary', 'id'=>'btnGerar')) !!}
    </div>
</div>
    
    
</form>

<div id="modalProcessando" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    Processando ... 
                </h4>
            </div>
            <div class="modal-body">
                <div class="progress">
                    <div class="progress-bar progress-bar-striped active" role="progressbar" id="pbProcessando" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 45%">
                    </div>
                </div>
                <div class='row-fluid text-center' id='labelPbProcessando'></div>
                <br>
                <pre class='row-fluid' id='logPbProcessando' style='height: 400px'></pre>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" id="btnFechaModalProcessando" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>


@section('inscript')
<script type="text/javascript">
function estoque ()
{
    if (!$('#chkEstoque').is(":checked"))
        return;

    $.getJSON(
        '{{ url('dominio/estoque') }}', 
        { 
            codfilial: $('#codfilial').val(),
            mes: $('#mes').val()
        }
        )
        .done(function(data) {
            $('#btnFechaModalProcessando').removeAttr('disabled');
            $('#logPbProcessando').prepend(JSON.stringify(data, null, '\t'));
            $('#pbProcessando').css('width', '100%');        
        }).fail(function(error ) {
            $('#btnFechaModalProcessando').removeAttr('disabled');
            $('#logPbProcessando').prepend(error);
            $('#pbProcessando').css('width', '100%');        
            console.log(error);
        });    
    
}
    
$(document).ready(function() {
    
    $('#mes').datetimepicker({
        locale: 'pt-br',
        format: 'MM/YYYY'
    });
    
    $("#mes").val("<?php echo date('m/Y'); ?>").change();
    
    $('#codfilial').select2({
        allowClear: true,
        width: 'resolve'        
    });
    
    $('#btnGerar').click(function (){
        $('#modalProcessando').modal('show');
        $('#btnFechaModalProcessando').attr('disabled', 'disabled');
        $('#pbProcessando').css('width', '0%');        
        estoque();
    });
    $('#chkEstoque').bootstrapSwitch();
});
</script>
@endsection
@stop