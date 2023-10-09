@extends('layouts.adminlte_user')

@section('title', 'Panel de Control')

@section('content')




    <style>
        /* Estilos generales de la aplicación */
        .main-home {
            flex-direction: column;
            /* Cambio la dirección de flex a columna para mejor manejo en dispositivos móviles */
            align-items: center;
            justify-content: center;
            height: 86vh;
        }

        .radio-page {
            flex-direction: column;
            /* Cambio la dirección de flex a columna para mejor manejo en dispositivos móviles */
            align-items: center;
            justify-content: center;
            height: 86vh;
        }

        .page-top {
            flex-direction: column;
            /* Cambio la dirección de flex a columna para mejor manejo en dispositivos móviles */
            align-items: center;
            justify-content: center;
            height: 86vh;
        }

        ion-toolbar {
            --background: #3880ff;
            --color: #fff;
        }

        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            max-width: 100%;
            /* Limita el ancho del contenido */
        }

        .logo {
            width: 90%;
            max-width: 200px;
            /* Limita el ancho del logo */
            padding-top: 15px !important;
            margin-bottom: 3px;
        }

        ion-card {
            width: 97%;
            margin-bottom: 10px;
            /* Aumento el margen inferior para separar las tarjetas */
        }

        .ion-card-center {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            /* Alineación de texto centrada */
            padding: 20px;
            /* Espaciado interno para las tarjetas */
        }

        #dataChart {
            max-width: 100%;
            /* Limita el ancho del gráfico para que se ajuste al contenido */
        }

        ion-badge {
            /* Color de fondo */
            color: #fff;
            /* Color del texto */
            font-size: 14px;
            /* Tamaño de fuente */
            padding: 5px 10px !important;
            margin: 10px;
            display: flex;
            justify-content: center;
            /* Espaciado interno */
            border-radius: 5px;
            width: 95%;
            /* Borde redondeado */
        }

        .mt-10 {
            margin-top: 10px !important;
        }
    </style>

    <ion-tabs>
        <ion-tab tab="home">
            <ion-nav id="home-nav"></ion-nav>
            <div id="home-page">

                <ion-content>
                    <div class="main-home">

                        <div class="container">
                            <img src="{{ asset('assets/img/Argo.png') }}" alt="Logo de la empresa" class="logo">

                            <ion-card class="rounded-card">
                                <ion-card-header color="primary">
                                    <ion-card-title>Información Personal</ion-card-title>
                                </ion-card-header>
                                <ion-card-content class="mt-10">
                                    <div class="client-name">
                                        <strong><ion-icon name="person"></ion-icon> Cliente:</strong>
                                        {{ $clientData['data']->name_client }} {{ $clientData['data']->lastname_client }}
                                    </div>
                                    <div class="client-name">
                                        <strong><ion-icon name="card"></ion-icon> Identificación:</strong>
                                        {{ $clientData['data']->ni_client }}
                                    </div>
                                    <div class="client-name">
                                        <strong><ion-icon name="wifi"></ion-icon> Plan Contratado:</strong>
                                        {{ $clientData['plan']['name'] }}
                                    </div>
                                    <div class="client-name">
                                        <strong><ion-icon name="checkmark-circle"></ion-icon> Estado del Servicio:</strong>
                                        {{ $clientData['information_mk']['status_connect'] ? 'Activo' : 'Desconectado' }}
                                    </div>
                                </ion-card-content>

                            </ion-card>

                            <ion-card class="rounded-card">
                                <ion-card-header color="primary">
                                    <ion-card-title>Detalles del consumo</ion-card-title>
                                </ion-card-header>
                                <ion-card-content class="mt-10">
                                    <p><strong><ion-icon name="arrow-up"></ion-icon> Consumo Total de Subida:</strong>
                                        {{ $clientData['information_mk']['traffic_total_upload'] }}</p>
                                    <p><strong><ion-icon name="arrow-down"></ion-icon> Consumo Total de Descarga:</strong>
                                        {{ $clientData['information_mk']['traffic_total_download'] }}</p>
                                </ion-card-content>
                            </ion-card>

                            <ion-card class="rounded-card">

                                <ion-card-header color="primary">
                                    <ion-card-title> <ion-icon name="trending-up"></ion-icon> Consumo en Tiempo
                                        Real</ion-card-title>
                                </ion-card-header>
                                <ion-card-content class="mt-10">
                                    <div class="chart-container">
                                        <canvas id="dataChart"></canvas>
                                    </div>
                                </ion-card-content>
                            </ion-card>



                        </div>
                    </div>

                    <script>
                       var chartOptions = {
    maintainAspectRatio: false,
    scales: {
        x: {
            grid: {
                display: false
            },
        },
        y: {
            grid: {
                color: "#ccc",
                borderDash: [2, 5]
            },
            ticks: {
                beginAtZero: true,
                stepSize: 1,
                callback: function(value) {
                    return value >= 1024 ?
                        (value / 1024).toFixed(2) + " MB" :
                        value + " KB";
                },
            },
        },
    },
    plugins: {
        legend: {
            display: true,
            position: "bottom"
        }
    },
};

