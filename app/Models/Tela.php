<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tela extends Model
{
    use HasFactory;

    protected $fillable = [
       'nome'
    ];
    public static $rules = [
        'nome' => 'required|string|max:15|unique:telas',
    ];

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'permissoesTelas' => $this->permissoesTelas
        ];
    }

    public function permissoesTelas()
    {
        return $this->belongsToMany(Permission::class, 'permissao_telas', 'tela_id', 'permission_id');
    }


}
