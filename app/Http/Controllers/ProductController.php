<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;


class ProductController extends Controller
{
    public function store(Request $request)
    {
        try {
            Log::info('=== DADOS RECEBIDOS ===');
            Log::info('All data:', $request->all());
            Log::info('Files:', $request->allFiles());

            $validated = $request->validate([
                'name' => 'required|string|max:100',
                'price' => 'required|numeric',
                'stock' => 'required|integer',
                'discount' => 'nullable|integer',
                'description' => 'nullable|string',
                'category_id' => 'required|exists:categories,id',
                'new_prod' => 'nullable|boolean',
                'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            Log::info('=== DADOS VALIDADOS ===');
            Log::info('Validated data:', $validated);

            $product = Product::create([
                'name' => $validated['name'],
                'price' => $validated['price'],
                'stock' => $validated['stock'],
                'discount' => $validated['discount'] ?? 0,
                'description' => $validated['description'] ?? '',
                'category_id' => $validated['category_id'],
                'new_prod' => $request->has('new_prod'),
            ]);

            Log::info('=== PRODUTO CRIADO ===');
            Log::info('Product ID: ' . $product->id);

            if ($request->hasFile('images')) {
                Log::info('=== PROCESSANDO IMAGENS ===');
                $images = $request->file('images');
                Log::info('Número de imagens: ' . count($images));
                Log::info('Tipo de dados images: ' . gettype($images));
                
                if (!is_array($images)) {
                    $images = [$images];
                }

                $processedHashes = [];

                foreach ($images as $index => $image) {
                    if (!$image || !$image->isValid()) {
                        Log::warning("Imagem {$index} inválida ou nula");
                        continue;
                    }

                    $fileHash = md5($image->getClientOriginalName() . $image->getSize() . $image->getMimeType());
                    
                    if (in_array($fileHash, $processedHashes)) {
                        Log::warning("Imagem {$index} duplicada detectada: " . $image->getClientOriginalName());
                        continue;
                    }
                    
                    $processedHashes[] = $fileHash;

                    Log::info("Processando imagem {$index}: " . $image->getClientOriginalName());
                    Log::info("Tamanho: " . $image->getSize() . " bytes");
                    Log::info("Tipo: " . $image->getMimeType());
                    Log::info("Hash: " . $fileHash);

                    try {
                        $path = $image->store('products', 'public');
                        
                        $imageUrl = 'storage/' . $path;

                        ProductImage::create([
                            'product_id' => $product->id,
                            'image_url' => $imageUrl,
                        ]);

                        Log::info("Imagem {$index} salva em: " . $path);
                        Log::info("URL da imagem: " . $imageUrl);
                    } catch (\Exception $e) {
                        Log::error("Erro ao salvar imagem {$index}: " . $e->getMessage());
                    }
                }
            } else {
                Log::info('Nenhuma imagem foi enviada');
            }

            return response()->json([
                'message' => 'Produto cadastrado com sucesso!',
                'product_id' => $product->id
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('=== ERRO DE VALIDAÇÃO ===');
            Log::error('Validation errors:', $e->errors());
            
            return response()->json([
                'message' => 'Erro de validação',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            Log::error('=== ERRO GERAL ===');
            Log::error('Error message:', $e->getMessage());
            Log::error('Error trace:', $e->getTraceAsString());
            
            return response()->json([
                'message' => 'Erro interno do servidor: ' . $e->getMessage()
            ], 500);
        }
    }

    public function create()
    {
        $categories = Category::all();
        return view('add-products', compact('categories'));
    }

    public function index(Request $request)
    {
        $query = Product::with('images');

        // Filtro por nome
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        // Filtro por preço mínimo
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        // Filtro por preço máximo
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Filtro por desconto mínimo
        if ($request->filled('min_discount')) {
            $query->where('discount', '>=', $request->min_discount);
        }

        // Filtro por desconto máximo
        if ($request->filled('max_discount')) {
            $query->where('discount', '<=', $request->max_discount);
        }

        $sortBy = $request->get('sort', 'name'); 
        $sortOrder = $request->get('order', 'asc'); 

        $allowedSorts = ['name', 'price', 'discount', 'created_at'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->orderBy('name', 'asc');
        }

        $products = $query->paginate(6)->appends($request->query());

        Log::info('=== PRODUTOS CONSULTADOS ===');
        if ($request->ajax()) {
            return response()->json([
                'products' => view('partials.products-grid', compact('products'))->render(),
                'pagination' => (string) $products->links()
            ]);
        }

        return view('view-products', compact('products'));
    }
    
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        foreach ($product->images as $image) {
            $filePath = str_replace('storage/', '', $image->image_url);
            Storage::disk('public')->delete($filePath);
            $image->delete();
        }
        $product->delete();
        return response()->json(['success' => true]);
    }

    public function edit($id)
    {
        $product = Product::with('images')->findOrFail($id);
        $categories = Category::all();

        return view('edit-products', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        try {
            $product = Product::findOrFail($id);

            $validated = $request->validate([
                'name' => 'required|string|max:100',
                'price' => 'required|numeric',
                'stock' => 'required|integer',
                'discount' => 'nullable|integer',
                'description' => 'nullable|string',
                'category_id' => 'required|exists:categories,id',
                'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
            $product->update([
                'name' => $validated['name'],
                'price' => $validated['price'],
                'stock' => $validated['stock'],
                'discount' => $validated['discount'] ?? 0,
                'description' => $validated['description'] ?? '',
                'category_id' => $validated['category_id'],
            ]);
            if ($request->hasFile('images') && is_array($request->file('images'))) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('products', 'public');   
                    $imageUrl = 'storage/' . $path;

                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_url' => $imageUrl,
                    ]);
                }
            }
            return response()->json(['success' => true, 'message' => 'Produto atualizado com sucesso.']);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
            ], 422);
        }
    }
    public function deleteImage($id)
{
    try {
        $image = ProductImage::findOrFail($id);
        $filePath = str_replace('storage/', '', $image->image_url);
        Storage::disk('public')->delete($filePath);
        $image->delete();

        return response()->json(['success' => true]);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => 'Erro ao excluir imagem.']);
    }
}
}