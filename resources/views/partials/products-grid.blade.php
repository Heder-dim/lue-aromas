<!-- resources/views/partials/products-grid.blade.php -->


<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-10  border-[#a75824] pt-5">
    @forelse ($products as $product)
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
                @if($product->discount > 0)
                    <p class="text-sm text-green-600 font-medium mb-1">{{ $product->discount }}% OFF</p>
                @endif
                <p class="text-xl font-bold text-gray-800 mb-1">R$ {{ number_format($product->price, 2, ',', '.') }}</p>
                <p class="text-sm text-gray-500">Estoque: {{ $product->stock }}</p>
                @if($product->description)
                    <p class="text-xs text-gray-400 mt-2 text-center line-clamp-2">{{ Str::limit($product->description, 80) }}</p>
                @endif
            </div>

            <!-- Ações -->
            <div class="p-4 border-t flex justify-center gap-4 mt-auto">
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
    @empty
        <div class="col-span-full text-center py-12">
            <div class="text-gray-400 mb-4">
                <svg class="mx-auto h-16 w-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2M4 13h2m13-8V9a2 2 0 00-2-2H9"></path>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum produto encontrado</h3>
            <p class="text-gray-500 mb-4">Não há produtos que correspondem aos filtros aplicados.</p>
            <button onclick="document.getElementById('clearFilters').click()" 
                    class="bg-[#a75824] text-white px-4 py-2 rounded hover:bg-[#8a3a17] transition">
                Limpar Filtros
            </button>
        </div>
    @endforelse
</div>