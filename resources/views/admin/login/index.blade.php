@extends('layouts.adminlte_default')

@section('title', 'Inicio de Sesión')

@section('content')

    <div class="flex justify-center items-center h-screen">
        <div class="w-full max-w-md p-6">

            <form method="POST" action="{{ route('admin.login_form') }}"
                class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                @csrf

                <div class="mb-8 text-center">
                    <a href="{{ url('/') }}">
                        <img src="{{ asset('assets/img/header.gif') }}" alt="Logo" class="w-1/1 mx-auto">
                    </a>
                </div>

                
                @if (session('error'))
                    <div class="mb-4 ">
                        <span class="bg-red-500 text-white  w-full rounded py-1 px-2">{{ session('error') }}</span>
                    </div>
                @endif


                <div class="mb-4">
                    <label for="email"
                        class="block text-gray-700 text-sm font-bold mb-2">{{ __('Correo Electrónico') }}</label>
                    <input id="email" type="email"
                        class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('email') border-red-500 @enderror"
                        name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                    @error('email')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Contraseña') }}</label>
                    <input id="password" type="password"
                        class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('password') border-red-500 @enderror"
                        name="password" required autocomplete="current-password">
                    @error('password')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>


                <div class="flex items-center justify-center">
                    <div class="w-full">
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full">
                            {{ __('Iniciar Sesión') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
