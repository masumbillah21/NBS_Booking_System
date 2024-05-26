<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Service;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\ServiceCategory;

class ServiceCategoryController extends Controller
{
    public function index()
    {
        $serviceCategories = ServiceCategory::with('category', 'service')->get();
        return Inertia::render('ServiceCategories/Index', ['serviceCategories' => $serviceCategories]);
    }

    public function createOrEdit($id)
    {
        $serviceCategory = $id ? ServiceCategory::findOrFail($id) : new ServiceCategory();
        $categories = Category::all();
        $services = Service::all();
        return Inertia::render('ServiceCategories/Form', [
            'serviceCategory' => $serviceCategory,
            'categories' => $categories,
            'services' => $services,
            'isEdit' => $id ? true : false
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'service_id' => 'required|exists:services,id',
        ]);

        ServiceCategory::create($request->all());
        return redirect()->route('service_categories.index')->with('success', 'Service category created successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'service_id' => 'required|exists:services,id',
        ]);

        $serviceCategory = ServiceCategory::findOrFail($id);
        $serviceCategory->update($request->all());
        return redirect()->route('service_categories.index')->with('success', 'Service category updated successfully.');
    }

    public function destroy($id)
    {
        $serviceCategory = ServiceCategory::findOrFail($id);
        $serviceCategory->delete();
        return redirect()->route('service_categories.index')->with('success', 'Service category deleted successfully.');
    }
}
