<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CnTbRouter extends Model
{
    use HasFactory;

    protected $table = 'cb_tb_routers';
    protected $primaryKey = 'id_router';
    public $timestamps = false;

    protected $fillable = [
        'name_router',
        'ip_router',
        'description_router',
        'interface_clients',
        'user_router',
        'pass_router',

    ];

    public function clients()
    {
        return $this->hasMany(CnTbClient::class, 'id_router');
    }
}
