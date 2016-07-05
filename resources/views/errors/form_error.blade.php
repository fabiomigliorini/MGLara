@if($errors->any())
<ul id="form-erros" class="alert alert-danger">
    @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
    @endforeach
</ul>
@endif
<style type="text/css">
/* Erro Form */
#form-erros {
    list-style: outside none square;
    padding: 15px 15px 15px 30px;
}
#form-erros > li {
    margin-bottom: 5px;
}	
</style>