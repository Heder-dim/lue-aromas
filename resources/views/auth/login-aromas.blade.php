<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <title>Luê Aromas</title>
</head>
<body>
@include('layouts.navigation')
        
<div class=" flex flex-col items-center justify-center bg-white px-4">
    <h1 class="text-4xl font-bold text-[#d09a50] mb-8 mt-8">Login</h1>

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
        <form action="{{ route('login-aromas') }}" method="POST">
            @csrf

            <div class="text-center">
                <label for="email" class="block text-lg font-medium text-black mb-2">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="Digite seu e-mail"
                    required
                    class="w-full px-4 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring focus:ring-[#d09a50]"
                />
            </div>

            <div class="text-center">
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

            <button
                type="submit"
                class="mt-4 bg-[#d09a50] text-white cursor-pointer font-medium py-2 px-4 rounded hover:bg-[#bb8642] transition"
            >
                Entrar
            </button>
            <div class="mt-4 text-center">
                <a href="{{ route('register') }}" class="text-[#d09a50] hover:underline">Não tem uma conta? Cadastre-se</a>
        </form>
    </div>
</div>
</body>
</html>