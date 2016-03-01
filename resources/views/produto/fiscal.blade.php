<table class="table table-striped "> 
    <tbody> 
        <tr> 
            <th class="col-md-2" scope="row">NCM</th> 
            <td class="col-md-10">
                @if($model->Ncm)
                {{ $model->Ncm->ncm }} {{ $model->Ncm->descricao or '' }}
                @endif
            </td> 
        </tr> 
        <tr> 
            <th scope="row">CEST</th> 
            <td>
                @if($model->Cest)
                <strong>{{ $model->Cest->ncm }} / {{ $model->Cest->cest }}</strong>
                - {{ $model->Cest->descricao }}
                @endif
            </td> 
        </tr> 
        @foreach($model->Ncm->IbptaxsS as $ibpt)
        <tr> 
            <th scope="row">IBPT</th> 
            <td>
                <strong>{{$ibpt->descricao}}</strong><br>
                Federal Nacional: {{$ibpt->nacionalfederal}}%<br>
                Federal Importado: {{$ibpt->nacionalfederal}}%<br>
                Estadual: {{$ibpt->estadual}}%<br>
                Municipal: {{$ibpt->municipal}}%<br>
            </td> 
        </tr> 
        @endforeach
        <tr> 
            <th scope="row">Regulamento ICMS ST/MT</th> 
            <td>
                ...
            </td> 
        </tr> 
    </tbody> 
</table>