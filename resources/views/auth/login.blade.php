@extends('layouts.login')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading auth"><h3>Autenticação do sistema</h3></div>
                <div class="panel-body">
                    @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <strong>Atenção!</strong> Alguns erros ocorreram.<br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                        
                    {!! Form::open(['action'=>'Auth\AuthController@postLogin', 'method'=>'POST', 'class' => 'form-horizontal']) !!}                        

                        <div class="form-group">
                            <label class="col-md-3 control-label">Usuário</label>
                            <div class="col-md-6">
                                {!! Form::text('usuario', null, ['class' => 'form-control', 'required'=>'required']) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Senha</label>
                            <div class="col-md-6">
                                {!! Form::password('password', ['class' => 'form-control', 'required'=>'required']) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember"> Mantenha-me autenticado
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">Entrar</button>
                            </div>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@section('inscript')
<style type="text/css">
.auth h3 {
    margin: 5px;
    text-align: center;
    text-transform: uppercase;
}    
</style>
@endsection
@stop
