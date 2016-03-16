@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
	<div class="container-fluid"> 
		<ul class="nav navbar-nav">
			<li>
			</li> 
		</ul>
	</div>
</nav>
<h1 class="header">Exportação Domínio</h1>
<hr>
<form class='form-horizontal'>

<?php

use MGLara\Models\Filial;
$filiais = [''=>''] + Filial::lists('filial', 'codfilial')->all();

?>
    
<div class="form-group">
    <label for="codfilial" class="col-sm-2 control-label">
        {!! Form::label('codfilial', 'Filial') !!}
    </label>
    <div class="col-md-2 col-xs-4">
        {!! Form::select('codfilial', $filiais, ['class'=> 'form-control'], ['style'=>'width:100%', 'id'=>'codfilial']) !!}
  </div>
</div>
    
    
<div class="form-group">
    <label for="mes" class="col-sm-2 control-label">
        {!! Form::label('chkEstoque', 'chkEstoque') !!}
    </label>
    <div class="col-md-2 col-xs-4">
        <div class='pull-left'>
            {!! Form::checkbox('chkEstoque', null, ['class'=> 'form-control text-center', 'id'=>'chkEstoque']) !!}
        </div>
        <div>
            {!! Form::text('mes', null, ['class'=> 'form-control text-center', 'id'=>'mes', 'required'=>'required']) !!}
        </div>
    </div>
</div>

<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        {!! Form::button('Gerar Arquivo', array('class' => 'btn btn-primary', 'id'=>'btnGerar')) !!}
    </div>
</div>
    
    
</form>

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
            console.log(data);
            bootbox.alert('Feito');
        }).fail(function(error ) {
            console.log(data);
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
        estoque();
    });

});
</script>
@endsection
@stop