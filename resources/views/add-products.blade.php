<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="products-store-url" content="{{ route('products.store') }}">
    <meta name="route-view-products" content="{{ route('view-products') }}">

    @vite('resources/css/app.css')
    <title>Luê Aromas</title>
</head>
<body>
    @include('layouts.navigation')

    
  <!-- Container Principal -->
  <main class="flex flex-col items-center justify-center min-h-screen px-4 py-10">
    <h1 class="text-5xl font-extrabold text-[#c7902f] text-center mb-10">Cadastro de Produtos</h1>

    <!-- Formulário -->
    <form class="bg-[#fef0d9] shadow-md rounded-xl p-8 w-full sm:w-[90%] max-w-2xl space-y-4"  id="form-product" action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" method="POST">
      @csrf
      <!-- Nome -->
      <div class="flex flex-col">
        <label class="text-[#a24d17] font-medium mb-1">Nome*:</label>
        <input type="text" placeholder="Nome" class="p-2 rounded border border-[#a24d17] focus:outline-none focus:ring-2 focus:ring-[#c7902f]" name="name" required/>
      </div>

      <!-- Preço -->
      <div class="flex flex-col">
        <label class="text-[#a24d17] font-medium mb-1">Preço*:</label>
        <input type="number" step="0.01" placeholder="Preço" class="p-2 rounded border border-[#a24d17] focus:outline-none focus:ring-2 focus:ring-[#c7902f]" name="price" required/>
      </div>

      <!-- Unidades Disponíveis -->
      <div class="flex flex-col">
        <label class="text-[#a24d17] font-medium mb-1">Unidades Disponíveis*:</label>
        <input type="number" placeholder="Unidades" class="p-2 rounded border border-[#a24d17] focus:outline-none focus:ring-2 focus:ring-[#c7902f]" name="stock" required/>
      </div>

      <div class="flex flex-col">
        <label class="text-[#a24d17] font-medium mb-1">Desconto(%):</label>
        <input type="number" placeholder="Porcentagem de desconto" class="p-2 rounded border border-[#a24d17] focus:outline-none focus:ring-2 focus:ring-[#c7902f]" name="discount"/>
      </div>
      
      <!-- Categoria -->
      <div class="flex flex-col">
        <label class="text-[#a24d17] font-medium mb-1">Categorias*:</label>
        <select name="category_id" class="p-2 rounded border border-[#a24d17] focus:outline-none focus:ring-2 focus:ring-[#c7902f]" required>
          <option selected disabled value="">Categorias</option>
          @foreach ($categories as $category)
            <option value="{{ $category->id }}">{{ $category->name }}</option>
          @endforeach
        </select>
      </div>

      
      <!-- Imagens -->
      <div class="flex flex-col">
        <label class="text-[#a24d17] font-medium mb-1">Imagens:</label>
        <input
          type="file" name="images[]"
          accept="image/*"
          multiple
          class="rounded-l-full file:mr-4 file:py-2 file:px-4
                file:rounded-l-full file:border-0
                file:text-sm file:font-semibold
                file:bg-[#a24d17] file:text-white
                file:cursor-pointer
                hover:file:bg-[#823d13]
                 text-gray-700 rounded border border-[#a24d17] cursor-pointer
                focus:outline-none focus:ring-2 focus:ring-[#c7902f]"
        />
        
      </div>


      <!-- Descrição -->
      <div class="flex flex-col">
        <label class="text-[#a24d17] font-medium mb-1">Descrição:</label>
        <textarea rows="4" placeholder="Descrição" name="description" class="p-2 rounded border border-[#a24d17] focus:outline-none focus:ring-2 focus:ring-[#c7902f]"></textarea>
      </div>

      <!-- Botão -->
      <div class="flex justify-end">
        <button type="submit" class="cursor-pointer bg-[#a24d17] text-white px-6 py-2 rounded-xl hover:bg-[#823d13] transition-all duration-200 ease-in-out">
          Adicionar Produto
        </button>
      </div>
    </form>
  </main>
  @vite(['resources/js/add-product.js'])

</body>
</html>