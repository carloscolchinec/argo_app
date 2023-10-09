<?php


namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CnTbConsumo extends Model
{
    protected $table = 'cn_daily_consumption'; // Nombre de la tabla en la base de datos

    protected $fillable = [
        'ni_client',
        'fecha',
        'consumo_upload',
        'consumo_download',
    ];

    public function cliente()
    {
        return $this->belongsTo(CnTbClient::class, 'ni_client', 'ni_client');
    }
}
