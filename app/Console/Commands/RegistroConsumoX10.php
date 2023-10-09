<?php

namespace App\Console\Commands;

// app/Console/Commands/RegistroConsumoDiario.php

use Illuminate\Console\Command;
use App\Models\CnTbClient;

use App\Models\CnTbConsumo;
use App\Models\CnTbRouter;
use App\Http\Controllers\InformationClientController;
use App\Libs\RouterosAPI;
use Auth;


class RegistroConsumoX10 extends Command
{
    protected $signature = 'consumo:registrar';
    protected $description = 'Registra el consumo diario de todos los usuarios';

    public function __construct()
    {
        parent::__construct();
    }
    public function handle()
    {
        $users = CnTbClient::all();
    
        foreach ($users as $user) {

            
            $routerData = CnTbRouter::find($user->id_router); // Asegúrate de utilizar el nombre correcto de la columna que almacena el ID del router
           
            if ($routerData) {
                $ipStatistics = $this->fetchIpStatistics($routerData, $user); // Pasar $user como parámetro
    
                // Registrar el consumo diario
                $consumoDiario = new CnTbConsumo();
                $consumoDiario->ni_client = $user->ni_client;
                $consumoDiario->fecha = now(); // Fecha y hora actual
                $consumoDiario->consumo_upload = isset($ipStatistics['upload']['bytes']) ? $ipStatistics['upload']['bytes'] : '0 Bytes';
                $consumoDiario->consumo_download = isset($ipStatistics['download']['bytes']) ? $ipStatistics['download']['bytes'] : '0 Bytes';
                try {
                    $consumoDiario->save();
                } catch (\Exception $e) {
                    // Manejar el error, por ejemplo, imprimir el mensaje de error
                    $this->error('Error al guardar el registro: ' . $e->getMessage());
                }
                
            }
        }
    
        $this->info('Consumo diario registrado para todos los usuarios.');
    }



    public function fetchIpStatistics($routerData, $user)
    {
        $API = new RouterosAPI();
        $ipStatisticsByIp = [];

        if ($API->connect($routerData->ip_router, $routerData->user_router, $routerData->pass_router)) {
            $ipStatisticsCommand = "/queue/simple/print";
            $ipStatistics = $API->comm($ipStatisticsCommand);

            $API->disconnect();

            foreach ($ipStatistics as $stat) {
                $ipParts = explode('/', $stat['target']);
                $ip = $ipParts[0];

    
                if ($ip === $user->ip_client) {
                    $bytesParts = explode('/', $stat['bytes']);
                    $uploadBytes = $this->convertBytes($bytesParts[0]);
                    $downloadBytes = $this->convertBytes($bytesParts[1]);
                
                
                    $ipStatisticsByIp['upload'] = [
                        'id' => 'upload',
                        'bytes' => $uploadBytes,
                        'packets' => $stat['packets'],
                    ];
                
                    $ipStatisticsByIp['download'] = [
                        'id' => 'download',
                        'bytes' => $downloadBytes,
                        'packets' => $stat['packets'],
                    ];
                
                    return $ipStatisticsByIp;
                }
                
            }
        }

        return $ipStatisticsByIp; // Devuelve un array vacío si no se encuentra la IP
    }

    public function convertBytes($bytes)
    {
        $bytes = (float) $bytes;

        if ($bytes >= 1099511627776) {
            return round($bytes / 1099511627776, 2) . ' TB';
        } elseif ($bytes >= 1073741824) {
            return round($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return round($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return round($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' Bytes';
        }
    }
}
