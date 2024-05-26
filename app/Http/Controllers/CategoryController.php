<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::with('children')->whereNull('parent_id')->get();
        // return response()->json($categories);
        return Inertia::render('Categories/Index', ['categories' => $categories]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function createOrEdit($id)
    {
        $category = $id ? Category::findOrFail($id) : new Category();
        $categories = Category::all();
        return Inertia::render('Categories/Form', [
            'category' => $category,
            'categories' => $categories,
            'isEdit' => $id ? true : false
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
        ]);

        $category = Category::create($request->all());
        return redirect()->route('categories.index')->with('success', 'Category created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */

    /*
    public function edit(Category $category, $id)
    {
        $category = Category::findOrFail($id);
        $categories = Category::all();
        return Inertia::render('Categories/Edit', ['category' => $category, 'categories' => $categories]);
    }
    */

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'category_name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
        ]);

        $category = Category::findOrFail($request->id);
        $category->update($request->all());
        return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category, $id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Category deleted successfully.');
    }
}
