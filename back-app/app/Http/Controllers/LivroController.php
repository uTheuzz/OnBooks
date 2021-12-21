<?php

namespace App\Http\Controllers;

use App\Models\Livro;
use Illuminate\Http\Request;
use App\Http\Resources\LivroResource;
use App\Http\Resources\OthersResource;
use Throwable;
use Illuminate\Support\Facades\DB;

class LivroController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $livros = Livro::with('pessoa');

        return response()->json(LivroResource::collection($livros->orderby('id','ASC')->get()),200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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

        try{
            
            $livro = Livro::All()->last();

            if(is_null($livro)){
                if($request->ebook === true){
                    $codigo = 'LVEBK-1';
                }else{
                    $codigo = 'LVNEBK-1';
                }
            }else{
                if($request->ebook === true){
                    $codigo = 'LVEBK-'.$livro->id+1;
                }else{
                    $codigo = 'LVNEBK-'.$livro->id+1;
                }
            }

            $request->validate([
                'nome' => ['required','string','max:255'],
                'categoria' => ['required','string','max:255'],
                'autor' => ['required','string','max:255'],
                'ebook' => ['required','boolean'],
                'id_pessoa' => ['required','numeric'],
                'peso' => ['nullable','numeric'],
                'tamanho' => ['nullable','numeric']
            ]);

            Livro::create([
                'nome' => $request->nome,
                'categoria' => $request->categoria,
                'codigo' => $codigo,
                'autor' => $request->autor,
                'ebook' => $request->ebook,
                'tamanho_do_arquivo' => $request->tamanho,
                'peso' => $request->peso,
                'id_pessoa' => $request->id_pessoa
            ]);

            return [
                'status' => 'success',
                'resume' => 'Livro criado com sucesso!',
                'code' => 200
            ];

        }catch(Throwable $e){
            return [
                'status' => 'error',
                'resume' => 'Erro ao cadastrar um novo livro...',
                'error_resume' => $e,
                'code' => 400
            ];
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Livro  $livro
     * @return \Illuminate\Http\Response
     */
    public function show(Livro $livro)
    {
        return response()->json(LivroResource::make($livro),200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Livro  $livro
     * @return \Illuminate\Http\Response
     */
    public function edit(Livro $livro)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Livro  $livro
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Livro $livro)
    {
        try{

            if($request->ebook === true){
                $codigo = 'LVEBK-'.$livro->id;
            }else{
                $codigo = 'LVNEBK-'.$livro->id;
            }
            
            $request->validate([
                'nome' => 'required|max:255',
                'categoria' => 'required|max:255',
                'autor' => 'required|max:255',
                'ebook' => 'required|boolean',
                'id_pessoa' => 'required'
            ]);

            Livro::where('id', $livro->id)->update([
                'nome' => $request->nome,
                'categoria' => $request->categoria,
                'codigo' => $codigo,
                'autor' => $request->autor,
                'ebook' => $request->ebook,
                'tamanho_do_arquivo' => $request->tamanho,
                'peso' => $request->peso,
                'id_pessoa' => $request->id_pessoa
            ]);

            return [
                'status' => 'success',
                'resume' => 'Livro editado com sucesso!',
                'code' => 200
            ];

        }catch(Throwable $e){

            return [
                'status' => 'error',
                'resume' => 'Erro ao editar o livro...',
                'error_resume' => $e,
                'code' => 400
            ];
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Livro  $livro
     * @return \Illuminate\Http\Response
     */
    public function destroy(Livro $livro)
    {
        try{
            $livro->delete();

            return [
                'status' => 'success',
                'resume' => 'Livro deletado com sucesso!',
                'code' => 200
            ];

        }catch(Throwable $e){
            return [
                'status' => 'error',
                'resume' => 'Erro ao deletar o livro...',
                'error_resume' => $e,
                'code' => 400
            ];
        }
    }

    public function searchBy(Request $request)
    {
        try{
            $list =  DB::select(DB::raw("SELECT 
                                    a.id as id_livro, a.nome as nome_livro, a.categoria, a.codigo, a.autor, a.ebook, a.tamanho_do_arquivo, a.peso, a.id_pessoa, a.created_at as criacao_livro, a.updated_at as atualizacao_livro, 
                                    b.nome as nome_pessoa, b.idade, b.sexo, b.created_at as criacao_pessoa, b.updated_at as atualizacao_pessoa
                                    FROM livros as A
                                    FULL OUTER JOIN pessoas as B
                                    ON a.id_pessoa = b.id
                                    WHERE UPPER(a.nome) LIKE UPPER('%$request->text%')
                                    ORDER BY a.id"));

            if(count($list) >= 1){
                return [
                    'status' => 'success',
                    'resume' => 'Busca efetuada com sucesso!',
                    'code' => 200,
                    'dados' => response(OthersResource::collection($list)) 
                ];
            }else{
                return [
                    'status' => 'warning',
                    'resume' => 'Nada localizado...',
                    'code' => 200
                ];
            }
        }catch(Throwable $e){
            return [
                'status' => 'error',
                'error_resume' => $e,
                'code' => 400
            ];
        }                        
    }
}
