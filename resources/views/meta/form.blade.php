<?php
use MGLara\Models\Pessoa;
use MGLara\Models\Filial;
use MGLara\Models\Cargo;
use Collective\Html\FormBuilder;

$cargos = [''=>''] + Cargo::orderBy('cargo')->lists('cargo', 'codcargo')->all();        
$filiais = Filial::orderBy('codfilial')->get();
$pessoas = [''=>''] + Pessoa::where('codgrupocliente', 8)
        ->where('vendedor', true)
        ->whereNull('inativo')
        ->orderBy('fantasia')
        ->lists('fantasia', 'codpessoa')
        ->all();
?>
<div class="form-group">
    {!! Form::label('meta[periodoinicial]', 'Período:', ['class'=>'col-sm-2 control-label']) !!}
    <div class="col-md-10">
        <div class="input-group">
            {!! Form::datetimeLocal('meta[periodoinicial]', $model->periodoinicial, ['class' => 'form-control text-center', 'id' => 'meta[periodoinicial]', 'placeholder' => 'De', 'style'=>'width:200px; margin-right:10px']) !!}
            {!! Form::datetimeLocal('meta[periodofinal]', $model->periodofinal, ['class' => 'form-control text-center', 'id' => 'meta[periodofinal]', 'placeholder' => 'Até', 'style'=>'width:200px;']) !!}
        </div>
    </div>
</div>

