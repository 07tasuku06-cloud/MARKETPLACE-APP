<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $keyword = request('keyword');

        // マイリストタブ
        if (request('tab') === 'mylist') {

            // 未ログインなら空
            if (!auth()->check()) {

                $products = collect();
            } else {

                $query = auth()->user()
                    ->likedProducts()
                    ->with('user', 'categories');

                // 商品検索
                if ($keyword) {

                    $query->where('name', 'like', '%' . $keyword . '%');
                }

                $products = $query->get();
            }
        } else {

            // 通常商品一覧
            $query = Product::with('user', 'categories');

            // 自分の商品除外
            if (auth()->check()) {

                $query->where('user_id', '!=', auth()->id());
            }

            // 商品検索
            if ($keyword) {

                $query->where('name', 'like', '%' . $keyword . '%');
            }

            $products = $query->get();
        }

        return view('index', compact('products'));


    }

    public function create()
    {
        $categories = Category::all();

        return view('sell', compact('categories'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image',
            'category_ids' => 'required|array',
            'category_ids.*' => 'exists:categories,id',
            'condition' => 'required|string',
            'name' => 'required|string|max:255',
            'brand' => 'nullable|string|max:255',
            'description' => 'required|string',
            'price' => 'required|integer|min:1',
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {

            $imagePath = $request
                ->file('image')
                ->store('products', 'public');
        }

        $product = Product::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'brand' => $request->brand,
            'description' => $request->description,
            'price' => $request->price,
            'condition' => $request->condition,
            'image' => $imagePath,
            'is_sold' => false,
        ]);

        $product->categories()->attach(
            $request->category_ids
        );

        return redirect('/');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $product = Product::with([
            'user',
            'categories',
            'comments.user',
            'likedUsers'
        ])->findOrFail($id);

        $likeCount = $product->likedUsers()->count();

        $isLiked = auth()->check() &&
            $product->likedUsers()
            ->where('user_id', auth()->id())
            ->exists();

        return view('show', compact('product', 'likeCount', 'isLiked'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
