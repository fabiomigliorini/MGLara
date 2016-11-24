<?php
use MGLara\Models\Pessoa;
use MGLara\Models\Filial;
use MGLara\Models\Cargo;
use Collective\Html\FormBuilder;

$cargos = [''=>''] + Cargo::orderBy('cargo')->lists('cargo', 'codcargo')->all();        
$filiais = Filial::whereIn('codfilial', ['102', '103', '104'])->get();
$pessoas = Pessoa::where('codgrupocliente', 8)
        ->where('vendedor', true)
        ->whereNull('inativo')
        ->orderBy('fantasia')
        ->get();
?>
<div class="form-group">
    {!! Form::label('meta[periodoinicial]', 'Período:', ['class'=>'col-sm-2 control-label']) !!}
    <div class="col-md-10">
        {!! Form::date('meta[periodoinicial]', $model->periodoinicial, ['class' => 'form-control pull-left', 'id' => 'meta[periodoinicial]', 'placeholder' => 'De', 'style'=>'width:200px; margin-right:10px']) !!}
        {!! Form::date('meta[periodofinal]', $model->periodofinal, ['class' => 'form-control pull-left', 'id' => 'meta[periodofinal]', 'placeholder' => 'Até', 'style'=>'width:200px;']) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('meta[premioprimeirovendedorfilial]', 'Prêmio melhor vendedor', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-md-2">
        <div class="input-group">
            <div class="input-group-addon">R$</div>
            {!! Form::number('meta[premioprimeirovendedorfilial]', null, ['class' => 'form-control',  'id'=> 'meta[premioprimeirovendedorfilial]', 'required'=>'required', 'placeholder' => '', 'step'=>'0.01']) !!}
        </div>
    </div>
</div>

<div class="form-group">
    {!! Form::label('meta[percentualcomissaovendedor]', 'Percentual comissão vendedor', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-md-2">
        <div class="input-group">
            <div class="input-group-addon">%</div>
            {!! Form::number('meta[percentualcomissaovendedor]', null, ['class' => 'form-control',  'id'=>'meta[percentualcomissaovendedor]', 'required'=>'required', 'placeholder' => '', 'step'=>'0.01']) !!}
        </div>
    </div>
</div>

<div class="form-group">
    {!! Form::label('meta[percentualcomissaovendedormeta]', 'Percentual comissão vendedor meta', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-md-2">
        <div class="input-group">
            <div class="input-group-addon">%</div>
            {!! Form::number('meta[percentualcomissaovendedormeta]', null, ['class' => 'form-control',  'id'=>'meta[percentualcomissaovendedormeta]', 'required'=>'required', 'placeholder' => '', 'step'=>'0.01']) !!}
        </div>
    </div>
</div>

<div class="form-group">
    {!! Form::label('meta[percentualcomissaosubgerentemeta]', 'Percentual comissão sub-gerente meta', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-md-2">
        <div class="input-group">
            <div class="input-group-addon">%</div>
            {!! Form::number('meta[percentualcomissaosubgerentemeta]', null, ['class' => 'form-control',  'id'=>'meta[percentualcomissaosubgerentemeta]', 'required'=>'required', 'placeholder' => '', 'step'=>'0.01']) !!}
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
    <div class="col-md-12">
        <ul class="nav nav-tabs col-md-offset-2" role="tablist">
            @foreach($filiais as $filial)
            <li role="presentation" class=""><a href="#{{$filial->codfilial}}" aria-controls="{{$filial->codfilial}}" role="tab" data-toggle="tab">{{$filial->filial}}</a></li>
            @endforeach
        </ul>
        <div class="tab-content">
            @foreach($filiais as $filial)
            <div role="tabpanel" class="tab-pane" id="{{$filial->codfilial}}">
                <br>
                {!! Form::hidden("metafilial[$filial->codfilial][codmetafilial]", $filial->codmetafilial, ['class' => 'form-control',  'id'=>'']) !!}
                <div class="form-group">
                    {!! Form::label('', 'Controla', ['class'=>'col-sm-2 control-label']) !!}
                    <div class="col-sm-9" id="wrapper-site">{!! Form::checkbox('metafilial[$filial->codfilial][controla]', true, null, ['id'=>'controla', 'class'=>'controla', 'data-off-text' => 'Não', 'data-on-text' => 'Sim']) !!}</div>
                </div>
                <div class="form-group">
                    {!! Form::label("metafilial[$filial->codfilial][valormetafilial]", 'Valor meta filial', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-md-2">
                        <div class="input-group">
                            <div class="input-group-addon">R$</div>
                            {!! Form::number("metafilial[$filial->codfilial][valormetafilial]", null, ['class' => 'form-control',  'id'=>'valormetafilial', 'required'=>'required', 'placeholder' => '', 'step'=>'0.01']) !!}
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label("metafilial[$filial->codfilial][valormetavendedor]", 'Valor meta vendedor', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-md-2">
                        <div class="input-group">
                            <div class="input-group-addon">R$</div>
                            {!! Form::number("metafilial[$filial->codfilial][valormetavendedor]", null, ['class' => 'form-control',  'id'=>'valormetavendedor', 'required'=>'required', 'placeholder' => '', 'step'=>'0.01']) !!}
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label("metafilial[$filial->codfilial][observacoes]", 'Observações:', ['class'=>'col-sm-2 control-label']) !!}
                    <div class="col-md-4 col-xs-4">
                        {!! Form::textarea("metafilial[$filial->codfilial][observacoes]", null, ['class'=> 'form-control', 'id'=>"metafilial[$filial->codfilial][observacoes]", 'rows'=>'3']) !!}
                    </div>
                </div>
                @foreach($pessoas as $pessoa)
                <div class="form-group">
                    {!! Form::label($filial->codfilial.$pessoa->codpessoa, $pessoa->fantasia, ['class'=>'col-sm-2 control-label']) !!}
                    <?php
                        $codmetafilialpessoa = null;
                        if(!empty($model->getAttributes())) {
                            if(array_key_exists($pessoa->codpessoa, $model['metafilial'][$filial->codfilial]['pessoas'])) {
                                $codmetafilialpessoa = $model['metafilial'][$filial->codfilial]['pessoas'][$pessoa->codpessoa]['codmetafilialpessoa'];
                            }
                        }
                    ?>
                    <div class="col-md-3">
                        {!! Form::hidden("metafilial[$filial->codfilial][pessoas][$pessoa->codpessoa][codmetafilialpessoa]", $codmetafilialpessoa, ['class' => 'form-control',  'id'=>"metafilial[$filial->codfilial][pessoas][codpessoa]"]) !!}
                        {!! Form::hidden("metafilial[$filial->codfilial][pessoas][$pessoa->codpessoa][codpessoa]", $pessoa->codpessoa, ['class' => 'form-control pull-left',  'id'=>"metafilial[$filial->codfilial][pessoas][codpessoa]"]) !!}
                        {!! Form::select2("metafilial[$filial->codfilial][pessoas][$pessoa->codpessoa][codcargo]", $cargos, null, ['class'=> 'form-control', 'id'=>$filial->codfilial.$pessoa->codpessoa]) !!}
                    </div>
                </div>
                @endforeach
            </div>
            @endforeach
        </div>
    </div>
</div>

<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        {!! Form::submit($submitTextButton, array('class' => 'btn btn-primary')) !!}
    </div>
</div>

@section('inscript')
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
    
});
</script>
@endsection
