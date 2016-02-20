@if (Session::has('flash_delete'))
    <div class="flash alert alert-info">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        {{ Session::get('flash_delete') }}
    </div>
@endif

@if (Session::has('flash_edit'))
    <div class="flash alert alert-info">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        {{ Session::get('flash_update') }}
    </div>
@endif

@if (Session::has('flash_create'))
    <div class="flash alert alert-info">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        {{ Session::get('flash_create') }}
    </div>
@endif

<script type="text/javascript">
    window.setTimeout(function() { $(".flash").alert('close'); }, 3000);
</script>