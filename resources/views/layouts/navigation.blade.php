<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <title>Luê Aromas</title>
</head>
<body>
    
<header class="flex items-center justify-between px-[200px] py-4 shadow-sm border-b max">
        <!-- Logo -->
        <div class="flex items-center space-x-2 cursor-pointer">
            <a href="/dashboard">
            <img src="{{ asset('img/logo.png') }}" alt="Luê Aromas" class="h-24 w-auto" />
            </a>
            </div>
        </div>

        <!-- Navegação -->
        <nav class="flex space-x-8 text-[#8a3a17] font-regular text-lg cursor-pointer">
            <a href="/dashboard" class="hover:underline hover:font-medium">Início</a>
            <a href="/view-products" class="hover:underline hover:font-medium">Produtos</a>
            <a href="#" class="hover:underline hover:font-medium">Sobre</a>
            <a href="#" class="hover:underline hover:font-medium">Fale Conosco</a>
        </nav>

        <!-- Ações -->
        <div class="flex items-center space-x-4 text-lg text-[#a75824]">
            @auth
                <span>Olá, {{ Auth::user()->name }}</span>

                <a href="/admin" class="bg-[#a75824] text-white px-4 py-2 rounded hover:bg-[#8a3a17] transition">
                    Gerenciar
                </a>

                <a href="#" class="hover:text-[#8a3a17]">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                    </svg>
                </a>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class=" cursor-pointer hover:text-[#8a3a17]">
                        Sair
                    </button>
                </form>
            @else
                <a href="/login-aromas" class="bg-[#a75824] text-white px-4 py-2 rounded hover:bg-[#8a3a17] transition">
                    Login
                </a>
                <a href="/register" class="hover:text-[#8a3a17] hover:underline">Registrar</a>
            @endauth
        </div>
    </header>

    <div class="container mx-auto px-4">
        @yield('content') {{-- This is where your page content will be injected --}}
    </div>
</body>
</html>
