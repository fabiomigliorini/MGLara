<div class="form-group">
    {!! Form::label('ncm', 'NCM', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-md-4">{!! Form::text('ncm', null, ['class' => 'form-control',  'id'=>'ncm', 'required'=>'required', 'placeholder' => 'NCM']) !!}</div>
</div>

<div class="form-group">
    {!! Form::label('descricao', 'Descrição', ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-md-6">{!! Form::textarea('descricao', null, ['class' => 'form-control',  'id'=>'descricao', 'required'=>'required', 'placeholder' => 'Descrição']) !!}</div>
</div>

<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        {!! Form::submit($submitTextButton, array('class' => 'btn btn-primary')) !!}
    </div>
</div>

@section('inscript')
<script type="text/javascript">
$(document).ready(function() {
    $('#form-ncm').on("submit", function(e){
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
