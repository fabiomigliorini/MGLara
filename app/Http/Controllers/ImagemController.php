<?php

namespace MGLara\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use MGLara\Http\Controllers\Controller;
use MGLara\Models\Imagem;
use MGLara\Models\Produto;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class ImagemController extends Controller
{
    public function __construct()
    {
        $this->middleware('parametros', ['only' => ['index']]);
    }    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$request->session()->has('imagem.index')) {
            $request->session()->put('imagem.index.ativo', '1');
        }

        $parametros = $request->session()->get('imagem.index');
        
        $model = Imagem::search($parametros)->orderBy('codimagem', 'DESC')->paginate(30);
        
        return view('imagem.index', compact('model'));
    }

    public function produto(Request $request, $id)
    {
        $model = Produto::find($id);
        return view('imagem.produto', compact('model', 'request'));
    }
    
    public function produtoStore(Request $request, $id)
    {
        $model = Produto::find($id);
        $codimagem = Input::file('imagem');
        $extensao = $codimagem->getClientOriginalExtension();
       
        $imagem = new Imagem();
        $imagem->save();
        
        if($request->get('imagem')) {
            $imagem_inativa = Imagem::find($request->get('imagem'));
            $imagem_inativa->inativo = Carbon::now();
            $imagem_inativa->save();
            $model->ImagemS()->detach($request->get('imagem'));
        }
        
        $imagem_update = Imagem::findOrFail($imagem->codimagem);
        $imagem_update->observacoes = $imagem->codimagem.'.'.$extensao;
        $imagem_update->save();
        
        $diretorio = './public/imagens';
        $arquivo = $imagem->codimagem.'.'.$extensao;       
        
        $codimagem->move($diretorio, $arquivo);    
        $model->ImagemS()->attach($imagem->codimagem);
        Session::flash('flash_update', 'Imagem inserida.');
        return redirect("produto/$id"); 
    }

    public function produtoDelete(Request $request, $id)
    {
        try {
            $model = Produto::find($id);
            $model->ImagemS()->detach($request->get('imagem'));
            
            $imagem = Imagem::find($request->get('imagem'));
            $imagem->inativo = Carbon::now();
            $imagem->save();
            
	    Session::flash('flash_delete', 'Imagem deletada!');
	    return redirect("produto/$id"); 
        }
        catch(\Exception $e){
            return view('errors.fk');
        }         
    }

        /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $model = Imagem::find($id);
        return view('imagem.show', compact('model'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $Model = '\MGLara\Models\\' . $request->get('model');
        $model = $Model::find($request->get('id'));
        
        return view('imagem.edit', compact('model', 'request'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $Model = '\MGLara\Models\\' . $request->get('model');
        $model = $Model::findOrFail($id);

        $codimagem = Input::file('codimagem');
        $extensao = $codimagem->getClientOriginalExtension();
        
        $imagem = new Imagem();
        $imagem->save();
        
        if(!is_null($model->codimagem)) {
            $imagem_inativa = Imagem::find($model->codimagem);
            $imagem_inativa->inativo = Carbon::now();
            $imagem_inativa->save();
        }
        
        $imagem_update = Imagem::findOrFail($imagem->codimagem);
        $imagem_update->observacoes = $imagem->codimagem.'.'.$extensao;
        $imagem_update->save();
        
        $diretorio = './public/imagens';
        $arquivo = $imagem->codimagem.'.'.$extensao;       
        
        try {
            $codimagem->move($diretorio, $arquivo);
            $model->codimagem = $imagem->codimagem;
            $model->save();
            Session::flash('flash_update', 'Registro atualizado.');
            return redirect(modelUrl($request->get('model')).'/'.$id);  
        } catch (\Symfony\Component\HttpFoundation\File\Exception\FileException $e) {
            Session::flash('flash_danger', "Não foi possível cadastrar essa imagem!");
            Session::flash('flash_danger_detail', $e->getMessage());
            return redirect(modelUrl($request->get('model')).'/'.$id);  
        }
    }

    public function lixeira()
    {
        $parametros['ativo'] = 2;
        $model = Imagem::search($parametros)->orderBy('codimagem', 'DESC')->paginate(20);
        return view('imagem.lixeira', compact('model'));        
    }

    public function esvaziarLixeira()
    {
        try{
            $imagens = Imagem::whereNotNull('inativo')->get();
            Imagem::whereNotNull('inativo')->delete();

            foreach ($imagens as $imagem)
            {
                unlink('./public/imagens/'.$imagem->observacoes);
            }
            Session::flash('flash_success', 'Lixeira esvaziada com sucesso!!');
            return redirect('imagem/lixeira');
        }
        catch(\Exception $e){
            $ret = ['resultado' => false, 'mensagem' => 'Erro ao esvaziar lixeira!', 'exception' => $e];
            return redirect('imagem/lixeira');
        }
        //return json_encode($ret);        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $model = Imagem::find($id);
            $model->delete();
            unlink('./public/imagens/'.$model->observacoes);
            $ret = ['resultado' => true, 'mensagem' => 'Imagem excluída com sucesso!'];
        }
        catch(\Exception $e){
            $ret = ['resultado' => false, 'mensagem' => 'Erro ao excluir imagem!', 'exception' => $e];
        }
        return json_encode($ret);
    }
    
    public function inativo(Request $request)
    {
        if(empty($request->get('produto'))) {
            $imagem = Imagem::find($request->get('codimagem'));
            $Model = Imagem::relacionamentos($request->get('codimagem'));
            $model = $Model::where('codimagem', $request->get('codimagem'))->first();            
            
            $model->codimagem = null;
            $imagem->inativo = Carbon::now();
            $msg = "Imagem '{$imagem->codimagem}' Inativada!";

            $model->save();
            $imagem->save();
            
        } else {
            $model = Produto::find($request->get('produto'));
            $model->ImagemS()->detach($request->get('codimagem'));
            
            $imagem = Imagem::find($request->get('codimagem'));
            $imagem->inativo = Carbon::now();
            $msg = "Imagem '{$imagem->codimagem}' Inativada!";

            $imagem->save();            
        }
        
        Session::flash('flash_success', $msg);
    }
    
    public function produtoImagens()
    {
        $root = '/media/publico/Arquivos/Produtos';
        $pastas = scandir($root);
        $pastas = array_diff($pastas,['.', '..']);
        
        foreach ($pastas as $pasta)
        {
            $produto = ltrim($pasta, "0");
            $fotos = \Illuminate\Support\Facades\File::allFiles("$root/$pasta");
            foreach ($fotos as $foto)
            {

                if($foto->getExtension() <> 'db' && $foto->getExtension() <> 'importado') {

                    $model = Produto::find($produto);
                    $extensao = $foto->getExtension();

                    $imagem = new Imagem();
                    $imagem->save();

                    $imagem_update = Imagem::findOrFail($imagem->codimagem);
                    $imagem_update->observacoes = $imagem->codimagem.'.'.$extensao;
                    $imagem_update->save();

                    $diretorio = '../public/imagens';
                    $arquivo = $imagem->codimagem.'.'.$extensao;       

                    try {
                        copy($foto, "/var/www/MGLara/public/imagens/$arquivo");
                        rename($foto, "$foto.importado");
                        $model->ImagemS()->attach($imagem->codimagem);                        
                    } catch (Exception $ex) {
                        dd($ex);
                    }
                }
            }
        }
    }
    
}
