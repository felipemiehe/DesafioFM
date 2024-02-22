<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GerenciarPermissoe extends Model
{

    protected $fillable = ['user_id', 'permission_id'];

    use HasFactory;
}
