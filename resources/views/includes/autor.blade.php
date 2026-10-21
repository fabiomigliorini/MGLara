<small class="text-muted">
    <?php if (isset($model->criacao) || isset($model->codusuariocriacao)): ?>
            Criado
            <?php echo isset($model->criacao) ? 'em ' . formataData($model->criacao, 'L') : '' ;?>
            <?php echo isset($model->codusuariocriacao) ? ' por ' . $model->UsuarioCriacao['usuario'] : '' ;?> 
    <?php endif;?>
    <?php if (($model->criacao <> $model->alteracao) or ($model->codusuariocriacao <> $model->codusuarioalteracao)): ?>
            Alterado
            <?php echo (isset($model->alteracao) && ($model->criacao <> $model->alteracao)) ? 'em ' . formataData($model->alteracao, 'L') : '' ;?>
            <?php echo (isset($model->codusuarioalteracao)) ? ' por ' . $model->UsuarioAlteracao['usuario'] : '' ;?> 
    <?php endif;?>
</small>
