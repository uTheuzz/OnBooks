<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LivroResource extends JsonResource
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
            'id' => $this->id,
            'nome' => $this->nome,
            'categoria' => $this->categoria,
            'codigo' => $this->codigo,
            'autor' => $this->autor,
            'ebook' => $this->ebook,
            'tamanho_do_arquivo' => $this->tamanho_do_arquivo,
            'peso' => $this->peso,
            'pessoa' => $this->pessoa,
            'data_de_criacao' => $this->created_at,
            'data_de_atualizacao' => $this->updated_at
        ];
    }
}