<div class="form-group">
    {!! Form::label('meta[premioprimeirovendedorfilial]', 'Prêmio Melhor Vendedor', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-md-2">
        <div class="input-group">
            <div class="input-group-addon">R$</div>
            {!! Form::number('meta[premioprimeirovendedorfilial]', null, ['class' => 'form-control text-right',  'id'=> 'meta[premioprimeirovendedorfilial]', 'required'=>'true', 'placeholder' => '', 'step'=>'0.01']) !!}
        </div>
    </div>
</div>

<div class="form-group">
    {!! Form::label('meta[percentualcomissaovendedor]', 'Comissão', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-md-2">
        <div class="input-group">
            {!! Form::number('meta[percentualcomissaovendedor]', null, ['class' => 'form-control text-right',  'id'=>'meta[percentualcomissaovendedor]', 'required'=>'true', 'placeholder' => '', 'step'=>'0.01']) !!}
            <div class="input-group-addon">%</div>
        </div>
    </div>
</div>

<div class="form-group">
    {!! Form::label('meta[percentualcomissaovendedormeta]', 'Prêmio Meta Vendedor', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-md-2">
        <div class="input-group">
            {!! Form::number('meta[percentualcomissaovendedormeta]', null, ['class' => 'form-control text-right',  'id'=>'meta[percentualcomissaovendedormeta]', 'required'=>'true', 'placeholder' => '', 'step'=>'0.01']) !!}
            <div class="input-group-addon">%</div>
        </div>
    </div>
</div>

<div class="form-group">
    {!! Form::label('meta[percentualcomissaosubgerentemeta]', 'Prêmio Meta Sub-Gerente', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-md-2">
        <div class="input-group">
            {!! Form::number('meta[percentualcomissaosubgerentemeta]', null, ['class' => 'form-control text-right',  'id'=>'meta[percentualcomissaosubgerentemeta]', 'required'=>'true', 'placeholder' => '', 'step'=>'0.01']) !!}
            <div class="input-group-addon">%</div>
        </div>
    </div>
</div>

<div class="form-group">
    {!! Form::label('meta[observacoes]', 'Observações:', ['class'=>'col-sm-2 control-label']) !!}
    <div class="col-md-4 col-xs-4">
        {!! Form::textarea('meta[observacoes]', null, ['class'=> 'form-control', 'id'=>'meta[observacoes]', 'rows'=>'3']) !!}
    </div>
</div>

<div class="col-xs-2">
    <ul class="nav nav-tabs tabs-left">
        @foreach($filiais as $filial)
        <li class=""><a href="#tab-filial-{{$filial->codfilial}}" data-toggle="tab">{{$filial->filial}}</a></li>
        @endforeach
    </ul>
</div>

<div class="col-xs-10">
    <div class="tab-content">
        @foreach($filiais as $filial)
        <div class="tab-pane" id="tab-filial-{{$filial->codfilial}}">
            <div class="form-group">
                {!! Form::label('', 'Controla', ['class'=>'col-sm-2 control-label']) !!}
                <div class="col-sm-9" id="wrapper-site">{!! Form::checkbox("metafilial[$filial->codfilial][controla]", true, null, ['class'=>'controla', 'data-off-text' => 'Não', 'data-on-text' => 'Sim',  'data-filial'=>$filial->codfilial]) !!}</div>
            </div>
            
            <div id="dados-filial-{{$filial->codfilial}}" @if(!isset($model['metafilial'][$filial->codfilial]['controla'])) style="display: none" @endif>            
            {!! Form::hidden("metafilial[$filial->codfilial][codmetafilial]", null, ['class' => 'form-control']) !!}
            <div class="form-group">
                {!! Form::label("metafilial[$filial->codfilial][valormetafilial]", 'Meta Filial', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-md-3">
                    <div class="input-group">
                        <div class="input-group-addon">R$</div>
                        {!! Form::number("metafilial[$filial->codfilial][valormetafilial]", null, ['class' => 'form-control text-right',  'id'=>"valormetafilial_$filial->codfilial", 'placeholder' => '', 'step'=>'0.01']) !!}
                    </div>
                </div>
            </div>
            <div class="form-group">
                {!! Form::label("metafilial[$filial->codfilial][valormetavendedor]", 'Meta Vendedor', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-md-3">
                    <div class="input-group">
                        <div class="input-group-addon">R$</div>
                        {!! Form::number("metafilial[$filial->codfilial][valormetavendedor]", null, ['class' => 'form-control text-right',  'id'=>"valormetavendedor_$filial->codfilial", 'placeholder' => '', 'step'=>'0.01']) !!}
                    </div>
                </div>
            </div>
            <div class="form-group">
                {!! Form::label("metafilial[$filial->codfilial][observacoes]", 'Observações:', ['class'=>'col-sm-2 control-label']) !!}
                <div class="col-md-4 col-xs-4">
                    {!! Form::textarea("metafilial[$filial->codfilial][observacoes]", null, ['class'=> 'form-control', 'id'=>"metafilial[$filial->codfilial][observacoes]", 'rows'=>'3']) !!}
                </div>
            </div>
            <br>
            <div class="form-group">
                <p class="col-md-12" style="padding-left: 30px;">
                    <a class="btn btn-primary adicionar-pessoas" data-filial="{{ $filial->codfilial }}">Adicionar Colaborador</a>
                </p>
                <div  id="add-{{ $filial->codfilial }}">
                    <div class="cargo-pessoa cargo-pessoa-{{ $filial->codfilial }} col-md-12 hide">
                        <div class="col-md-4">                    
                        {!! Form::select("pessoa", $pessoas, null, ['class'=> 'form-control adicionar-pessoa', 'data-filial'=>$filial->codfilial]) !!}
                        </div>
                        <div class="col-md-3">                    
                        {!! Form::select("cargo", $cargos, null, ['class'=> 'form-control adicionar-cargo', 'data-filial'=>$filial->codfilial]) !!}
                        </div>
                        <a class="btn text-danger pull-left remover-pessoa"><i class="glyphicon glyphicon-trash"></i></a>
                    </div>
                </div>
                @if(isset($model['metafilial'][$filial->codfilial]))
                    @foreach($model['metafilial'][$filial->codfilial]['pessoas'] as $pessoa)
                    <div class="cargo-pessoa col-md-12">
                        <div class="col-md-4">
                        {!! Form::select("metafilial[$filial->codfilial][pessoas][$pessoa[codpessoa]][codpessoa]", $pessoas, null, ['class'=> 'form-control select pessoa', 'style'=>"width: 100%;", 'data-filial'=>$filial->codfilial]) !!}
                        </div>
                        <div class="col-md-3">
                        {!! Form::select("metafilial[$filial->codfilial][pessoas][$pessoa[codpessoa]][codcargo]", $cargos, null, ['class'=> 'form-control select cargo', 'style'=>"width: 100%", 'data-filial'=>$filial->codfilial]) !!}
                        </div>
                        {!! Form::hidden("metafilial[$filial->codfilial][pessoas][$pessoa[codpessoa]][codmetafilialpessoa]", null, ['class'=> 'form-control', ]) !!}
                        <a class="btn text-danger pull-left remover-pessoa"><i class="glyphicon glyphicon-trash"></i></a>
                    </div>
                    @endforeach
                @endif
            </div>
        </div>
            
        </div>
        @endforeach  
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                {!! Form::submit($submitTextButton, array('class' => 'btn btn-primary')) !!}
            </div>
        </div>
    </div>
</div>  

@section('inscript')
<link href="{{ URL::asset('public/vendor/bootstrap-vertical-tabs/tabs.css') }}" rel="stylesheet">
<style>
.pessoas-metafilial {
    margin-bottom: 10px;
}
.cargo-pessoa {
    margin-bottom: 10px;
}
.tabs-left li a {
    text-align: right; 
    font-weight: bold
}
</style>
<script type="text/javascript">
   
function validaPessoas() {
    var pessoas = [];
    $("select.select.pessoa").each( function(index) {
        pessoas.push($(this).val());
    });
    
    var pessoasFiltradas = pessoas.filter(function(pessoa, i) {
        return pessoas.indexOf(pessoa) === i;
    });

    if(pessoas.length === pessoasFiltradas.length){
        return true;
    } else {
        bootbox.alert("Uma pessoa foi lançada duas vezes");
        return false;
    }
}

$(document).ready(function() {
    $('#form-meta').on("submit", function(e){
        var currentForm = this;
        e.preventDefault();
        if(validaPessoas()) {
            bootbox.confirm("Tem certeza que deseja salvar?", function(result) {
                if (result) {
                    currentForm.submit();
                }
            });
        }
    });

    $('.controla').bootstrapSwitch();
    $( "ul.nav-tabs li:first-child, div.tab-content div.tab-pane:first-child").addClass('active');

    $('.select').select2({
        allowClear: true,
        closeOnSelect: true        
    });

    $('.controla').on('switchChange.bootstrapSwitch', function(event, state) {
        var filial = $(this).data("filial");
        if (state === true) {
            $('#dados-filial-'+filial).slideDown('slow');
            $('#valormetavendedor_'+filial).attr('required', true);
            $('#valormetafilial_'+filial).attr('required', true);
        } else {
            $('#dados-filial-'+filial).slideUp('slow');
            $('#valormetavendedor_'+filial).attr('required', false);
            $('#valormetafilial_'+filial).attr('required', false);
        }
    });

    $('.adicionar-pessoas').on('click', function(e) {
        e.preventDefault();
        $('.select').select2("destroy");
        var filial = $(this).data("filial");
        var seletor = '.cargo-pessoa-'+filial;
        var div = '#add-'+filial;
        
        $(seletor).first().clone(true, true).appendTo(div);
        $(seletor).last().removeClass('hide').addClass('clone');
        $(seletor+'.clone select').addClass('select');
        
        $('.select').select2({
            allowClear: true,
            closeOnSelect: true        
        });

        var nome = $(seletor+'.clone select').prev().attr('id');
        $(seletor+'.clone:last-child select').attr('name', nome).attr('required', true);
        $(seletor+'.clone:last-child select:last-child').attr('name', nome+'1').attr('required', true);


    });    

    $('.adicionar-pessoa').on('change', function () {
        var filial = $(this).data('filial');
        var pessoa = $(this).val();
        $(this).attr('name', 'metafilial['+filial+'][pessoas]['+pessoa+'][codpessoa]');
        $(this).parent()
            .next('.col-md-3')
            .children('select')
            .attr('name', 'metafilial['+filial+'][pessoas]['+pessoa+'][codcargo]');
    });

    $('.remover-pessoa').on('click', function() {
        $(this).parent().remove();
    });

});
</script>
@endsection
