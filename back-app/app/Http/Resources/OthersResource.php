<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OthersResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id_livro' => $this->id_livro,
            'nome_livro' => $this->nome_livro,
            'categoria' => $this->categoria,
            'codigo' => $this->codigo,
            'autor' => $this->autor,
            'ebook' => $this->ebook,
            'tamanho_do_arquivo' => $this->tamanho_do_arquivo,
            'peso' => $this->peso,
            'criaca_livro' => $this->criacao_livro,
            'atualizacao_livro' => $this->atualizacao_livro,
            'id_pessoa' => $this->id_pessoa,
            'nome_pessoa' => $this->nome_pessoa,
            'idade' => $this->idade,
            'sexo' => $this->sexo,
            'criacao_pessoa' => $this->criacao_pessoa,
            'atualizacao_pessoa' => $this->atualizacao_pessoa
        ];
    }
}