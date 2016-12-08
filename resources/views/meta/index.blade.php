@extends('layouts.default')
@section('content')
<ol class="breadcrumb header">
    {!! titulo(null, 'Metas', null) !!}
    <li class='active'>
        <small>
            <a title="Nova" href="{{ url("meta/create") }}"><i class="glyphicon glyphicon-plus"></i></a>
        </small>
    </li>   
</ol>
@if(!$model)
<h2>Nenhuma meta cadastrada</h2>
@endif
@section('inscript')
<style type="text/css">
    .tab-pane {
        padding: 10px 0 0;
    }
</style>
<script type="text/javascript">
$(document).ready(function() {
    //...
});
</script>
@endsection
@stop