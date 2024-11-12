<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use App\Http\Requests\StoreMarcaRequest;
use App\Http\Requests\UpdateMarcaRequest;

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
        return $this->marca->all();
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

        $marca = $this->marca->create($request->all());
        return response()->json($marca, 201);
    }

    /**
     * Display the specified resource.
     */ 
    public function show($id)
    {
        $marca = $this->marca->find($id);

        if ($marca === null) {
            return response()->json(['erro' => 'Recurso pesquisado não existe'], 404);
        }

        return $this->marca->find($id);
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

        $request->validate($marca->rules(), $marca->feedback());

        $marca->update($request->all());
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

        $marca->delete();
        return ['msg' => 'Marca deletada com sucesso!'];
    }
}
