<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CnTbClient;
use App\Models\CnTbConsumo;
use App\Models\CnTbConsumoDiario;
use App\Models\CnTbRouter;
use App\Models\CnTbPlan;
use App\Libs\RouterosAPI;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth as FacadesAuth;


class InformationClientController extends Controller
{
    public function showFormLogin()
    {
        return view('user.login.index');
    }

    public function login(Request $request)
    {
        $request->validate([
            'ni_client' => 'required|exists:cn_tb_clients,ni_client',
        ], [
            'ni_client.required' => 'El campo de identificación es obligatorio.',
            'ni_client.exists' => 'Cliente no encontrado. Por favor, verifica la identificación.',
        ]);

        $niClient = $request->input('ni_client');

        // Realiza la autenticación usando el campo ni_client como identificador único
        $client = CnTbClient::where('ni_client', $niClient)->first();

        if ($client) {
            // Autenticación exitosa, inicia sesión y redirige al dashboard
            Auth::login($client);
            return redirect()->route('user.dashboard');
        } else {
            // Cliente no encontrado, redirige de nuevo al formulario de inicio de sesión
            return redirect()->route('user.login_form')->with('error', 'Cliente no encontrado');
        }
    }

    public function dashboard()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('user.login_form');
        }

        $routerData = CnTbRouter::find($user->router)->first();

        if ($routerData) {
            $routerData->client_status_mk = $this->pingRouter($routerData, $user->ip_client);

            $ipStatistics = $this->fetchIpStatistics($routerData, $user->ip_client);

            $information = [
                'status_connect' => $routerData->client_status_mk,
                'traffic_total_upload' => isset($ipStatistics['upload']) ? $ipStatistics['upload']['bytes'] : 'N/A',
                'traffic_total_download' => isset($ipStatistics['download']) ? $ipStatistics['download']['bytes'] : 'N/A',
            ];

            unset($routerData->client_status_mk);


            $consumo = CnTbConsumo::where('ni_client', $user->ni_client)->get();


            $yesterday = Carbon::yesterday(); // Obtener la fecha de ayer
            $now = Carbon::now(); // Obtener la fecha y hora actual


            $consumoDiario = CnTbConsumoDiario::where('ni_client', $user->ni_client)
                ->where('fecha', '>=', $yesterday)
                ->where('fecha', '<=', $now)
                ->orderBy('fecha', 'desc')
                ->get();


            $startOfWeek = Carbon::now()->subDays(6); // Hace 6 días para incluir hoy
            $endOfWeek = Carbon::now();
            $consumoSemanalUpload = CnTbConsumoDiario::where('ni_client', $user->ni_client)
                ->where('fecha', '>=', $startOfWeek)
                ->where('fecha', '<=', $endOfWeek)
                ->sum('consumo_upload');

            $consumoSemanalDownload = CnTbConsumoDiario::where('ni_client', $user->ni_client)
                ->where('fecha', '>=', $startOfWeek)
                ->where('fecha', '<=', $endOfWeek)
                ->sum('consumo_download');


            // Obtén la fecha de inicio y fin del mes actual
            $firstDayOfMonth = now()->firstOfMonth();
            $lastDayOfMonth = now()->lastOfMonth();

            // Calcula el consumo mensual de subida y bajada
            $consumoMensualUpload = CnTbConsumoDiario::where('ni_client', $user->ni_client)
                ->where('fecha', '>=', $firstDayOfMonth)
                ->where('fecha', '<=', $lastDayOfMonth)
                ->sum('consumo_upload');

            $consumoMensualDownload = CnTbConsumoDiario::where('ni_client', $user->ni_client)
                ->where('fecha', '>=', $firstDayOfMonth)
                ->where('fecha', '<=', $lastDayOfMonth)
                ->sum('consumo_download');

            // Obtén la fecha de inicio y fin del año actual
            $firstDayOfYear = now()->firstOfYear();
            $lastDayOfYear = now()->lastOfYear();


            // Calcula el consumo anual de subida y bajada
            $consumoAnualUpload = CnTbConsumoDiario::where('ni_client', $user->ni_client)
                ->where('fecha', '>=', $firstDayOfYear)
                ->where('fecha', '<=', $lastDayOfYear)
                ->sum('consumo_upload');

            $consumoAnualDownload = CnTbConsumoDiario::where('ni_client', $user->ni_client)
                ->where('fecha', '>=', $firstDayOfYear)
                ->where('fecha', '<=', $lastDayOfYear)
                ->sum('consumo_download');



            if ($consumo->count() > 0 || $consumoDiario->count() > 0) {
                $clientData = [
                    'information_mk' => $information,
                    'data' => $user,
                    'plan' => $this->getClientPlan($user->id_plan),
                    'consumo' => $consumo,
                    'consumoDiario' => $consumoDiario,
                    'consumoSemanalUpload' => $consumoSemanalUpload,
                    'consumoSemanalDownload' => $consumoSemanalDownload,
                    'consumoMensualUpload' => $consumoMensualUpload,
                    'consumoMensualDownload' => $consumoMensualDownload,
                    'consumoAnualUpload' => $consumoAnualUpload,
                    'consumoAnualDownload' => $consumoAnualDownload,
                ];


                unset($clientData['plan']['id_plan']);

                return view('user.dashboard.index', compact('clientData'));
            } else {
                return redirect()->route('user.login_form')->with('error', 'No se encontraron datos del router');
            }
        } else {
            return redirect()->route('user.login_form')->with('error', 'No se encontraron datos del router');
        }
    }



    public function pingRouter($routerData, $ip_client)
    {
        $API = new RouterosAPI();

        if ($API->connect($routerData->ip_router, $routerData->user_router, $routerData->pass_router)) {
            $pingCommand = "/ping";
            $pingParams = [
                "address" => $ip_client,
                "count" => "1"
            ];
            $response = $API->comm($pingCommand, $pingParams);
            $API->disconnect();

            if ($response[0]['received'] == 1) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }


    public function fetchIpStatistics($routerData, $ipToFetch)
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

                if ($ip === $ipToFetch) {
                    $bytesParts = explode('/', $stat['bytes']);
                    $uploadBytes = $this->convertBytes($bytesParts[0]);
                    $downloadBytes = $this->convertBytes($bytesParts[1]);

                    // dd($downloadBytes);

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



    public function getTrafficData()
    {
        $user = Auth::user();

        if (!$user) {
            return [
                'error' => 'Usuario no autenticado',
            ];
        }

        $routerData = CnTbRouter::find($user->router)->first();



        if ($routerData) {
            $ipStatistics = $this->fetchIpStatisticsReal($routerData, $user->ip_client);


            $information = [
                'traffic_up' => isset($ipStatistics['upload']['bytes']) ? $ipStatistics['upload']['bytes'] : '0 Bytes',
                'traffic_down' => isset($ipStatistics['download']['bytes']) ? $ipStatistics['download']['bytes'] : '0 Bytes',
            ];
        } else {
            $information = [
                'traffic_up' => '0 Bytes',
                'traffic_down' => '0 Bytes',
            ];
        }

        return $information;
    }


    public function scanTop10DstAddresses($routerData, $ipToFetch)
    {
        $API = new RouterosAPI();
        $dstAddresses = [];
        // $maxExecutionTime = 45; // Establece el tiempo máximo de ejecución en segundos

        // // Establece el límite de tiempo de ejecución
        // set_time_limit($maxExecutionTime);

        if ($API->connect($routerData->ip_router, $routerData->user_router, $routerData->pass_router)) {
            $torchCommand = "/tool/torch";

            $torchParams = [
                "interface" => $routerData->interface_clients,
                "src-address" => $ipToFetch,
                "dst-address" => "0.0.0.0/0",
                "port" => "https",
                "duration" => "6s",
            ];

            $torchResults = $API->comm($torchCommand, $torchParams);

            $API->disconnect();

            foreach ($torchResults as $result) {


                // Verifica si la clave 'dst-address' existe en el resultado
                if (isset($result['dst-address'])) {
                    $ipParts = explode(':', $result['dst-address']);
                    $dstAddress = $ipParts[0];

                    $dstAddresses[] = $dstAddress;
                }
            }

            return $dstAddresses;
        }

        return [];
    }

    public function getTop10ASN()
    {
        $user = Auth::user();

        if (!$user) {
            return [
                'error' => 'Usuario no autenticado',
            ];
        }

        $routerData = CnTbRouter::find($user->router)->first();

        if ($routerData) {
            $ipAddresses = $this->scanTop10DstAddresses($routerData, $user->ip_client);


            // Eliminar direcciones IP duplicadas
            $ipAddresses = array_unique($ipAddresses);

            // Obtener el nombre del ISP para cada dirección IP
            $ispNames = [];
            foreach ($ipAddresses as $ipAddress) {

                $ispName = $this->getISPName($ipAddress);

                if ($ispName !== "Error al obtener el ISP." && $ispName !== "No se pudo obtener el ISP.") {
                    // Filtrar el nombre del ISP para incluir solo palabras clave de redes sociales
                    $filteredName = $this->filterIspName($ispName);
                    if (!empty($filteredName)) {
                        $ispNames[] = $filteredName;
                    }
                }
            }

            // Eliminar nombres de ISP duplicados
            $uniqueIspNames = array_unique($ispNames);

            return array_values($uniqueIspNames);
        }
    }

    // Función para filtrar el nombre del ISP y retener solo palabras clave de redes sociales
    private function filterIspName($ispName)
    {
        // Lista de palabras clave de redes sociales
        $redesSociales = [
            'Speed Test',
            'Fast',
            'Facebook',
            'Instagram',
            'Twitter',
            'LinkedIn',
            'Snapchat',
            'TikTok',
            'Google',
            'WhatsApp',
            'YouTube',
            'Telegram',
            'Pinterest',
            'Reddit',
            'Tumblr',
            'Discord',
            'Clubhouse',
            'Signal',
            'Parler',
            'MeWe',
            'Gab',
            'Twitch',
            'Vimeo',
            'Dailymotion',
            'Netflix',
            'Amazon Prime Video',
            'HBO Max',
            'Disney+',
            'Hulu',
            'Apple TV+',
            'YouTube TV',
            'Sling TV',
            'FuboTV',
            'Peacock',
            'Crunchyroll',
            'Funimation',
            'Tubi',
            'Pluto TV',
            'Vudu',
            'Vine',
            'Periscope',
            'MySpace',
            'Orkut',
            'Hi5',
            'Friendster',
            'Google+',
            'Pinterest',
            'VKontakte',
            'Weibo',
            'WeChat',
            'QQ',
            'Qzone',
            'Baidu Tieba',
            'Xiaohongshu',
            'Zhihu',
            'Douyin',
            'Kuaishou',
            'Weibo',
            'Minds',
            'Steemit',
            'Rumble',
            'BitChute',
            'LBRY',
            'BrandNewTube',
        ];
        // Agrega más según sea necesario

        // Verificar si alguna palabra clave de redes sociales está presente en el nombre del ISP
        foreach ($redesSociales as $redSocial) {
            if (stripos($ispName, $redSocial) !== false) {
                return $redSocial; // Devolver la palabra clave si se encuentra
            }
        }

        return ''; // Devolver una cadena vacía si no se encuentra ninguna palabra clave
    }




    public function getISPName($ipAddress)
    {
        $apiUrl = "http://ip-api.com/json/{$ipAddress}";

        // Inicializa una sesión cURL
        $ch = curl_init($apiUrl);

        // Configura las opciones de la solicitud cURL
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Realiza la solicitud
        $response = curl_exec($ch);

        // Verifica si la solicitud fue exitosa
        if ($response === false) {
            return "Error al obtener el ISP.";
        }

        // Decodifica la respuesta JSON
        $data = json_decode($response, true);

        // Verifica si la solicitud fue exitosa
        if ($data['status'] === "success") {
            return $data['isp']; // Devuelve el nombre del ISP
        } else {
            return "No se pudo obtener el ISP.";
        }
    }


    // public function getASNForIP($ip)
    // {
    //     $apiUrl = "http://ip-api.com/json/{$ip}?fields=as";

    //     // Realiza una solicitud HTTP para obtener información de ASN
    //     $response = file_get_contents($apiUrl);

    //     if ($response !== false) {
    //         $data = json_decode($response);

    //         // Verifica si se pudo obtener la información del ASN
    //         if (isset($data->as)) {
    //             return $data->as;
    //         }
    //     }

    //     return null;
    // }



    public function fetchIpStatisticsReal($routerData, $ipToFetch)
    {
        $API = new RouterosAPI();
        $ipStatisticsByIp = array();

        if ($API->connect($routerData->ip_router, $routerData->user_router, $routerData->pass_router)) {
            $ipStatisticsCommand = "/queue/simple/print";
            $ipStatistics = $API->comm($ipStatisticsCommand);

            foreach ($ipStatistics as $stat) {
                $ipParts = explode('/', $stat['target']);
                $ip = $ipParts[0];


                if ($ip === $ipToFetch) {
                    $bytesParts = explode('/', $stat['rate']);

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
                    // Importante: devolver aquí el array
                }
            }
        }

        return $ipStatisticsByIp; // Devolver el array vacío si no se encuentra la IP
    }





    public function getClientPlan($id_plan)
    {
        return CnTbPlan::find($id_plan)->toArray();
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
