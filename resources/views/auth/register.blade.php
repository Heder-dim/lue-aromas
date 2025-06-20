<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <title>Luê Aromas</title>
</head>
<body>
@extends('layouts.navigation') 
<div></div>

<div class="min-h-screen flex flex-col items-center justify-center bg-white px-4">
    <h1 class="text-4xl font-bold text-[#d09a50] mb-8">Registro</h1>

    @if ($errors->any())
        <div class="mb-4 text-red-600">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-[#fff4e5] rounded-2xl shadow-md p-10 w-full max-w-md">
        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Email -->
            <div class="text-center mb-4">
                <label for="email" class="block text-lg font-medium text-black mb-2">E-mail</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    placeholder="E-mail"
                    class="w-full px-4 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring focus:ring-[#d09a50]"
                />
            </div>

            <!-- Nome -->
            <div class="text-center mb-4">
                <label for="name" class="block text-lg font-medium text-black mb-2">Nome</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    placeholder="Nome"
                    class="w-full px-4 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring focus:ring-[#d09a50]"
                />
            </div>

            <!-- Senha -->
            <div class="text-center mb-4">
                <label for="password" class="block text-lg font-medium text-black mb-2">Senha</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    required
                    placeholder="Senha"
                    class="w-full px-4 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring focus:ring-[#d09a50]"
                />
            </div>

            <!-- Confirmar Senha -->
            <div class="text-center mb-6">
                <label for="password_confirmation" class="block text-lg font-medium text-black mb-2">Confirmar Senha</label>
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    required
                    placeholder="Confirme a senha"
                    class="w-full px-4 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring focus:ring-[#d09a50]"
                />
            </div>

            <div class="text-center">
                <button
                    type="submit"
                    class="bg-[#a75824] text-white font-medium py-2 px-6 rounded hover:bg-[#8a3a17] transition"
                >
                    Registrar
                </button>
            </div>
        </form>
    </div>
</div>
</body>
</html>