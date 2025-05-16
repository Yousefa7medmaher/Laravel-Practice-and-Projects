<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::withCount('likes');

        // Apply keyword search
        if ($request->filled('keywords')) {
            $keywords = $request->keywords;
            $query->where(function($q) use ($keywords) {
                $q->where('name', 'like', "%{$keywords}%")
                  ->orWhere('description', 'like', "%{$keywords}%")
                  ->orWhere('code', 'like', "%{$keywords}%");
            });
        }

        // Apply price filters
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Filter out held products for customers
        if (auth()->check() && auth()->user()->hasRole('customer')) {
            $query->where('hold', false);
        }

        // Apply sorting
        $sortBy = $request->input('sort_by', 'likes_count');
        $sortDirection = $request->input('sort_direction', 'desc');

        // Validate sort field to prevent SQL injection
        $allowedSortFields = ['name', 'price', 'created_at', 'likes_count'];
        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy($sortBy, $sortDirection);
        }

        $products = $query->paginate(12)->withQueryString();

        return view('products.index', compact('products'));
    }

    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    public function create()
    {
        Gate::authorize('manage-products');
        return view('products.create');
    }

    public function store(Request $request)
    {
        Gate::authorize('manage-products');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:products',
            'model' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        try {
            // Handle image upload
            if ($request->hasFile('image')) {
                $image = $request->file('image');

                // Log image details for debugging
                \Log::info('Image upload attempt', [
                    'original_name' => $image->getClientOriginalName(),
                    'mime_type' => $image->getMimeType(),
                    'size' => $image->getSize(),
                    'error' => $image->getError()
                ]);

                // Check if file is valid
                if (!$image->isValid()) {
                    \Log::error('Invalid image file', ['error' => $image->getError()]);
                    throw new \Exception('Invalid image file: ' . $image->getErrorMessage());
                }

                $imageName = time() . '_' . $image->getClientOriginalName();

                // Make sure the products directory exists
                $productsDir = 'products';
                if (!Storage::disk('public')->exists($productsDir)) {
                    \Log::info('Creating products directory');
                    $dirCreated = Storage::disk('public')->makeDirectory($productsDir);
                    if (!$dirCreated) {
                        \Log::error('Failed to create products directory');
                        throw new \Exception('Failed to create products directory');
                    }
                }

                // Store the image
                \Log::info('Storing image', ['path' => $productsDir . '/' . $imageName]);
                $path = $image->storeAs($productsDir, $imageName, 'public');

                if (!$path) {
                    \Log::error('Failed to store image');
                    throw new \Exception('Failed to upload image');
                }

                // Verify the file was actually stored
                if (!Storage::disk('public')->exists($path)) {
                    \Log::error('Image not found after upload', ['path' => $path]);
                    throw new \Exception('Image upload verification failed');
                }

                \Log::info('Image uploaded successfully', ['path' => $path]);
                $validated['image'] = $path;
            } else {
                \Log::info('No image file provided in the request');
            }

            $product = Product::create($validated);
            \Log::info('Product created successfully', ['product_id' => $product->id]);

            return redirect()->route('products.index')
                ->with('success', 'Product created successfully.');
        } catch (\Exception $e) {
            \Log::error('Product creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->withInput()
                ->withErrors(['error' => 'Failed to create product. ' . $e->getMessage()]);
        }
    }

    public function edit(Product $product)
    {
        Gate::authorize('manage-products');
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        Gate::authorize('manage-products');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:products,code,' . $product->id,
            'model' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        try {
            // Handle image upload or removal
            if ($request->hasFile('image')) {
                $image = $request->file('image');

                // Log image details for debugging
                \Log::info('Image update attempt', [
                    'product_id' => $product->id,
                    'original_name' => $image->getClientOriginalName(),
                    'mime_type' => $image->getMimeType(),
                    'size' => $image->getSize(),
                    'error' => $image->getError()
                ]);

                // Check if file is valid
                if (!$image->isValid()) {
                    \Log::error('Invalid image file during update', ['error' => $image->getError()]);
                    throw new \Exception('Invalid image file: ' . $image->getErrorMessage());
                }

                // Delete old image if exists
                if ($product->image && Storage::disk('public')->exists($product->image)) {
                    \Log::info('Deleting old image', ['path' => $product->image]);
                    $deleted = Storage::disk('public')->delete($product->image);
                    if (!$deleted) {
                        \Log::warning('Failed to delete old image', ['path' => $product->image]);
                    }
                }

                $imageName = time() . '_' . $image->getClientOriginalName();

                // Make sure the products directory exists
                $productsDir = 'products';
                if (!Storage::disk('public')->exists($productsDir)) {
                    \Log::info('Creating products directory during update');
                    $dirCreated = Storage::disk('public')->makeDirectory($productsDir);
                    if (!$dirCreated) {
                        \Log::error('Failed to create products directory during update');
                        throw new \Exception('Failed to create products directory');
                    }
                }

                // Store the image
                \Log::info('Storing updated image', ['path' => $productsDir . '/' . $imageName]);
                $path = $image->storeAs($productsDir, $imageName, 'public');

                if (!$path) {
                    \Log::error('Failed to store updated image');
                    throw new \Exception('Failed to upload image');
                }

                // Verify the file was actually stored
                if (!Storage::disk('public')->exists($path)) {
                    \Log::error('Updated image not found after upload', ['path' => $path]);
                    throw new \Exception('Image upload verification failed');
                }

                \Log::info('Image updated successfully', ['path' => $path]);
                $validated['image'] = $path;
            } elseif ($request->input('remove_image') == '1' && $product->image) {
                // Remove image if the remove flag is set
                \Log::info('Removing image by request', ['product_id' => $product->id, 'path' => $product->image]);
                if (Storage::disk('public')->exists($product->image)) {
                    $deleted = Storage::disk('public')->delete($product->image);
                    if (!$deleted) {
                        \Log::warning('Failed to delete image during removal', ['path' => $product->image]);
                    }
                }
                $validated['image'] = null;
            } else {
                \Log::info('No image changes for product update', ['product_id' => $product->id]);
            }

            $product->update($validated);
            \Log::info('Product updated successfully', ['product_id' => $product->id]);

            return redirect()->route('products.index')
                ->with('success', 'Product updated successfully.');
        } catch (\Exception $e) {
            \Log::error('Product update failed', [
                'product_id' => $product->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->withInput()
                ->withErrors(['error' => 'Failed to update product. ' . $e->getMessage()]);
        }
    }

    public function destroy(Product $product)
    {
        Gate::authorize('manage-products');

        try {
            // Delete image if exists
            if ($product->image) {
                \Log::info('Attempting to delete product image', [
                    'product_id' => $product->id,
                    'image_path' => $product->image
                ]);

                // Check if file exists
                if (Storage::disk('public')->exists($product->image)) {
                    $deleted = Storage::disk('public')->delete($product->image);

                    if ($deleted) {
                        \Log::info('Product image deleted successfully', [
                            'product_id' => $product->id,
                            'image_path' => $product->image
                        ]);
                    } else {
                        \Log::warning('Failed to delete product image', [
                            'product_id' => $product->id,
                            'image_path' => $product->image
                        ]);
                    }
                } else {
                    \Log::warning('Product image file not found', [
                        'product_id' => $product->id,
                        'image_path' => $product->image
                    ]);
                }
            }

            // Delete the product
            $productId = $product->id;
            $productName = $product->name;
            $product->delete();

            \Log::info('Product deleted successfully', [
                'product_id' => $productId,
                'product_name' => $productName
            ]);

            return redirect()->route('products.index')
                ->with('success', 'Product deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Failed to delete product', [
                'product_id' => $product->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->withErrors(['error' => 'Failed to delete product. ' . $e->getMessage()]);
        }
    }

    public function toggleHold(Product $product)
    {
        $product->hold = !$product->hold;
        $product->save();

        $status = $product->hold ? 'held' : 'unheld';
        return redirect()->back()->with('success', "Product has been {$status}.");
    }

    /**
     * Debug method to test image upload functionality
     */
    public function debugImageUpload(Request $request)
    {
        if (!app()->environment('production')) {
            try {
                // Check if an image was uploaded
                if ($request->hasFile('test_image')) {
                    $image = $request->file('test_image');

                    // Log image details
                    \Log::info('Debug image upload attempt', [
                        'original_name' => $image->getClientOriginalName(),
                        'mime_type' => $image->getMimeType(),
                        'size' => $image->getSize(),
                        'error' => $image->getError()
                    ]);

                    // Check if the image is valid
                    if (!$image->isValid()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Invalid image file: ' . $image->getErrorMessage(),
                            'error' => $image->getError()
                        ]);
                    }

                    // Generate a unique filename
                    $imageName = 'debug_' . time() . '_' . $image->getClientOriginalName();

                    // Make sure the products directory exists
                    $productsDir = 'products';
                    if (!Storage::disk('public')->exists($productsDir)) {
                        $dirCreated = Storage::disk('public')->makeDirectory($productsDir);
                        if (!$dirCreated) {
                            return response()->json([
                                'success' => false,
                                'message' => 'Failed to create products directory'
                            ]);
                        }
                    }

                    // Store the image
                    $path = $image->storeAs($productsDir, $imageName, 'public');

                    if (!$path) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Failed to store image'
                        ]);
                    }

                    // Verify the file was actually stored
                    if (!Storage::disk('public')->exists($path)) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Image not found after upload',
                            'path' => $path
                        ]);
                    }

                    // Check permissions
                    $fullPath = storage_path('app/public/' . $path);
                    $permissions = substr(sprintf('%o', fileperms($fullPath)), -4);

                    return response()->json([
                        'success' => true,
                        'message' => 'Image uploaded successfully',
                        'path' => $path,
                        'url' => asset('storage/' . $path),
                        'file_exists' => file_exists($fullPath),
                        'file_permissions' => $permissions,
                        'file_size' => filesize($fullPath)
                    ]);
                }

                // If no image was uploaded, return storage information
                $publicPath = public_path('storage');
                $storagePath = storage_path('app/public');
                $productsPath = storage_path('app/public/products');

                return response()->json([
                    'success' => true,
                    'message' => 'No image uploaded. Showing storage information.',
                    'public_storage_exists' => file_exists($publicPath),
                    'public_storage_is_link' => is_link($publicPath),
                    'public_storage_target' => is_link($publicPath) ? readlink($publicPath) : null,
                    'storage_path_exists' => file_exists($storagePath),
                    'products_path_exists' => file_exists($productsPath),
                    'products_path_permissions' => file_exists($productsPath) ? substr(sprintf('%o', fileperms($productsPath)), -4) : null,
                    'storage_path_permissions' => file_exists($storagePath) ? substr(sprintf('%o', fileperms($storagePath)), -4) : null
                ]);
            } catch (\Exception $e) {
                \Log::error('Debug image upload failed', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Error: ' . $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]);
            }
        }

        return redirect()->route('products.index');
    }

}