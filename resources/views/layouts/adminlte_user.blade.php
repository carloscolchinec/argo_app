<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>

    <script nomodule src="https://cdn.jsdelivr.net/npm/@ionic/core/dist/ionic/ionic.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ionic/core/css/ionic.bundle.css" />
    <script type="module" src="https://cdn.jsdelivr.net/npm/@ionic/core/dist/ionic/ionic.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    {{-- <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script> --}}
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" /> --}}

    {{-- <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script> --}}

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body>

    <style>
      body {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
    background-color: #f0f0f0; /* Cambia el color de fondo según tus preferencias */
}

.loader {
    width: 20px;
    height: 20px;
    background-color: #007bff; /* Fondo transparente */
    
    border-radius: 50%; /* Hace que sea un círculo */
    margin: 5px;
    display: inline-block; /* Para que se comporte como un elemento en línea */
    animation: bounce 1s alternate infinite ease-in-out; /* Aplica la animación de subir y bajar con efecto ease-in-out */
}

.loader:nth-child(2) {
    animation-delay: 0.2s; /* Agrega un retraso a la segunda bolita */
}

.loader:nth-child(3) {
    animation-delay: 0.4s; /* Agrega un retraso mayor a la tercera bolita */
}

@keyframes bounce {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-20px); /* Sube las bolitas en la mitad de la animación */
    }
}

    </style>
    <ons-page>
        <!-- Bolitas de carga -->
        <div class="loader"></div>
        <div class="loader"></div>
        <div class="loader"></div>

        @yield('content')
    </ons-page>
    <script>
        // Función para quitar las bolitas de carga
        function removeLoaders() {
            const loaders = document.querySelectorAll(".loader");
            loaders.forEach(loader => {
                loader.style.display = "none";
            });
        }

        // Llama a la función para quitar las bolitas de carga después de 3 segundos (ajusta el tiempo según tus necesidades)
        setTimeout(removeLoaders, 5000);
    </script>
</body>

</html>
