<div class="panel panel-default" id="div-fiscal">
    <table class="table table-striped "> 
        <tbody> 
            <tr> 
                <th class="col-md-2" scope="row">NCM</th> 
                <td class="col-md-10">
                    @if($model->Ncm)
                        <a href="ncm/{{ $model->Ncm->codncm }}">
                            {{formataNcm($model->Ncm->ncm)}}
                        </a>
                        {{  $model->Ncm->descricao or '' }}
                    @endif
                </td> 
            </tr> 
            <tr> 
                <th scope="row">CEST</th> 
                <td>
                    @if($model->Cest)
                    <strong>{{ formataNcm($model->Cest->ncm) }}/{{ formataCest($model->Cest->cest) }}</strong>
                    - {{ $model->Cest->descricao }}
                    @endif
                </td> 
            </tr> 
            @foreach($model->Ncm->IbptaxS as $ibpt)
            <tr> 
                <th scope="row">IBPT</th> 
                <td>
                    <strong>{{$ibpt->descricao}}</strong><br>
                    Federal Nacional: {{ formataNumero($ibpt->nacionalfederal) }}%<br>
                    Federal Importado: {{ formataNumero($ibpt->nacionalfederal) }}%<br>
                    Estadual: {{ formataNumero($ibpt->estadual) }}%<br>
                    Municipal: {{ formataNumero($ibpt->municipal) }}%<br>
                </td> 
            </tr> 
            @endforeach
            <tr> 
                <th scope="row">Regulamento ICMS ST/MT</th> 
                <td>
                @foreach($model->Ncm->regulamentoIcmsStMtsDisponiveis() as $reg)
                    <strong>{{formataNcm($reg['ncm'])}}/{{$reg['subitem']}}</strong> - {{$reg['descricao']}}
                    <br>
                @endforeach 
                </td> 
            </tr> 
        </tbody> 
    </table>
</div>