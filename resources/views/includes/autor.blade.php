<small>
    <?php if (isset($model->criacao) || isset($model->codusuariocriacao)): ?>
            Criado
            <?php echo isset($model->criacao) ? 'em ' . formataData($model->criacao, 'L') : '' ;?>
            <?php echo isset($model->codusuariocriacao) ? ' por <a href='.url('usuario/'.$model->codusuariocriacao).'>'.$model->UsuarioCriacao['usuario']. '</a>' : '' ;?> 
    <?php endif;?>
    <?php if (($model->criacao <> $model->alteracao) or ($model->codusuariocriacao <> $model->codusuarioalteracao)): ?>
            Alterado
            <?php echo (isset($model->alteracao) && ($model->criacao <> $model->alteracao)) ? 'em ' . formataData($model->alteracao, 'L') : '' ;?>
            <?php echo (isset($model->codusuarioalteracao)) ? ' por <a href='.url('usuario/'.$model->codusuarioalteracao).'>'.$model->UsuarioAlteracao['usuario']. '</a>' : '' ;?> 
    <?php endif;?>
</small>