<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    protected $fillable = ['nome', 'imagem'];

    public function rules() {
        return [
            'nome' => 'required|unique:marcas,nome,'.$this->id.'|min:3|max:50',
            'imagem' => 'required|file|mimes:png'
        ];

        /*
            1) tabela
            2) nome da coluna que será pesquisada na tabela
            3) id do registro que será desconsiderado na pesquisa

        */
    }

    public function feedback() {
        return [
            'nome.required' => 'O campo nome é obrigatório',
            'nome.unique' => 'O nome da marca já existe',
            'nome.min' => 'O nome da marca deve ter no mínimo 3 caracteres',
            'nome.max' => 'O nome da marca deve ter no máximo 50 caracteres',
            'imagem.required' => 'O campo imagem é obrigatório',
            'imagem.mimes' => 'O arquivo deve ter extensão png'
        ];
    }
}
