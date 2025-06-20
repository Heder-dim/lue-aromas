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
    <section class="pt-10 flex flex-col items-center justify-center bg-white text-center px-4">
    <h1 class="text-4xl sm:text-5xl font-extrabold text-[#bb8642] mb-4">
        Olá {{ Auth::user()->name }}, <br> o que você deseja?
    </h1>

    <div class="space-y-6 text-[#bb8642] font-semibold text-xl">
        <!-- Cadastrar -->
        <div>
            <p class="mb-2">Cadastrar novo produto:</p>
            <a href="{{ route('add-products') }}" class="bg-[#a75824] text-white px-6 py-2 rounded hover:bg-[#8a3a17] transition">
                Adicionar novo produto
            </a>
        </div>

        <!-- Editar -->
        <div>
            <p class="mb-2">Editar produto existente:</p>
            <a href="{{ route('edit-products') }}" class="bg-[#a75824] text-white px-6 py-2 rounded hover:bg-[#8a3a17] transition">
                Editar produto
            </a>
        </div>

        <!-- Listar/Excluir -->
        <div>
            <p class="mb-2">Listar/Excluir produto:</p>
            <a href="{{ route('view-products') }}" class="bg-[#a75824] text-white px-6 py-2 rounded hover:bg-[#8a3a17] transition">
                Vizualizar ou excluir produtos
            </a>
        </div>
    </div>
</section>

</body>
</html>