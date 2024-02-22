<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/**
 * @property string roles
 * @property int id
 */
class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'roles'
    ];
    public static $rules = [
        'roles' => 'required|string|max:255|unique:permissions'
    ];


    /**
     * Transform the resource into an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'roles' => $this->roles,
            'id' => $this->id
        ];
    }



}
