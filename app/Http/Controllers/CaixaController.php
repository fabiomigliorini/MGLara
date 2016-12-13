<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use MGLara\Http\Controllers\Controller;

class CaixaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $inicialpadrao = Carbon::today();
        $finalpadrao = Carbon::today();
        $finalpadrao->hour = 23;
        $finalpadrao->minute = 59;
        $finalpadrao->second = 59;

        $parametros = self::filtroEstatico(
            $request,
            'caixa.index',
            [
                'ativo' => 1,
                'codusuario' => Auth::user()->codusuario,
                'datainicial' => $inicialpadrao,
                'datafinal' => $finalpadrao,
            ],
            [
                'datainicial',
                'datafinal',
            ]);

        if (empty($parametros['codusuario'])) {
            abort(500, 'Usuário não informado!');
        }

        if (empty($parametros['datainicial'])) {
            abort(500, 'Data Inicial não informada!');
        }

        if (empty($parametros['datafinal'])) {
            abort(500, 'Data Final não informada!');
        }

        switch ($parametros['ativo']) {
            case 1:
                $ativo = 'and n.codnegociostatus = 2';
                break;

            case 2:
                $ativo = 'and n.codnegociostatus != 2';
                break;

            default:
                $ativo = '';
                break;
        }

        $sql = "
            select
              o.operacao
            , n.codoperacao
            , no.naturezaoperacao
            , n.codnaturezaoperacao
            , ns.negociostatus
            , n.codnegociostatus
            , sum(coalesce(valoravista, 0)) as avista
            , sum(coalesce(valoraprazo, 0)) as aprazo
            , sum(coalesce(valortotal, 0)) as total
            , count(n.codnegocio) as quantidade
            from tblnegocio n
            left join tblnegociostatus ns on (ns.codnegociostatus = n.codnegociostatus)
            left join tblnaturezaoperacao no on (no.codnaturezaoperacao = n.codnaturezaoperacao)
            left join tbloperacao o on (o.codoperacao = n.codoperacao)
            where n.codusuario  = {$parametros['codusuario']}
            and n.lancamento between '{$parametros['datainicial']->toDateTimeString()}' and '{$parametros['datafinal']->toDateTimeString()}'
            $ativo
            group by
              ns.negociostatus
            , n.codnegociostatus
            , o.operacao
            , n.codoperacao
            , no.naturezaoperacao
            , n.codnaturezaoperacao
            order by
              ns.negociostatus DESC
            , n.codoperacao DESC
            , no.naturezaoperacao DESC
            ";

        $dados['negocios'] = DB::select($sql);

        switch ($parametros['ativo']) {
            case 1:
                $ativo = 'and vc.inativo is null';
                break;

            case 2:
                $ativo = 'and vc.inativo is not null';
                break;

            default:
                $ativo = '';
                break;
        }

        $sql = "
            select
                case when inativo is null then 'Ativo' else 'Inativo' end as status
                , sum(vc.total) as total
                , count(vc.codvalecompra) as quantidade
            from tblvalecompra vc
            where vc.codusuariocriacao = {$parametros['codusuario']}
            and vc.criacao between '{$parametros['datainicial']->toDateTimeString()}' and '{$parametros['datafinal']->toDateTimeString()}'
            $ativo
            group by case when inativo is null then 'Ativo' else 'Inativo' end
            order by case when inativo is null then 'Ativo' else 'Inativo' end ASC
            ";

        $dados['vales'] = DB::select($sql);

        switch ($parametros['ativo']) {
            case 1:
                $ativo = 'and lt.estornado is null';
                break;

            case 2:
                $ativo = 'and lt.estornado is not null';
                break;

            default:
                $ativo = '';
                break;
        }

        $sql = "
            select
                case when estornado is null then 'Ativa' else 'Estornada' end as status
                , sum(coalesce(debito, 0)) as debito
                , sum(coalesce(credito, 0)) as credito
                , count(codliquidacaotitulo) as quantidade
            from tblliquidacaotitulo lt
            where lt.codusuariocriacao = {$parametros['codusuario']}
            and lt.criacao between '{$parametros['datainicial']->toDateTimeString()}' and '{$parametros['datafinal']->toDateTimeString()}'
            $ativo
            group by case when estornado is null then 'Ativa' else 'Estornada' end
            ";

        $dados['liquidacoes'] = DB::select($sql);

        $parametros['datainicial'] = $parametros['datainicial']->format('Y-m-d\TH:i:s');
        $parametros['datafinal'] = $parametros['datafinal']->format('Y-m-d\TH:i:s');

        return view('caixa.index', compact('dados', 'parametros'));
    }

}
