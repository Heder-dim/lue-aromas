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
        <h1 class="text-5xl sm:text-5xl text-center font-extrabold text-[#bb8642] mb-4">
            Olá {{ Auth::user()->name }} <br> bem vindo ao sistema de gerenciamento de produtos!
        </h1>

        <div class="mb-6 text-center text-[#bb8642] font-semibold text-xl ">
            <p class="mb-4">Cadastrar novo produto:</p>
            <a href="{{ route('add-products') }}" class="bg-[#a75824] text-white px-6 py-2 rounded hover:bg-[#8a3a17] transition">
                Adicionar novo produto
            </a>
        </div>

        <div class="bg-gray-50 p-6 rounded-lg mb-6 border border-gray-200">
            <h2 class="text-xl font-semibold text-[#bb8642] mb-4">Filtros</h2>
            
            <form id="filterForm" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nome do Produto</label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ request('name') }}"
                           placeholder="Digite o nome..."
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#bb8642] focus:border-transparent">
                </div>

                <div>
                    <label for="min_price" class="block text-sm font-medium text-gray-700 mb-2">Preço Mínimo</label>
                    <input type="number" 
                           id="min_price" 
                           name="min_price" 
                           value="{{ request('min_price') }}"
                           placeholder="R$ 0,00"
                           step="0.01"
                           min="0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#bb8642] focus:border-transparent">
                </div>

                <div>
                    <label for="max_price" class="block text-sm font-medium text-gray-700 mb-2">Preço Máximo</label>
                    <input type="number" 
                           id="max_price" 
                           name="max_price" 
                           value="{{ request('max_price') }}"
                           placeholder="R$ 1000,00"
                           step="0.01"
                           min="0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#bb8642] focus:border-transparent">
                </div>

                <div>
                    <label for="min_discount" class="block text-sm font-medium text-gray-700 mb-2">Desconto Mínimo (%)</label>
                    <input type="number" 
                           id="min_discount" 
                           name="min_discount" 
                           value="{{ request('min_discount') }}"
                           placeholder="0"
                           min="0"
                           max="100"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#bb8642] focus:border-transparent">
                </div>

                <div>
                    <label for="max_discount" class="block text-sm font-medium text-gray-700 mb-2">Desconto Máximo (%)</label>
                    <input type="number" 
                           id="max_discount" 
                           name="max_discount" 
                           value="{{ request('max_discount') }}"
                           placeholder="100"
                           min="0"
                           max="100"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#bb8642] focus:border-transparent">
                </div>

                <div>
                    <label for="sort" class="block text-sm font-medium text-gray-700 mb-2">Ordenar por</label>
                    <select id="sort" name="sort" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#bb8642] focus:border-transparent">
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nome</option>
                        <option value="price" {{ request('sort') == 'price' ? 'selected' : '' }}>Preço</option>
                        <option value="discount" {{ request('sort') == 'discount' ? 'selected' : '' }}>Desconto</option>
                        <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Data de Criação</option>
                    </select>
                </div>

                
                <div>
                    <label for="order" class="block text-sm font-medium text-gray-700 mb-2">Ordem</label>
                    <select id="order" name="order" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#bb8642] focus:border-transparent">
                        <option value="asc" {{ request('order') == 'asc' ? 'selected' : '' }}>Crescente</option>
                        <option value="desc" {{ request('order') == 'desc' ? 'selected' : '' }}>Decrescente</option>
                    </select>
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit" 
                            class="bg-[#a75824] text-white px-4 py-2 rounded hover:bg-[#8a3a17] transition flex-1">
                        Filtrar
                    </button>
                    <button type="button" 
                            id="clearFilters"
                            class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition flex-1">
                        Limpar
                    </button>
                </div>
            </form>
        </div>

        <div id="loading" class="hidden text-center py-8">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-[#bb8642]"></div>
            <p class="mt-2 text-gray-600">Carregando produtos...</p>
        </div>

        <div id="productsContainer">
            @include('partials.products-grid', ['products' => $products])
        </div>

        <!-- Container da Paginação -->
        <div id="paginationContainer" class="mt-10 flex justify-center">
            {{ $products->links() }}
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const filterForm = document.getElementById('filterForm');
            const clearFiltersBtn = document.getElementById('clearFilters');
            const productsContainer = document.getElementById('productsContainer');
            const paginationContainer = document.getElementById('paginationContainer');
            const loading = document.getElementById('loading');

            filterForm.addEventListener('submit', function (e) {
                e.preventDefault();
                applyFilters();
            });

            clearFiltersBtn.addEventListener('click', function () {
                filterForm.reset();
                applyFilters();
            });

            const filterInputs = filterForm.querySelectorAll('input, select');
            filterInputs.forEach(input => {
                input.addEventListener('change', function() {
                    clearTimeout(this.timeout);
                    this.timeout = setTimeout(() => {
                        applyFilters();
                    }, 500);
                });
            });

            function applyFilters() {
                const formData = new FormData(filterForm);
                const params = new URLSearchParams();

                // Adicionar apenas campos preenchidos
                for (let [key, value] of formData.entries()) {
                    if (value.trim() !== '') {
                        params.append(key, value);
                    }
                }

                // Mostrar loading
                loading.classList.remove('hidden');
                productsContainer.style.opacity = '0.5';

                fetch(`{{ route('view-products') }}?${params.toString()}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    productsContainer.innerHTML = data.products;
                    paginationContainer.innerHTML = data.pagination;

                    attachDeleteListeners();

                    loading.classList.add('hidden');
                    productsContainer.style.opacity = '1';

                    const newUrl = `${window.location.pathname}?${params.toString()}`;
                    window.history.pushState(null, '', newUrl);
                })
                .catch(error => {
                    console.error('Erro ao aplicar filtros:', error);
                    loading.classList.add('hidden');
                    productsContainer.style.opacity = '1';
                    alert('Erro ao aplicar filtros. Tente novamente.');
                });
            }

            function attachDeleteListeners() {
                const deleteButtons = document.querySelectorAll('.delete-btn');
                deleteButtons.forEach(button => {
                    button.addEventListener('click', handleDelete);
                });
            }

            function handleDelete(e) {
                const productId = e.target.getAttribute('data-id');
                
                if (confirm('Tem certeza que deseja excluir este produto?')) {
                    fetch(`/products/${productId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            e.target.closest('.bg-white').remove();
                            alert('Produto excluído com sucesso!');
                        } else {
                            alert('Erro ao excluir produto.');
                        }
                    })
                    .catch(error => {
                        console.error('Erro:', error);
                        alert('Erro ao excluir produto.');
                    });
                }
            }

            attachDeleteListeners();
        });
    </script>
</body>
</html>