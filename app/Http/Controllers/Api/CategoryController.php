<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

        // Retrieve all categories
        public function index()
        {
        
        $categories = Category::all();
        return response()->json($categories);
    }
    

        public function show($id)
        {
        // جلب الفئة مع الأساتذة باستخدام التحميل المسبق
        $category = Category::with('teachers')->findOrFail($id);
        
        return response()->json($category);
        }
}