var ctx = document.getElementById("dataChart").getContext("2d");
var dataChart = new Chart(ctx, {
    type: "line",
    data: {
        labels: [],
        datasets: [{
                label: "Subida (KB/MB)",
                data: [],
                borderColor: "#007BFF",
                backgroundColor: "rgba(0, 123, 255, 0.1)",
                borderWidth: 2,
                fill: true,
            },
            {
                label: "Bajada (KB/MB)",
                data: [],
                borderColor: "#28A745",
                backgroundColor: "rgba(40, 167, 69, 0.1)",
                borderWidth: 2,
                fill: true,
            },
        ],
    },
    options: chartOptions,
});


function updateChartData(dataChart, timeLabel, convertedTrafficUp, convertedTrafficDown) {
    if (dataChart.data.labels.length >= 4) {
        dataChart.data.labels.shift();
        dataChart.data.datasets[0].data.shift();
        dataChart.data.datasets[1].data.shift();
    }

    dataChart.data.labels.push(timeLabel);
    dataChart.data.datasets[0].data.push(convertedTrafficUp);
    dataChart.data.datasets[1].data.push(convertedTrafficDown);
    dataChart.update();
}

function getTrafficData() {
    fetch('/traffic')
        .then(response => response.json())
        .then(data => {
            const trafficUpValue = data.traffic_up;
            const trafficDownValue = data.traffic_down;
            const trafficUpUnit = trafficUpValue.split(" ")[1];
            const trafficDownUnit = trafficDownValue.split(" ")[1];
            const convertedTrafficUp = trafficUpUnit === "MB" ? parseFloat(trafficUpValue) * 1024 : parseFloat(trafficUpValue);
            const convertedTrafficDown = trafficDownUnit === "MB" ? parseFloat(trafficDownValue) * 1024 : parseFloat(trafficDownValue);
            const currentTime = new Date();
            const timeLabel = currentTime.toLocaleTimeString("en-US");

            updateChartData(dataChart, timeLabel, convertedTrafficUp, convertedTrafficDown);
        })
        .catch(error => {
            console.error('Error al obtener datos de tráfico:', error);
        });
}

