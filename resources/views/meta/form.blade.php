<?php
use MGLara\Models\Pessoa;
use MGLara\Models\Filial;
use MGLara\Models\Cargo;
use Collective\Html\FormBuilder;

$cargos = [''=>''] + Cargo::orderBy('cargo')->lists('cargo', 'codcargo')->all();        
//$filiais = Filial::whereIn('codfilial', ['102', '103', '104'])->get();
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
            {!! Form::date('meta[periodoinicial]', $model->periodoinicial, ['class' => 'form-control text-center', 'id' => 'meta[periodoinicial]', 'placeholder' => 'De', 'style'=>'width:200px; margin-right:10px']) !!}
            {!! Form::date('meta[periodofinal]', $model->periodofinal, ['class' => 'form-control text-center', 'id' => 'meta[periodofinal]', 'placeholder' => 'Até', 'style'=>'width:200px;']) !!}
        </div>
    </div>
</div>

<div class="form-group">
    {!! Form::label('meta[premioprimeirovendedorfilial]', 'Prêmio Melhor Vendedor', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-md-2">
        <div class="input-group">
            <div class="input-group-addon">R$</div>
            {!! Form::number('meta[premioprimeirovendedorfilial]', null, ['class' => 'form-control text-right',  'id'=> 'meta[premioprimeirovendedorfilial]', 'required'=>'required', 'placeholder' => '', 'step'=>'0.01']) !!}
        </div>
    </div>
</div>

<div class="form-group">
    {!! Form::label('meta[percentualcomissaovendedor]', 'Comissão', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-md-2">
        <div class="input-group">
            {!! Form::number('meta[percentualcomissaovendedor]', null, ['class' => 'form-control text-right',  'id'=>'meta[percentualcomissaovendedor]', 'required'=>'required', 'placeholder' => '', 'step'=>'0.01']) !!}
            <div class="input-group-addon">%</div>
        </div>
    </div>
</div>

<div class="form-group">
    {!! Form::label('meta[percentualcomissaovendedormeta]', 'Prêmio Meta Vendedor', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-md-2">
        <div class="input-group">
            {!! Form::number('meta[percentualcomissaovendedormeta]', null, ['class' => 'form-control text-right',  'id'=>'meta[percentualcomissaovendedormeta]', 'required'=>'required', 'placeholder' => '', 'step'=>'0.01']) !!}
            <div class="input-group-addon">%</div>
        </div>
    </div>
</div>

<div class="form-group">
    {!! Form::label('meta[percentualcomissaosubgerentemeta]', 'Prêmio Meta Sub-Gerente', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-md-2">
        <div class="input-group">
            {!! Form::number('meta[percentualcomissaosubgerentemeta]', null, ['class' => 'form-control text-right',  'id'=>'meta[percentualcomissaosubgerentemeta]', 'required'=>'required', 'placeholder' => '', 'step'=>'0.01']) !!}
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



<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        {!! Form::submit($submitTextButton, array('class' => 'btn btn-primary')) !!}
    </div>
</div>

<div class="col-xs-3">
    <ul class="nav nav-tabs tabs-left">
        @foreach($filiais as $filial)
        <li class=""><a href="#tab-filial-{{$filial->codfilial}}" data-toggle="tab">{{$filial->filial}}</a></li>
        @endforeach
    </ul>
</div>

<div class="col-xs-9">
    <div class="tab-content">
        @foreach($filiais as $filial)
        <div class="tab-pane" id="tab-filial-{{$filial->codfilial}}">
            <div class="form-group">
                {!! Form::label('', 'Controla', ['class'=>'col-sm-2 control-label']) !!}
                <div class="col-sm-9" id="wrapper-site">{!! Form::checkbox('metafilial[$filial->codfilial][controla]', true, null, ['id'=>'controla', 'class'=>'controla', 'data-off-text' => 'Não', 'data-on-text' => 'Sim',  'data-filial'=>$filial->codfilial]) !!}</div>
            </div>
            
        <div id="dados-filial-{{$filial->codfilial}}"  style="display: none">            
            <div class="form-group">
                {!! Form::label("metafilial[$filial->codfilial][valormetafilial]", 'Meta Filial', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-md-3">
                    <div class="input-group">
                        <div class="input-group-addon">R$</div>
                        {!! Form::number("metafilial[$filial->codfilial][valormetafilial]", null, ['class' => 'form-control text-right',  'id'=>'valormetafilial', 'placeholder' => '', 'step'=>'0.01']) !!}
                    </div>
                </div>
            </div>
            <div class="form-group">
                {!! Form::label("metafilial[$filial->codfilial][valormetavendedor]", 'Meta Vendedor', ['class' => 'col-sm-2 control-label']) !!}
                <div class="col-md-3">
                    <div class="input-group">
                        <div class="input-group-addon">R$</div>
                        {!! Form::number("metafilial[$filial->codfilial][valormetavendedor]", null, ['class' => 'form-control text-right',  'id'=>'valormetavendedor', 'placeholder' => '', 'step'=>'0.01']) !!}
                    </div>
                </div>
            </div>
            <div class="form-group">
                {!! Form::label("metafilial[$filial->codfilial][observacoes]", 'Observações:', ['class'=>'col-sm-2 control-label']) !!}
                <div class="col-md-4 col-xs-4">
                    {!! Form::textarea("metafilial[$filial->codfilial][observacoes]", null, ['class'=> 'form-control', 'id'=>"metafilial[$filial->codfilial][observacoes]", 'rows'=>'3']) !!}
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12"  id="add-{{ $filial->codfilial }}">
                    <a class="btn btn-default adicionar-pessoas" data-filial="{{ $filial->codfilial }}">Adicionar</a>
                    <div class="cargo-pessoa-{{ $filial->codfilial }}">
                    {!! Form::select("cargos", $cargos, null, ['class'=> 'form-control adicionar-cargo', 'id'=>"cargo_$filial->codfilial", 'style'=>"width: 200px; float:left"]) !!}
                    {!! Form::select("pessoas", $pessoas, null, ['class'=> 'form-control adicionar-pessoa', 'id'=>"pessoa_$filial->codfilial", 'style'=>"width: 300px"]) !!}
                    </div>
                </div>
            </div>
        </div>
            
        </div>
        @endforeach  
    </div>
</div>  

@section('inscript')
<link href="{{ URL::asset('public/vendor/bootstrap-vertical-tabs/tabs.css') }}" rel="stylesheet">
<style>
    .pessoas-metafilial {
        margin-bottom: 10px;
    }    
</style>
<script type="text/javascript">
$(document).ready(function() {
    $('#form-meta').on("submit", function(e){
        var currentForm = this;
        e.preventDefault();
        bootbox.confirm("Tem certeza que deseja salvar?", function(result) {
            if (result) {
                currentForm.submit();
            }
        });
    });
    $('.controla').bootstrapSwitch();
    $( "ul.nav-tabs li:first-child, div.tab-content div.tab-pane:first-child").addClass('active');
    
    /*
    $(".adicionar-pessoa").select2({
        placeholder:'Pessoas',
        allowClear:true,
        closeOnSelect:true        
    });
    
    $(".adicionar-cargo").select2({
        placeholder:'Cargos',
        allowClear:true,
        closeOnSelect:true        
    });
    */
    
    $('.controla').on('switchChange.bootstrapSwitch', function(event, state) {
        var filial = $(this).data("filial");
        
        if (state === true) {
            console.log(filial);
            $('#dados-filial-'+filial).slideDown('slow');
        } else {
            console.log(filial);
            $('#dados-filial-'+filial).slideUp('slow');
        }
    });
    
    $('.adicionar-pessoas').on('click', function(e) {
        e.preventDefault();
        var filial = $(this).data("filial");
        var seletor = '.cargo-pessoa-'+filial;
        $(seletor).clone().appendTo('#add-'+filial);
        //$('#add-'+filial+' '+seletor+':last-child').removeClass(seletor);
    });    
    
});
</script>
@endsection
