<div class='row'>
    <div class='col-md-12'>
        <div class="form-group">
            {!! Form::label('numero', 'Número Motivo:', ['class'=>'col-sm-3 control-label']) !!}
            <div class="col-sm-1">{!! Form::number('numero', null, ['class'=> 'form-control text-right', 'id'=>'numero', 'required'=>'required', 'step'=>1, 'min'=>1, 'max'=>199]) !!}</div>
        </div>
        <div class="form-group">
            {!! Form::label('chequemotivodevolucao', 'Descrição:', ['class'=>'col-sm-3 control-label']) !!}
            <div class="col-sm-4">{!! Form::text('chequemotivodevolucao', null, ['class'=> 'form-control', 'id'=>'chequemotivodevolucao', 'required'=>'required']) !!}</div>
        </div>

        <div class="form-group">
            <div class="col-sm-offset-3 col-sm-9">
                {!! Form::submit($submitTextButton, array('class' => 'btn btn-primary')) !!}
            </div>
        </div>
    </div>
</div>
@section('inscript')
<script type="text/javascript">
$(document).ready(function() {

    $(':input:enabled:visible:first').focus();
    $('#chequemotivodevolucao').Setcase();

    $('#form-cheque-motivo-devolucao').on("submit", function(e) {
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
