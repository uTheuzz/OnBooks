<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pessoa extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'nome',
        'idade',
        'sexo',
        'data_de_criacao',
        'data_de_atualizacao'
    ];

    public function livro(){
        return $this->hasMany(Livro::class, 'id_pessoa','id');
    }
}
