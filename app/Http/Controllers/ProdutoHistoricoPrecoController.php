<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;
use MGLara\Http\Controllers\Controller;
use Carbon\Carbon;

use MGLara\Models\ProdutoHistoricoPreco;

class ProdutoHistoricoPrecoController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        
        $parametros = self::filtroEstatico(
            $request, 
            'produto-historico-preco.index', 
            ['alteracao_de' => Carbon::now()->subDays(5)], 
            ['alteracao_de', 'alteracao_ate']
        );
        
        $model = ProdutoHistoricoPreco::search($parametros)->orderBy('criacao', 'DESC')->paginate(20);
        return view('produto-historico-preco.index', compact('model'));
    }    

    public function validarRelatorio(Request $request)
    {
        $parametros = self::datasParaCarbon($request->all(), ['alteracao_de', 'alteracao_ate']);

        if (!$this->filtroInformado($parametros)) {
            return response()->json([
                'valido' => false,
                'mensagem' => 'Faça um filtro antes de imprimir.',
            ]);
        }

        $quantidade = ProdutoHistoricoPreco::search($parametros)->count();
        if ($quantidade > 500) {
            return response()->json([
                'valido' => false,
                'mensagem' => "A impressão permite no máximo 500 registros. Foram encontrados {$quantidade}.",
            ]);
        }

        return response()->json(['valido' => true]);
    }

    private function filtroInformado(array $parametros)
    {
        foreach ($parametros as $valor) {
            if ($valor instanceof Carbon || (is_scalar($valor) && trim((string) $valor) !== '')) {
                return true;
            }
        }

        return false;
    }

    public function relatorioFiltro(Request $request)
    {
        
        $filtro = $request->all();
        
        return view('produto-historico-preco.relatorio-filtro', compact('filtro'));
    }
    public function relatorio(Request $request)
    {
        $parametros = self::datasParaCarbon($request->all(), ['alteracao_de', 'alteracao_ate']);

        if (!$this->filtroInformado($parametros)) {
            abort(422, 'Faça um filtro antes de imprimir.');
        }

        $quantidade = ProdutoHistoricoPreco::search($parametros)->count();
        if ($quantidade > 500) {
            abort(422, "A impressão permite no máximo 500 registros. Foram encontrados {$quantidade}.");
        }

        $dados = ProdutoHistoricoPreco::search($parametros)->orderBy('criacao', 'DESC')->get();
        
        return view('produto-historico-preco.relatorio', compact('dados'));
    }}