setInterval(getTrafficData, 3000);

                    </script>
                </ion-content>
            </div>
        </ion-tab>

        <ion-tab tab="top">
            <ion-nav id="top-nav"></ion-nav>
            <div id="top-page">
                <ion-content>
                    <div class="page-top">
                        <ion-card>
                            <ion-card-header color="primary">
                                Lista de Redes más usadas!
                            </ion-card-header>
                            <ion-card-content>
                                <canvas id="networkPieChart" width="400" height="400"></canvas>
                            </ion-card-content>
                        </ion-card>
                    </div>
                </ion-content>

            </div>

            <script>
                // Función para obtener los datos de la API
                async function fetchNetworkData() {
                    try {
                        const response = await fetch('http://192.168.0.101:8000/top');
                        if (!response.ok) {
                            throw new Error('No se pudo obtener la información de la API.');
                        }
                        const data = await response.json();
                        return data;
                    } catch (error) {
                        console.error('Error al obtener datos de la API:', error);
                        return [];
                    }
                }

                // Función para crear el gráfico de pastel
                async function createPieChart() {
                    const networkData = await fetchNetworkData();

                    const ctx = document.getElementById('networkPieChart').getContext('2d');
                    const pieChart = new Chart(ctx, {
                        type: 'pie',
                        data: {
                            labels: networkData,
                            datasets: [{
                                data: new Array(networkData.length).fill(1), // Datos de ejemplo
                                backgroundColor: [
                                    'rgba(255, 99, 132, 0.7)', // Color para Facebook
                                    'rgba(54, 162, 235, 0.7)', // Color para Google
                                    'rgba(255, 206, 86, 0.7)' // Color para Netflix
                                    // Puedes agregar más colores según la cantidad de redes sociales
                                ],
                            }],
                        },
                    });
                }

                // Llama a la función para crear el gráfico de pastel cuando la página se cargue
                window.addEventListener('DOMContentLoaded', () => {
                    createPieChart();
                });
            </script>
        </ion-tab>



        <ion-tab tab="radio">
            <ion-nav id="radio-nav"></ion-nav>
            <div id="radio-page">
                <ion-content>
                    <div class="page-radio">
                        <style>
                            .content {
                                padding: 10px;
                                margin-left: 15px;
                                margin-bottom: -20px !important;
                            }

                            .hidden {
                                display: none;
                            }
                        </style>

                        <div class="content">
                            <h1><ion-icon name="stats-chart-outline"></ion-icon> Registros de Consumo</h1>
                        </div>

                        <style>
                            ion-slides {
                                height: 100%;
                            }

                            .content1 {

                                margin-bottom: -15px;
                            }

                            .rounded-card-custom {
                                width: 320px !important;
                                margin: 10px;
                                height: auto;
                                margin: 15px auto;
                                display: flex;
                                flex-direction: column;
                                justify-content: space-between;
                            }
                        </style>



                        <div class="content1">
                            <div style="width: 100%; overflow-x: auto;">
                                <ion-slides pager="true" style="display: flex; flex-direction: row;">
                                    <!-- Diario -->
                                    <ion-slide style="flex: 0 0 auto;">
                                                                                <ion-list id="data-list">
                                            @if ($clientData['consumoDiario']->count() > 0)
                                                @php
                                                    // Obtén el valor más reciente sin recorrer la colección
                                                    $data = $clientData['consumoDiario']->first();
                                                @endphp
                                                <ion-card class="rounded-card-custom"
                                                style="width: 95%; margin: 10px auto; margin-left: 25px; @media (max-width: 767px) { margin-left: 15px; }">

                                                    <ion-card-header color="primary">
                                                        <ion-card-title>Detalles del consumo diario</ion-card-title>
                                                    </ion-card-header>
                                                    <ion-card-content class="mt-10">
                                                        <ion-grid>
                                                            <ion-row>
                                                                <ion-col size="12" class="center-text">
                                                                    <ion-icon name="cloud-upload-outline"></ion-icon>
                                                                    <ion-label>Total de Subida:
                                                                        {{ $clientData['information_mk']['traffic_total_upload'] }}</ion-label>
                                                                </ion-col>
                                                            </ion-row>
                                                            <ion-row>
                                                                <ion-col size="12" class="center-text">
                                                                    <ion-icon name="cloud-download-outline"></ion-icon>
                                                                    <ion-label>Total de Bajada:
                                                                        {{ $clientData['information_mk']['traffic_total_download'] }}</ion-label>
                                                                </ion-col>
                                                            </ion-row>
                                                        </ion-grid>
                                                    </ion-card-content>
                                                </ion-card>
                                            @else
                                                <ion-card id="no-data-message" style="margin: 10px;">
                                                    <ion-card-content>
                                                        <p>No se encontraron datos de consumo diario para este usuario.</p>
                                                    </ion-card-content>
                                                </ion-card>
                                            @endif
                                        </ion-list>
                                    </ion-slide>

                                    <!-- Semanal -->
                                    <ion-slide style="flex: 0 0 auto;">
                                        <ion-list id="semanal-list" style="margin-left: 3px">
                                            <ion-card class="rounded-card-custom" style="width: 95%; margin: 10px auto;">
                                                <ion-card-header color="primary" style="white-space: nowrap;">
                                                    <ion-card-title>Resumen del consumo semanal</ion-card-title>
                                                </ion-card-header>
                                                <ion-card-content class="mt-10">
                                                    <ion-grid>
                                                        <ion-row>
                                                            <ion-col size="12" class="center-text">
                                                                <ion-label><ion-icon name="cloud-upload-outline"></ion-icon>
                                                                    Total de Subida:
                                                                    {{ $clientData['consumoSemanalUpload'] }}
                                                                    GB</ion-label>
                                                            </ion-col>
                                                        </ion-row>
                                                        <ion-row>
                                                            <ion-col size="12" class="center-text">
                                                                <ion-label><ion-icon
                                                                        name="cloud-download-outline"></ion-icon> Total de
                                                                    Bajada:
                                                                    {{ $clientData['consumoSemanalDownload'] }}
                                                                    GB</ion-label>
                                                            </ion-col>
                                                        </ion-row>
                                                    </ion-grid>
                                                </ion-card-content>
                                            </ion-card>
                                        </ion-list>
                                    </ion-slide>

                                    <!-- Mensual -->
                                    <ion-slide style="flex: 0 0 auto;">
                                        <ion-list id="mensual-list" style="margin-left: 3px">
                                            <ion-card class="rounded-card-custom" style="width: 95%; margin: 10px auto;">
                                                <ion-card-header color="primary" style="white-space: nowrap;">
                                                    <ion-card-title>Resumen del consumo Mensual</ion-card-title>
                                                </ion-card-header>
                                                <ion-card-content class="mt-10">
                                                    <ion-grid>
                                                        <ion-row>
                                                            <ion-col size="12" class="center-text">
                                                                <ion-label><ion-icon name="cloud-upload-outline"></ion-icon>
                                                                    Total de Subida:
                                                                    {{ $clientData['consumoMensualUpload'] }}
                                                                    GB</ion-label>
                                                            </ion-col>
                                                        </ion-row>
                                                        <ion-row>
                                                            <ion-col size="12" class="center-text">
                                                                <ion-label><ion-icon
                                                                        name="cloud-download-outline"></ion-icon> Total de
                                                                    Bajada:
                                                                    {{ $clientData['consumoMensualDownload'] }}
                                                                    GB</ion-label>
                                                            </ion-col>
                                                        </ion-row>
                                                    </ion-grid>
                                                </ion-card-content>
                                            </ion-card>
                                        </ion-list>
                                    </ion-slide>

                                    <!-- Anual -->
                                    <ion-slide style="flex: 0 0 auto;">
                                        <ion-list id="mensual-list" style="margin-left: 3px">
                                            <ion-card class="rounded-card-custom" style="width: 95%; margin: 10px auto; margin-right: 10px;">
                                                <ion-card-header color="primary" style="white-space: nowrap;">
                                                    <ion-card-title>Resumen del consumo Anual</ion-card-title>
                                                </ion-card-header>
                                                <ion-card-content class="mt-10">
                                                    <ion-grid>
                                                        <ion-row>
                                                            <ion-col size="12" class="center-text">
                                                                <ion-label><ion-icon name="cloud-upload-outline"></ion-icon>
                                                                    Total de Subida:
                                                                    {{ $clientData['consumoAnualUpload'] }}
                                                                    GB</ion-label>
                                                            </ion-col>
                                                        </ion-row>
                                                        <ion-row>
                                                            <ion-col size="12" class="center-text">
                                                                <ion-label><ion-icon
                                                                        name="cloud-download-outline"></ion-icon> Total de
                                                                    Bajada:
                                                                    {{ $clientData['consumoAnualDownload'] }}
                                                                    GB</ion-label>
                                                            </ion-col>
                                                        </ion-row>
                                                    </ion-grid>
                                                </ion-card-content>
                                            </ion-card>
                                        </ion-list>
                                    </ion-slide>

                                </ion-slides>
                            </div>
                        </div>



                        <ion-list id="data-list">
                            @if (count($clientData['consumo']) > 0)
                                @foreach ($clientData['consumo']->reverse() as $key => $data)
                                    <ion-card class="rounded-card" style="width: 95%; margin: 10px auto;">
                                        <ion-card-header color="primary" style="white-space: nowrap;">
                                            <ion-card-title style="white-space: nowrap;">Detalles del
                                                consumo</ion-card-title>
                                        </ion-card-header>
                                        <ion-card-content class="mt-10">
                                            <ion-grid>
                                                <ion-row>
                                                    <ion-col size="12" class="center-text">
                                                        <ion-icon name="cloud-upload-outline"></ion-icon>
                                                        @php
                                                            $uploadValue = $data->consumo_upload;
                                                            $uploadUnit = substr($uploadValue, -2); // Obtener las últimas dos letras (GB o MB)
                                                            $uploadValue = floatval($uploadValue); // Convertir a número decimal
                                                            
                                                            // Mostrar el valor en GB o MB
                                                            if ($uploadUnit === 'GB') {
                                                                $uploadValueFormatted = $uploadValue . ' GB';
                                                            } elseif ($uploadUnit === 'MB') {
                                                                $uploadValueFormatted = $uploadValue . ' MB';
                                                            } else {
                                                                $uploadValueFormatted = $uploadValue . ' ' . $uploadUnit;
                                                            }
                                                        @endphp
                                                        <ion-label>Subida: {{ $uploadValueFormatted }}</ion-label>
                                                    </ion-col>
                                                </ion-row>
                                                <ion-row>
                                                    <ion-col size="12" class="center-text">
                                                        <ion-icon name="cloud-download-outline"></ion-icon>
                                                        @php
                                                            $downloadValue = $data->consumo_download;
                                                            $downloadUnit = substr($downloadValue, -2); // Obtener las últimas dos letras (GB o MB)
                                                            $downloadValue = floatval($downloadValue); // Convertir a número decimal
                                                            
                                                            // Mostrar el valor en GB o MB
                                                            if ($downloadUnit === 'GB') {
                                                                $downloadValueFormatted = $downloadValue . ' GB';
                                                            } elseif ($downloadUnit === 'MB') {
                                                                $downloadValueFormatted = $downloadValue . ' MB';
                                                            } else {
                                                                $downloadValueFormatted = $downloadValue . ' ' . $downloadUnit;
                                                            }
                                                        @endphp
                                                        <ion-label>Bajada: {{ $downloadValueFormatted }}</ion-label>
                                                    </ion-col>
                                                </ion-row>
                                                <ion-row>
                                                    <ion-col size="12" class="center-text">
                                                        <ion-icon name="calendar-outline"></ion-icon>
                                                        <ion-label data-date="{{ $data->fecha }}">Hora y Fecha:
                                                            {{ $data->fecha }}</ion-label>
                                                    </ion-col>
                                                </ion-row>
                                            </ion-grid>
                                        </ion-card-content>

                                        @if ($downloadUnit === 'MB' && $downloadValue < 100)
                                            <ion-badge color="danger">Posible Reinicio o Mantenimiento en el
                                                Servicio</ion-badge>
                                        @endif
                                    </ion-card>
                                @endforeach
                            @else
                                <ion-card id="no-data-message" style="margin: 10px;">
                                    <ion-card-content>
                                        <p>No se encontraron datos de consumo diario para este usuario.</p>
                                    </ion-card-content>
                                </ion-card>
                            @endif
                        </ion-list>
                    </div>

                </ion-content>






            </div>
        </ion-tab>

        <style>
            /* Estilo para el card del usuario */
            .user-card {
                display: flex;
                flex-direction: column;
                align-items: center;
                text-align: center;
                background-color: #fff;
                border-radius: 10px;
                box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
                padding: 20px;
                max-width: 400px;
                margin: 0 auto;
            }

            /* Estilos para el icono de usuario */
            .user-icon {
                font-size: 100px;
                /* Tamaño del icono de usuario */
                color: #007BFF;
                /* Color del icono */
                margin-bottom: 10px;
            }

            /* Estilo para el nombre del usuario */
            .user-name {
                font-size: 24px;
                /* Tamaño del nombre del usuario */
                margin-bottom: 20px;
            }

            /* Estilo para el menú de lista */
            .menu-list {
                list-style-type: none;
                padding: 0;
            }

            /* Estilo para los elementos del menú */
            .menu-item {
                font-size: 18px;
                /* Tamaño de fuente de los elementos del menú */
                margin-bottom: 10px;
            }
        </style>

        <ion-tab tab="library">
            <ion-nav id="library-nav"></ion-nav>
            <div id="library-page">
                <ion-content>

                    <ion-card class="user-card">
                        <ion-icon name="person-circle-outline" class="user-icon"></ion-icon>
                        <p class="user-name">
                            {{ $clientData['data']->name_client }} {{ $clientData['data']->lastname_client }}
                        </p>



                        <!-- Botón de cerrar sesión -->
                        <ion-button expand="full" class="logout-button" onclick="window.location.href='/login'">
                            Cerrar Sesión
                        </ion-button>
                    </ion-card>

                </ion-content>
            </div>
        </ion-tab>
        </style>



        <ion-tab-bar slot="bottom">
            <ion-tab-button tab="home">
                <ion-icon name="home"></ion-icon>
                Inicio
            </ion-tab-button>
            {{-- <ion-tab-button tab="top">
                <ion-icon name="share-social-outline"></ion-icon>
                Top
            </ion-tab-button> --}}
            <ion-tab-button tab="radio">
                <ion-icon name="list-outline"></ion-icon>
                Registros
            </ion-tab-button>
            <ion-tab-button tab="library">
                <ion-icon name="person-circle-outline"></ion-icon>
                Perfil
            </ion-tab-button>

        </ion-tab-bar>
    </ion-tabs>

    <script>
        const homeNav = document.querySelector('#home-nav');
        const homePage = document.querySelector('#home-page');
        homeNav.root = homePage;

        const radioNav = document.querySelector('#radio-nav');
        const radioPage = document.querySelector('#radio-page');
        radioNav.root = radioPage;


        // const topNav = document.querySelector('#top-nav');
        // const topPage = document.querySelector('#top-page');
        // topNav.root = topPage;

        const libraryNav = document.querySelector('#library-nav');
        const libraryPage = document.querySelector('#library-page');
        libraryNav.root = libraryPage;

        const searchNav = document.querySelector('#search-nav');
        const searchPage = document.querySelector('#search-page');
        searchNav.root = searchPage;
    </script>

@endsection
