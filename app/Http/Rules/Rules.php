<?php

namespace App\Http\Rules;

class Rules
{
    public static $AtulizarUsuario = [
        'username' => 'string|max:255|unique:usuarios',
        'password' => 'string|min:8',
        'nome' => 'string|max:15',
        'email' => 'string|email',
    ];
    public static $Login = [
        'username' => 'required|string|max:255',
        'password' => 'required|string|min:8',
    ];
    public static $AtualizarPermissao = [
        'roles' => 'required|string|max:255|unique:permissions'
    ];

    public static $AdicionaPermisaoxUser = [
        'user_id' => 'required|exists:usuarios,id',
        'permission_id' => 'required|exists:permissions,id',
    ];
    public static $AdicionaPermisaoxTelas = [
        'tela_id' => 'required|exists:telas,id',
        'permission_id' => 'required|exists:permissions,id',
    ];
}
