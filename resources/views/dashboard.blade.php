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
   <main class="">
        <div class="relative h-screen bg-cover bg-center" style="background-image: url('{{ asset('img/image_home.png') }}')">
            <div class="absolute inset-0 bg-black/40"></div> <!-- escurecimento do fundo -->

            <div class="relative z-10 flex items-center justify-start h-full px-10">
                <div class="bg-[#FAEBD7] max-w-xl p-8 rounded-xl shadow-lg">
                    <h1 class="text-6xl font-bold text-[#c3924d] leading-tight">
                        Descubra Um Mundo De Aromas
                    </h1>
                    <p class="text-gray-800 mt-4 text-lg leading-relaxed  mb-4">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec porta tristique sapien. 
                        Mauris convallis lorem non ligula consequat eleifend. Pellentesque pharetra eros tellus, 
                        ut molestie mauris sollicitudin sollicitudin.
                    </p>
                    <div> 
                        @auth
                            <span>Olá, {{ Auth::user()->name }}</span>

                            <a href="/admin" class="bg-[#a75824] text-white px-4 py-2 rounded hover:bg-[#8a3a17] transition">
                                Gerenciar
                            </a>

                        @else
                        <div class="text-[#8a3a17]">
                            <span>Faça login para começar</span>
                            <a href="/login-aromas" class="bg-[#a75824] text-white px-3 py-2 rounded hover:bg-[#8a3a17] transition">
                                Login
                            </a>
                            <span class="px-1">ou</span>
                            <a href="/register" class="hover:text-[#8a3a17] hover:underline">Registre-se</a>
                        </div>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>