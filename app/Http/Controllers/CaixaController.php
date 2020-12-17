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
                , sum(prazo.aprazo) as aprazo
                , sum(vc.total) - sum(coalesce(prazo.aprazo, 0)) as avista
                , count(vc.codvalecompra) as quantidade
            from tblvalecompra vc
            left join (
                select vcfp.codvalecompra, sum(vcfp.valorpagamento) as aprazo
                from tblvalecompraformapagamento vcfp
                inner join tblformapagamento fp on (fp.codformapagamento = vcfp.codformapagamento and fp.avista = false)
                group by vcfp.codvalecompra
            ) prazo on (prazo.codvalecompra = vc.codvalecompra)
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

        $sql = "
            select
                t.terminal
                , count(pp.codliopedidopagamento) as quantidade
                , sum(case when pp.codigov40 = 28 then pp.valor else null end) as entrada
                , sum(case when pp.codigov40 != 28 then pp.valor else null end) as saida
            from tblliopedidopagamento pp
            left join tbllioterminal t on (t.codlioterminal = pp.codlioterminal)
            where pp.criacao between '{$parametros['datainicial']->toDateTimeString()}' and '{$parametros['datafinal']->toDateTimeString()}'
            and pp.codusuariocriacao = {$parametros['codusuario']}
            group by t.terminal
            order by 1, 2, 3
            ";

        $dados['lio'] = DB::select($sql);

        $sql = "
            select
            	pp.codliopedidopagamento,
            	t.terminal,
            	bc.bandeiracartao ,
            	pr.lioproduto,
            	pp.parcelas,
            	pp.valor,
            	pp.cartao,
            	pp.nome,
            	pp.codigov40,
            	p.uuid,
            	p.valorpago,
            	ps.liopedidostatus,
            	n.codnegocio,
              n.codnegociostatus,
            	ns.negociostatus,
            	n.lancamento,
            	n.valortotal,
            	pp.autorizacao,
            	pp.nsu
            from tblliopedidopagamento pp
            inner join tblliopedido p on (p.codliopedido = pp.codliopedido)
            left join tbllioterminal t on (t.codlioterminal = pp.codlioterminal)
            left join tblliopedidostatus ps on (ps.codliopedidostatus = p.codliopedidostatus)
            left join tbllioproduto pr on (pr.codlioproduto = pp.codlioproduto)
            left join tblliobandeiracartao bc on (bc.codliobandeiracartao = pp.codliobandeiracartao)
            left join tblnegocioformapagamento nfp on (nfp.codliopedido = p.codliopedido)
            left join tblnegocio n on (n.codnegocio = nfp.codnegocio)
            left join tblnegociostatus ns on (ns.codnegociostatus = n.codnegociostatus)
            where pp.criacao between '{$parametros['datainicial']->toDateTimeString()}' and '{$parametros['datafinal']->toDateTimeString()}'
            and pp.codusuariocriacao = {$parametros['codusuario']}
            order by pp.criacao desc
            ";

        $dados['liolistagem'] = DB::select($sql);

        $parametros['datainicial'] = $parametros['datainicial']->format('Y-m-d\TH:i:s');
        $parametros['datafinal'] = $parametros['datafinal']->format('Y-m-d\TH:i:s');

        return view('caixa.index', compact('dados', 'parametros'));
    }

}
