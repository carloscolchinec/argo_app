<?php

namespace App\Jobs;

use App\Models\CnTbClient;
use App\Libs\RouterosAPI;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PingClientJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $client;

    public function __construct($client)
    {
        $this->client = $client;
    }

    public function handle()
    {
        $API = new RouterosAPI();

        if ($API->connect($this->client->router->ip_router, $this->client->router->user_router, $this->client->router->pass_router)) {
            $pingCommand = "/ping";
            $pingParams = [
                "address" => $this->client->ip_client,
                "count" => "1"
            ];

            // Realizar un solo intento de ping
            $response = $API->comm($pingCommand, $pingParams);

            $API->disconnect();

            // Verificar si el ping fue exitoso y actualizar el estado del cliente
            $isActive = $response[0]['received'] == 1;
            $this->client->update(['is_connected' => $isActive]);
        } else {
            // Si no se puede conectar al router, marcar como desconectado en la base de datos
            $this->client->update(['is_connected' => false]);
        }
    }
}
