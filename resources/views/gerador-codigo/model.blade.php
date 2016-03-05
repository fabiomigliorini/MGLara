<pre>
&lt?php

namespace MGLara\Models;

/**
 * Campos
@foreach ($cols as $col)
<?php

$tipo = trim($col->data_type);

if (strstr($tipo, ' '))
    $tipo = $col->udt_name;

switch ($tipo)
{
    case 'numeric':
            $tipo .= "({$col->numeric_precision},{$col->numeric_scale})";
            break;
    case 'varchar':
            $tipo .= "({$col->character_maximum_length})";
            break;
}

$tipo = str_pad($tipo, 30, ' ');

$coluna = "\${$col->column_name}";
$coluna = trim($coluna);
$coluna = str_pad($coluna, 35, ' ');

$comentario = ($col->is_nullable == 'NO')?'NOT NULL':'';
if (!empty($col->column_default))
    $comentario .= ' DEFAULT ' . $col->column_default;
$comentario = trim($comentario);

?>
 * @property {{$tipo}} {{$coluna}} {{$comentario}}
@endforeach
 *
 * Chaves Estrangeiras
@foreach ($pais as $rel)
<?php
$classe = str_replace('tbl', '', $rel->foreign_table_name);
$classe = str_pad(ucfirst($classe), 30, ' ');

$coluna = $classe;

if ($rel->column_name == 'codusuariocriacao')
    $coluna = 'UsuarioCriacao';

if ($rel->column_name == 'codusuarioalteracao')
    $coluna = 'UsuarioAlteracao';

?>
 * @property {{$classe}} ${{$coluna}}
@endforeach
 *
 * Tabelas Filhas
@foreach ($filhas as $rel)
<?php
$classe = ucfirst(str_replace('tbl', '', $rel->foreign_table_name));
$coluna = $classe . 'S';
$classe .=  '[]';
$classe = str_pad($classe, 30, ' ');

?>
 * @property {{$classe}} ${{$coluna}}
@endforeach
 */

class {{ucfirst(str_replace('tbl', '', $tabela))}} extends MGModel
{
    protected $table = '{{$tabela}}';
    protected $primaryKey = '{{str_replace('tbl', 'cod', $tabela)}}';
    protected $fillable = [
@foreach ($cols as $col)
@if (!in_array($col->column_name, [str_replace('tbl', 'cod', $tabela), 'criacao', 'codusuariocriacao', 'alteracao', 'codusuarioalteracao']) )
        '{{$col->column_name}}',
@endif
@endforeach
    ];
    protected $dates = [
@foreach ($cols as $col)
@if (in_array($col->udt_name, ['date', 'timestamp']) )
        '{{$col->column_name}}',
@endif
@endforeach
    ];


    // Chaves Estrangeiras
@foreach ($pais as $rel)
<?php
$classe = str_replace('tbl', '', $rel->foreign_table_name);
$classe = ucfirst($classe);

$coluna = $classe;

if ($rel->column_name == 'codusuariocriacao')
    $coluna = 'UsuarioCriacao';

if ($rel->column_name == 'codusuarioalteracao')
    $coluna = 'UsuarioAlteracao';

?>
    public function {{$coluna}}()
    {
        return $this->belongsTo({{$classe}}::class, '{{$rel->column_name}}', '{{$rel->foreign_column_name}}');
    }

@endforeach

    // Tabelas Filhas
@foreach ($filhas as $rel)
<?php
$classe = ucfirst(str_replace('tbl', '', $rel->foreign_table_name));
$coluna = $classe . 'S';

?>
    public function {{$coluna}}()
    {
        return $this->hasMany({{$classe}}::class, '{{$rel->column_name}}', '{{$rel->foreign_column_name}}');
    }

@endforeach

}
</pre>