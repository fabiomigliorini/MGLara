
<div class='text-center'>
    <h3>
        Estoque {{ ($fiscal)?'Fiscal':'Fisico' }}
    </h3>
</div>
<br>

<div class="form-group">
    <label for="data" class="col-md-4 control-label">
        Data do Ajuste
    </label>
    <div class="col-md-4">
        {!! Form::datetimeLocal('data', $data, ['class'=> 'form-control input-sm text-center', 'id'=>'data', 'required'=>'required', 'placeholder'=>'Data Ajuste']) !!}
    </div>
</div>

<div class="form-group">
    <div class='col-md-4 control-label'>
        <label for="quantidadeinformada">
            Quantidade
        </label>
        /
        <label for="customedioinformado">
            Custo
        </label>
    </div>
    <div class="col-md-8 form-inline">
        <div class="input-group" style='width:160px'>
            {!! Form::number('quantidadeinformada', $quantidadeinformada, ['class'=> 'form-control input-sm text-right', 'step' => 0.001, 'style' => 'width: 100%', 'id'=>'quantidadeinformada', 'required'=>'required', 'autofocus'=>true, 'placeholder'=>'Quantidade']) !!}
            <span class="input-group-addon" id="basic-addon2">
                {!! $pv->Produto->UnidadeMedida->sigla !!}
            </span>
        </div>
        {!! Form::number('customedioinformado', $customedio, ['class'=> 'form-control input-sm text-right', 'step' => 0.000001, 'style' => 'width: 160px', 'id'=>'customedioinformado', 'required'=>'required', 'placeholder'=>'Custo']) !!}
    </div>
</div>

<div class="form-group">
    <div class='col-md-4 control-label'>
        <label for="estoqueminimo">
            Mínimo <span class='glyphicon glyphicon-arrow-down'></span>
        </label>
        /
        <label for="estoquemaximo">
            Máximo <span class='glyphicon glyphicon-arrow-up'></span>
        </label>
    </div>
    <div class="col-md-8 form-inline">
        <div class='input-group' style='width:160px'>
            {!! Form::number('estoqueminimo', $estoqueminimo, ['class'=> 'form-control input-sm text-right', 'style' => 'width: 100%', 'step' => 1, 'min' => 0, 'id'=>'estoqueminimo', 'placeholder'=>'Mínimo']) !!}
            <div class='input-group-addon'>
                <span class='glyphicon glyphicon-arrow-down'></span>
            </div>
        </div>
        <div class='input-group' style='width:160px'>
            {!! Form::number('estoquemaximo', $estoquemaximo, ['class'=> 'form-control input-sm text-right', 'style' => 'width: 100%', 'step' => 1, 'min' => 0, 'id'=>'estoquemaximo', 'placeholder'=>'Máximo']) !!}
            <div class='input-group-addon'>
                <span class='glyphicon glyphicon-arrow-up'></span>
            </div>
        </div>
    </div>
</div>


<div class="form-group">
    <label for="corredor" class="col-md-4 control-label">
        Localização
    </label>
    <div class="col-md-8 form-inline">
        {!! Form::number('corredor', $corredor, ['class'=> 'form-control text-center', 'id'=>'corredor', 'style'=>'width:78px', 'placeholder'=>'Corredor', 'step' => 1, 'min' => 0]) !!}
        {!! Form::number('prateleira', $prateleira, ['class'=> 'form-control text-center', 'id'=>'prateleira', 'style'=>'width:78px', 'placeholder'=>'Prateleira', 'step' => 1, 'min' => 0]) !!}
        {!! Form::number('coluna', $coluna, ['class'=> 'form-control text-center', 'id'=>'coluna', 'style'=>'width:78px', 'placeholder'=>'Coluna', 'step' => 1, 'min' => 0]) !!}
        {!! Form::number('bloco', $bloco, ['class'=> 'form-control text-center', 'id'=>'bloco', 'style'=>'width:78px', 'placeholder'=>'Bloco', 'step' => 1, 'min' => 0]) !!}
    </div>
</div>

<div class="form-group">
    <div class="col-md-offset-4 col-md-9">
        <button type="submit" class="btn btn-primary">
            Salvar
        </button>
        <a href='{{ url('estoque-saldo-conferencia/create') }}' class='btn btn-danger'>
            Cancelar
        </a>
    </div>
</div>
