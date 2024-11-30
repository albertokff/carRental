<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use App\Http\Requests\StoreMarcaRequest;
use App\Http\Requests\UpdateMarcaRequest;
use Illuminate\Support\Facades\Storage;

class MarcaController extends Controller
{
    protected $marca;
    public function __construct(Marca $marca)
    {
        $this->marca = $marca;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json($this->marca->with('modelos')->get(), 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMarcaRequest $request)
    {
        $request->validate($this->marca->rules(), $this->marca->feedback());

        $image = $request->file('imagem');
        $image_urn = $image->store('imagens', 'public');    
        $marca = $this->marca->create([
            'nome' => $request->nome,
            'imagem' => $image_urn
        ]);
        return response()->json($marca, 201);
    }

    /**
     * Display the specified resource.
     */ 
    public function show($id)
    {
        $marca = $this->marca->with('modelos')->find($id);

        if ($marca === null) {
            return response()->json(['erro' => 'Recurso pesquisado não existe'], 404);
        }

        return $marca;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Marca $marca)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMarcaRequest $request, $id)
    {
        // print_r($request->all());
        // echo '<br>';
        // print_r($marca->getAttributes());

        // $marca->update($request->all());
        // return $marca;

        $marca = $this->marca->find($id);

        if ($marca === null) {
            return response()->json(['erro' => 'Impossível realizar a atualização. Recurso pesquisado não existe'], 404);
        }

        $dados = '';

        if ($request->method() == 'PATCH') {

            //Percorrendo todas as regras definidas no model
            foreach ($marca->rules() as $input => $regra) {
                
                //Coletar apenas as regras aplicáveis aos parâmetros parciais da requisição PATCH
                if (array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }
            }

            $request->validate($regrasDinamicas, $marca->feedback());
        } else {
            $request->validate($marca->rules(), $marca->feedback());
        }

        //Remove o arquivo antigo caso um novo arquivo tenha sido enviado
        if ($request->file('imagem')) {
            Storage::disk('public')->delete($marca->imagem);
        }

        $image = $request->file('imagem');
        $image_urn = $image->store('imagens', 'public');   

        //preencher o objeto $marca com os dados do request
        //então, por exemplo o nome é obrigatório, mas se o request não foi passado o nome, só a imagem, aí fazendo esse fill a gente faz um merge do request com o que tá no banco
        $marca->fill($request->all());
        $marca->imagem = $image_urn;

        //$marca->getAttributes() esse comando vai recuperar os atributos do objeto

        $marca->save();
        /*
        $marca->update([
            'nome' => $request->nome,
            'imagem' => $image_urn
        ]);
        */
        
        return response()->json($marca, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // $marca->delete();
        // return ['msg' => 'Marca deletada com sucesso!'];
        $marca = $this->marca->find($id);

        if ($marca === null) {
            return response()->json(['erro' => 'Impossível realizar a exclusão. Recurso pesquisado não existe'], 404);
        }

        //Remove o arquivo antigo caso um novo arquivo tenha sido enviado
        Storage::disk('public')->delete($marca->imagem);

        $marca->delete();
        return ['msg' => 'Marca deletada com sucesso!'];
    }
}
