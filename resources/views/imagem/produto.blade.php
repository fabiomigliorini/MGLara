@extends('layouts.default')
@section('content')
<script src="{{ URL::asset('public/vendor/slim-image-cropper/slim.kickstart.min.js') }}"></script>
<link href="{{ URL::asset('public/vendor/slim-image-cropper/slim.min.css') }}" rel="stylesheet">

<ol class="breadcrumb header">
{!! 
    titulo(
        $model->codproduto,
        [
            url("produto") => 'Produtos',
            url("produto/$model->codproduto") => $model->produto,
            'Enviar Imagem',
        ],
        $model->inativo,
        6
    ) 
!!}     
</ol>

<hr>
@include('errors.form_error')
{!! Form::model($model, [
    'method' => 'POST', 
    'class' => 'form-horizontal', 
    'id' => 'form-imagem-produto', 
    'action' => ['ImagemController@produtoStore', $model->codproduto, 'imagem' => $request->get('imagem')],
    'files' => true 
]) !!}

<div class="form-group">
    <label for="usuario" class="col-sm-2 control-label">
        {!! Form::label('Imagem: ') !!}
    </label>    
    <div class="col-md-10">
        <div 
            class="slim" 
            id="my-cropper"
            data-instant-edit="true" 
            data-label="Arraste a Imagem..." 
            data-ratio="1:1" 
            data-size="1536,1536" 
            data-status-upload-success="Imagem Salva..." 
            data-force-type="jpg" 
            data-did-upload="imageUpload"
            data-button-edit-label="Editar"
            data-button-remove-label="Descartar"
            data-button-download-label="Baixar"
            data-button-upload-label="Salvar"
            data-button-cancel-label="Cancelar"
            data-button-confirm-label="Confirmar"
            data-button-edit-title="Editar"
            data-button-remove-title="Descartar"
            data-button-download-title="Baixar"
            data-button-upload-title="Salvar"
            data-button-rotate-title="Girar"
            data-button-cancel-title="Cancelar"
            data-button-confirm-title="Confirmar"
            style='max-width: 500px'
            > 
            <input type="file" />
        </div>
        
    </div>
</div>

<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        {!! Form::submit('Enviar', array('class' => 'btn btn-primary')) !!}
    </div>
</div>    
{!! Form::close() !!}   
@section('inscript')
<script type="text/javascript">
$(document).ready(function() {
    $('#form-imagem-produto').on("submit", function(e){
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

@stop