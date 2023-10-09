<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;



use App\Models\CnTbRouter;


class CnTbClient extends Model implements Authenticatable
{
    use HasFactory;

    protected $table = 'cn_tb_clients';
    protected $primaryKey = 'id_client';
    public $timestamps = false;

    protected $fillable = [
        'id_router',
        'id_plan',
        'name_client',
        'lastname_client',
        'ni_client',
        'ip_client',
        'fecha_suscripcion',
    ];

    public function router()
    {
        return $this->belongsTo(CnTbRouter::class, 'id_router');
    }
    
    public function plan()
    {
        return $this->belongsTo(CnTbPlan::class, 'id_plan');
    }

    public function getAuthIdentifierName()
    {
        return 'ni_client'; // Cambia esto si el nombre del campo es diferente
    }

    public function getAuthIdentifier()
    {
        return $this->{$this->getAuthIdentifierName()};
    }

    public function getAuthPassword()
    {
        return $this->password; // Cambia esto si el nombre del campo es diferente
    }

    // Implementa el método necesario para recordar si el usuario está "remembered"
    public function getRememberToken()
    {
        return $this->remember_token;
    }

    // Implementa el método necesario para establecer el "remember token"
    public function setRememberToken($value)
    {
        $this->remember_token = $value;
    }

    // Implementa el método necesario para obtener el nombre de la columna del "remember token"
    public function getRememberTokenName()
    {
        return 'remember_token';
    }
}
