@extends('layouts.default')
@section('content')
<nav class="navbar navbar-default navbar-fixed-top" id="submenu">
    <div class="container-fluid"> 
        <ul class="nav navbar-nav">
            <li><a href="{{ url("estoque-saldo-conferencia") }}"><span class="glyphicon glyphicon-list-alt"></span> Listagem</a></li>
        </ul>
    </div>
</nav>
<h1 class="header">
    {!! 
    titulo(
        NULL ,
        [
            ['url' => "estoque-saldo-conferencia", 'descricao' => 'Conferência Saldo de Estoque'],
            ['url' => "", 'descricao' => 'Nova'],
        ],
        NULL
    ) 
!!}
</h1>
<hr>
{!! Form::model($model, ['method' => 'GET', 'class' => 'form-inline', 'id' => 'form-estoque-saldo-conferencia-selecao', 'route' => 'estoque-saldo-conferencia.create']) !!}

    @include('errors.form_error')
    @include('estoque-saldo-conferencia.form-selecao')

{!! Form::close() !!}   

<hr>
@if (!empty($model->codestoquesaldo))
    <div class='row-fluid'>

        {!! Form::model($model, ['method' => 'POST', 'class' => 'form-horizontal', 'id' => 'form-estoque-saldo-conferencia', 'route' => 'estoque-saldo-conferencia.store']) !!}

            @include('errors.form_error')
            @include('estoque-saldo-conferencia.form')

        {!! Form::close() !!}   
    </div>
@endif

@section('inscript')
<script type="text/javascript">
$(document).ready(function() {
    
    $('#barras').focus();
    $('#quantidadeinformada').focus();
    
    $('#estoqueMovimento').on("submit", function(e){
        var currentForm = this;
        e.preventDefault();
        bootbox.confirm("Tem certeza que deseja salvar?", function(result) {
            if (result) {
                currentForm.submit();
            }
        });
    });    
    
    
    $('#data').datetimepicker({
        useCurrent: false,
        showClear: true,
        locale: 'pt-br',
        format: 'DD/MM/YYYY HH:mm:ss',
        sideBySide: true
    });
    
    $('#codestoquelocal').select2({
        placeholder: 'Local',
        allowClear: true,
        closeOnSelect: true
    });
    
    $('#fiscal').bootstrapSwitch('state', <?php echo (!empty($fiscal) ? 'true' : 'false'); ?>);
    
    $('#quantidadeinformada').autoNumeric('init', {aSep:'.', aDec:',', altDec:'.', mDec:3 });
    $('#customedioinformado').autoNumeric('init', {aSep:'.', aDec:',', altDec:'.', mDec:6 });
    
    $('#estoqueminimo, #estoquemaximo').autoNumeric('init', {aSep:'.', aDec:',', altDec:'.', mDec:0 });
    
    $('#corredor, #prateleira, #coluna, #bloco').autoNumeric('init', {aSep:'.', aDec:',', altDec:'.', mDec:0 });
    
});

</script>
@endsection



@stop