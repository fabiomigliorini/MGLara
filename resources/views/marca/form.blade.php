<div class="form-group">
    {!! Form::label('marca', 'Marca:', ['class'=>'col-sm-2 control-label']) !!}
    <div class="col-md-3 col-xs-4">
        {!! Form::text('marca', null, ['class'=> 'form-control', 'id'=>'marca', 'required'=>'required']) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('site', 'Disponível no Site:', ['class'=>'col-sm-2 control-label']) !!}
    <div class="col-sm-9" id="wrapper-site">{!! Form::checkbox('site', true, null, ['id'=>'site', 'data-off-text' => 'Não', 'data-on-text' => 'Sim']) !!}</div>
</div>

<div class="form-group">
    {!! Form::label('descricaosite', 'Descrição Site:', ['class'=>'col-sm-2 control-label']) !!}
    <div class="col-md-4 col-xs-4">
        {!! Form::textarea('descricaosite', null, ['class'=> 'form-control', 'id'=>'descricaosite']) !!}
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
    $('#form-marca').on("submit", function(e){
        var currentForm = this;
        e.preventDefault();
        bootbox.confirm("Tem certeza que deseja salvar?", function(result) {
            if (result) {
                currentForm.submit();
            }
        });
    });
    $('#site').bootstrapSwitch();    
    $('#marca').Setcase();     
});
</script>
@endsection
