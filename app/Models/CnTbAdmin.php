<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class CnTbAdmin extends Authenticatable
{
    use HasFactory;

    protected $table = 'cn_tb_admins';
    protected $primaryKey = 'id_admin';
    public $timestamps = false;

    protected $fillable = [
        'name_admin', 'lastname_admin', 'email', 'password'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];
}
