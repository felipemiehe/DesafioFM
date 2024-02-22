<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string username
 * @property string password
 * @property string nome
 * @property string email
 */
class Usuario extends Model
{
    use HasFactory;

    protected $fillable = [
        'username', 'password','nome','email'
    ];
    public static $rules = [
        'username' => 'required|string|max:255|unique:usuarios',
        'password' => 'required|string|min:8',
        'nome' => 'required|string|max:15',
        'email' => 'required|string|email',
    ];

    protected $hidden = [
    ];

    protected $casts = [
        'password' => 'hashed'
    ];


    /**
     * Transform the resource into an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'nome' => $this->nome,
            'email' => $this->email,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'permissoes' => $this->permissoes
        ];
    }

    public function permissoes()
    {
        return $this->belongsToMany(Permission::class, 'gerenciar_permissoes', 'user_id', 'permission_id');
    }

}
