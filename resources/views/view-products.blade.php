<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="products-store-url" content="{{ route('products.store') }}">
    @vite('resources/css/app.css')
    <title>Luê Aromas</title>
</head>
<body class="bg-white text-gray-800">
    @include('layouts.navigation')

    <div class="p-6 max-w-7xl mx-auto">
        <h2 class="text-3xl font-extrabold text-center text-yellow-700 mb-10">Produtos</h2>

        <!-- Produtos -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-10">
            @foreach ($products as $product)
                <div class="bg-white rounded-2xl shadow-md overflow-hidden border border-gray-200 flex flex-col">
                    
                    <!-- Imagem -->
                    @if($product->images->count() > 0)
                        <img src="{{ asset($product->images->first()->image_url) }}"
                             alt="{{ $product->name }}"
                             class="h-64 w-full object-cover rounded-t-2xl" />
                    @else
                        <div class="h-64 bg-gray-100 flex items-center justify-center text-gray-500 rounded-t-2xl">
                            Sem imagem
                        </div>
                    @endif

                    <!-- Informações -->
                    <div class="p-4 flex flex-col items-center">
                        <h3 class="text-lg font-semibold">{{ $product->name }}</h3>
                        <p class="text-sm text-gray-500 mb-1">{{ $product->name }}</p>
                        <p class="text-xl font-bold text-gray-800 mb-1">R$ {{ number_format($product->price, 2, ',', '.') }}</p>
                        <p class="text-sm text-gray-500">Estoque: {{ $product->stock }}</p>
                    </div>

                    <!-- Ações -->
                    <div class="p-4 border-t flex justify-center gap-4">
                        <a href="{{ route('edit-products', $product->id) }}"
                            class="text-yellow-600 border border-yellow-600 px-4 py-1 rounded hover:bg-yellow-100 transition">
                            Editar
                        </a>
                        <button data-id="{{ $product->id }}"
                                class="delete-btn text-red-600 border border-red-600 px-5 py-1 rounded hover:bg-red-100 transition font-medium">
                            Excluir
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Paginação -->
        <div class="mt-10 flex justify-center">
            {{ $products->links() }}
        </div>
    </div>

    @vite(['resources/js/view-product.js'])
</body>
</html>
