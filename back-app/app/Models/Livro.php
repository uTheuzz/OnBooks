<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Livro extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'nome',
        'categoria',
        'codigo',
        'autor',
        'ebook',
        'tamanho_do_arquivo',
        'peso',
        'id_pessoa',
        'data_de_criacao',
        'data_de_atualizacao'
    ];

    public function pessoa(){
        return $this->hasOne(Pessoa::class, 'id','id_pessoa');
    }
}