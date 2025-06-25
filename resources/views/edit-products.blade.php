<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="route-view-products" content="{{ route('view-products') }}">
    @vite('resources/css/app.css')
    <title>Luê Aromas</title>
</head>
<body>
    @include('layouts.navigation')

<main class="flex flex-col items-center justify-center min-h-screen px-4 py-10">
    <h1 class="text-5xl font-extrabold text-[#c7902f] text-center mb-10">Editar Produto</h1>
    <div id="edit-message" class="text-center text-sm font-medium mb-4 hidden"></div>

    <form id="edit-product-form"
        class="bg-[#fef0d9] shadow-md rounded-xl p-8 w-full sm:w-[90%] max-w-2xl space-y-4"
        enctype="multipart/form-data"
        data-product-id="{{ $product->id }}">
        @csrf

        <div class="flex flex-col">
        <label class="text-[#a24d17] font-medium mb-1">Nome*:</label>
        <input type="text" name="name" value="{{ old('name', $product->name) }}" class="p-2 rounded border border-[#a24d17]" required>
        </div>

        <div class="flex flex-col">
        <label class="text-[#a24d17] font-medium mb-1">Preço*:</label>
        <input type="number" step="0.01" name="price" value="{{ old('price', $product->price) }}" class="p-2 rounded border border-[#a24d17]" required>
        </div>

        <div class="flex flex-col">
        <label class="text-[#a24d17] font-medium mb-1">Unidades Disponíveis*:</label>
        <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" class="p-2 rounded border border-[#a24d17]" required>
        </div>

        <div class="flex flex-col">
        <label class="text-[#a24d17] font-medium mb-1">Desconto(%):</label>
        <input type="number" name="discount" value="{{ old('discount', $product->discount) }}" class="p-2 rounded border border-[#a24d17]">
        </div>

        <div class="flex flex-col">
        <label class="text-[#a24d17] font-medium mb-1">Categorias*:</label>
        <select name="category_id" class="p-2 rounded border border-[#a24d17]" required>
            <option value="" disabled>Selecione</option>
            @foreach ($categories as $category)
            <option value="{{ $category->id }}" @if ($product->category_id == $category->id) selected @endif>{{ $category->name }}</option>
            @endforeach
        </select>
        </div>

        <div class="flex flex-col">
        <label class="text-[#a24d17] font-medium mb-1">Imagens (adicionais):</label>
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
        <div class="mt-2 flex gap-2 flex-wrap">
            @foreach ($product->images as $image)
            <img src="{{ asset($image->image_url) }}" alt="Imagem" class="w-20 h-20 object-cover rounded">
            @endforeach
        </div>
        </div>

        <div class="flex flex-col">
        <label class="text-[#a24d17] font-medium mb-1">Descrição:</label>
        <textarea rows="4" name="description" class="p-2 rounded border border-[#a24d17]">{{ old('description', $product->description) }}</textarea>
        </div>

        <div class="flex justify-end">
        <button type="submit" class="bg-[#a24d17] text-white px-6 py-2 rounded-xl hover:bg-[#823d13] transition-all">
            Salvar Alterações
        </button>
        </div>
    </form>
    </main>

    @vite(['resources/js/edit-products.js'])
</body>
</html>