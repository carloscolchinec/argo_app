<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CnTbPlan extends Model
{
    use HasFactory;

    protected $table = 'cn_tb_plans';
    protected $primaryKey = 'id_plan';
    public $timestamps = false;


    protected $fillable = [
        'name',
        'price',
    ];

    public function clients()
    {
        return $this->hasMany(CnTbClient::class, 'id_plan');
    }
}
