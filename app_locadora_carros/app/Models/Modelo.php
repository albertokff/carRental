<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Modelo extends Model
{
    protected $fillable = ['marca_id', 'nome', 'imagem', 'numero_portas', 'lugares', 'air_bag', 'abs'];

    public function rules() {
        return [
            'marca_id' => 'exists:marcas,id',
            'nome' => 'required|unique:modelos,nome,'.$this->id.'|min:3|max:50',
            'imagem' => 'required|file|mimes:png,jpeg,jpg',
            'numero_portas' => 'required|integer|digits_between:1,5',
            'lugares' => 'required|integer|digits_between:1,20',
            'air_bag' => 'required|boolean',
            'abs' => 'required|boolean'
        ];

        /*
            1) tabela
            2) nome da coluna que será pesquisada na tabela
            3) id do registro que será desconsiderado na pesquisa

        */
    }

    public function marca() {
        // UM modelo pertence a UMA marca
        return $this->belongsTo(Marca::class);
    }
}
