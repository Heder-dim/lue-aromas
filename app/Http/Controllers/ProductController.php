<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function store(Request $request)
    {
        try {
            // Debug: log dos dados recebidos
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

            // Processar imagens
            if ($request->hasFile('images')) {
                Log::info('=== PROCESSANDO IMAGENS ===');
                $images = $request->file('images');
                Log::info('Número de imagens: ' . count($images));
                Log::info('Tipo de dados images: ' . gettype($images));
                
                // Verificar se images é um array ou um único arquivo
                if (!is_array($images)) {
                    $images = [$images]; // Converter para array se for um único arquivo
                }

                // Filtrar arquivos duplicados ou inválidos
                $processedHashes = [];

                foreach ($images as $index => $image) {
                    if (!$image || !$image->isValid()) {
                        Log::warning("Imagem {$index} inválida ou nula");
                        continue;
                    }

                    // Criar hash do arquivo para detectar duplicatas
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
                        // Salvar a imagem
                        $path = $image->store('products', 'public');
                        
                        // A URL correta para salvar no banco
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

    public function index()
    {
        $products = Product::with('images')->paginate(6);
        return view('view-products', compact('products'));
    }
    
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // Exclui imagens
        foreach ($product->images as $image) {
            // Remove 'storage/' para acessar o arquivo no disco
            $filePath = str_replace('storage/', '', $image->image_url);
            Storage::disk('public')->delete($filePath);
            $image->delete();
        }

        $product->delete();

        return response()->json(['success' => true]);
    }
}