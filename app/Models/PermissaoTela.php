<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermissaoTela extends Model
{
    use HasFactory;

    protected $table = 'permissao_telas';

    protected $fillable = [
        'tela_id' , 'permission_id'
    ];
    public function tela()
    {
        return $this->belongsTo(Tela::class, 'tela_id');
    }

    public function permission()
    {
        return $this->belongsTo(Permission::class, 'permission_id');
    }
}
