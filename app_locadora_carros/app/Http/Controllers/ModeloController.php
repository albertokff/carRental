<?php

namespace App\Http\Controllers;

use App\Models\Modelo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ModeloController extends Controller
{
    public $modelo;

    public function __construct(Modelo $modelo)
    {
        $this->modelo = $modelo;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $modelos = [];

        if ($request->has('atributos')) {
            $atributos = $request->atributos;
            $modelos = $this->modelo->selectRaw($atributos)->with('marca')->get();
        } else {
            $modelos = $this->modelo->with('marca')->get();
        }

        //returnresponse()->json($this->modelo->all(), 200);
        // a instrução acima impossibilita de modificar a consulta, adicionando os relacionamentos, pois cria um objeto de consulta e em seguida faz um get()

        // o comando abaixo com o get possibilita montar uma query e só depois montar o objeto de consulta
        return response()->json($modelos, 200); 
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
    public function store(Request $request)
    {
        $request->validate($this->modelo->rules());

        $image = $request->file('imagem');
        $image_urn = $image->store('imagens/modelos', 'public');    
        $modelo = $this->modelo->create([
            'marca_id' => $request->marca_id,
            'nome' => $request->nome,
            'imagem' => $image_urn,
            'numero_portas' => $request->numero_portas,
            'lugares' => $request->lugares,
            'air_bag' => $request->air_bag,
            'abs' => $request->abs
        ]);
        return response()->json($modelo, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $modelo = $this->modelo->with('marca')->find($id);

        if ($modelo === null) {
            return response()->json(['erro' => 'Recurso pesquisado não existe'], 404);
        }

        return response()->json($modelo, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Modelo $modelo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $modelo = $this->modelo->find($id);

        if ($modelo === null) {
            return response()->json(['erro' => 'Impossível realizar a atualização. Recurso pesquisado não existe'], 404);
        }

        $teste = '';

        if ($request->method() == 'PATCH') {
            $regrasDinamicas = [];

            //Percorrendo todas as regras definidas no model
            foreach ($modelo->rules() as $input => $regra) {
                
                //Coletar apenas as regras aplicáveis aos parâmetros parciais da requisição PATCH
                if (array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }
            }

            $request->validate($regrasDinamicas);
        } else {
            $request->validate($modelo->rules());
        }

        //Remove o arquivo antigo caso um novo arquivo tenha sido enviado
        if ($request->file('imagem')) {
            Storage::disk('public')->delete($modelo->imagem);
        }

        $image = $request->file('imagem');
        $image_urn = $image->store('imagens/modelos', 'public');    

        $modelo->fill($request->all());
        $modelo->imagem = $image_urn;
        $modelo->save();
        /*
        $modelo->update([
            'marca_id' => $request->marca_id,
            'nome' => $request->nome,
            'imagem' => $image_urn,
            'numero_portas' => $request->numero_portas,
            'lugares' => $request->lugares,
            'air_bag' => $request->air_bag,
            'abs' => $request->abs
        ]);
        */
        
        return response()->json($modelo, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $modelo = $this->modelo->find($id);

        if ($modelo === null) {
            return response()->json(['erro' => 'Impossível realizar a exclusão. Recurso pesquisado não existe'], 404);
        }

        //Remove o arquivo antigo caso um novo arquivo tenha sido enviado
        Storage::disk('public')->delete($modelo->imagem);

        $modelo->delete();
        return ['msg' => 'Modelo deletado com sucesso!'];
    }
}
