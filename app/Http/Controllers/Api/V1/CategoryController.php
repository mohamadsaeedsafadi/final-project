<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\CategoryService;
class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index()
    {
        return response()->json(
            $this->categoryService->listCategories()
        );
    }

    public function show($id)
    {
        return response()->json(
            $this->categoryService->getCategoryWithQuestions($id)
        );
    }
    
}